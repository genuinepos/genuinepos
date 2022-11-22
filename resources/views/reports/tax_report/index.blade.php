@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/daterangepicker/daterangepicker.min.css') }}"/>
<link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .sale_and_purchase_amount_area table tbody tr th,td {color: #32325d;}
        .report_data_area {position: relative;}
        .data_preloader{top:2.3%}
        .sale_and_purchase_amount_area table tbody tr th{text-align: left;}
        .sale_and_purchase_amount_area table tbody tr td{text-align: left;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <!-- =====================================================================BODY CONTENT================== -->
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-money-bill-wave-alt"></span>
                    <h5>Tax Report <i data-bs-toggle="tooltip" data-bs-placement="right" title="Output: Purchase Order Tax, Input: Sale Order Tax, Expense: Tax On Expense" class="fas fa-info-circle tp"></i></h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                </a>
            </div>

            <div class="p-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-3">
                            <div class="element-body">
                                <form id="filter_tax_report_form" action="" method="get">
                                    @csrf
                                    <div class="form-group row">
                                        @if ($addons->branches == 1)
                                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                <div class="col-md-3 offset-md-6">
                                                    <label><strong>Branch :</strong></label>
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
                                            @else
                                                <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id }}">
                                            @endif
                                        @endif

                                        <div class="col-md-3">
                                            <label><strong>Date Range :</strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input readonly type="text" name="date_range" id="date_range"
                                                    class="form-control daterange submitable_input"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="report_data_area">
                            <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6></div>
                            <div class="report_data">
                                <div class="sale_and_expense_sum_area">
                                    <div class="card-body card-custom px-0">

                                        <div class="heading">
                                            <h4>Overall (Output - Input - Expense) </h4>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="tax_sum">
                                                    <h4 class="text-muted">Output Tax - Input Tax - Expense Tax : {{ json_decode($generalSettings->business, true)['currency'] }} 00.00 </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="user_sale_and_expense_list">
                                    <div class="card">
                                        <div class="card-body">
                                            <!--begin: Datatable-->
                                            <div class="tab_list_area">
                                                <div class="btn-grooup">
                                                    <a id="tab_btn" data-show="purchase" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                                        <i class="fas fa-info-circle"></i> Input Tax
                                                    </a>
                                                    <a id="tab_btn" data-show="sale" class="btn btn-sm btn-primary tab_btn" href="#">
                                                        <i class="fas fa-scroll"></i>Output Tax
                                                    </a>
                                                    <a id="tab_btn" data-show="expense" class="btn btn-sm btn-primary tab_btn" href="#">
                                                        <i class="fas fa-scroll"></i>Expense Tax
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="tab_contant sale">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="table-responsive" >
                                                            <table class="table" id="kt_datatable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Date</th>
                                                                        <th>Invoice ID</th>
                                                                        <th>Customer</th>
                                                                        <th>Tax Number</th>
                                                                        <th>Discount</th>
                                                                        <th>Tax Percent</th>
                                                                        <th>Tax Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>--/--/----</td>
                                                                        <td>SI000555</td>
                                                                        <td>Walk-In-Customer</td>
                                                                        <td>Tax Number</td>
                                                                        <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                                        <td>(5.00%)</td>
                                                                        <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab_contant purchase d-hide">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table class="table" id="kt_datatable2">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Date</th>
                                                                        <th>Invoice ID</th>
                                                                        <th>Supplier</th>
                                                                        <th>Tax Number</th>
                                                                        <th>Discount</th>
                                                                        <th>Tax Percent</th>
                                                                        <th>Tax Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>--/--/----</td>
                                                                        <td>SI000555</td>
                                                                        <td>Freedan Joo</td>
                                                                        <td>Tax Number</td>
                                                                        <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                                        <td>(0.00%)</td>
                                                                        <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab_contant expense d-hide">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table class="table" id="kt_datatable3">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Date</th>
                                                                        <th>Invoice ID</th>
                                                                        <th>Expense Category</th>
                                                                        <th>Branch</th>
                                                                        <th>Tax Percent</th>
                                                                        <th>Total Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>--/--/----</td>
                                                                        <td>EX000555</td>
                                                                        <td>Expense Category</td>
                                                                        <td>Dhaka Branch - D8557</td>
                                                                        <td>(0.00%)</td>
                                                                        <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                                    </tr>
                                                                </tbody>
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
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('assets/plugins/custom/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/daterangepicker/daterangepicker.js') }}"></script>
<script>
    // Get sale representative report **requested by ajax**
    function getTaxReport() {
        $('.data_preloader').show();
        var branch_id = $('#branch_id').val();
        var date_range = $('#date_range').val();
        $.ajax({
            url:"{{ route('reports.taxes.get') }}",
            type:'get',
            data: {
                branch_id,
                date_range,
            },
            success:function(data){
                //console.log(data);
                $('.report_data').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getTaxReport();

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        $('#filter_tax_report_form').submit();
    });

    //Submit filter form by date-range field blur
    $(document).on('blur', '.submitable_input', function () {
        setTimeout(function() {
            $('#filter_tax_report_form').submit();
        }, 500);
    });

    //Submit filter form by date-range apply button
    $(document).on('click', '.applyBtn', function () {
        setTimeout(function() {
            $('.submitable_input').addClass('.form-control:focus');
            $('.submitable_input').blur();
        }, 500);
    });

    $(document).on('submit', '#filter_tax_report_form', function(e) {
        e.preventDefault();
        getTaxReport();
    });

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();
        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').hide();
        var show_content = $(this).data('show');
        $('.' + show_content).show();
        $(this).addClass('tab_active');
    });
</script>
232332
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
