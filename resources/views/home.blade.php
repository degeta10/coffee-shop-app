@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ route('orders') }}" class="list-group-item list-group-item-action">My Orders</a>
                            <a href="{{ route('orders.create') }}" class="list-group-item list-group-item-action">Place New
                                Order</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
