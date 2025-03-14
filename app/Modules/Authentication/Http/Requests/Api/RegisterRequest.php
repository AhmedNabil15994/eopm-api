<?php

namespace Modules\Authentication\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required',
            'calling_code' => 'required|numeric',
            'mobile' =>  'required|numeric|unique:users,mobile,'. $this->mobile . '',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
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
            'name.required' => __('authentication::api.register.validation.name.required'),
            'calling_code.required' => __('authentication::api.register.validation.calling_code.required'),
            'calling_code.numeric' => __('authentication::api.register.validation.calling_code.numeric'),
            'mobile.required' => __('authentication::api.register.validation.mobile.required'),
            'mobile.numeric' => __('authentication::api.register.validation.mobile.numeric'),
            'mobile.unique' => __('authentication::api.register.validation.mobile.unique'),
            'email.required' => __('authentication::api.register.validation.email.required'),
            'email.email' => __('authentication::api.register.validation.email.email'),
            'email.unique' => __('authentication::api.register.validation.email.unique'),
            'password.required' => __('authentication::api.register.validation.password.required'),
            'password.min' => __('authentication::api.register.validation.password.min'),
            'password.confirmed' => __('authentication::api.register.validation.password.confirmed'),
            'password_confirmation.required' => __('authentication::api.register.validation.password_confirmation.required'),
            'password_confirmation.min' => __('authentication::api.register.validation.password_confirmation.min'),
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
