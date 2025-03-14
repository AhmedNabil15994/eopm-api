<?php

namespace Modules\Cart\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Modules\Cart\Repositories\CartRepository;
use Modules\Cart\Transformers\Api\CartResource;
use Modules\Product\Repositories\Api\ProductRepository;
use Modules\Product\Transformers\Api\ProductCartResource;

class CartController extends ApiController
{

    public function __construct(CartRepository $cart, ProductRepository $product)
    {
        $this->cart = $cart;
        $this->product = $product;
    }

    public function index(Request $request)
    {
        if (!auth('api')->user() && is_null($request->user_token)) {
            return $this->invalidData(__('cart::api.general.user_token_not_found'), [],422);
        }
        return $this->response($this->responseData($request));
    }

    public function createOrUpdate(Request $request,$productId)
    {
        if (!auth('api')->user() && is_null($request->user_token)) {
            return $this->invalidData(__('cart::api.general.user_token_not_found'), [],422);
        }
        $type = 'product';
        $userToken = $request->user_token ?? null;
        $item = $this->getItem((int)$productId, $type);
        if (is_null($item)) {
            return $this->error(__('cart::api.products.not_found'), [], 404);
        }

        if(!is_null($item['qty']) && $request->qty > $item['qty'] ){
            return $this->error(__('cart::api.products.request_limit',['limit' => $item['qty']]), [], 422);
        }

        $this->cart->addToCart($item, $type, (int)$request->qty);

        return $this->response($this->responseData($request));
    }

    private function  getItem($id, $type)
    {
        try {
            switch($type){
                case 'product':
                    $model = $this->product->getById($id);
                    $item = !is_null($model) ? (new ProductCartResource($model))->jsonSerialize() : null;
                    break;
            }
            return $item;
        } catch (\Throwable $th) {

        }
    }

    public function remove(Request $request,$id)
    {
        if (!auth('api')->user() && is_null($request->user_token)) {
            return $this->invalidData(__('cart::api.general.user_token_not_found'), [],422);
        }
        $type = 'product';
        $userToken = $request->user_token ?? null;
        $item = $this->getItem((int)$id, $type);

        if (is_null($item)) {
            return $this->error(__('cart::api.products.not_found'), [], 404);
        }

        $inCart = $this->cart->findItemById($item, $type);
        if (empty($inCart)) {
            return $this->error(__('cart::api.products.not_found_in_cart'), [], 404);
        }

        $this->cart->removeItem($item,$type);
        return $this->response($this->responseData($request));
    }

    public function clear(Request $request)
    {
        $this->cart->clearCart();
        return $this->response([]);
    }

    public function responseData($request)
    {
        $collections = collect($this->cart->cartDetails($request));
        $data = $this->returnCustomResponse($request);
        $data['items'] = CartResource::collection($collections);
        return $data;
    }

    protected function returnCustomResponse($request)
    {
        return [
            'subTotal' => $this->cart->cartSubTotal($request),
            'total' => $this->cart->cartTotal($request),
            'count' => $this->cart->cartCount($request),
        ];
    }
}
