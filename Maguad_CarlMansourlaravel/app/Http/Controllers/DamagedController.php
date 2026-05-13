<?php

namespace App\Http\Controllers;

use App\Models\DamagedGood;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamagedController extends Controller
{
    public function index()
    {
        $damagedGoods = DamagedGood::with('product')->latest()->paginate(20);
        return view('damaged.index', compact('damagedGoods'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('damaged.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough stock to mark as damaged.');
        }

        DB::transaction(function () use ($validated, $product) {
            DamagedGood::create($validated);
            $product->decrement('quantity', $validated['quantity']);
        });

        return redirect()->route('damaged.index')->with('success', 'Damaged goods recorded successfully.');
    }

    public function destroy(DamagedGood $damagedGood)
    {
        DB::transaction(function () use ($damagedGood) {
            $product = Product::findOrFail($damagedGood->product_id);
            $product->increment('quantity', $damagedGood->quantity);
            $damagedGood->delete();
        });

        return redirect()->route('damaged.index')->with('success', 'Damaged goods record deleted successfully.');
    }
}

