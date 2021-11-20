@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Customers') }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <input class="form-control" type="text" name="search_key" id="search_key"
                                    placeholder="Search your customer here">
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('admin.customer.create') }}" class="btn btn-primary">Create New
                                    Customer</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="customers-list" class="list-group mt-3">
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
            listCustomers();
        });

        $('#search_key').keypress(function(e) {
            if (e.keyCode == 13 || e.which == 13) {
                listCustomers();
            }
        });

        function listCustomers(page = '') {
            $.ajax({
                url: page ? page : "{{ route('admin.customer.search') }}",
                type: 'POST',
                data: {
                    search_key: $('#search_key').val()
                },
                cache: false,
                beforeSend: function() {
                    $('#customers-list').html('');
                },
                success: function(data) {
                    $('#customers-list').html(data);
                }
            });
        }
    </script>
@endpush
