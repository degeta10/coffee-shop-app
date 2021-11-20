@foreach ($products as $productKey => $product)
    <div class="list-group-item list-group-item-action flex-column align-items-start">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">#{{ $product->id }}</h5>
            <small>Created On:&nbsp;{{ $product->created_at->format('d/m/Y g:i A') }}</small>
        </div>
        <div class="d-flex w-100 justify-content-start">
            <h5 class="mb-1">{{ $product->title }}</h5>
        </div>
        <div class="d-flex w-100 justify-content-end mt-2">
            <a class="btn btn-info btn-sm mx-1" href="{{ route('admin.product.edit', [$product]) }}">Edit</a>
            <form action="{{ route('admin.product.destroy', [$product]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </form>
        </div>
        <div class="d-flex w-100 justify-content-start mb-1">
            <small>No Of Orders:&nbsp;{{ $product->orders->count() }}</small>
        </div>
        <div class="d-flex w-100 justify-content-start mb-1">
            <small>Price:&nbsp;{{ $product->price }}</small>
        </div>
    </div>
@endforeach

@if ($products->count() > 0 && $products->hasPages())
    <nav class="mt-2" aria-label="Page navigation example">
        <ul class="pagination float-right">
            @if ($products->previousPageUrl())
                <li class="page-item"><a class="page-link"
                        href="javascript:listProducts('{{ $products->previousPageUrl() }}')">Previous</a></li>
            @endif
            @if ($products->hasMorePages())
                <li class="page-item"><a class="page-link"
                        href="javascript:listProducts('{{ $products->nextPageUrl() }}')">Next</a></li>
            @endif
        </ul>
    </nav>
@endif
