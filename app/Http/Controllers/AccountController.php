<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\UpdateProfile;
use App\Http\Requests\Wallet\UpdateWallet;

class AccountController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        return view('account.profile.index', compact('user'));
    }

    public function updateProfile(UpdateProfile $request)
    {
        if (auth()->user()->update($request->validated())) {
            return redirect()->route('account.profile')->with('success', 'Your profile has been updated.');
        } else {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update profile.']);
        }
    }

    public function wallet()
    {
        return view('account.wallet.index');
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
