<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $filter = request()->get('filter');
            $total_transaction = $this->getTotalTransactions($filter);
            $total_menu_sold = $this->getTotalMenuSold($filter);
            $gross_profit = $this->getGrossProfit($filter);
            $net_profit = $this->getNetProfit($filter);
            $top_products = $this->getTopProducts($filter);
            $top_total_sold = $this->getTopTotalSold($top_products);

            return response()->json([
                'total_transaction' => $total_transaction,
                'total_menu_sold' => $total_menu_sold,
                'gross_profit' => $this->idrFormat($gross_profit),
                'net_profit' => $this->idrFormat($net_profit),
                'top_products' => $top_products,
                'top_total_sold' => $top_total_sold
            ]);
        }


        return view('dashboard.index');
    }

    private function idrFormat($number)
    {
        return "Rp " . number_format($number, 0, ',', '.');
    }

    private function getTimeFilter($query, $filter)
    {
        switch ($filter) {
            case 'today':
                return $query->whereDate('created_at', now());
            case 'this_week':
                return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            case 'this_month':
                return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            case 'this_year':
            default:
                return $query->whereYear('created_at', now()->year);
        }
    }

    function getTotalTransactions($filter)
    {
        return $this->getTimeFilter(Transaction::query(), $filter)->count();
    }

    function getTotalMenuSold($filter)
    {
        return $this->getTimeFilter(ProductTransaction::query(), $filter)->count();
    }

    function getGrossProfit($filter)
    {
        return $this->getTimeFilter(Transaction::query(), $filter)->sum('price');
    }

    function getNetProfit($filter)
    {
        $net_profit = 0;
        $product_transactions = $this->getTimeFilter(ProductTransaction::query(), $filter)->get();

        foreach ($product_transactions as $pt) {
            $product = Product::find($pt->product_id);
            $net_profit += ($product->base_price * $pt->quantity);
        }

        return $net_profit;
    }

    function getTopProducts($filter)
    {
        return $this->getTimeFilter(ProductTransaction::select('product_id', DB::raw('SUM(quantity) as total_sold')), $filter)
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    }

    function getTopTotalSold($top_products)
    {
        $top_total_sold = 0;

        foreach ($top_products as $top_product) {
            if (intval($top_product->total_sold) > $top_total_sold) {
                $top_total_sold = intval($top_product->total_sold);
            }
        }

        return $top_total_sold;
    }
}
