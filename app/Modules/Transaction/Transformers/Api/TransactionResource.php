<?php

namespace Modules\Transaction\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */


    public function toArray($request)
    {
        return  [
            'id'    => $this->id,
            'method'    => $this->method,
            'payment_id'    => $this->payment_id,
            'tran_id'       => $this->tran_id,
            'result'        => $this->result,
            'post_date'     => $this->postDate,
            'ref'           => $this->ref,
            'track_id'      => $this->track_id,
            'order_id'      => $this->transaction_id,
            'user_id'       => $this->order?->user_id,
        ];
    }
}
