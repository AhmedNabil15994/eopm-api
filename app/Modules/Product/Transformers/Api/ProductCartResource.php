<?php

namespace Modules\Product\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\Api\CategoryResource;

class ProductCartResource extends JsonResource
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
            'id'            => $this->id,
            'name'        => $this->name,
            'price'        =>  number_format($this->price ?? 0,3),
            'qty'       =>  $this->qty,
       ];
    }
}
