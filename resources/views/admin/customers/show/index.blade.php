@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Customer Details') }}</div>

                    <div class="card-body">
                        <div class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">#{{ $customer->id }}</h5>
                                <small>Created On:&nbsp;{{ $customer->created_at->format('d/m/Y g:i A') }}</small>
                            </div>
                            <div class="d-flex w-100 justify-content-start">
                                <h5 class="mb-1">{{ $customer->name }}</h5>
                            </div>
                            <div class="d-flex w-100 justify-content-end mt-2">
                                <a class="btn btn-info btn-sm mx-1"
                                    href="{{ route('admin.customer.edit', [$customer]) }}">Edit</a>
                                <form action="{{ route('admin.customer.destroy', [$customer]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </div>
                            @if ($customer->orders->count())
                                <div class="d-flex w-100 justify-content-start mb-1">
                                    <small>Last Ordered
                                        On:&nbsp;{{ $customer->orders->last()['date_of_order'] }}</small>
                                </div>
                            @endif
                            <div class="d-flex w-100 justify-content-start mb-1">
                                <small>Gender:&nbsp;<span class="text-capitalize">{{ $customer->gender }}</span></small>
                            </div>
                            <div class="d-flex w-100 justify-content-start mb-1">
                                <small>Phone:&nbsp;{{ $customer->phone }}</small>
                            </div>
                            <div class="d-flex w-100 justify-content-start mb-1">
                                <small>Wallet Balance:&nbsp;{{ $customer->balanceFloat }}</small>
                            </div>
                            <div class="d-flex w-100 justify-content-start mb-1">
                                <small>Address:&nbsp;{{ $customer->address }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
