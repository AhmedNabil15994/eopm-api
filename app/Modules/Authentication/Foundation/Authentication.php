<?php

namespace Modules\Authentication\Foundation;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

trait Authentication
{
    public static function authentication($credentials)
    {
        return JWTAuth::fromUser($credentials);
    }

    public function login($credentials)
    {
        try {
            if (self::authentication($credentials)) {
                return false;
            }

            $errors = new MessageBag([
                'password' => __('authentication::dashboard.login.validations.failed')
            ]);

            return $errors;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function loginAfterRegister($credentials)
    {
        try {
            self::authentication($credentials);
        } catch (Exception $e) {
            return $e;
        }
    }

}
