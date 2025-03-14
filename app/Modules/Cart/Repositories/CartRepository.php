<?php
namespace Modules\Cart\Repositories;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Entities\DatabaseStorageModel as Cart;
class CartRepository
{
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
        $identifier = request()->user_token;;
        $instance = 'guest';
        if (auth('api')->user()){
            $identifier = auth('api')->user()->id;
            $instance = 'user';
        }

        $this->instance = $instance;
        $this->identifier = $identifier;
    }

    public function addToCart($item, $type, $quantity)
    {
        $inCart = $this->findItemById($item, $type);
        if (!empty($inCart)) {
            return $this->updateItemInCart($item, $type,$quantity);
        }

        if($quantity){
            $this->addItemToCart($item, $type, $quantity);
        }

        return true;
    }

    public function getCart()
    {
        return $this->cart->where([
            ['instance' , $this->instance],
            ['identifier', $this->identifier]
        ])->first();
    }

    public function findItemById($item, $type)
    {
        $rowId = $item['id'];
        $cart = $this->getCart()?->getContent();
        if(!$cart){
            return [];
        }
        return array_filter($cart,function ($cartItem) use ($rowId) {
            return $cartItem['id'] === $rowId;
        });
    }

    public function removeItem($item, $type)
    {
        $this->updateItemInCart($item, $type,0);
    }

    public function clearCart()
    {
        return $this->getCart()?->delete();
    }

    public function updateItemInCart($item, $type,$quantity)
    {
        $cart = $this->getCart();
        $cartItems = json_decode($cart->content,true);
        foreach ($cartItems as $key => $cartItem) {
            $cartItem = (array) $cartItem;
            if($cartItem['type'] === $type && $cartItem['id'] == $item['id'] ){
                if($quantity){
                    $cartItems[$key]['qty'] = $quantity;
                }else{
                    unset($cartItems[$key]);
                }
                break;
            }
        }
        $calculations = $this->calcCart($cartItems);
        return $this->updateDB($calculations,$cartItems);
    }

    public function addItemToCart($item, $type, $quantity = 1)
    {
        $items = [
            [
                'id' => $item['id'],
                'name'  => $item['name'],
                'type'  => $type,
                'qty'  => $quantity ?? 1,
                'price' => number_format($item['price'],3),
                'extra_attributes' => []
            ]
        ];

        $cartObj = $this->cart->where([
            ['instance' , $this->instance],
            ['identifier' , $this->identifier],
        ])->first();

        if($cartObj){
            $items = json_decode($cartObj->content);
            $items[] = (object)[
                'id' => $item['id'],
                'name'  => $item['name'],
                'type'  => $type,
                'qty'  => $quantity ?? 1,
                'price' => number_format($item['price'],3),
                'extra_attributes' => []
            ];
        }

        $calculations = $this->calcCart($items);

        return $this->updateDB($calculations,$items);
    }

    public function updateDB($calculations,$items){
        DB::beginTransaction();
        try {
            $this->cart->updateOrCreate([
                'instance' => $this->instance,
                'identifier' => $this->identifier,
            ],[
                'tax' => $calculations['tax'],
                'discount' => $calculations['discount'],
                'subtotal' => $calculations['subtotal'],
                'total' => $calculations['total'],
                'count' => $calculations['count'],
                'content'   => json_encode($items),
            ]);
            DB::commit();
            return true;
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }

    public function calcCart($items)
    {
        $subtotal = 0;
        $tax = 0;
        $discount = 0;
        $total = 0;
        $count = 0;

        foreach ($items as $item) {
            $item = (array)$item;
            $subtotal += $item['price'] * $item['qty'];
            $count++;
        }
        $tax = $subtotal * config('cart.tax');
        $total = $subtotal + $tax;

        return [
            'count' => $count,
            'subtotal' => number_format($subtotal,3),
            'tax' => number_format($tax,3),
            'discount' => number_format($discount,3),
            'total' => number_format($total,3),
        ];
    }

    public function cartDetails()
    {
        $cart = $this->getCart()?->getContent();
        if(!$cart){
            return null;
        }
        $items = [];
        foreach ($cart as $key => $item) {
            $items[] = $item;
        }
        return $items;
    }

    public function cartTotal()
    {
        $cart = $this->getCart();
        return number_format($cart->total ?? 0,3);
    }

    public function cartSubTotal()
    {
        $cart = $this->getCart();
        return number_format($cart->subtotal ?? 0,3);
    }

    public function cartCount()
    {
        $cart = $this->getCart();
        return $cart ? count($cart->getContent()) : 0;
    }
}
