<?php

return [
    'invalid_order' => "This Order is invalid or doesn't belong to you.",
    'invalid_invoice' => "This Invoice is invalid or doesn't belong to you.",
    'in_review' => 'This order is in review, please wait',
    'order_in_review'   => 'This order is in review, please wait to be approved',
    'invalid_payment_method'    => 'This payment method is invalid .',
    'order_has_transactions'    => "This order has transactions so you can't delete it.",
    'orders'    => [
        'in_review' => 'Your order is being reviewed, please wait.',
        'deleted' => 'Your order has been deleted.',
        'cancelled' => 'Your order has been cancelled.',
        'cancelled_before' => 'Your order has been cancelled before.',
        'paid_before' => 'Your order has been paid before.',
        'validations'   => [
            'availability'  => [
                'not_available' => 'Doctor not available in your selection, please change date or time.',
            ],
            'celebrity'     => [
                'not_available' => 'Sorry this celebrity busy , can not receive the request',
            ],
            'celebrity_id'  => [
                'required'  => 'Please select the celebrities id',
            ],
            'date'          => [
                'required'  => 'Please select the date',
            ],
            'doctor_id'     => [
                'required'  => 'Select doctor',
            ],
            'email'         => [
                'required'  => 'Please fill the email input',
            ],
            'instructions'  => [
                'required'  => 'Please fill the instructions',
            ],
            'max'           => [
                'required'  => 'The maximum file size allowed for videos 10 MB',
            ],
            'mimes'         => [
                'required'  => 'You should upload the video extinction with mp4',
            ],
            'mobile'        => [
                'required'  => 'Please fill the mobile input',
            ],
            'name'          => [
                'required'  => 'Please fill the name input',
            ],
            'occasion_id'   => [
                'required'  => 'Please select the occasion',
            ],
            'service_id'    => [
                'required'  => 'Select service',
            ],
            'time_from'     => [
                'required'  => 'Select time from',
            ],
            'time_to'       => [
                'required'  => 'Select time from',
            ],
            'to'            => [
                'required'  => 'please fill the name input',
            ],
            'video'         => [
                'required'  => 'Please upload your video',
            ],
            'worker_id'     => [
                'required'  => 'Select worker id',
            ],
        ],
    ],
    'order_statuses'    => [
        'status'    => [
            'success'   => 'Success',
            'failed'    => 'Failed',
            'pending'   => 'Pending',
            'in-complete'   => 'In-Complete',
            'in_review'   => 'In-Review',
        ],
        'payments'  => [
            'moyasar'   => 'Moyasar',
            'upayment'  => 'Upayment',
            'wallet'    => 'E-Wallet',
            'myfatoorah' => 'MyFatoorah',
        ],
    ],
];
