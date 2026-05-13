<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user
        User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'name' => 'Admin User',
            'email' => 'admin@coffee.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create sample suppliers
        $suppliers = [
            ['name' => 'Coffee Bean Direct', 'contact' => 'contact@coffeebean.com'],
            ['name' => 'Roasters Inc.', 'contact' => 'orders@roasters.com'],
            ['name' => 'Green Bean Supply', 'contact' => 'sales@greenbean.com'],
            ['name' => 'Packaging Plus', 'contact' => 'info@packagingplus.com'],
        ];
        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create sample employees
        $employees = [
            ['name' => 'John Smith', 'role' => 'Manager'],
            ['name' => 'Sarah Johnson', 'role' => 'Barista'],
            ['name' => 'Mike Brown', 'role' => 'Staff'],
            ['name' => 'Emily Davis', 'role' => 'Cashier'],
        ];
        foreach ($employees as $employee) {
            Employee::create($employee);
        }

        // Create sample products
        $products = [
            ['name' => 'Arabica Beans - 1kg', 'category' => 'Beans', 'quantity' => 50, 'min_stock' => 10],
            ['name' => 'Robusta Beans - 1kg', 'category' => 'Beans', 'quantity' => 30, 'min_stock' => 10],
            ['name' => 'Espresso Ground - 500g', 'category' => 'Ground', 'quantity' => 25, 'min_stock' => 5],
            ['name' => 'French Roast Ground', 'category' => 'Ground', 'quantity' => 20, 'min_stock' => 5],
            ['name' => 'Instant Coffee Classic', 'category' => 'Instant', 'quantity' => 100, 'min_stock' => 20],
            ['name' => 'Coffee Filters - 100pc', 'category' => 'Equipment', 'quantity' => 200, 'min_stock' => 50],
            ['name' => 'Paper Cups - 50pc', 'category' => 'Packaging', 'quantity' => 500, 'min_stock' => 100],
            ['name' => 'Coffee Stirrers', 'category' => 'Packaging', 'quantity' => 1000, 'min_stock' => 200],
        ];
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

