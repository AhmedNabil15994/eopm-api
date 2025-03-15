<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Modules\Cart\Repositories\CartRepository;
use Modules\Product\Entities\Product;
use Modules\Cart\Entities\DatabaseStorageModel;

class CartUnitTest extends TestCase
{
    use DatabaseTransactions;

    protected $cartRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $cartModel = new DatabaseStorageModel();
        $this->cartRepository = new CartRepository($cartModel);
        $this->cartRepository->identifier = 100;
    }

    public function test_can_add_product_to_cart()
    {
        $product = Product::factory()->create();
        $result = $this->cartRepository->addToCart(['id' => $product->id, 'name' => $product->name, 'price' => $product->price], 'product', 2);

        $this->assertTrue($result);
    }

    public function test_can_update_cart_item_quantity()
    {
        $product = Product::factory()->create();

        $this->cartRepository->addToCart(['id' => $product->id, 'name' => $product->name, 'price' => $product->price], 'product', 2);

        $result = $this->cartRepository->updateItemInCart(['id' => $product->id], 'product', 5);

        $this->assertTrue($result);
    }

    public function test_can_remove_product_from_cart()
    {
        $product = Product::factory()->create();

        $this->cartRepository->addToCart(['id' => $product->id, 'name' => $product->name, 'price' => $product->price], 'product', 2);
        $result = $this->cartRepository->removeItem(['id' => $product->id], 'product');

        $this->assertEmpty($this->cartRepository->findItemById(['id' => $product->id], 'product'));
    }

    public function test_can_clear_cart()
    {
        $product = Product::factory()->create();

        $this->cartRepository->addToCart(['id' => $product->id, 'name' => $product->name, 'price' => $product->price], 'product', 2);

        $this->cartRepository->clearCart();

        $this->assertNull($this->cartRepository->getCart());
    }

    public function test_can_fetch_cart_details()
    {
        $cartDetails = $this->cartRepository->cartDetails();
        $this->assertNull($cartDetails);
    }

    public function test_can_calculate_cart_totals()
    {
        $totals = $this->cartRepository->calcCart([]);
        $this->assertEquals('0.000', $totals['total']);
    }
}
