<?php

namespace Modules\Authentication\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Modules\Authentication\Repositories\Api\AuthenticationRepository;
use Modules\User\Transformers\Api\UserResource;
use Modules\Authentication\Foundation\Authentication;
use Modules\Authentication\Http\Requests\Api\LoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends ApiController
{
    use Authentication;

    protected $user;

    public function __construct(AuthenticationRepository $user)
    {
        $this->user = $user;
    }

    public function login(LoginRequest $request)
    {
        $failedAuth = $this->user->loginCredentials($request);
        if (!$failedAuth['status'])
            return $this->invalidData($failedAuth['data'], [], getStatusCode($request));

        return $this->tokenResponse($request,$failedAuth['data'],$failedAuth['token']);
    }

    public function tokenResponse($request,$user = null,$token=null)
    {
        $user = $user ?? auth('api')->user();
        return $this->response([
            'access_token' => $token,
            'user' => new UserResource($user),
            'token_type' => 'Bearer',
            'expires_at' => date('Y-m-d H:i:s',strtotime("+".config('jwt.ttl')." minutes"))
        ]);
    }

    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->response([], __('authentication::api.logout.messages.success'));
    }
}
