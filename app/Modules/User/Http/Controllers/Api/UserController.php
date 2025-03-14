<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\User\Transformers\Api\UserResource;
use Modules\User\Repositories\Api\UserRepository as User;

class UserController extends ApiController
{
    function __construct(User $user)
    {
        $this->user = $user;
    }

    public function profile()
    {
        $user =  $this->user->userProfile();
        return $this->response(new UserResource($user));
    }

    public function deleteUser(Request $request)
    {
        $user =  $this->user->findById(auth()->id());
        if($user){
            $user->delete();
        }
        return $this->response([]);
    }
}
