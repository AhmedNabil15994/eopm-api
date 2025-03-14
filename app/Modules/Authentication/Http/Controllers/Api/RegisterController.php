<?php

namespace Modules\Authentication\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\DB;
use Modules\Authentication\Foundation\Authentication;
use Modules\Authentication\Http\Requests\Api\RegisterRequest;
use Modules\Authentication\Repositories\Api\AuthenticationRepository as AuthenticationRepo;
use Modules\User\Transformers\Api\UserResource;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends ApiController
{
    use Authentication;

    protected $auth;

    public function __construct(AuthenticationRepo $auth)
    {
        $this->auth = $auth;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $registered = $this->auth->register($request);
            if (!$registered['status'])
                return $this->invalidData($registered['data'], [], getStatusCode($request));

            return $this->responseData($request,$registered['data'],$registered['token']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function responseData($request,$user = null,$token = null)
    {
        $user = $user ? $user : auth('api')->user();
        return $this->response([
            'access_token' => $token,
            'user' => new UserResource($user),
            'token_type' => 'Bearer',
            'expires_at' => date('Y-m-d H:i:s',strtotime("+".config('jwt.ttl')." minutes"))
        ]);
    }

}
