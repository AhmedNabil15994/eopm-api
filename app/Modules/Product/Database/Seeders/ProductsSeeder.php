<?php
namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder{
    public function run(){
        \DB::table('products')->insert([
            [
                'name' => 'Product 1',
                'price' => '100',
                'qty'   => 100,
                'status' => 1,
                'sku'       => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'Product 2',
                'price' => '50',
                'qty'   => 200,
                'status' => 1,
                'sku'       => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'Product 3',
                'price' => '25',
                'qty'   => 400,
                'status' => 1,
                'sku'       => Str::random(10),
                'created_at' => now(),
            ]
        ]);
    }
}
