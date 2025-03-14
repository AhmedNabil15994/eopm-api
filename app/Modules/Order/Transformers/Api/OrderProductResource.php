<?php

namespace Modules\Order\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\Api\ProductResource;

class OrderProductResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'product'              => (new ProductResource($this->product,false))->jsonSerialize(),
            'qty'                => $this->qty,
            'total'              => number_format($this->total  , 3),
       ];
    }
}
