<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Modules\Product\Entities\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test product creation.
     */
    public function testItCanCreateAProduct(): void
    {
        $product = Product::factory()->create([
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
