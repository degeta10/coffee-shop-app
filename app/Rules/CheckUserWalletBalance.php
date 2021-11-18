<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckUserWalletBalance implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (request()->has('transaction_type') && request()->get('transaction_type') === 'withdraw') {
            return auth()->user()->balanceFloat >= $value ? true : false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Insufficient funds.';
    }
}
