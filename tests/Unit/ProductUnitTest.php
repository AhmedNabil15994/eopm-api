<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Modules\Product\Entities\Product;

class ProductUnitTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test product creation.
     */
    public function testItCanCreateAProduct(): void
    {
        $product = Product::factory()->count(1)->create([
            'name' => 'Test Product',
            'price' => 100.00,
            'qty' => 50,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 100.00,
            'qty' => 50,
        ]);
    }

    /**
     * Test product price update.
     */
    public function testItCanUpdateProductPrice(): void
    {
        $product = Product::factory()->create([
            'price' => 100.00,
        ]);

        $product->update(['price' => 120.00]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 120.00,
        ]);
    }

}
