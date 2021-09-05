@extends('layout.master')
@push('stylesheets')
<link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
@endpush
@section('title', 'Loans - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-glass-whiskey"></span>
                                <h5>Loans</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_tax_report_form" action="" method="get">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label><strong>Company :</strong></label>
                                                    <select name="company_id" class="form-control submit_able" id="company_id" autofocus>
                                                        <option value="">All</option>
                                                    </select>
                                                </div>
                                    
                                                <div class="col-md-3">
                                                    <label><strong>Date range :</strong></label>
                                                    <input type="text" class="form-control" id="date_range">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sec-name mt-1">
                            <div class="name-head">
                                <div class="tab_list_area">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a id="tab_btn" data-show="companies" class="tab_btn tab_active" href="#"><i class="fas fa-info-circle"></i> Companies</a>
                                        </li>

                                        <li>
                                            <a id="tab_btn" data-show="loans" class="tab_btn" href="#">
                                            <i class="fas fa-scroll"></i> Loans</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        @include('accounting.loans.bodyPartials.companyBody')

                        <div class="row tab_contant loans mt-1">
                            <div class="col-md-4">
                                <div class="card" id="add_loan_form">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <h6>Add Loan </h6>
                                        </div>
                                    </div>
                        
                                    <div class="form-area px-3 pb-2">
                                        <form id="adding_loan_form" action="{{ route('accounting.loan.store') }}" method="POST">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label><strong>Company : <span class="text-danger">*</span></strong></label>
                                                    <select name="company_id" class="form-control" id="company_id">
                                                        <option value="">Select Company</option>
                                                        @foreach ($companies as $company)
                                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_company_id"></span>
                                                </div>

                                                <div class="col-md-6">
                                                    <label><b>Type :</b> <span class="text-danger">*</span></label>
                                                    <select name="type" class="form-control" id="type">
                                                        <option value="">Select Type</option>
                                                        <option value="1">Pay Loan</option>
                                                        <option value="2">Get Loan</option>
                                                    </select>
                                                    <span class="error error_type"></span>
                                                </div>
                                            </div>
                    
                                            <div class="form-group row mt-1">
                                                <div class="col-md-6">
                                                    <label><b>Loan Amount :</b> <span class="text-danger">*</span> </label>
                                                    <input type="number" step="any" name="loan_amount" class="form-control" id="loan_amount" placeholder="Loan Amount"/>
                                                    <span class="error error_loan_amount"></span>
                                                </div>

                                                <div class="col-md-6">
                                                    <label><b>Account :</b> <span class="text-danger">*</span></label>
                                                    <select name="account_id" class="form-control" id="account_id">
                                                        <option value="">Select Account</option>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}">{{ $account->name.' (A/C: '.$account->account_number.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_account_id"></span>
                                                </div>
                                            </div>

                                            <div class="form-group row mt-1">
                                                <div class="col-md-12">
                                                    <label><b>Loan Reason :</b> </label>
                                                    <textarea name="loan_reason" class="form-control" id="loan_reason" cols="10" rows="3" placeholder="Loan Reason"></textarea>
                                                </div>
                                            </div>
                    
                                            <div class="form-group row mt-2">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                                    <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
                                                    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Reset</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                        
                                <div class="card d-none" id="edit_loan_form">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <h6>Edit Loan </h6>
                                        </div>
                                    </div>
                        
                                    <div class="form-area px-3 pb-2" id="edit_loan_form_body">
                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card">
                                    <div class="section-header">
                                        <div class="col-md-6">
                                            <h6>Loans</h6>
                                        </div>
                                    </div>
                                    <div class="widget_content">
                                        <div class="data_preloader">
                                            <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                        </div>
                                        
                                        <div class="table-responsive" >
                                            <table class="display data_tbl2 data__table asset_table w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Date</th>
                                                        <th>B.Location</th>
                                                        <th>Ref. No.</th>
                                                        <th>Company</th>
                                                        <th>Type</th>
                                                        <th>Loan Amount</th>
                                                        <th>Due</th>
                                                        <th>Total Paid</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                            <form id="delete_loan_form" action="" method="post">
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
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @include('accounting.loans.jsPartials.companyBodyJs')
    <script>
        var loans_table = $('.data_tbl2').DataTable({
            dom: "lBfrtip",
            buttons: [ 
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',autoPrint: true,exportOptions: {columns: ':visible'}}
            ],
            "lengthMenu" : [25, 100, 500, 1000, 2000],
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: "{{ route('accounting.loan.index') }}",
            columns: [
                {data: 'action', name: 'action'},
                {data: 'report_date',name: 'report_date'},
                {data: 'branch',name: 'branch'},
                {data: 'reference_no', name: 'reference_no'},
                {data: 'c_name', name: 'c_name'},
                {data: 'type', name: 'type'},
                {data: 'loan_amount', name: 'loan_amount'},
                {data: 'due', name: 'due'},
                {data: 'total_paid', name: 'total_paid'},
            ],
        });

        // Add loan by ajax
        $(document).on('submit', '#adding_loan_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $('.submit_button').prop('type', 'button');
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.error').html('');
                    toastr.success(data);
                    $('#adding_loan_form')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    loans_table.ajax.reload();
                    companies_table.ajax.reload();
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_' + key + '').html(error[0]);
                    });
                    $('.submit_button').prop('type', 'submit');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit_loan', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function (data) {
                if (!$.isEmptyObject(data.errorMsg)) {
                    toastr.error(data.errorMsg);
                }else{
                    $('#edit_loan_form_body').html(data);
                    $('#add_loan_form').hide();
                    $('#edit_loan_form').show();
                    document.getElementById('e_company_id').focus();
                }
                $('.data_preloader').hide();
            });
        });

         // Edit company by ajax
        $(document).on('submit', '#editting_loan_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    companies_table.ajax.reload();
                    loans_table.ajax.reload();
                    $('.loading_button').hide();
                    $('#add_loan_form').show();
                    $('#edit_loan_form').hide();
                    $('.error').html('');
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_e_' + key + '').html(error[0]);
                    });
                }
            }); 
        });

        $(document).on('click', '#delete_loan',function(e){
            e.preventDefault(); 
            var url = $(this).attr('href');
            $('#delete_loan_form').attr('action', url);       
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#delete_loan_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#delete_loan_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                    }else{
                        toastr.error(data);
                        companies_table.ajax.reload();
                        loans_table.ajax.reload();
                        $('#delete_loan_form')[0].reset();
                    }
                }
            });
        });

        $(document).on('click', '#close_loan_edit_form', function() {
            $('#add_loan_form').show();
            $('#edit_loan_form').hide();
        });
    </script>
@endpush
