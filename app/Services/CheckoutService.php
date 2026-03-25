<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class CheckoutService
{
    protected $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function processCheckout($data)
    {
        $orderId = 'ORDER-' . now()->timestamp . '-' . Str::random(6);

        $product = Product::findOrFail($data['product_id']);
        $totalPrice = $product->price * $data['quantity'];


        $transaction = $this->createTransaction([
            ...$data,
            'total_price' => $totalPrice
        ], $orderId);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $data['customer_name'],
                'email' => $data['customer_email'],
                'phone' => $data['customer_phone'] ?? '',
            ],
            'item_details' => [
                [
                    'id' => $product->id,
                    'price' => (int) $product->price,
                    'quantity' => $data['quantity'],
                    'name' => $product->name,
                ]
            ],
        ];

        try {
            $snapToken = $this->generateSnapToken($params);

            $transaction->update([
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            throw new \Exception('Gagal membuat pembayaran');
        }

        return [
            'transaction' => $transaction,
            'snap_token' => $snapToken,
        ];
    }

    protected function createTransaction($data, $orderId)
    {
        return Transaction::create([
            'order_id' => $orderId,
            'product_id' => $data['product_id'],
            'customer_id' => $data['customer_id'],
            'quantity' => $data['quantity'],
            'total_price' => $data['total_price'],
            'status' => 'pending',
        ]);
    }

    protected function generateSnapToken($params)
    {
        return Snap::getSnapToken($params);
    }
}
