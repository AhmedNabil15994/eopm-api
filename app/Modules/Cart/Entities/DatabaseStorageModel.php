<?php

namespace Modules\Cart\Entities;

use Illuminate\Database\Eloquent\Model;

class DatabaseStorageModel extends Model
{

    protected $table = 'shoppingcart';
    protected $fillable = [
        'identifier','instance','content','tax','discount','subtotal','total','created_at','updated_at'
    ];

    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    public function scopeInstance($query,$userId)
    {
        $instaceArr = explode('-',$userId);
        return $query->where('instance',$instaceArr[0])->where('identifier',$instaceArr[1]);
    }

    public function getContent()
    {
        return json_decode($this->content,true);
    }

}
