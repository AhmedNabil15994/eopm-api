<?php

namespace App\Modules\Order\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatusResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'                 => $this->id,
            'title'           => $this->title,
       ];
    }
}
