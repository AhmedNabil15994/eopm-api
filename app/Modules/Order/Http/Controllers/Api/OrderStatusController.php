<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Modules\Order\Transformers\Api\OrderStatusResource;
use Illuminate\Http\Request;
use Modules\Order\Repositories\Api\OrderRepository as Order;



class OrderStatusController extends ApiController
{

    public function __construct(
        public Order $order,
    ){}

    public function index(Request $request) {
        $orders = $this->order->getOrderStatuses($request);
        return $this->responsePaginationWithData(OrderStatusResource::collection($orders));
    }
}
