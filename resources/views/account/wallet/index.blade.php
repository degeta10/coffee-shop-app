@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('My Wallet') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('account.wallet.update') }}">
                            @csrf
                            @method('PATCH')
                            <div class="form-group row">
                                <label for="current_balance"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Current Balance') }}</label>
                                <div class="col-md-6">
                                    <input id="current_balance" type="text" class="form-control"
                                        value="{{ auth()->user()->balanceFloat }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Action') }}</label>
                                <div class="col-md-6">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-secondary active">
                                            <input type="radio" name="transaction_type" id="option1" autocomplete="off"
                                                value="deposit" checked>
                                            Deposit
                                        </label>
                                        <label class="btn btn-secondary">
                                            <input type="radio" name="transaction_type" id="option2" autocomplete="off"
                                                value="withdraw">
                                            Withdraw
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="amount"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Amount') }}</label>

                                <div class="col-md-6">
                                    <input id="amount" type="number" step="0.01" min="1" max="1000"
                                        class="form-control @error('amount') is-invalid @enderror" name="amount"
                                        value="{{ old('amount') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
