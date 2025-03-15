<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use IlluminateAgnostic\Collection\Support\Carbon;
use Modules\Product\Entities\Product;
use App\Traits\ScopesTrait;
use Modules\Transaction\Entities\Transaction;

class Order extends Model
{
    use SoftDeletes ;
    use ScopesTrait;
    use HasFactory;

    protected $fillable = [
        'total',
        'subtotal',
        'discount',
        'tax',
        'note',
        'user_id',
        'order_status_id',
        'payment_method'
    ];

    public function user()
    {
        return $this->belongsTo(\Modules\User\Entities\User::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, OrderItem::class, 'order_id', 'id', 'id', 'product_id');
    }


    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transaction');
    }
}
