<?php

namespace Modules\Authentication\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'     => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'email.required' => __('authentication::api.login.validation.email.required'),
            'email.email' => __('authentication::api.login.validation.email.email'),
            'password.required' => __('authentication::api.login.validation.password.required'),
            'password.min' => __('authentication::api.login.validation.password.min'),
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $code = 422;
        $response = new JsonResponse([
            'message' => 'The given data is invalid',
            'code'  => $code,
            'errors' => $validator->errors()
            ], $code);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
