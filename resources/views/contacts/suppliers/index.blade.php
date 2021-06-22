@extends('layout.master')
@push('stylesheets') @endpush
@section('title', 'Supplier List - ')
@section('content')
<div class="body-woaper">
    <div class="container-fluid">
        <div class="row">
            <div class="border-class">
                <div class="main__content">
                    <!-- =====================================================================BODY CONTENT================== -->
                    <div class="sec-name">
                        <div class="name-head">
                            <span class="fas fa-users"></span>
                            <h5>Suppliers</h5>
                        </div>

                        <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                    </div>
                </div>
                <!-- =========================================top section button=================== -->

                <div class="container-fluid">
                    <div class="row">
                        <div class="form_element">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>All Supplier</h6>
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="btn_30_blue float-end">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> Add</a>
                                    </div>

                                    <div class="btn_30_blue float-end">
                                        <a href="{{ route('contacts.suppliers.import.create') }}"><i class="fas fa-plus-square"></i> Import Suppliers</a>
                                    </div>
                                </div>
                            </div>

                                <div class="widget_content">
                                    <div class="data_preloader"> <h6><i class="fas fa-spinner"></i> Processing...</h6></div>
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
                                <b>Contact Type :</b>
                                <select name="contact_type" class="form-control ">
                                    <option value="">Select contact type</option>
                                    <option value="1">Supplier</option>
                                    <option value="2">Customer</option>
                                    <option value="3">Both (Supplier - Customer)</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                              <b>Supplier ID :</b> 
                                <input type="text" name="contact_id" class="form-control "  placeholder="Contact ID"/>
                            </div>

                            <div class="col-md-3">
                                <b>Business Name :</b>
                                <input type="text" name="business_name" class="form-control " placeholder="Business name"/>
                            </div>

                            <div class="col-md-3">
                                <b>Name :</b>  <span class="text-danger">*</span>
                                <input type="text" name="name" class="form-control  add_input" data-name="Supplier name" id="name" placeholder="Supplier name"/>
                                <span class="error error_name" style="color: red;"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <b>Phone :</b> <span class="text-danger">*</span>
                                <input type="text" name="phone" class="form-control  add_input" data-name="Phone number" id="phone" placeholder="Phone number"/>
                                <span class="error error_phone"></span>
                            </div>

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
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <b>Date Of Birth :</b>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                    </div>
                                    <input type="date" name="date_of_birth" class="form-control " autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <b>Tax Number :</b>
                                <input type="text" name="tax_number" class="form-control " placeholder="Tax number"/>
                            </div>

                            <div class="col-md-3">
                                <b>Opening Balance :</b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Opening balance will be added in this supplier due." class="fas fa-info-circle tp"></i>
                                <input type="number" name="opening_balance" class="form-control " placeholder="Opening balance"/>
                            </div>

                            <div class="col-md-3">
                                <b>Pay Term :</b>
                                <div class="col-md-12">
                                    <div class="row">
                                        <input type="text" name="pay_term_number" class="form-control  w-50"/>
                                        <select name="pay_term" class="form-control  w-50">
                                            <option value="">Select term</option>
                                            <option value="1">Days </option>
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
                               <b>Prefix :</b> 
                                <input type="text" name="prefix" class="form-control " placeholder="prefix"/>
                                <small style="font-size: 10px; color: red;">Note: This prefix for barcode.</small>
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
                                <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
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
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_supplier_form" action="{{ route('contacts.supplier.update') }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <b>Contact Type :</b>
                                <select name="contact_type" class="form-control " id="e_contact_type">
                                    <option value="">Select contact type</option>
                                    <option value="1">Supplier</option>
                                    <option value="2">Customer</option>
                                    <option value="3">Both (Supplier - Customer)</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <b>Supplier ID :</b>
                                <input type="text" name="contact_id" class="form-control "  placeholder="Contact ID" id="e_contact_id"/>
                            </div>

                            <div class="col-md-3">
                                <b>Business Name :</b>
                                <input type="text" name="business_name" class="form-control " placeholder="Business name" id="e_business_name"/>
                            </div>

                            <div class="col-md-3">
                                <b>Name :</b>  <span class="text-danger">*</span>
                                <input type="text" name="name" class="form-control  edit_input" data-name="Supplier name" id="e_name" placeholder="Supplier name" />
                                <span class="error error_e_name"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <b>Phone :</b><span class="text-danger">*</span>
                                <input type="text" name="phone" class="form-control  edit_input" data-name="Phone number" id="e_phone" placeholder="Phone number"/>
                                <span class="error error_e_phone"></span>
                            </div>

                            <div class="col-md-3">
                                <b>Alternative Number :</b>
                                <input type="text" name="alternative_phone" class="form-control " placeholder="Alternative phone number" id="e_alternative_phone"/>
                            </div>

                            <div class="col-md-3">
                                <b>Landline :</b>
                                <input type="text" name="landline" class="form-control " placeholder="landline number" id="e_landline"/>
                            </div>

                            <div class="col-md-3">
                                <b>Email :</b>
                                <input type="text" name="email" class="form-control " placeholder="Email address" id="e_email"/>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <b>Date Of Birth :</b> 
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                    </div>
                                    <input type="date" name="date_of_birth" class="form-control" autocomplete="off" id="e_date_of_birth">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <b>Tax Number :</b>
                                <input type="text" name="tax_number" class="form-control " placeholder="Tax number" id="e_tax_number"/>
                            </div>

                            <div class="col-md-3">
                                <b>Pay Term :</b>
                                <div class="col-md-12">
                                    <div class="row">
                                        <input type="text" name="pay_term_number" class="form-control  w-50" id="e_pay_term_number"/>
                                        <select name="pay_term" class="form-control  w-50" id="e_pay_term">
                                            <option value="">Select term</option>
                                            <option value="1">Days </option>
                                            <option value="2">Months</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-9">
                                <b>Address :</b>
                                <input type="text" name="address" class="form-control "  placeholder="Address" id="e_address">
                            </div>

                            <div class="col-md-3">
                               <b>Prefix :</b> 
                                <input type="text" name="prefix" id="e_prefix" class="form-control " placeholder="prefix"/>
                                <small style="font-size: 10px; color: black;">Note: This prefix for barcode.</small>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                               <b>City :</b>  
                                <input type="text" name="city" class="form-control " placeholder="City" id="e_city"/>
                            </div>

                            <div class="col-md-3">
                               <b>State :</b>
                                <input type="text" name="state" class="form-control " placeholder="State" id="e_state"/>
                            </div>

                            <div class="col-md-3">
                                <b>Country :</b> 
                                <input type="text" name="country" class="form-control " placeholder="Country" id="e_country"/>
                            </div>

                            <div class="col-md-3">
                                <b>Zip-Code :</b> 
                                <input type="text" name="zip_code" class="form-control " placeholder="zip_code" id="e_zip_code"/>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-5">
                                <b>Shipping Address :</b> 
                                <input type="text" name="shipping_address" class="form-control " placeholder="Shipping address" id="e_shipping_address"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue me-0 float-end">Save Change</button>
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    <!-- Edit Modal End--> 

    <!-- Supplier payment Modal--> 
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Payment</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="payment_modal_body">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Supplier payment Modal End-->
@endsection
@push('scripts')
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method 
    $(document).ready(function(){
        // Add category by ajax
        $('#add_supplier_form').on('submit', function(e){
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
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            $('.form-control').removeClass('is-invalid');
            $('.error').html('');
            var supplier = $(this).closest('tr').data('info');
            console.log(supplier);
            $('#id').val(supplier.id);
            $('#e_contact_type').val(supplier.type);
            $('#e_contact_id').val(supplier.contact_id);
            $('#e_name').val(supplier.name);
            $('#e_business_name').val(supplier.business_name);
            $('#e_phone').val(supplier.phone);
            $('#e_alternative_phone').val(supplier.alternative_phone);
            $('#e_landline').val(supplier.landline);
            $('#e_email').val(supplier.email);
            $('#e_address').val(supplier.address);
            $('#e_date_of_birth').val(supplier.date_of_birth);
            $('#e_tax_number').val(supplier.tax_number);
            $('#e_city').val(supplier.city);
            $('#e_state').val(supplier.state);
            $('#e_country').val(supplier.country);
            $('#e_zip_code').val(supplier.zip_code);
            $('#e_opening_balance').val(supplier.opening_balance);
            $('#e_pay_term').val(supplier.pay_term);
            $('#e_pay_term_number').val(supplier.pay_term_number);
            $('#e_shipping_address').val(supplier.shipping_address);
            $('#e_prefix').val(supplier.prefix);
            $('#editModal').modal('show');
        });

        // edit category by ajax
        $('#edit_supplier_form').on('submit', function(e){
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
                    console.log(data);
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
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {
                            // alert('Deleted canceled.')
                        } 
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
            console.log(url);
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    toastr.success(data);
                    getAllSupplier();
                }
            });
        });

        // Show supplier payment modal
        $(document).on('click', '#pay_button',function(e){
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

        //Add Supplier payment request by ajax
        $(document).on('submit', '#supplier_payment_form', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var available_amount = $('#p_available_amount').val();
            var paying_amount = $('#p_amount').val();
            if (parseFloat(paying_amount)  > parseFloat(available_amount)) {
                $('.error_p_amount').html('Paying amount must not be greater then due amount.');
                $('.loading_button').hide();
                return;
            }

            var url = $(this).attr('action');
            var inputs = $('.p_input');
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
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    if(!$.isEmptyObject(data.errorMsg)){
                        toastr.error(data.errorMsg,'ERROR'); 
                        $('.loading_button').hide();
                    }else{
                        $('.loading_button').hide();
                        $('#paymentModal').modal('hide');
                        toastr.success(data); 
                        getAllSupplier();
                    }
                }
            });
        });

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
    });
</script>
 @endpush 