<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class LoginRequest extends FormRequest
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
            'email'     => 'required|string|email|exists:users',
            'password'  => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'email.required'        => 'Email is required',
            'email.string'          => 'Email must be a string',
            'email.email'           => 'Wrong email format',
            'email.exists'          => 'This email is not registered. Please signup to continue.',
            'password.required'     => 'Password is required',
            'password.string'       => 'Password must be valid',
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
