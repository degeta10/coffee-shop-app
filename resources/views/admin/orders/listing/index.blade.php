@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Orders') }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <input class="form-control" type="text" name="search_key" id="search_key"
                                    placeholder="Search your order here">
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('admin.order.create') }}" class="btn btn-primary">Create New Order</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="orders-list" class="list-group mt-3">
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
            listOrders();
        });

        $('#search_key').keypress(function(e) {
            if (e.keyCode == 13 || e.which == 13) {
                listOrders();
            }
        });

        function listOrders(page = '') {
            $.ajax({
                url: page ? page : "{{ route('admin.order.search') }}",
                type: 'POST',
                data: {
                    search_key: $('#search_key').val()
                },
                cache: false,
                beforeSend: function() {
                    $('#orders-list').html('');
                },
                success: function(data) {
                    $('#orders-list').html(data);
                }
            });
        }
    </script>
@endpush
