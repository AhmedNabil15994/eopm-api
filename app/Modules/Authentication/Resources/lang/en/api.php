<?php

return [
    'login'             => [
        'validation'    => [
            'email'     => [
                'email'     => 'Please enter correct email format',
                'required'  => 'The email field is required',
            ],
            'failed'    => 'These credentials do not match our records.',
            'password'  => [
                'min'       => 'Password must be more than 6 characters',
                'required'  => 'The password field is required',
            ],
        ],
        'messages'      => [
            'failed'    => 'This number is not registered, please register first',
            'relogin'   => 'Session Expired, please login again',
        ],
    ],
    'logout'            => [
        'messages'  => [
            'failed'    => 'logout failed',
            'success'   => 'logout successfully',
        ],
    ],
    'register'          => [
        'messages'      => [
            'failed'    => 'Register Failed , Please try again later',
        ],
        'validation'    => [
            'email'     => [
                'email'     => 'Please enter correct email format',
                'required'  => 'The email field is required',
                'unique'    => 'The email has already been taken',
            ],
            'calling_code'    => [
                'numeric'           => 'The calling_code must be a number',
                'required'          => 'The calling_code field is required',
            ],
            'mobile'    => [
                'digits_between'    => 'You must enter mobile number with 8 digits',
                'numeric'           => 'The mobile must be a number',
                'required'          => 'The mobile field is required',
                'unique'            => 'The mobile has already been taken',
            ],
            'name'      => [
                'required'  => 'The name field is required',
            ],
            'password'  => [
                'confirmed' => 'Password not match with the confirmation',
                'min'       => 'Password must be more than 8 characters',
                'required'  => 'The password field is required',
            ],
            'password_confirmation'  => [
                'min'       => 'Password Confirmation must be more than 8 characters',
                'required'  => 'The password field is required',
            ],
        ],
    ],
];
