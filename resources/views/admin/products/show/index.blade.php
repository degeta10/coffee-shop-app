@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Product Details') }}</div>

                    <div class="card-body">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">#{{ $product->id }}</h5>
                            <small>Created On:&nbsp;{{ $product->created_at->format('d/m/Y g:i A') }}</small>
                        </div>
                        <div class="d-flex w-100 justify-content-start">
                            <h5 class="mb-1">{{ $product->title }}</h5>
                        </div>
                        <div class="d-flex w-100 justify-content-end mt-2">
                            <a class="btn btn-info btn-sm mx-1"
                                href="{{ route('admin.product.edit', [$product]) }}">Edit</a>
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
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
