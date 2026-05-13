<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\DamagedController;
use App\Http\Controllers\ExpiredController;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Employee;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\DamagedGood;
use App\Models\ExpiredGood;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    });

    Route::get('/register', function () {
        return view('register');
    });

    Route::post('/register', function (Request $request) {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
        ]);

        $user = User::create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'name' => $validated['firstname'] . ' ' . $validated['lastname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    });
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    });

    Route::get('/dashboard', function () {
        $totalProducts = Product::count();
        $lowStocks = Product::whereColumn('quantity', '<=', 'min_stock')->count();
        $totalSuppliers = Supplier::count();
        $totalEmployees = Employee::count();
        $recentStockIns = StockIn::with('product')->latest()->take(5)->get();
        $recentStockOuts = StockOut::with('product')->latest()->take(5)->get();

        return view('dashboard', compact('totalProducts', 'lowStocks', 'totalSuppliers', 'totalEmployees', 'recentStockIns', 'recentStockOuts'));
    })->name('dashboard');

    Route::get('/home', function () {
        return redirect('/dashboard');
    });

    // Products
    Route::resource('products', ProductController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Employees
    Route::resource('employees', EmployeeController::class);

    // Stock In
    Route::get('stock-ins', [StockInController::class, 'index'])->name('stock-ins.index');
    Route::get('stock-ins/create', [StockInController::class, 'create'])->name('stock-ins.create');
    Route::post('stock-ins', [StockInController::class, 'store'])->name('stock-ins.store');
    Route::delete('stock-ins/{stockIn}', [StockInController::class, 'destroy'])->name('stock-ins.destroy');

    // Stock Out
    Route::get('stock-outs', [StockOutController::class, 'index'])->name('stock-outs.index');
    Route::get('stock-outs/create', [StockOutController::class, 'create'])->name('stock-outs.create');
    Route::post('stock-outs', [StockOutController::class, 'store'])->name('stock-outs.store');
    Route::delete('stock-outs/{stockOut}', [StockOutController::class, 'destroy'])->name('stock-outs.destroy');

    // Damaged Goods
    Route::get('damaged', [DamagedController::class, 'index'])->name('damaged.index');
    Route::get('damaged/create', [DamagedController::class, 'create'])->name('damaged.create');
    Route::post('damaged', [DamagedController::class, 'store'])->name('damaged.store');
    Route::delete('damaged/{damagedGood}', [DamagedController::class, 'destroy'])->name('damaged.destroy');

    // Expired Goods
    Route::get('expired', [ExpiredController::class, 'index'])->name('expired.index');
    Route::get('expired/create', [ExpiredController::class, 'create'])->name('expired.create');
    Route::post('expired', [ExpiredController::class, 'store'])->name('expired.store');
    Route::delete('expired/{expiredGood}', [ExpiredController::class, 'destroy'])->name('expired.destroy');
});

