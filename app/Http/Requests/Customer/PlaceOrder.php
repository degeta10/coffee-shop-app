<?php

namespace App\Http\Requests\Customer;

use App\Rules\CheckOrderFromWalletBalance;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Redirect;

class PlaceOrder extends FormRequest
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
            'product_id'    => ['required', 'integer', 'exists:products,id'],
            'quantity'      => ['required', 'integer', 'min:1', 'max:100'],
            'type'          => ['required', 'in:cod,online'],
            'amount'        => ['required', 'numeric', new CheckOrderFromWalletBalance],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return Redirect::back()->withInput()->withErrors($validator);
    }
}
