@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit Order') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.order.update', [$order]) }}">
                            @csrf
                            @method('PATCH')

                            <div class="form-group row">
                                <label for="current_balance"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Customer') }}</label>
                                <div class="col-md-6">
                                    <select name="customer_id" id="customer_id" title="Select One"
                                        class="form-control @error('customer_id') is-invalid @enderror" required>
                                        <option value="">Choose One</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                data-balance="{{ $customer->balanceFloat }}"
                                                {{ $order->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="current_balance"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Product') }}</label>
                                <div class="col-md-6">
                                    <select name="product_id" id="product_id" title="Select One"
                                        class="form-control @error('product_id') is-invalid @enderror" required>
                                        <option value="">Choose One</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                                {{ $order->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->title }} -
                                                {{ $product->price }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="quantity"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Quantity') }}</label>

                                <div class="col-md-6">
                                    <input id="quantity" type="number" step="1" min="1" max="1000"
                                        class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                        value="{{ $order->quantity }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="amount"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Total Amount') }}</label>

                                <div class="col-md-6">
                                    <input id="amount" type="number" class="form-control" value="{{ $order->amount }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="form-group row wallet-balance d-none">
                                <label for="balance"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Wallet balance') }}</label>

                                <div class="col-md-6">
                                    <input id="balance" type="number" class="form-control" value="0" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Payment Method') }}</label>
                                <div class="col-md-6">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-secondary active">
                                            <input type="radio" name="type" id="option1" autocomplete="off" value="cod"
                                                @if ($order->type == 'cod')
                                            checked
                                            @endif
                                            >
                                            Cash On Delivery
                                        </label>
                                        <label class="btn btn-secondary">
                                            <input type="radio" name="type" id="option2" autocomplete="off" value="online"
                                                @if ($order->type == 'online')
                                            checked
                                            @endif>
                                            Wallet
                                        </label>
                                        @error('type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
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

@push('scripts')
    <script>
        $('#product_id').on('change', function(e) {
            if ($('#quantity').val() == '') {
                $('#quantity').val('1');
            }
            var price = $(this).find(':selected').data('price');
            var qty = $('#quantity').val();
            $('#amount').val(price * qty);
        });

        $('#quantity').on('change', function(e) {
            var price = $('#product_id :selected').data('price');
            var qty = $(this).val();
            $('#amount').val(price * qty);
        });

        $('input[name="type"]').on('change', function(e) {
            var balance = $('#customer_id :selected').data('balance');
            if ($(this).val() == 'cod') {
                $('.wallet-balance').addClass('d-none');
            } else {
                $('.wallet-balance').removeClass('d-none');
                $('#balance').val(balance);
            }
        });
    </script>
@endpush
