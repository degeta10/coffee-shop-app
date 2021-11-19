<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class UpdateProfileRequest extends FormRequest
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
            'name'          => ['required', 'string', 'max:180'],
            'gender'        => ['required', 'in:male,female'],
            'dob'           => ['required', 'date', 'date_format:Y-m-d'],
            'phone'         => ['required', 'min:7', 'max:20'],
            'address'       => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required'             => 'Name is required',
            'name.string'               => 'Name must be valid',
            'name.max'                  => 'Name can have max 180 characters',
            'dob.required'              => 'Date of Birth is required',
            'dob.date'                  => 'Date of Birth must be a date',
            'dob.date_format'           => 'Date of Birth must be of date format Y-m-d. Example 1990-12-31',
            'gender.in'                 => 'Gender must be either male or female',
            'phone.required'            => 'Phone is required',
            'phone.min'                 => 'Phone number must have minimum 7 digits',
            'phone.max'                 => 'Phone number can have max 20 digits',
            'address.required'          => 'Address is required',
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
