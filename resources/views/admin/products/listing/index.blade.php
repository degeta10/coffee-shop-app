@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Products') }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <input class="form-control" type="text" name="search_key" id="search_key"
                                    placeholder="Search your product here">
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('admin.product.create') }}" class="btn btn-primary">Create New
                                    Product</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="products-list" class="list-group mt-3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(e) {
            listProducts();
        });

        $('#search_key').keypress(function(e) {
            if (e.keyCode == 13 || e.which == 13) {
                listProducts();
            }
        });

        function listProducts(page = '') {
            $.ajax({
                url: page ? page : "{{ route('admin.product.search') }}",
                type: 'POST',
                data: {
                    search_key: $('#search_key').val()
                },
                cache: false,
                beforeSend: function() {
                    $('#products-list').html('');
                },
                success: function(data) {
                    $('#products-list').html(data);
                }
            });
        }
    </script>
@endpush
