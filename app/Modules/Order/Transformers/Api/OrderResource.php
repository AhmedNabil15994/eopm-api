<?php

namespace Modules\Order\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
//        $method = $this->orderStatus->success_status ? $this->transactions()->whereIn('result',['paid','CAPTURED'])->first()?->method : '';
//        'transaction'       => __('order::dashboard.order_statuses.payments.'.$method),

        return [
            'id'                 => $this->id,
            'user_id'           => $this->user_id,
            'subtotal'           => $this->subtotal,
            'discount'           => $this->discount,
            'tax'               => $this->tax,
           'total'              => $this->total,
           'items'             => OrderProductResource::collection($this->orderItems()->groupBy('product_id')->get() ?? []),
           'order_status'       => __('order::api.order_statuses.status.'.$this->orderStatus->title),
           'created_at'         => date('d-m-Y H:i A' , strtotime($this->created_at)),
       ];
    }
}
