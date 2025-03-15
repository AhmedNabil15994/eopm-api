<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Modules\Product\Entities\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fetching the product list.
     */
    public function testItCanListProducts(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/catalog/products?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    /**
     * Test fetching a single product by ID.
     */
    public function testItCanFetchProductDetails(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/catalog/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $product->id]]);
    }

    /**
     * Test fetching a non-existing product returns 404.
     */
    public function testItReturns404ForInvalidProduct(): void
    {
        $response = $this->getJson('/api/catalog/products/999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Invalid data.']);
    }
}
