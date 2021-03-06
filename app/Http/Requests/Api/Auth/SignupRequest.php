<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                      => 'required|string|max:180',
            'email'                     => 'required|string|email|max:255|unique:users',
            'password'                  => 'required|string|confirmed|min:8',
            'password_confirmation'     => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required'                     => 'Name is required',
            'name.string'                       => 'Name must be valid',
            'name.max'                          => 'Name can have max 180 characters',
            'email.required'                    => 'Email is required',
            'email.unique'                      => 'Email already registered. Please login to your account.',
            'email.max'                         => 'Email can have max 255 characters',
            'password.string'                   => 'Password must be valid',
            'password.min'                      => 'Password must have minimum 8 characters',
            'password.confirmed'                => 'Passwords does not match',
            'password_confirmation.required'    => 'Confirmation Password is required',
            'password_confirmation.min'         => 'Confirmation Password must have minimum 8 characters',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json([
            'errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
