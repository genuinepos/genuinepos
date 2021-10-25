@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Account Cash Flows - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="far fa-money-bill-alt"></span>
                                <h5>Cash Flow</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name mt-1">
                                    <div class="col-md-12">
                                        <form id="filter_cash_flow" action="{{ route('accounting.filter.cash.flow') }}" method="get" class="px-2">
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label><strong>Transaction Type :</strong></label>
                                                    <select name="transaction_type" class="form-control submit_able" id="transaction_type" autofocus>
                                                        <option value=""><strong>All</strong></option>
                                                        <option value="1"><strong>Debit</strong></option>
                                                        <option value="2">Credit</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>From Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="datepicker"
                                                            class="form-control from_date date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>To Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> Filter</button>
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
                    </div>

                    <div class="row margin_row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>All Cash Flow</h6>
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
                                                <th class="text-start">Description</th>
                                                <th class="text-start">Created By</th>
                                                <th class="text-start">Debit</th>
                                                <th class="text-start">Credit</th>
                                                <th class="text-start">Balance</th>
                                                <th class="text-start text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    function getCashFlows() {
       $('.data_preloader').show();
       $.ajax({
           url:"{{ route('accounting.all.cash.flow') }}",
           success:function(data){
               console.log(data);
               $('#data-list').html(data);
               $('.data_preloader').hide();
           }
       });
    }
    getCashFlows();

    $(document).on('click', '#delete',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Delete Confirmation',
            'message': 'Are you sure?',
            'buttons': {
                'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_form').submit();}},
                'No': {'class': 'no btn-modal-primary','action': function() {console.log('Deleted canceled.');}}
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
               getCashFlows();
               toastr.error(data);
               $('#deleted_form')[0].reset();
           }
       });
    });

    //Send account filter request
    $('#filter_cash_flow').on('submit', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'get',
            data: request,
            success:function(data){
                $('#data-list').html(data);
                $('.data_preloader').hide();
            }
        });
    });

    //Print purchase Payment report
    $(document).on('click', '#print_report', function (e) {
        e.preventDefault();
        var url = "{{ route('accounting.print.cash.flow') }}";
        var transaction_type = $('#transaction_type').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        $.ajax({
            url:url,
            type:'get',
            data: {transaction_type, from_date, to_date},
            success:function(data) {
                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",
                    removeInline: false,
                    printDelay: 700,
                    header: null,
                });
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
