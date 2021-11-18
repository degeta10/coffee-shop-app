<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\UpdateWallet;

class AccountController extends Controller
{
    public function wallet()
    {
        $balance = auth()->user()->balanceFloat;
        return view('account.wallet.index', compact('balance'));
    }

    public function updateWallet(UpdateWallet $request)
    {
        $data = $request->validated();
        if ($data['transaction_type'] == 'deposit') {
            auth()->user()->depositFloat($data['amount']);
        } else {
            auth()->user()->withdrawFloat($data['amount']);
        }
        return redirect()->route('account.wallet')->with('success', 'Your wallet has been updated.');
    }
}
