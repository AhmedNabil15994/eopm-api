<?php

namespace Modules\Order\Repositories\Api;

use Modules\Order\Entities\OrderItem;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Modules\User\Entities\User;
use Carbon\Carbon;
use Auth;
use DB;
use Modules\Cart\Entities\DatabaseStorageModel as Cart;

class OrderRepository
{

    public function __construct(Order $order, OrderStatus $status, User $user,Cart $cart,Product $product)
    {
        $this->user      = $user;
        $this->order     = $order;
        $this->status    = $status;
        $this->cart    = $cart;
        $this->product    = $product;
    }

    public function getOrderStatuses() {
        return $this->status->orderBy('id','desc')->paginate($request->per_page ?? env('PER_PAGE'));
    }

    public function getAll($request){
        $query = auth('api')->user()->orders();

        $query = $query->where(function ($q) use ($request){
            if (isset($request->search) && !empty($request->search)) {
                $q->where(\Illuminate\Support\Facades\DB::raw('lower(title)'),'LIKE','%'.strtolower($request->search).'%');
            }
            if(isset($request->status) && !empty($request->status)){
                $q->whereHas('orderStatus',function($q2) use($request){
                   $q2->where('title',$request->status);
                });
            }
        });

        return $query->orderBy('id','desc')->paginate($request->per_page ?? env('PER_PAGE'));
    }

    public function findById($id)
    {
        return $this->order->where('id', $id)->first();
    }

    public function findOrderUserById($id)
    {
        return auth('api')->user()->orders()->where('id', $id)->first();
    }



    public function calculateTheOrder()
    {
        $cart = $this->cart->where([
            ['instance' , 'user'],
            ['identifier' , auth('api')->id()]
        ])->first();

        if (!$cart) {
            throw new \Exception('Cart is empty or not found for the user.');
        }
        return $cart;
    }

    public function create($request, $status = true)
    {
        DB::beginTransaction();

        try {
            $data = $this->calculateTheOrder();
            $user =  $this->user->find($request['user_token']);
            if(auth('api')->check()){
                $user = auth('api')->user();
            }

            $order = $this->order->create([
                'subtotal'          => $data->subtotal,
                'discount'          => $data->discount,
                'total'             => $data->total,
                'tax'             => $data->tax,
                'user_id'           => $user ? $user['id'] : 1,
                'order_status_id'   => 1, // pending status
            ]);

            $this->orderProducts($order, $data);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function orderProducts($order, $data)
    {
        $items = json_decode($data->content,true);
        foreach ($items as $key => $orderItem) {
            $count = $orderItem['qty'];
            $availableItems = $this->product->find($orderItem['id']);
            if($availableItems?->qty < $count){
                return false;
            }

            $remaining = $availableItems?->qty - $count;
            if($remaining >= 0){
                $availableItems->update(['qty' => $remaining]);
            }

            $price = $orderItem['price'] * $orderItem['qty'];

            $order->orderItems()->create([
                'product_id'    => $orderItem['id'],
                'qty'        => $orderItem['qty'],
                'total'        => number_format($price,3),
                'user_id'      => auth('api')->id(),
            ]);
        }
    }

    public function deleteOrder($order,$delete=true)
    {
        DB::beginTransaction();
        try {
            $order->update(['order_status_id' => 4]); // change to cancelled status
            foreach ($order->orderItems as $item) {
                $item->product->increment('qty', $item->qty);
            }
            DB::commit();
            return $delete ? $order->delete() : true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateOrder($request)
    {
        $order = $this->findById($request['OrderID']);

        $status = ($request['Result'] == 'CAPTURED') ? $this->statusOfOrder(true) : $this->statusOfOrder(false);

        $order->update([
          'order_status_id' => $status['id'],
          'is_holding'      => false
        ]);

        $order->transactions()->updateOrCreate(
            [
            'transaction_id'  => $request['OrderID']
          ],
            [
            'auth'          => $request['Auth'],
            'tran_id'       => $request['TranID'],
            'result'        => $request['Result'],
            'post_date'     => $request['PostDate'],
            'ref'           => $request['Ref'],
            'track_id'      => $request['TrackID'],
            'payment_id'    => $request['PaymentID'],
        ]
        );

        return ($request['Result'] == 'CAPTURED') ? true : false;
    }

    public function statusOfOrder($type)
    {
        if ($type == 1) {
            $status = $this->status->successPayment()->first();
        }else if($type == 2){
            $status = $this->status->failedOrderStatus()->first();
        }else if ($type == 3) {
            $status = $this->status->pendingOrderStatus()->first();
        }else if ($type == 4) {
            $status = $this->status->inReviewStatus()->first();
        }
        return $status;
    }

    public function update($id, $boolean)
    {
        $order = $this->findById($id);

        $order->update([
            'order_status_id' => $boolean,
        ]);

        return $order;
    }


}
