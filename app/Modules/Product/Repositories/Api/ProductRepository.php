<?php

namespace Modules\Product\Repositories\Api;

use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;

class ProductRepository
{

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getAll($request){
        $query = $this->model->active();

        $query = $query->where(function ($q) use ($request){
                if (isset($request->search) && !empty($request->search)) {
                    $q->where(DB::raw('lower(title)'),'LIKE','%'.strtolower($request->search).'%');
                }
            });

//        if(isset($request->is_my_orders) && $request->is_my_orders){
//            $query = $query->whereHas('orderItems',function ($where){
//                $where->whereUserId(auth('sanctum')->id())
//                    ->notExpired()
//                    ->successPay();
//            });
//        }

        return $query->orderBy('id','desc')->paginate($request->per_page ?? env('PER_PAGE'));
    }

    public function getById($id){
        $id = (int) $id;
        return  $this->model->active()->find($id);
    }

    public function userProducts(){
//        return $this->model
//            ->when(auth('sanctum')->user(), fn ($q) => $q->subscribed(auth('sanctum')->id()))
//            ->withCount('orderItems')
//            ->whereHas(
//                'orderItems',
//                fn ($q) => $q
//                    ->whereUserId(auth('sanctum')->id())
//                    ->notExpired()
//                    ->successPay()
//            )->orderBy('order','asc')->get();
    }

}
