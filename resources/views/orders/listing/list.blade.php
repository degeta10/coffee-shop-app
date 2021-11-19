@foreach ($orders as $orderKey => $order)
    <div class="list-group-item list-group-item-action flex-column align-items-start">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">{{ ++$orderKey }}.&nbsp;{{ $order->product->title }}</h5>
            <small>Date & Time:&nbsp;{{ $order->created_at->format('d/m/Y g:i A') }}</small>
        </div>
        @if ($order->status == 'in-progress')
            <div class="d-flex w-100 justify-content-end">
                <form action="{{ route('orders.cancel', [$order]) }}" method="POST">
                    @csrf
                    <button class="btn btn-danger btn-sm" type="submit">Cancel</button>
                </form>
            </div>
        @else
            @if ($order->status == 'delivered')
                <div class="d-flex w-100 justify-content-end">
                    <small>Delivered</small>
                </div>
            @else
                <div class="d-flex w-100 justify-content-end">
                    <small>Cancelled</small>
                </div>
            @endif
        @endif
        <p>Order No:&nbsp;{{ $order->order_no }}</p>
        <small>Quantity:&nbsp;{{ $order->quantity }}</small>
        <br>
        <small>Payment Method:&nbsp;<span class="text-uppercase">{{ $order->type }}</span></small>
        <br>
        <small>Amount:&nbsp;{{ $order->amount }}</small>
    </div>
@endforeach

@if ($orders->count() > 0 && $orders->hasPages())
    <nav class="mt-2" aria-label="Page navigation example">
        <ul class="pagination float-right">
            @if ($orders->previousPageUrl())
                <li class="page-item"><a class="page-link"
                        href="javascript:listOrders('{{ $orders->previousPageUrl() }}')">Previous</a></li>
            @endif
            @if ($orders->hasMorePages())
                <li class="page-item"><a class="page-link"
                        href="javascript:listOrders('{{ $orders->nextPageUrl() }}')">Next</a></li>
            @endif
        </ul>
    </nav>
@endif
