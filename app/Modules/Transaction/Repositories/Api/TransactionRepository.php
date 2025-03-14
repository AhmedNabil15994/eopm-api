<?php

namespace Modules\Transaction\Repositories\Api;

use Illuminate\Support\Facades\DB;
use Modules\Transaction\Entities\Transaction;

class TransactionRepository
{
    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    public function getAll($request){
        $query = $this->model;
        if(isset($request['status']) && !empty($request['status'])){
            $query = $query->where('result',$request['status']);
        }

        $query = $query->whereHas('order',function($query) use ($request){
            $query->where('orders.user_id',auth('api')->id());
            if(isset($request['order_id']) && !empty($request['order_id'])){
                $query->where('id',$request['order_id']);
            }
        });

        return $query->orderBy('id','desc')->paginate($request->per_page ?? env('PER_PAGE'));
    }

    public function getById($id){
        $id = (int) $id;
        return  $this->model->whereHas('order',function($query){
            $query->where('user_id',auth('api')->id());
        })->find($id);
    }

}
