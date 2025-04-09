<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as category', 'products.name as name', 'products.price', 'products.id as id')
            ->where('products.is_deleted', 0)
            ->get();
        $products = $products->groupBy('category');
        $payment_methods = ['QRIS', 'Cash', 'BCA'];
        return view('transactions.index', [
            'products' => $products,
            'payment_methods' => $payment_methods,
            'with_tax' => env('WITH_TAX') ? 'yes' : 'no'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $transaction = Transaction::create([
                'user_id' => Auth::user()->id,
                'price' => 0,
                'tax' => 0,
                'total_price' => 0,
                'payment_method' => $request->post('payment_method')
            ]);

            foreach ($request->post('id') as $i => $id) {
                ProductTransaction::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $id,
                    'quantity' => $request->post('qty')[$i]
                ]);

                $transaction->price += Product::find($id)->price * $request->post('qty')[$i];
            }

            if (env('WITH_TAX')) {
                $transaction->tax = $transaction->price * 11 / 100;
                $transaction->total_price = $transaction->price + $transaction->tax;
            } else {
                $transaction->total_price = $transaction->price;
            }

            $transaction->save();


            return redirect()->route('receipt')->with('receipt', $transaction);
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }

    public function receipt(Request $request)
    {
        $transaction = Transaction::orderBy('id', 'DESC')->first();
        $items = ProductTransaction::with('product')->where('transaction_id', $transaction->id)->get();
        return view('transactions.receipt', [
            'transaction' => $transaction,
            'items' => $items
        ]);
    }
}
