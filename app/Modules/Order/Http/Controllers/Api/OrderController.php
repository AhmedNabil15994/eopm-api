<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Modules\Cart\Repositories\CartRepository;
use Modules\Product\Repositories\Api\ProductRepository;
use Modules\Order\Transformers\Api\OrderResource;
use Modules\Authentication\Foundation\Authentication;
use Modules\Order\Repositories\Api\OrderRepository as Order;
use Modules\Authentication\Repositories\Api\AuthenticationRepository;
use DB;


class OrderController extends ApiController
{
    use Authentication;


    public function __construct(
        public Order                    $order,
        public ProductRepository    $product,
        public AuthenticationRepository $auth,
        public CartRepository           $cart,
        public PaymentGatewayService    $paymentService
    ){}

    public function index(Request $request) {
        $orders = $this->order->getAll($request);
        return $this->responsePaginationWithData(OrderResource::collection($orders));
    }

    public function show($id) {
        $order = $this->order->findOrderUserById($id);
        if(!$order){
            return  $this->error(__('order::api.invalid_order'));
        }

        return $this->response(new OrderResource($order));
    }

    public function create(Request $request)
    {
        if ($this->cart->cartCount() > 0) {
            return $this->addOrder($request);
        }else{
            return $this->error(__('cart::api.cart.empty_cart'));
        }
    }

    public function addOrder($data)
    {
        $user = $data->user();
        $data['user_id'] = $user->id;
        $order =  $this->order->create($data);
        $this->cart->clearCart();

        return $this->response([
            'order_id'  => $order->id,
        ],__('order::api.orders.in_review'));
    }

    public function checkout(Request $request,$id)
    {
        if(!isset($request->payment_method) || !in_array($request->payment_method,config('app.supported_payments'))){
            return $this->error(__('order::api.invalid_payment_method'));
        }

        $order = $this->order->findOrderUserById($id);
        if(!$order){
            return  $this->error(__('order::api.invalid_order'));
        }

        if($order->order_status_id == 2){ // if order is paid before
            return $this->error(__('order::api.orders.paid_before'));
        }

        if(in_array($order->order_status_id,[3,4])){ // if order is cancelled or failed
            return $this->error(__('order::api.invalid_order'));
        }

        if($order->order_status_id != 5){
            return $this->error(__('order::api.order_in_review'));
        };

        $payment = $this->paymentService->processPayment($order, $request->payment_method);

        if($request->payment_method != 'cash'){
            if (isset($payment['status'])) {
                if ($payment['status'] == true) {
                    return $this->response([
                        'payment_url' => $payment['url'],
                        'order_id'  => $order->id,
                    ]);
                } else {
                    return $this->error(__('cart::api.cart.invalid_payment'));
                }
            }
        }

        return $this->response(OrderResource::make($order));
    }

    public function accept(Request $request,$id)
    {
        $order = $this->order->findById($id);
        if(!$order){
            return  $this->error(__('order::api.invalid_order'));
        }

        if($order->order_status_id != 1){
            return $this->error(__('order::api.invalid_order'));
        };

        $order->update(['order_status_id' => 5]); // change to confirmed status

        return $this->response([
            'order_id'  => $order->id,
        ],__('order::api.order_statuses.status.pending'));
    }

    public function delete(Request $request,$id)
    {
        $order = $this->order->findOrderUserById($id);
        if(!$order){
            return  $this->error(__('order::api.invalid_order'));
        }

        if($order->transactions){
            return $this->error(__('order::api.order_has_transactions'));
        };

        $this->order->deleteOrder($order);
        return $this->response([],__('order::api.orders.deleted'));
    }

    public function cancel(Request $request,$id)
    {
        $order = $this->order->findOrderUserById($id);
        if(!$order){
            return  $this->error(__('order::api.invalid_order'));
        }

        if($order->order_status_id == 4){
            return $this->error(__('order::api.orders.cancelled_before'));
        };

        $this->order->deleteOrder($order,false);
        return $this->response([],__('order::api.orders.cancelled'));
    }

    public function success(Request $request,$method)
    {
        if(!in_array($method,config('app.supported_payments'))){
            return $this->error('Payment method not supported');
        }

        $order = $this->order->findById($request['OrderID']);
        if (!$order) {
            return false;
        }

        $this->paymentService->createTransaction($order, $request,$method,'success');
        $this->order->update($request['OrderID'], 2);

        return $this->response([
            'order' => new OrderResource($order),
        ], __('cart::api.cart.success_payment'));
    }

    public function failed(Request $request,$method)
    {
        if(!in_array($method,config('app.supported_payments'))){
            return $this->error('Payment method not supported');
        }

        $order = $this->order->findById($request['OrderID']);
        if (!$order) {
            return false;
        }

        $this->paymentService->createTransaction($order, $request,$method,'failed');
        $this->order->update($request['OrderID'], 3);

        return $this->error(__('cart::api.cart.failed_payment'));
    }
}
