<?php

namespace Modules\User\Repositories\Api;

use Modules\User\Entities\User;
use Hash;
use DB;

class UserRepository
{

    function __construct(User $user)
    {
        $this->user  = $user;
    }

    public function getAll()
    {
        return $this->user->orderBy('id','DESC')->get();
    }


    public function update($request)
    {
        $user = auth()->user();

        if ($request['password'] == null)
            $password = $user['password'];
        else
            $password  = Hash::make($request['password']);

        DB::beginTransaction();

        try {

            $user->update([
                'name'          => $request['name'],
                'email'         => $request['email'],
                'mobile'        => $request['mobile'],
                'first_login'   => false,
                'phone_code'    => '965',
                'password'      => $password,
            ]);

            $user->refresh();
            DB::commit();
            return true;

        }catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
    }


    public function userProfile()
    {
        return $this->user->where('id',auth()->id())->first();
    }

    public function findById($id)
    {
        return $this->user->find($id);
    }

    public function deleteAccount()
    {
        return $this->user->find(auth()->id());
    }


}
