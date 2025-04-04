<?php

return [
    'login'             => [
        'validation'    => [
            'email'     => [
                'email'     => 'من فضلك ادخل البريد بشكل صحيح',
                'required'  => 'من فضلك ادخل البريد الالكتروني',
            ],
            'failed'    => 'هذه البيانات غير متطابقة لدينا من فضلك تآكد من بيانات تسجيل الدخول',
            'password'  => [
                'min'       => 'كلمة المرور يجب ان تكون اكثر من ٦ مدخلات',
                'required'  => 'يجب ان تدخل كلمة المرور',
            ],
        ],
        'messages'      => [
            'failed'    => 'هذا الرقم غير مُسجلاً يرجى إعادة التسجيل',
            'relogin'   => 'انتهت الجلسة، يرجى تسجيل الدخول مرة أخرى',

        ],
    ],
    'logout'            => [
        'messages'  => [
            'failed'    => 'فشلت عملية تسجيل الخروج',
            'success'   => 'تم تسجيل الخروج بنجاح',
        ],
    ],
    'register'          => [
        'messages'      => [
            'failed'    => 'فشلت عملية تسجيل الدخول ، حاول مره اخرى',
        ],
        'validation'    => [
            'email'     => [
                'email'     => 'من فضلك ادخل البريد بشكل صحيح',
                'required'  => 'من فضلك ادخل البريد الالكتروني',
                'unique'    => 'هذا البريد الالكتروني تم حجزة من قبل شخص اخر',
            ],
            'mobile'    => [
                'digits_between'    => 'يجب ان يتكون رقم الهاتف من ٨ ارقام',
                'numeric'           => 'من فضلك ادخل رقم الهاتف من ارقام انجليزية فقط',
                'required'          => 'من فضلك ادخل رقم الهاتف',
                'unique'            => 'رقم الهاتف تم حجزه من قبل شخص اخر',
            ],
            'calling_code'    => [
                'numeric'           => 'من فضلك ادخل كود الدوله من ارقام انجليزية فقط',
                'required'          => 'من فضلك ادخل كود الدوله',
            ],
            'name'      => [
                'required'  => 'من فضلك ادخل الاسم الشخصي',
            ],
            'password'  => [
                'confirmed' => 'كلمة المرور غير متطابقة مع التآكيد',
                'min'       => 'كلمة المرور يجب ان تتكون من اكثر من ٦ مدخلات',
                'required'  => 'يجب ان تدخل كلمة المرور',
            ],
            'password_confirmation'  => [
                'min'       => 'تأكيد كلمة المرور يجب ان تتكون من اكثر من ٦ مدخلات',
                'required'  => 'يجب ان يتم تأكيد كلمة المرور',
            ],
        ],
    ],
];
