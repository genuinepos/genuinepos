@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css" />
@endpush
@section('title', 'Category Wise Expense List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-money-bill"></span>
                                <h5>Category Wise Expenses</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form action="" method="get" class="px-2">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>Business Location :</strong></label>
                                                            <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                                                <option value="">All</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif
                                             
                                                <div class="col-md-2">
                                                    <label><strong>Expense For :</strong></label>
                                                    <select name="admin_id" class="form-control submit_able" id="admin_id" >
                                                        <option value="">All</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>Category :</strong></label>
                                                    <select name="category_id" class="form-control submit_able" id="category_id" >
                                                        <option value="">All</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>Date Range :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input readonly type="text" name="date_range" id="date_range" class="form-control daterange submit_able_input"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>Category Wise Expense List</h6>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">Date</th>
                                                <th class="text-start">Expense Category</th>
                                                <th class="text-start">Reference ID</th>
                                                <th class="text-start">B.Location</th>
                                                <th class="text-start">Amount</th>
                                                <th class="text-start">Expanse For</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="4" class="text-end text-white">Total :</th>
                                                <th class="text-white">
                                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                                    <span id="total_amount"></span>
                                                </th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <form id="deleted_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [ 
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('expanses.category.wise.expense') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.admin_id = $('#admin_id').val();
                    d.category_id = $('#category_id').val();
                    d.date_range = $('#date_range').val();
                }
            },
            columns: [
                {data: 'date', name: 'date' },
                {data: 'category_name', name: 'expanse_categories.name'},
                {data: 'invoice_id', name: 'invoice_id'},
                {data: 'from', name: 'branches.name'},
                {data: 'amount', name: 'amount' },
                {data: 'user_name', name: 'admin_and_users.name'},
            ],fnDrawCallback: function() {
                var paid = sum_table_col($('.data_tbl'), 'amount');
                $('#total_amount').text(parseFloat(paid).toFixed(2));
            },
        });

        function sum_table_col(table, class_name) {
            var sum = 0;
            table.find('tbody').find('tr').each(function() {
                if (parseFloat($(this).find('.' + class_name).data('value'))) {
                    sum += parseFloat(
                        $(this).find('.' + class_name).data('value')
                    );
                }
            });
            return sum;
        }

        //Submit filter form by select input changing
        $(document).on('change', '.submit_able', function () {
            table.ajax.reload();
        });

        //Submit filter form by date-range field blur 
        $(document).on('blur', '.submit_able_input', function () {
            setTimeout(function() {
                table.ajax.reload();
            }, 500);
        });

        //Submit filter form by date-range apply button
        $(document).on('click', '.applyBtn', function () {
            setTimeout(function() {
                $('.submit_able_input').addClass('.form-control:focus');
                $('.submit_able_input').blur();
            }, 500);
        });

        // Set accounts in payment and payment edit form
        function setExpanseCategory(){
            $.ajax({
                url:"{{route('expanses.all.categories')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(categories){
                    $.each(categories, function (key, category) {
                        $('#category_id').append('<option value="'+category.id+'">'+ category.name +' ('+category.code+')'+'</option>');
                    });
                }
            });
        }
        setExpanseCategory();

        // Set accounts in payment and payment edit form
        function setAdmin(){
            $.ajax({
                url:"{{route('expanses.all.admins')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(admins){
                    $.each(admins, function (key, admin) {
                        var prefix = admin.prefix ? admin.prefix : '';
                        var last_name = admin.last_name ? admin.last_name : '';
                        $('#admin_id').append('<option value="'+admin.id+'">'+ admin.name+' '+last_name+'</option>');
                    });
                }
            });
        }setAdmin();
    </script>

    <script type="text/javascript">
        $(function() {
            var start = moment().startOf('year');
            var end = moment().endOf('year');
            $('.daterange').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',
                startDate: start,
                endDate: end,
                locale: {cancelLabel: 'Clear'},
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')],
                }
            });
            $('.daterange').val('');
        });

        $(document).on('click', '.cancelBtn ', function () {
           $('.daterange').val('');
        });
    </script>
@endpush