@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ route('admin.order.index') }}"
                                class="list-group-item list-group-item-action">Orders</a>
                            <a href="{{ route('admin.product.index') }}"
                                class="list-group-item list-group-item-action">Products</a>
                            <a href="{{ route('admin.customer.index') }}"
                                class="list-group-item list-group-item-action">Customers</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
