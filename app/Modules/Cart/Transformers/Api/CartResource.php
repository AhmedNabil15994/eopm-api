<?php

namespace Modules\Cart\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Entities\Product;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
         return [
            'id' => $this['id'],
            'name' => $this['name'],
            'quantity'  => (int) $this['qty'],
             'price'    =>  number_format($this['price'],3),
        ];
    }
}
