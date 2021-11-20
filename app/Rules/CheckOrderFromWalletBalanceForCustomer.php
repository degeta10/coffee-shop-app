<?php

namespace App\Rules;

use App\Models\Product;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class CheckOrderFromWalletBalanceForCustomer implements Rule
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
        if (request()->has('type') && request()->get('type') === 'online' && request()->get('product_id')) {
            $product = Product::find(request()->get('product_id'));
            $amount = $product->price * $value;
            $user = User::find(request()->get('customer_id'));
            return $user->balanceFloat >= $amount ? true : false;
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
        return 'Insufficient wallet balance. Please select less quantity.';
    }
}
