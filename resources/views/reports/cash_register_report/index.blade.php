@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    
@endpush
@section('title', 'Cash Register Reports - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-cash-register"></span>
                                <h5>Cash Register Reports</h5>
                            </div>

                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="register_report_filter_form" action="" method="get">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>Business Location :</strong></label>
                                                            <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                                                <option value="">All</option>
                                                                <option value="NULL">
                                                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)
                                                                </option>

                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else 
                                                        <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id }}">
                                                    @endif
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>User :</strong></label>
                                                    <select name="user_id" class="form-control submit_able" id="user_id" autofocus>
                                                        <option value="">All</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>Status :</strong></label>
                                                    <select name="status" class="form-control submit_able" id="status">
                                                        <option value="">All</option>
                                                        <option value="1">Open</option>
                                                        <option value="2">Closed</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>From Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>

                                                        <input type="text" name="from_date" id="datepicker"
                                                            class="form-control from_date date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>To Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>

                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" id="filter_button" class="btn text-white btn-sm btn-secondary float-start">
                                                                    <i class="fas fa-funnel-dollar"></i> Filter
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mt-3">
                                                            <a href="#" class="btn btn-sm btn-primary float-end " id="print_report"><i class="fas fa-print "></i> Print</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <div class="report_data_area">
                                    <div class="data_preloader"> 
                                        <h6> 
                                            <i class="fas fa-spinner text-primary"></i> Processing...
                                        </h6>
                                    </div>

                                    <div class="card">
                                        <div class="table-responsive" id="data-list">
                                            <table class="display data_tbl data__table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-start">Open Time</th>
                                                        <th class="text-start">Closed Time</th>
                                                        <th class="text-start">Business Location</th>
                                                        <th class="text-start">User</th>
                                                        <th class="text-start">Closing Note</th>
                                                        <th class="text-start">Status</th>
                                                        <th class="text-start">Closing Amount</th>
                                                        <th class="text-start">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr class="bg-secondary">
                                                        <th colspan="6" class="text-end text-white">Total : 
                                                            {{ json_decode($generalSettings->business, true)['currency'] }}  
                                                        </th>
                                                        <th id="closed_amount" class="text-end text-white"></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cashRegisterDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" id="cash_register_details_content"></div>
        </div>
    </div> 
@endsection
@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    // $('.loading_button').hide();
    // Filter area toggle
    // function getCashRegisterReport() {
    //     $('.data_preloader').show();
    //     var branch_id = $('#branch_id').val();
    //     var user_id = $('#user_id').val();
    //     var status = $('#status').val();
    //     var date_range = $('#date_range').val();
    //     $.ajax({
    //         url:"{{ route('reports.get.cash.registers') }}",
    //         type:'get',
    //         data: {
    //             branch_id, 
    //             user_id, 
    //             status, 
    //             date_range, 
    //         },
    //         success:function(data){
    //             $('#data-list').html(data);
    //             $('.data_preloader').hide();
    //         }
    //     });
    // }
    // getCashRegisterReport();

    // // Get all users for filter form
    // function setUsers(){
    //     $.ajax({
    //         url:"{{ route('sales.get.all.users') }}",
    //         async:true,
    //         type:'get',
    //         dataType: 'json',
    //         success:function(users){
    //             $.each(users, function(key, val){
    //                 var role = '';
    //                 if (val.role_type == 1) {
    //                     role = 'Super-Admin';
    //                 }else if(val.role_type == 2){
    //                     role = 'Admin';
    //                 }else if(val.role_type == 3){
    //                     role = val.role.name
    //                 }
    //                 $('#user_id').append('<option value="'+val.id+'">'+ val.name +' ('+role+')'+'</option>');
    //             });
    //         }
    //     });
    // }
    // setUsers();

    // $(document).on('change', '.submit_able', function () {
    //     $('#register_report_filter_form').submit();
    // });

    // //Submit filter form by date-range field blur 
    // $(document).on('blur', '.submit_able_input', function () {
    //     setTimeout(function() {
    //         $('#register_report_filter_form').submit();
    //     }, 500);
    // });

    // //Submit filter form by date-range apply button
    // $(document).on('click', '.applyBtn', function () {
    //     setTimeout(function() {
    //         $('.submit_able_input').addClass('.form-control:focus');
    //         $('.submit_able_input').blur();
    //     }, 500);
    // });

    // $('#register_report_filter_form').on('submit', function (e) {
    //    e.preventDefault();
    //    getCashRegisterReport();
    // });

    $(document).on('click', '#register_details_btn',function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
                $('#cash_register_details_content').html(data);
                $('#cashRegisterDetailsModal').modal('show');
            }
        });
    });
</script>

<script type="text/javascript">
    new Litepicker({
        singleMode: true,
        element: document.getElementById('datepicker'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('datepicker2'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY',
    });
</script>
@endpush