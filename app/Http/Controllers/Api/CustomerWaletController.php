<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\UpdateWalletRequest;
use App\Http\Resources\Customer\WalletBalance;
use Illuminate\Http\Response;

class CustomerWaletController extends Controller
{
    /**
     * Get wallet balance amount.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBalance()
    {
        return new WalletBalance(auth()->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWalletRequest $request)
    {
        $data = $request->validated();
        if ($data['transaction_type'] == 'deposit') {
            auth()->user()->depositFloat($data['amount']);
        } else {
            auth()->user()->withdrawFloat($data['amount']);
        }
        return response()->json([
            'message' => "Your wallet has been updated."
        ], Response::HTTP_OK);
    }
}
