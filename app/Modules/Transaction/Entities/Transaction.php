<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Entities\Order;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
      'auth',
      'method' ,
      'tran_id' ,
      'result' ,
      'post_date' ,
      'ref' ,
      'track_id' ,
      'payment_id' ,
      'transaction_type' ,
      'transaction_id',
    ];

    public function transaction()
    {
        return $this->morphTo();
    }

    public function order()
    {
        return $this->hasOne(Order::class,'id','transaction_id');
    }
}
