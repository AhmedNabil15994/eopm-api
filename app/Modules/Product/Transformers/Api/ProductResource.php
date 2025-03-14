<?php

namespace Modules\Product\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public function __construct($resource,$displayQty=true)
    {
        $this->resource = $resource;
        $this->displayQty = $displayQty;
    }
    public function toArray($request)
    {
        $extra= [];
        $base = [
            'id'            => $this->id,
            'name'        => $this->name,
            'price'        => number_format($this->price ?? 0,3),
        ];
        if($this->displayQty){
            $extra=[
                'qty'       =>  $this->qty,
            ];
        }
        return  array_merge($base,$extra);
    }
}
