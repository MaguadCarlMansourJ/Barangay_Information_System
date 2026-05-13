<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Employee;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index()
    {
        $stockIns = StockIn::with(['product', 'supplier', 'employee'])->latest()->paginate(20);
        return view('stock-ins.index', compact('stockIns'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $employees = Employee::orderBy('name')->get();
        return view('stock-ins.create', compact('products', 'suppliers', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'employee_id' => 'required|exists:employees,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        DB::transaction(function () use ($validated) {
            StockIn::create($validated);
            $product = Product::findOrFail($validated['product_id']);
            $product->increment('quantity', $validated['quantity']);
        });

        return redirect()->route('stock-ins.index')->with('success', 'Stock added successfully.');
    }

    public function destroy(StockIn $stockIn)
    {
        DB::transaction(function () use ($stockIn) {
            $product = Product::findOrFail($stockIn->product_id);
            $product->decrement('quantity', $stockIn->quantity);
            $stockIn->delete();
        });

        return redirect()->route('stock-ins.index')->with('success', 'Stock in record deleted successfully.');
    }
}

