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

    /**
     * Test creating a new product.
     */
    public function testItCanCreateAProduct(): void
    {
        $response = $this->postJson('/api/catalog/products', [
            'name' => 'New Product',
            'price' => 150.00,
            'qty' => 30,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name', 'price', 'qty']]);

        $this->assertDatabaseHas('products', ['name' => 'New Product']);
    }

    /**
     * Test updating an existing product.
     */
    public function testItCanUpdateAProduct(): void
    {
        $product = Product::factory()->create([
            'name' => 'Old Name',
            'price' => 100.00,
        ]);

        $response = $this->putJson("/api/catalog/products/{$product->id}", [
            'name' => 'Updated Name',
            'price' => 120.00,
        ]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['name' => 'Updated Name', 'price' => 120.00]]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
    }

    /**
     * Test deleting a product.
     */
    public function testItCanDeleteAProduct(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/catalog/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
