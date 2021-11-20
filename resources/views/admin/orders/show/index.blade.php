@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Order Details') }}</div>

                    <div class="card-body">
                        <div class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">#{{ $order->order_no }}</h5>
                                <small>Date & Time:&nbsp;{{ $order->date_of_order->format('d/m/Y g:i A') }}</small>
                            </div>
                            @if ($order->status == 'delivered')
                                <div class="d-flex w-100 justify-content-end mb-2">
                                    <small>Delivered
                                        On:&nbsp;{{ $order->date_of_delivery->format('d/m/Y g:i A') }}</small>
                                </div>
                            @else
                                @if ($order->status == 'cancelled')
                                    <div class="d-flex w-100 justify-content-end mb-2">
                                        <small>Cancelled
                                            On:&nbsp;{{ $order->date_of_cancellation->format('d/m/Y g:i A') }}</small>
                                    </div>
                                @endif
                            @endif
                            @if ($order->status == 'in-progress')
                                <div class="d-flex w-100 justify-content-end mb-2">
                                    <form action="{{ route('admin.order.cancel', [$order]) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger btn-sm" type="submit">Cancel</button>
                                    </form>
                                </div>
                                <div class="d-flex w-100 justify-content-end">
                                    <form action="{{ route('admin.order.deliver', [$order]) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger btn-sm" type="submit">Set As Delivered</button>
                                    </form>
                                </div>
                            @else
                                @if ($order->status == 'delivered')
                                    <div class="d-flex w-100 justify-content-end">
                                        <strong>Delivered</strong>
                                    </div>
                                @else
                                    <div class="d-flex w-100 justify-content-end">
                                        <strong>Cancelled</strong>
                                    </div>
                                @endif
                            @endif
                            <div class="d-flex w-100 justify-content-start mb-1">
                                <small>Product:&nbsp;{{ $order->product->title }}</small>
                            </div>
                            <div class="d-flex w-100 justify-content-start mb-1">
                                <small>Quantity:&nbsp;{{ $order->quantity }}</small>
                            </div>
                            <div class="d-flex w-100 justify-content-start mb-1">
                                <small>Payment Method:&nbsp;<span class="text-uppercase">{{ $order->type }}</span></small>
                            </div>
                            <div class="d-flex w-100 justify-content-start mb-1">
                                <small>Amount:&nbsp;{{ $order->amount }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
