@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'Payrolls - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Payrolls') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end">
                                    @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                        <div class="col-md-4">
                                            <label><strong>{{ __('Shop/Business') }}</strong></label>
                                            <select name="branch_id" class="form-control select2" id="f_branch_id" autofocus>
                                                <option value="">{{ __("All") }}</option>
                                                <option value="NULL">{{ $generalSettings['business__business_name'] }}({{ __('Business') }})</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        @php
                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                            $branchCode = '-' . $branch->branch_code;
                                                        @endphp
                                                        {{ $branchName . $areaName . $branchCode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-md-2">
                                        <label><strong>{{ __('Employee') }} </strong></label>
                                        <select name="user_id" class="form-control select2" id="f_user_id" autofocus>
                                            <option value="">{{ __("All") }}</option>
                                            @foreach($users as $row)
                                                <option value="{{ $row->id }}">{{$row->prefix.' '.$row->name.' '.$row->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>{{ __("Month & Year") }}</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                                            </div>
                                            <input type="month" name="month_year" class="form-control" id="f_month_year" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __('List Of Payrolls') }}</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> {{ __("Add Payroll") }}</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6></div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __("Month/Year") }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __("Payroll voucher") }}</th>
                                    <th>{{ __("Shop/Business") }}</th>
                                    <th>{{ __("Department") }}</th>
                                    <th>{{ __("Payment Status") }}</th>
                                    <th>{{ __("Gross Amount") }}</th>
                                    <th>{{ __("Paid") }}</th>
                                    <th>{{ __("Due") }}</th>
                                    <th>{{ __("Action") }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6"></th>
                                    <th id="gross_amount"></th>
                                    <th id="paid"></th>
                                    <th id="due"></th>
                                    <th>---</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <form id="deleted_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>

                <form id="delete_payroll_payment_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Select Employee & Month') }}</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <form  action="{{ route('hrm.payrolls.create') }}" method="get">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="fw-bold">{{ __("Department") }}</label>
                                <select class="form-control employee" id="department_id">
                                    <option value="all"> {{ __('All') }} </option>
                                    @foreach($departments as $dep)
                                       <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="fw-bold"><b>{{ __('Employee') }} </b></label>
                                <select required name="user_id" class="form-control" id="user_id">
                                    <option value=""> {{ __('Select Employee') }} </option>
                                    @foreach($users as $user)
                                        @php
                                            $empId = $user->emp_id ? '(' . $user->emp_id . ')' : '';
                                        @endphp
                                       <option value="{{ $user->id }}">{{$user->prefix .' '. $user->name .' '. $user->last_name . $empId }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><strong>{{ __("Month & Year") }}</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                                    </div>
                                    <input required type="month" name="month_year" class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                                    <button type="submit" class="btn btn-sm btn-success">{{ __("Create") }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->

    <div id="details"></div>
    <div id="extra_details"></div>

    <div class="modal fade" id="addOrEditPaymentModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/plugins/custom/print_this/printThis.js') }}"></script>

    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var payrollsTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel', text: 'Excel', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf', text: 'Pdf', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print', text: 'Print', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            "searching" : true,
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('hrm.payrolls.index') }}",
                "data": function(d) {
                    d.branch_id = $('#f_branch_id').val();
                    d.user_id = $('#f_user_id').val();
                    d.month_year = $('#f_month_year').val();
                }
            },
            columns: [
                {data: 'month_year', name: 'hrm_payrolls.month'},
                {data: 'user', name: 'users.name'},
                {data: 'voucher_no', name: 'hrm_payrolls.voucher_no', className: 'fw-bold'},
                {data: 'branch', name: 'branches.name'},
                {data: 'department_name', name: 'hrm_departments.name'},
                {data: 'payment_status', name: 'users.last_name'},
                {data: 'gross_amount', name: 'parentBranch.name', className: 'fw-bold'},
                {data: 'paid', name: 'hrm_payrolls.paid', className: 'fw-bold'},
                {data: 'due', name: 'hrm_payrolls.due', className: 'fw-bold'},
                {data: 'action'},
            ],fnDrawCallback: function() {

                var gross_amount = sum_table_col($('.data_tbl'), 'gross_amount');
                $('#gross_amount').text(bdFormat(gross_amount));

                var paid = sum_table_col($('.data_tbl'), 'paid');
                $('#paid').text(bdFormat(paid));

                var due = sum_table_col($('.data_tbl'), 'due');
                $('#due').text(bdFormat(due));
                $('.data_preloader').hide();
            }
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
        $(document).on('submit', '#filter_form', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            payrollsTable.ajax.reload();
        });

        $('#department_id').on('change', function(e){
            e.preventDefault();
            var department_id = $(this).val();

            var url = "{{ route('hrm.departments.users', ':department_id') }}";
            var route = url.replace(':department_id', department_id);

            $.ajax({
                url: route,
                type: 'get',
                success: function(users) {

                    $('#user_id').empty();
                    $('#user_id').append('<option value="">' + "{{ __('Select Employee') }}" + '</option>');

                    $.each(users, function(key, user) {

                        var prefix = user.prefix != null ? user.prefix : '';
                        var name = user.name != null ? ' ' + user.name : '';
                        var last_name = user.last_name != null ? ' ' + user.last_name : '';
                        var emp_id = user.emp_id != null ? '(' + user.emp_id + ')' : '';

                        var __name = prefix + name + last_name + emp_id;

                        $('#user_id').append('<option value="' + user.id + '">' + __name + '</option>');
                    });
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        // Show details modal with data
        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#details').html(data);
                    $('#detailsModal').modal('show');
                    $('.data_preloader').hide();
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        $(document).on('click', '#extraDetailsBtn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#extra_details').html(data);
                    $('#extra_details #detailsModal').modal('show');
                    $('.data_preloader').hide();
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        // Make print
        $(document).on('click', '#modalDetailsPrintBtn', function(e) {
            e.preventDefault();

            var filename = $(this).attr('filename');
            var body = $('#details .print_modal_details').html();

            document.title = filename;

            setTimeout(function() {
                document.title = "Payrolls - GPOSS";
            }, 1000);

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });

        $(document).on('click', '#modalExtraDetailsPrintBtn', function(e) {
            e.preventDefault();

            var filename = $(this).attr('filename');
            var body = $('#extra_details .print_modal_details').html();

            document.title = filename;

            setTimeout(function() {
                document.title = "Payrolls - GPOSS";
            }, 1000);

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes bg-primary',
                        'action': function() {$('#deleted_form').submit();}
                    },
                    'No': {'class': 'no bg-danger','action': function() {console.log('Deleted canceled.');}
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    payrollsTable.ajax.reload();
                    toastr.error(data);
                },error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    }else if(err.status == 500){

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '#addPayment', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                async: false,
                dataType: 'html',
                success: function(data) {

                    $('#addOrEditPaymentModal').empty();
                    $('#addOrEditPaymentModal').html(data);
                    $('#addOrEditPaymentModal').modal('show');

                    setTimeout(function() {

                        $('#payment_date').focus().select();
                    }, 500);
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#editPayment', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                async: false,
                dataType: 'html',
                success: function(data) {

                    $('#addOrEditPaymentModal').empty();
                    $('#addOrEditPaymentModal').html(data);
                    $('#addOrEditPaymentModal').modal('show');

                    setTimeout(function() {

                        $('#payment_date').focus().select();
                    }, 500);
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#deletePayment',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_payroll_payment_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes bg-primary',
                        'action': function() {$('#delete_payroll_payment_form').submit();}
                    },
                    'No': {'class': 'no bg-danger','action': function() {console.log('Deleted canceled.');}
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#delete_payroll_payment_form',function(e){
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    $('.modal').modal('hide');
                    payrollsTable.ajax.reload(null, false);
                    toastr.error(data);
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    }else if(err.status == 500){

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    </script>
@endpush
