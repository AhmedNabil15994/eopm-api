<?php

namespace Modules\Order\Entities;

use Carbon\Carbon;
use Modules\Product\Entities\Product;
use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'qty',
        'total',
        'product_id',
        'order_id',
        'user_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    function scopeSuccessPay($q)
    {
        $q->whereHas(
            'order',
            fn ($q) => $q->whereHas(
                'orderStatus',
                fn ($q) => $q->successPayment()
            )
        );
    }
}
