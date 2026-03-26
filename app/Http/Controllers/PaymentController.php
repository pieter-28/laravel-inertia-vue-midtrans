<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $checkoutService;

    /**
     * Summary of __construct
     */
    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function payment(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string',
        ]);

        $validated['order_id'] = '';

        try {
            $result = $this->checkoutService->processCheckout($validated);

            if (! $result) {
                return response()->json(['message' => 'Checkout gagal'], 500);
            }

            return response()->json([
                'snap_token' => $result['snap_token'],
                'transaction' => $result['transaction'],
            ]);
        } catch (\Exception $e) {
            Log::error('Checkout error: '.$e->getMessage());

            return response()->json(
                [
                    'message' => 'Checkout gagal',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function handleWebhook(Request $request)
    {
        // Ambil raw JSON dari Midtrans
        $payload = json_decode($request->getContent(), true);

        if (! $payload) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $serverKey = config('midtrans.server_key');

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        // Validasi field wajib
        if (! $orderId || ! $statusCode || ! $grossAmount || ! $signatureKey) {
            return response()->json(['message' => 'Invalid data'], 400);
        }

        // Validasi signature
        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        if ($expectedSignature !== $signatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // ✅ FIX: cari pakai order_id
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (! $transaction) {
            Log::warning('Transaction not found', ['order_id' => $orderId]);

            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        // 🧠 Idempotent (hindari overwrite)
        if (in_array($transaction->status, ['paid', 'failed'])) {
            return response()->json(['message' => 'Already processed']);
        }

        // Mapping status
        switch ($transactionStatus) {
            case 'capture':
                $transaction->status = ($fraudStatus === 'challenge') ? 'challenge' : 'paid';
                break;

            case 'settlement':
                $transaction->status = 'paid';
                break;

            case 'pending':
                $transaction->status = 'pending';
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
                $transaction->status = 'failed';
                break;

            case 'refund':
            case 'partial_refund':
                $transaction->status = 'refunded';
                break;

            default:
                $transaction->status = 'unknown';
                break;
        }

        // Simpan full response (debugging penting)
        $transaction->midtrans_response = $payload;
        $transaction->save();

        Log::info('Midtrans Webhook Success', [
            'order_id' => $orderId,
            'status' => $transaction->status,
        ]);

        return response()->json(['message' => 'Webhook processed']);
    }
}
