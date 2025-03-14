<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ScopesTrait;

class OrderStatus extends Model
{
    use ScopesTrait;

    protected $fillable= ['title','color_label', 'success_status', 'failed_status', 'final_status'];

}
