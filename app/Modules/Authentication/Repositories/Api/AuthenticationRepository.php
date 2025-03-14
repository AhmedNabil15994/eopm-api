<?php

namespace Modules\Authentication\Repositories\Api;

use App\Http\Controllers\Api\ApiController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Modules\Authentication\Foundation\Authentication;
use Modules\User\Entities\User;
use Modules\User\Transformers\Api\UserResource;

class AuthenticationRepository extends ApiController
{
    use Authentication;

    private $user;
    private $password;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['status'] = 1;
            $user = $this->user->create($data);
            DB::commit();
            $token = self::authentication($user);
            return ['status' => 1 , 'data' => $user , 'token' => $token];
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }

    public function loginCredentials($request)
    {
        try {
            $user = $this->findUserByEmailActive($request);
            if ($user && Hash::check($request->password, $user->password)) {
                $token = self::authentication($user);
                return ['status' => 1 , 'data' => $user , 'token' => $token];
            }

            return ['status' => 0 ,'data' => new MessageBag([
                'password' => __('authentication::api.login.validation.failed'),
            ])];

        } catch (\Exception$e) {
            throw $e;
        }
    }

    public function getAuthUser($request = null)
    {
        return $request ? $request->user() : auth()->user();
    }

    public function findUserByEmailActive($request, $checkClubApp = false)
    {
        $user = $this->user->where('email', $request->email);
        return $user->first();
    }

    public function profileInfo($request)
    {
        $user = $request->user();
        return [
            'user' => new UserResource($user),
        ];
    }

    public function tokenResponse(Request $request, $user = null)
    {
        $user = $user ? $user : $request->user();
        $user->refresh();

//        if ($request->user_token)
//            $this->updateCartKey($request->user_token,$user->id);

        $token = $this->generateToken($request, $user);

        return $this->response([
            'access_token' => $token->plainTextToken,
            'user' => new UserResource($user),
            'token_type' => 'Bearer',
        ]);
    }

}
