@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('title', 'Supplier List - ')
@section('content')
<div class="body-woaper">
    <div class="container-fluid">
        <div class="row">
            <div class="border-class">
                <div class="main__content">
                    <div class="sec-name">
                        <div class="name-head">
                            <span class="fas fa-users"></span>
                            <h5>Suppliers</h5>
                        </div>

                        <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                    </div>
                </div>
                
                <div class="row margin_row mt-1">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>All Supplier</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="btn_30_blue float-end">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> Add (Ctrl+Enter)</a>
                                </div>

                                <div class="btn_30_blue float-end">
                                    <a href="{{ route('contacts.suppliers.import.create') }}"><i class="fas fa-plus-square"></i> Import Suppliers</a>
                                </div>
                            </div>
                        </div>

                            <div class="widget_content">
                                <div class="data_preloader"> <h6>
                                    <i class="fas fa-spinner"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>Supplier ID</th>
                                                <th>Name</th>
                                                <th>Business Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Tax Number</th>
                                                <th>Opening Balance</th>
                                                <th>Total Purchase Due</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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

    <!-- Add Modal ---->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Supplier</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_supplier_form" action="{{ route('contacts.supplier.store') }}">

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <b>Name :</b>  <span class="text-danger">*</span>
                                <input type="text" name="name" class="form-control  add_input" data-name="Supplier name" id="name" placeholder="Supplier name"/>
                                <span class="error error_name" style="color: red;"></span>
                            </div>

                            <div class="col-md-3">
                              <b>Supplier ID :</b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Leave empty to auto generate." class="fas fa-info-circle tp"></i>
                                <input type="text" name="contact_id" class="form-control" placeholder="Contact ID"/>
                            </div>

                            <div class="col-md-3">
                                <b>Business Name :</b>
                                <input type="text" name="business_name" class="form-control" placeholder="Business name"/>
                            </div>

                            <div class="col-md-3">
                                <b>Phone :</b> <span class="text-danger">*</span>
                                <input type="text" name="phone" class="form-control  add_input" data-name="Phone number" id="phone" placeholder="Phone number"/>
                                <span class="error error_phone"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                               <b>Alternative Number :</b>
                                <input type="text" name="alternative_phone" class="form-control " placeholder="Alternative phone number"/>
                            </div>

                            <div class="col-md-3">
                               <b>Landline :</b>
                                <input type="text" name="landline" class="form-control " placeholder="landline number"/>
                            </div>

                            <div class="col-md-3">
                                <b>Email :</b>
                                <input type="text" name="email" class="form-control " placeholder="Email address"/>
                            </div>

                            <div class="col-md-3">
                                <b>Date Of Birth :</b>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                    </div>
                                    <input type="text" name="date_of_birth" class="form-control date-of-birth-picker" autocomplete="off"  placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <b>Tax Number :</b>
                                <input type="text" name="tax_number" class="form-control " placeholder="Tax number"/>
                            </div>

                            <div class="col-md-3">
                                <b>Opening Balance :</b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Opening balance will be added in this supplier due." class="fas fa-info-circle tp"></i>
                                <input type="number" name="opening_balance" class="form-control " placeholder="Opening balance"/>
                            </div>

                            <div class="col-md-3">
                                <label><b>Pay Term</b> : </label>
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="number" step="any" name="pay_term_number" class="form-control"
                                        id="pay_term_number" placeholder="Number"/>
                                    </div>
                                    
                                    <div class="col-md-7">
                                        <select name="pay_term" class="form-control">
                                            <option value="">Days/Months</option>
                                            <option value="1">Days</option>
                                            <option value="2">Months</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-9">
                                <b>Address :</b>
                                <input type="text" name="address" class="form-control "  placeholder="Address">
                            </div>
                            <div class="col-md-3">
                               <b>Prefix <i data-bs-toggle="tooltip" data-bs-placement="right" title="This prefix for barcode." class="fas fa-info-circle tp"></i> :</b>
                                <input type="text" name="prefix" class="form-control " placeholder="prefix"/>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <b>City :</b>
                                <input type="text" name="city" class="form-control " placeholder="City"/>
                            </div>

                            <div class="col-md-3">
                               <b>State :</b>
                                <input type="text" name="state" class="form-control " placeholder="State"/>
                            </div>

                            <div class="col-md-3">
                                <b>Country :</b>
                                <input type="text" name="country" class="form-control " placeholder="Country"/>
                            </div>

                            <div class="col-md-3">
                                <b>Zip-Code :</b>
                                <input type="text" name="zip_code" class="form-control " placeholder="zip_code"/>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-5">
                               <b>Shipping Address :</b>
                                <input type="text" name="shipping_address" class="form-control " placeholder="Shipping address"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn button-success me-0 float-end submit_button">Save</button>
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End---->

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Supplier</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Edit Modal End-->

    <!-- Supplier payment Modal-->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Payment</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="payment_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Supplier payment Modal End-->

    <!-- Supplier payment view Modal-->
    <div class="modal fade" id="viewPaymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">View Payment</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="payment_list"></div>
            </div>
        </div>
    </div>
    <!-- Supplier payment view Modal End-->

    <!-- Supplier payment details Modal-->
    <div class="modal fade" id="paymentDatailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">View Payment</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <div id="payment_details_body"></div>

                    <div class="row">
                        <div class="col-md-6 text-right">
                            <ul class="list-unstyled">
                                <li class="mt-3" id="payment_attachment"></li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-end">
                            <ul class="list-unstyled">
                                {{-- <li class="mt-3"><a href="" id="print_payment" class="btn btn-sm btn-primary">Print</a></li> --}}
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
                                <button type="submit" id="print_payment" class="c-btn button-success">Print</button>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Supplier payment details Modal End-->
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Get all category by ajax
    function getAllSupplier(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('contacts.supplier.get.all.supplier') }}",
            type:'get',
            success:function(data){
                $('.table-responsive').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllSupplier();

    // Setup ajax for csrf token.
    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).ready(function(){
        // Add Supplier by ajax
        $('#add_supplier_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
                $('.error').html('');  
                var countErrorField = 0; 
                 
            $.each(inputs, function(key, val){

                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();

                if(idValue == ''){

                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){

                $('.loading_button').hide();
                return;
            }
            
            $('.submit_button').prop('type', 'button');
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('#add_supplier_form')[0].reset();
                    getAllSupplier();
                    $('.loading_button').hide();
                    $('#addModal').modal('hide');
                    $('.submit_button').prop('type', 'submit');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#edit_modal_body').html(data);
                $('#editModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // edit category by ajax
        $(document).on('submit', '#edit_supplier_form', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.edit_input');
                $('.error').html('');  
                var countErrorField = 0;  
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){
                $('.loading_button').hide();
                return;
            }
            
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('.loading_button').hide();
                    $('#edit_supplier_form')[0].reset();
                    getAllSupplier();
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
        $(document).on('submit', '#deleted_form',function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                async:false,
                data:request,
                success:function(data){
                    getAllSupplier();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#change_status',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    toastr.success(data);
                    getAllSupplier();
                }
            });
        });

        // // Show supplier payment modal
        // $(document).on('click', '#pay_button',function(e){
        //     e.preventDefault();
        //     var url = $(this).attr('href');
        //     $('.data_preloader').show();
        //     $.ajax({
        //         url:url,
        //         type:'get',
        //         success:function(data) {
        //             $('#payment_modal_body').html(data);
        //             $('#paymentModal').modal('show');
        //             $('.data_preloader').hide();
        //             document.getElementById('p_amount').focus();
        //         }
        //     });
        // });

        // //Add Supplier payment request by ajax
        // $(document).on('submit', '#supplier_payment_form', function(e){
        //     e.preventDefault();

        //     $('.loading_button').show();
        //     var available_amount = $('#p_available_amount').val();
        //     var paying_amount = $('#p_paying_amount').val();

        //     if (parseFloat(paying_amount)  > parseFloat(available_amount)) {
        //         $('.error_p_paying_amount').html('Paying amount must not be greater then due amount.');
        //         $('.loading_button').hide();
        //         return;
        //     }

        //     var url = $(this).attr('action');
          
        //     $.ajax({
        //         url:url,
        //         type:'post',
        //         data: new FormData(this),
        //         contentType: false,
        //         cache: false,
        //         processData: false,
        //         success:function(data){
        //             $('.loading_button').hide();
        //             $('.error').html('');
        //             if(!$.isEmptyObject(data.errorMsg)){
        //                 toastr.error(data.errorMsg,'ERROR');
        //             }else{
        //                 $('#paymentModal').modal('hide');
        //                 toastr.success(data);
        //                 getAllSupplier();
        //             }
        //         },
        //         error: function(err) {
        //             $('.loading_button').hide();
        //             $('.error').html('');

        //             if (err.status == 0) {
        //                 toastr.error('Net Connetion Error. Reload This Page.'); 
        //                 return;
        //             }

        //             $.each(err.responseJSON.errors, function(key, error) {
        //                 $('.error_p_' + key + '').html(error[0]);
        //             });
        //         }
        //     });
        // });

        // $(document).on('click', '#add_payment',function(e){
        //     e.preventDefault();
        //     var url = $(this).attr('href');
        //     $('#deleted_form').attr('action', url);
        //     $.confirm({
        //         'title': 'Payment Confirmation',
        //         'content': 'Are you sure to make this payment?',
        //         'buttons': {
        //             'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#supplier_payment_form').submit();}},
        //             'No': {'class': 'no btn-danger','action': function() {console.log('Edit canceled.');}}
        //         }
        //     });
        // });

        // Show supplier return payment modal
        $(document).on('click', '#pay_receive_button',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('.data_preloader').show();
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('#payment_modal_body').html(data);
                    $('#paymentModal').modal('show');
                    $('.data_preloader').hide();
                }
            });
        });

        $(document).on('change', '#payment_method', function () {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#'+value).show();
        });

        $(document).on('click', '#view_payment', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#payment_list').html(data);
                $('#viewPaymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        $(document).on('click', '#payment_details', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#payment_details_body').html(data);
                $('#paymentDatailsModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // Print single payment details
        $('#print_payment').on('click', function (e) {
           e.preventDefault();
            var body = $('.sale_payment_print_area').html();
            var header = $('.header_area').html();
            var footer = $('.signature_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('public/assets/css/print/purchase.print.css')}}",
                removeInline: true,
                printDelay: 500,
                header: header,
                footer: footer
            });
        });

        $(document).on('click', '#delete_payment',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_payment_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_payment_form').submit();}},
                    'No': {'class': 'no btn-modal-primary','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_payment_form',function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                async:false,
                data:request,
                success:function(data){
                    getAllSupplier();
                    toastr.error(data);
                    $('#deleted_payment_form')[0].reset();
                    $('#viewPaymentModal').modal('hide');
                }
            });
        });
    });

    document.onkeyup = function () {
        var e = e || window.event; // for IE to cover IEs window event-object
        //console.log(e);
        if(e.ctrlKey && e.which == 13) {
            $('#addModal').modal('show');
            setTimeout(function () {
                $('#name').focus();
            }, 500);
            //return false;
        }
    }
</script>
 @endpush
