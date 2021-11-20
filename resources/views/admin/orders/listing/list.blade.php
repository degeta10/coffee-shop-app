@foreach ($orders as $orderKey => $order)
    <div class="list-group-item list-group-item-action flex-column align-items-start">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">#{{ $order->order_no }}</h5>
            <small>Date & Time:&nbsp;{{ $order->date_of_order->format('d/m/Y g:i A') }}</small>
        </div>
        @if ($order->status == 'delivered')
            <div class="d-flex w-100 justify-content-end mb-2">
                <small>Delivered On:&nbsp;{{ $order->date_of_delivery->format('d/m/Y g:i A') }}</small>
            </div>
        @else
            @if ($order->status == 'cancelled')
                <div class="d-flex w-100 justify-content-end mb-2">
                    <small>Cancelled On:&nbsp;{{ $order->date_of_cancellation->format('d/m/Y g:i A') }}</small>
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
        <div class="d-flex w-100 justify-content-end mt-2">
            @if ($order->status == 'in-progress')
                <a class="btn btn-info btn-sm mx-1" href="{{ route('admin.order.edit', [$order]) }}">Edit</a>
            @endif
            <a class="btn btn-info btn-sm mx-1" href="{{ route('admin.order.show', [$order]) }}">View</a>
            <form action="{{ route('admin.order.destroy', [$order]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </form>
        </div>
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
