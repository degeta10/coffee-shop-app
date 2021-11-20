<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Redirect;

class UpdateCustomer extends FormRequest
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
            'name'          => ['required', 'max:180'],
            'gender'        => ['required', 'in:male,female'],
            'dob'           => ['required', 'date', 'date_format:Y-m-d'],
            'phone'         => ['required', 'min:7', 'max:20'],
            'address'       => ['required'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return Redirect::back()->withInput()->withErrors($validator);
    }
}
