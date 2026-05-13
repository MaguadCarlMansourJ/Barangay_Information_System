<?php

namespace App\Http\Controllers;

use App\Models\ExpiredGood;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpiredController extends Controller
{
    public function index()
    {
        $expiredGoods = ExpiredGood::with('product')->latest()->paginate(20);
        return view('expired.index', compact('expiredGoods'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('expired.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'expiration_date' => 'required|date',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough stock to record expiration.');
        }

        DB::transaction(function () use ($validated, $product) {
            ExpiredGood::create($validated);
            $product->decrement('quantity', $validated['quantity']);
        });

        return redirect()->route('expired.index')->with('success', 'Expired goods recorded successfully.');
    }

    public function destroy(ExpiredGood $expiredGood)
    {
        DB::transaction(function () use ($expiredGood) {
            $product = Product::findOrFail($expiredGood->product_id);
            $product->increment('quantity', $expiredGood->quantity);
            $expiredGood->delete();
        });

        return redirect()->route('expired.index')->with('success', 'Expired goods record deleted successfully.');
    }
}

