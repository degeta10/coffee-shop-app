@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit Customer') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.customer.update', [$customer]) }}">
                            @csrf
                            @method('PATCH')

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                                <div class="col-md-6">
                                    <input id="name" name="name" type="text" class="form-control"
                                        value="{{ $customer->name }}" placeholder="Enter your name here" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" value="{{ $customer->email }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>
                                <div class="col-md-6">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-secondary active">
                                            <input type="radio" name="gender" id="option1" autocomplete="off" value="male"
                                                @if ($customer->gender == 'male') checked @endif>
                                            Male
                                        </label>
                                        <label class="btn btn-secondary">
                                            <input type="radio" name="gender" id="option2" autocomplete="off" value="female"
                                                @if ($customer->gender == 'female') checked @endif>
                                            Female
                                        </label>
                                    </div>
                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dob"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Date of Birth') }}</label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" id="dob" name="dob"
                                        placeholder="Select your date of birth"
                                        value="{{ $customer->dob->format('Y-m-d') }}" required>
                                    @error('dob')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>
                                <div class="col-md-6">
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        placeholder="+(country code) number" minlength="7" maxlength="20"
                                        value="{{ $customer->phone }}" required>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>
                                <div class="col-md-6">
                                    <textarea id="address" name="address" class="form-control" cols="30"
                                        placeholder="Type your address here"
                                        rows="10">{{ $customer->address }}</textarea>
                                    @error('address')
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
