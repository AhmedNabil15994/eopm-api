<?php

namespace Modules\User\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $base =  [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'mobile'        => $this->mobile,
            'calling_code'        => $this->calling_code,
        ];


        return $base;
    }
}
