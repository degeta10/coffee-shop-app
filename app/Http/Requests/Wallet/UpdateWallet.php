<?php

namespace App\Http\Requests\Wallet;

use App\Rules\CheckUserWalletBalance;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Redirect;

class UpdateWallet extends FormRequest
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
            'transaction_type'  => ['required', 'in:deposit,withdraw'],
            'amount'            => ['required', 'numeric', 'min:1', 'max:1000', new CheckUserWalletBalance],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return Redirect::back()->withInput()->withErrors($validator);
    }
}
