@extends('layout.master')
@push('stylesheets')@endpush
@section('title', 'Customer List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-people-arrows"></span>
                                <h5>Customers</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>All Customer</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="btn_30_blue float-end">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                class="fas fa-plus-square"></i> Add</a>
                                    </div>

                                    <div class="btn_30_blue float-end">
                                        <a href="{{ route('contacts.customers.import.create') }}"><i class="fas fa-plus-square"></i> Import Customers</a>
                                    </div>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr class="text-start">
                                                <th>Actions</th>
                                                <th>Customer ID</th>
                                                <th>Name</th>
                                                <th>Business Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Tax Number</th>
                                                <th>Group</th>
                                                <th>Opening Balance</th>
                                                <th>Sale Due</th>
                                                <th>Return Due</th>
                                                <th>Status</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Customer</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_customer_form" action="{{ route('contacts.customer.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <label><strong>Contact Type :</strong> </label>
                                <select name="contact_type" class="form-control">
                                    <option value="">Select contact type</option>
                                    <option value="1">Supplier</option>
                                    <option value="2">Customer</option>
                                    <option value="3">Both (Supplier - Customer)</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Customer ID :</strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Leave empty to auto generate." class="fas fa-info-circle tp"></i></label>
                                <input type="text" name="contact_id" class="form-control"
                                    placeholder="Customer ID"/>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Business Name :</strong></label>
                                <input type="text" name="business_name" class="form-control"
                                    placeholder="Business name" />
                            </div>

                            <div class="col-md-3">
                                <label><strong>Name :</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control add_input"
                                    data-name="Customer name" id="name" placeholder="Customer name" />
                                <span class="error error_name"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <label><strong>Phone :</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control add_input"
                                    data-name="Phone number" id="phone" placeholder="Phone number" />
                                <span class="error error_phone"></span>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Alternative Number :</strong> </label>
                                <input type="text" name="alternative_phone" class="form-control"
                                    placeholder="Alternative phone number" />
                            </div>

                            <div class="col-md-3">
                                <label><strong>Landline :</strong></label>
                                <input type="text" name="landline" class="form-control"
                                    placeholder="landline number" />
                            </div>

                            <div class="col-md-3">
                                <label><strong>Email :</strong></label>
                                <input type="text" name="email" class="form-control"
                                    placeholder="Email address" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <label><strong>Date Of Birth :</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fas fa-calendar-week input_i"></i></span>
                                    </div>
                                    <input type="date" name="date_of_birth" class="form-control"
                                        autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Tax Number :</strong></label>
                                <input type="text" name="tax_number" class="form-control"
                                    placeholder="Tax number" />
                            </div>

                            <div class="col-md-3">
                                <label><strong>Opening Balance :</strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Opening balance will be added in this customer due." class="fas fa-info-circle tp"></i></label>
                                <input type="number" step="any" name="opening_balance" class="form-control"
                                    placeholder="Opening balance" value="0.00" />
                            </div>

                            <div class="col-md-3">
                                <label><strong>Pay Term :</strong> </label>
                                <div class="col-md-12">
                                    <div class="row">
                                        <input type="text" name="pay_term_number"
                                            class="form-control w-50" />
                                        <select name="pay_term" class="form-control w-50">
                                            <option value="1">Select term</option>
                                            <option value="2">Days </option>
                                            <option value="3">Months</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <label><strong>Customer Group :</strong> </label>
                                <select name="customer_group_id" class="form-control"
                                    id="customer_group_id">
                                    <option value="">None</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-9">
                                <label><strong>Address :</strong> </label>
                                <input type="text" name="address" class="form-control"
                                    placeholder="Address">
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <label><strong>City :</strong> </label>
                                <input type="text" name="city" class="form-control" placeholder="City" />
                            </div>

                            <div class="col-md-3">
                                <label><strong>State :</strong> </label>
                                <input type="text" name="state" class="form-control" placeholder="State" />
                            </div>

                            <div class="col-md-3">
                                <label><strong>Country :</strong> </label>
                                <input type="text" name="country" class="form-control"
                                    placeholder="Country" />
                            </div>

                            <div class="col-md-3">
                                <label><strong>Zip-Code :</strong> </label>
                                <input type="text" name="zip_code" class="form-control"
                                    placeholder="zip_code" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-5">
                                <label><strong>Shipping Address :</strong> </label>
                                <input type="text" name="shipping_address" class="form-control"
                                    placeholder="Shipping address" />
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Customer</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit-modal-form-body">
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Customer payment Modal-->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Receive Payment</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="payment_modal_body">

                </div>
            </div>
        </div>
    </div>
    <!-- Customer payment Modal End-->

    <!-- Money Receipt list Modal-->
    <div class="modal fade" id="moneyReceiptListModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Payment Receipt Voucher List</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="receipt_voucher_list_modal_body">

                </div>
            </div>
        </div>
    </div>
    <!-- Money Receipt list Modal End-->

    <!--add money receipt Modal-->
    <div class="modal fade" id="MoneyReciptModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Generate Money Receipt Voucher</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="money_receipt_modal">
                    <!--begin::Form-->
                </div>
            </div>
        </div>
    </div>
    <!--add money receipt Modal End-->

    <!--add money receipt Modal-->
    <div class="modal fade" id="changeReciptStatusModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
    </div>
    <!--add money receipt Modal End-->
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [ 
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
            ],
            "processing": true,
            "serverSide": true,
            aaSorting: [[0, 'asc']],
            ajax: "{{ route('contacts.customer.index') }}",
            "lengthMenu" : [25, 100, 500, 1000, 2000],
            columnDefs: [{"targets": [0],"orderable": false,"searchable": false}],
            columns: [
                {data: 'action',name: 'action'},
                {data: 'contact_id',name: 'contact_id'},
                {data: 'name',name: 'name'},
                {data: 'business_name',name: 'business_name'},
                {data: 'phone',name: 'phone'},
                {data: 'email',name: 'email'},
                {data: 'tax_number',name: 'tax_number'},
                {data: 'group_name',name: 'category'},
                {data: 'opening_balance',name: 'opening_balance'},
                {data: 'total_sale_due',name: 'total_sale_due'},
                {data: 'total_sale_return_due',name: 'total_sale_return_due'},
                {data: 'status',name: 'status'},
            ],
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method 
        $(document).ready(function() {
            // Add category by ajax
            $('#add_customer_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.add_input');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val();
                    if (idValue == '') {
                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_' + inputId).html(fieldName + ' is required.');
                    }
                });

                if (countErrorField > 0) {
                    $('.loading_button').hide();
                    return;
                }

                $('.submit_button').prop('type', 'button');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_customer_form')[0].reset();
                        table.ajax.reload();
                        $('.loading_button').hide();
                        $('#addModal').modal('hide');
                        $('.submit_button').prop('type', 'submit');
                    }
                });
            });

            // Pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.get(url, function(data) {
                    $('#edit-modal-form-body').html(data);
                    $('#editModal').modal('show');
                    $('.data_preloader').hide();
                });
            });

            // edit category by ajax
            $(document).on('submit', '#edit_customer_form',function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.edit_input');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val();
                    if (idValue == '') {
                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_' + inputId).html(fieldName + ' is required.');
                    }
                });

                if (countErrorField > 0) {
                    $('.loading_button').hide();
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        console.log(data);
                        toastr.success(data);
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#editModal').modal('hide');
                    }
                });
            });

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
            $(document).on('submit', '#deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    async: false,
                    data: request,
                    success: function(data) {
                        table.ajax.reload();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });

            // Show sweet alert for delete
            $(document).on('click', '#change_status', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                 $.confirm({
                    'title': 'Changes Status Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger', 'action': function() {
                                $.ajax({
                                    url: url,type: 'get',
                                    success: function(data) {
                                        toastr.success(data);
                                        table.ajax.reload();
                                    }
                                });
                            }
                        },
                        'No': {'class': 'no btn-modal-primary','action': function() { console.log('Confirmation canceled.');}}
                    }
                });
            });

            // Show Customer payment modal
            $(document).on('click', '#pay_button', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('.data_preloader').show();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#payment_modal_body').html(data);
                        $('#paymentModal').modal('show');
                        $('.data_preloader').hide();
                    }
                });
            });

            // Show supplier return payment modal
            $(document).on('click', '#pay_return_button', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('.data_preloader').show();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#payment_modal_body').html(data);
                        $('#paymentModal').modal('show');
                        $('.data_preloader').hide();
                    }
                });
            });

            //Add Customer payment request by ajax
            $(document).on('submit', '#customer_payment_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var available_amount = $('#p_available_amount').val();
                var paying_amount = $('#p_amount').val();
                if (parseFloat(paying_amount) > parseFloat(available_amount)) {
                    $('.error_p_amount').html('Paying amount must not be greater then due amount.');
                    $('.loading_button').hide();
                    return;
                }

                var url = $(this).attr('action');
                var inputs = $('.p_input');
                inputs.removeClass('is-invalid');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val();
                    if (idValue == '') {
                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_' + inputId).html(fieldName + ' is required.');
                    }
                });

                if (countErrorField > 0) {
                    $('.loading_button').hide();
                    toastr.error('Please check again all form fields.', 'Some thing want wrong.');
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (!$.isEmptyObject(data.errorMsg)) {
                            toastr.error(data.errorMsg, 'ERROR');
                            $('.loading_button').hide();
                        } else {
                            $('.loading_button').hide();
                            $('#paymentModal').modal('hide');
                            toastr.success(data);
                            table.ajax.reload();
                        }
                    }
                });
            });

            $(document).on('change', '#payment_method', function() {
                var value = $(this).val();
                $('.payment_method').hide();
                $('#' + value).show();
            });

            $(document).on('click', '#generate_receipt', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#money_receipt_modal').html(data);
                        $('#MoneyReciptModal').modal('show');
                    }
                });
            });

            $(document).on('click', '#money_receipt_list', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('.data_preloader').show();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#receipt_voucher_list_modal_body').html(data);
                        $('#moneyReceiptListModal').modal('show');
                        $('.data_preloader').hide();
                    }
                });
            });

            $(document).on('submit', '#money_receipt_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success('Successfully money receipt voucher is generated.');
                        $('#MoneyReciptModal').modal('hide');
                        $('#moneyReceiptListModal').modal('hide');
                        $('.loading_button').hide();
                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('public/assets/css/print/sale.print.css') }}",
                            removeInline: false,
                            printDelay: 500,
                            header: null,
                        });
                    }
                });
            });

            // Pass editable data to edit modal fields
            $(document).on('click', '#edit_receipt', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.get(url, function(data) {
                    $('#money_receipt_modal').html(data);
                    $('#MoneyReciptModal').modal('show');
                });
            });

            $(document).on('click', '#print_receipt', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'html',
                    success: function(data) {
                        console.log(data);
                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('public/assets/css/print/sale.print.css') }}",
                            removeInline: false,
                            printDelay: 500,
                            header: null,
                        });
                        $('.print_area').remove();
                        return;
                    }
                });
            });

            // Show sweet alert for delete
            $(document).on('click', '#change_receipt_status', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('.receipt_preloader').show();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#changeReciptStatusModal').html(data);
                        $('#changeReciptStatusModal').modal('show');
                        $('.receipt_preloader').hide();
                    }
                });
            });

            $(document).on('submit', '#change_voucher_status_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.vcs_input');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val();
                    if (idValue == '') {
                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_vcs_' + inputId).html(fieldName + ' is required.');
                    }
                });

                if (countErrorField > 0) {
                    $('.loading_button').hide();
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('#changeReciptStatusModal').modal('hide');
                        $('#moneyReceiptListModal').modal('hide');
                        table.ajax.reload();
                    }
                });
            });

            $(document).on('click', '#delete_receipt',function(e){
                e.preventDefault(); 
                var url = $(this).attr('href');
                    var tr = $(this).closest('tr');
                    $('#receipt_deleted_form').attr('action', url);     
                $.confirm({
                    'title': 'Delete Confirmation',
                    'content': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $('#receipt_deleted_form').submit();
                                tr.remove();
                            }
                        },
                        'No': {
                            'class': 'no btn-modal-primary',
                            'action': function() {console.log('Deleted canceled.');} 
                        }
                    }
                });
            });

            //data delete by ajax
            $(document).on('submit', '#receipt_deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    async: false,
                    data: request,
                    success: function(data) {
                        toastr.error(data);
                        $('#receipt_deleted_form')[0].reset();
                    }
                });
            });

            $(document).on('change', '#is_header_less', function() {
                if ($(this).is(':CHECKED', true)) {
                    $('.gap-from-top-add').show();
                } else {
                    $('.gap-from-top-add').hide();
                }
            });
        });
    </script>
@endpush
