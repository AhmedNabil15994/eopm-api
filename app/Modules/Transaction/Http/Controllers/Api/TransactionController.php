<?php

namespace Modules\Transaction\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Modules\Transaction\Repositories\Api\TransactionRepository;
use Modules\Transaction\Transformers\Api\TransactionResource;

class TransactionController extends ApiController
{
    public function __construct(TransactionRepository $transaction)
    {
        $this->transaction = $transaction;
    }
    public function index(Request $request) {
        $transactions = $this->transaction->getAll($request);
        return $this->responsePaginationWithData(TransactionResource::collection($transactions));
    }

    public function show(Request $request,$id) {
        $transaction   = $this->transaction->getById($id);
        if(!$transaction){
            return $this->invalidData(__('transaction::api.transactions.invalid_transaction'), [], 404);
        }

        $data = (new TransactionResource($transaction))->jsonSerialize();
        return $this->response($data);
    }
}
