<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DahsboardController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->input('range', 'month');
        $year = (int) $request->input('year', now()->year);

        $productsCount = Product::count();
        $customersCount = Customer::count();
        $usersCount = User::count();
        $transactionsCount = Transaction::count();

        $totalRevenue = Transaction::sum('total_price');

        $statusSummary = Transaction::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $allTransactions = Transaction::orderBy('created_at', 'desc')->get();
        $yearOptions = $allTransactions
            ->pluck('created_at')
            ->map(fn ($createdAt) => $createdAt instanceof \DateTime ? (int) $createdAt->format('Y') : (int) now()->parse($createdAt)->format('Y'))
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        if (!$yearOptions) {
            $yearOptions = [now()->year];
        }

        if (!in_array($year, $yearOptions, true)) {
            $year = $yearOptions[0];
        }

        $monthlyMap = array_fill(1, 12, 0);
        $yearlyMap = [];

        if ($range === 'month') {
            $transactionsForYear = $allTransactions->filter(fn ($tx) => $tx->created_at->year === $year);

            foreach ($transactionsForYear as $tx) {
                $month = $tx->created_at->month;
                $monthlyMap[$month] += (float) $tx->total_price;
            }

            $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $chartData = array_values($monthlyMap);
            $salesTotal = array_sum($chartData);
        } else {
            $startYear = min($yearOptions);
            $endYear = max($yearOptions);

            if ($range === 'year') {
                $startYear = max($endYear - 4, $startYear);
            }

            $rangeYears = range($startYear, $endYear);
            $groupedByYear = $allTransactions->groupBy(fn ($tx) => $tx->created_at->year);

            foreach ($rangeYears as $y) {
                $yearlyMap[$y] = isset($groupedByYear[$y]) ? (float) $groupedByYear[$y]->sum('total_price') : 0;
            }

            $chartLabels = array_map(fn ($y) => (string) $y, $rangeYears);
            $chartData = array_values($yearlyMap);
            $salesTotal = array_sum($chartData);
        }

        $averageOrderValue = $transactionsCount ? $totalRevenue / max($transactionsCount, 1) : 0;

        $topProducts = Transaction::selectRaw('product_id, SUM(quantity) as total_quantity, SUM(total_price) as revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                return [
                    'product_name' => $row->product?->name ?? 'Unknown',
                    'quantity' => (int) $row->total_quantity,
                    'revenue' => (float) $row->revenue,
                ];
            });

        return inertia('Dashboard', [
            'cards' => [
                ['label' => 'Products', 'value' => $productsCount, 'icon' => 'box'],
                ['label' => 'Customers', 'value' => $customersCount, 'icon' => 'users'],
                ['label' => 'Users', 'value' => $usersCount, 'icon' => 'user'],
                ['label' => 'Transactions', 'value' => $transactionsCount, 'icon' => 'credit-card'],
            ],
            'totalRevenue' => (float) $totalRevenue,
            'averageOrderValue' => round($averageOrderValue, 2),
            'salesTotal' => round($salesTotal, 2),
            'statusSummary' => $statusSummary,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'selectedRange' => $range,
            'selectedYear' => $year,
            'yearOptions' => $yearOptions,
            'topProducts' => $topProducts,
        ]);
    }
}
