<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Employee;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    public function index()
    {
        $stockOuts = StockOut::with(['product', 'employee'])->latest()->paginate(20);
        return view('stock-outs.index', compact('stockOuts'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $employees = Employee::orderBy('name')->get();
        return view('stock-outs.create', compact('products', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'employee_id' => 'required|exists:employees,id',
            'quantity' => 'required|integer|min:1',
            'department' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough stock to complete this transaction.');
        }

        DB::transaction(function () use ($validated, $product) {
            StockOut::create($validated);
            $product->decrement('quantity', $validated['quantity']);
        });

        return redirect()->route('stock-outs.index')->with('success', 'Stock deducted successfully.');
    }

    public function destroy(StockOut $stockOut)
    {
        DB::transaction(function () use ($stockOut) {
            $product = Product::findOrFail($stockOut->product_id);
            $product->increment('quantity', $stockOut->quantity);
            $stockOut->delete();
        });

        return redirect()->route('stock-outs.index')->with('success', 'Stock out record deleted successfully.');
    }
}

