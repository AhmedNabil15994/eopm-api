<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Order\Database\Seeders\OrderStatusSeeder;
use Modules\Product\Database\Seeders\ProductsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductsSeeder::class,
        ]);

        $this->call([
            OrderStatusSeeder::class,
        ]);
    }
}
