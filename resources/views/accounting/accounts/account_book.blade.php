@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css"/>
@endpush
@section('title', 'Account Book - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-book"></span>
                                <h5>Accounts</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="sec-name mt-1">
                                    <div class="col-md-12">
                                        <table class="table modal-table table-sm">
                                            <tbody>
                                                <tr>
                                                    <td class="text-start"> <strong>Bank :</strong> </td>
                                                    <td class="bank_name text-start">{{ $account->bank->name .' ('.$account->bank->branch_name.')' }}</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start"> <strong>Account Name :</strong> </td>
                                                    <td class="account_name text-start">{{ $account->name }}</td>
                                                </tr>
        
                                                <tr>
                                                    <td class="text-start"><strong>Account Type :</strong></td>
                                                    <td class="account_type text-start">{{ $account->account_type ? $account->account_type->name : '' }}</td>
                                                </tr>
        
                                                <tr>
                                                    <td class="text-start"><strong>Account Number :</strong></td>
                                                    <td class="account_number text-start">{{ $account->account_number }}</td>
                                                </tr>
        
                                                <tr>
                                                    <td class="text-start"><strong>Balance :</strong> </td>
                                                    <td class="account_balance text-start">{{ $account->balance }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="sec-name mt-1 t">
                                    <div class="col-md-12">
                                        <i class="fas fa-funnel-dollar ms-2"></i> <b>Filter</b>
                                        <form id="filter_account_cash_flow" action="{{ route('accounting.accounts.account.cash.flow.filter', $account->id) }}" method="get" class="px-2">
                                            <div class="form-group row mt-4">
                                                <div class="col-md-6">
                                                    <label><strong>Transaction Type :</strong></label>
                                                    <select name="transaction_type" class="form-control submit_able" id="transaction_type" autofocus>
                                                        <option value=""><strong>All</strong></option> 
                                                        <option value="1"><strong>Debit</strong></option>  
                                                        <option value="2">Credit</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label><strong>Date Range :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week text-dark input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="date_range" class="form-control daterange submit_able_input" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =========================================top section button=================== -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-10">
                                        <h6>All Transactions <i data-bs-toggle="tooltip" data-bs-placement="right" title="Note: Initially current year's data is available here, if need another year's data go to the data filter." class="fas fa-info-circle tp"></i></h6>
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
    </div>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
<script src="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
<script>
    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    function getCashFlow() {
        $('.data_preloader').show();
        $.ajax({
            url:"{{route('accounting.accounts.account.cash.flows', $account->id)}}",
            type:'get',
            success:function(data){
                $('#data-list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getCashFlow();

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
               getCashFlow();
               toastr.error(data);
               $('#deleted_form')[0].reset();
           }
       });
   });

   //Submit filter form by select input changing
   $(document).on('change', '.submit_able', function () {
       $('#filter_account_cash_flow').submit();
   });

   //Submit filter form by date-range field blur 
   $(document).on('blur', '.submit_able_input', function () {
       setTimeout(function() {
           $('#filter_account_cash_flow').submit();
       }, 800);
   });

   //Submit filter form by date-range apply button
   $(document).on('click', '.applyBtn', function () {
       setTimeout(function() {
           $('.submit_able_input').addClass('.form-control:focus');
           $('.submit_able_input').blur();
       }, 1000);
   });

   //Send account filter request
   $('#filter_account_cash_flow').on('submit', function (e) {
       e.preventDefault();
       $('.data_preloader').show();
       var url = $(this).attr('action');
       var request = $(this).serialize();
       console.log(request);
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
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year')
                    .subtract(1, 'year')
                ],
            }
        });
    });
</script>
@endpush
