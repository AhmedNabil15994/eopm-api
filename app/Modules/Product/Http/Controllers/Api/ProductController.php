<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Modules\Product\Repositories\Api\ProductRepository;
use Modules\Product\Transformers\Api\ProductResource;

class ProductController extends ApiController
{
    public function __construct(ProductRepository $product)
    {
        $this->product = $product;
    }
    public function index(Request $request) {
        $products = $this->product->getAll($request);
        return $this->responsePaginationWithData(ProductResource::collection($products));
    }

    public function show(Request $request,$id) {
        $product   = $this->product->getById($id);
        if(!$product){
            return $this->invalidData(__('product::api.products.invalid_product'), [], 404);
        }

        $data = (new ProductResource($product))->jsonSerialize();
        return $this->response($data);
    }
}
