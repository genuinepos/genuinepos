@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('title', 'Supplier List - ')
@section('content')
<div class="body-woaper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <span class="fas fa-users"></span>
                <h5>Suppliers</h5>
            </div>

            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
        </div>
    </div>

    <div class="p-3">
        @if ($addons->branches == 1)
            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-3">
                            <form id="filter_form" class="p-2">
                                <div class="form-group row">
                                    <div class="col-xl-2 col-lg-3 col-md-4">
                                        <label><strong>@lang('menu.business_location') :</strong></label>
                                        <select name="branch_id"
                                            class="form-control submit_able" id="branch_id" autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (@lang('menu.head_office'))</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">
                                                    {{ $branch->name . '/' . $branch->branch_code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-lg-3 col-md-4">
                                        <label><strong></strong></label>
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>All Supplier</h6>
                </div>

                <div class="col-md-6 d-flex justify-content-end gap-2">
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> @lang('menu.add') (Ctrl+Enter)</a>

                    <a href="{{ route('contacts.suppliers.import.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> Import Suppliers</a>
                </div>
            </div>

                <div class="widget_content">
                    <div class="data_preloader"> <h6>
                        <i class="fas fa-spinner"></i> @lang('menu.processing')...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr class="text-start">
                                    <th>Actions</th>
                                    <th>Supplier ID</th>
                                    <th>Prefix</th>
                                    <th>@lang('menu.name')</th>
                                    <th>Business</th>
                                    <th>Phone</th>
                                    <th>Opening Balance</th>
                                    <th>@lang('menu.total_purchase')</th>
                                    <th>@lang('menu.total_paid')</th>
                                    <th>@lang('menu.purchase_due')</th>
                                    <th>Total Return</th>
                                    <th>Return Due</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th colspan="6" class="text-white text-end">@lang('menu.total') : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                    <th id="opening_balance" class="text-white text-end"></th>
                                    <th id="total_purchase" class="text-white text-end"></th>
                                    <th id="total_paid" class="text-white text-end"></th>
                                    <th id="total_purchase_due" class="text-white text-end"></th>
                                    <th id="total_return" class="text-white text-end"></th>
                                    <th id="total_purchase_return_due" class="text-white text-end"></th>
                                    <th class="text-white text-start">---</th>
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

    <!-- Add Modal ---->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Supplier</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_supplier_form" action="{{ route('contacts.supplier.store') }}">

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                                <b>@lang('menu.name') :</b>  <span class="text-danger">*</span>
                                <input type="text" name="name" class="form-control  add_input" data-name="Supplier name" id="name" placeholder="Supplier name"/>
                                <span class="error error_name" style="color: red;"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <b>@lang('menu.phone') :</b> <span class="text-danger">*</span>
                                <input type="text" name="phone" class="form-control  add_input" data-name="Phone number" id="phone" placeholder="Phone number"/>
                                <span class="error error_phone"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                              <b>Supplier ID :</b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Leave empty to auto generate." class="fas fa-info-circle tp"></i>
                                <input type="text" name="contact_id" class="form-control" placeholder="Contact ID"/>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <b>Business Name :</b>
                                <input type="text" name="business_name" class="form-control" placeholder="Business name"/>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                               <b>Alternative Number :</b>
                                <input type="text" name="alternative_phone" class="form-control " placeholder="Alternative phone number"/>
                            </div>

                            <div class="col-lg-3 col-md-6">
                               <b>Landline :</b>
                                <input type="text" name="landline" class="form-control " placeholder="landline number"/>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <b>Email :</b>
                                <input type="text" name="email" class="form-control " placeholder="Email address"/>
                            </div>

                            <div class="col-lg-3 col-md-6">
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
                            <div class="col-lg-3 col-md-6">
                                <b>Tax Number :</b>
                                <input type="text" name="tax_number" class="form-control " placeholder="Tax number"/>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <b>@lang('menu.opening_balance') :</b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Opening balance will be added in this supplier due." class="fas fa-info-circle tp"></i>
                                <input type="number" name="opening_balance" class="form-control" placeholder="@lang('menu.opening_balance')"/>
                            </div>

                            <div class="col-lg-3 col-md-6">
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
                                <b>@lang('menu.address') :</b>
                                <input type="text" name="address" class="form-control "  placeholder="Address">
                            </div>
                            <div class="col-md-3">
                               <b>Prefix <i data-bs-toggle="tooltip" data-bs-placement="right" title="This prefix for barcode." class="fas fa-info-circle tp"></i> :</b>
                                <input type="text" name="prefix" class="form-control " placeholder="prefix"/>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                                <b>City :</b>
                                <input type="text" name="city" class="form-control " placeholder="City"/>
                            </div>

                            <div class="col-lg-3 col-md-6">
                               <b>State :</b>
                                <input type="text" name="state" class="form-control " placeholder="State"/>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <b>Country :</b>
                                <input type="text" name="country" class="form-control " placeholder="Country"/>
                            </div>

                            <div class="col-lg-3 col-md-6">
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
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                    <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                                </div>
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
        <div class="modal-dialog modal-xl" role="document">
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
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
        ],
        "processing": true,
        "serverSide": true,
        aaSorting: [[0, 'asc']],
        ajax: "{{ route('contacts.supplier.index') }}",
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('contacts.supplier.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
            }
        },
        columnDefs: [{"targets": [0, 12],"orderable": false,"searchable": false}],
        columns: [
            {data: 'action',name: 'action'},
            {data: 'contact_id', name: 'contact_id'},
            {data: 'prefix', name: 'prefix'},
            {data: 'name', name: 'name'},
            {data: 'business_name', name: 'business_name'},
            {data: 'phone', name: 'phone'},
            {data: 'opening_balance', name: 'opening_balance', className: 'text-end'},
            {data: 'total_purchase', name: 'total_purchase', className: 'text-end'},
            {data: 'total_paid', name: 'total_paid', className: 'text-end'},
            {data: 'total_purchase_due', name: 'total_purchase_due', className: 'text-end'},
            {data: 'total_return', name: 'total_return', className: 'text-end'},
            {data: 'total_purchase_return_due', name: 'total_purchase_return_due', className: 'text-end'},
            {data: 'status', name: 'status'},
        ],fnDrawCallback: function() {

            var opening_balance = sum_table_col($('.data_tbl'), 'opening_balance');
            $('#opening_balance').text(bdFormat(opening_balance));
            var total_purchase = sum_table_col($('.data_tbl'), 'total_purchase');
            $('#total_purchase').text(bdFormat(total_purchase));
            var total_purchase_due = sum_table_col($('.data_tbl'), 'total_purchase_due');
            $('#total_purchase_due').text(bdFormat(total_purchase_due));
            var total_paid = sum_table_col($('.data_tbl'), 'total_paid');
            $('#total_paid').text(bdFormat(total_paid));
            var total_return = sum_table_col($('.data_tbl'), 'total_return');
            $('#total_return').text(bdFormat(total_return));
            var total_purchase_return_due = sum_table_col($('.data_tbl'), 'total_purchase_return_due');
            $('#total_purchase_return_due').text(bdFormat(total_purchase_return_due));
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
        table.ajax.reload();
    });

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
                success:function(data) {

                    toastr.success('Supplier added successfully.');
                    $('#add_supplier_form')[0].reset();
                    $('.loading_button').hide();
                    $('#addModal').modal('hide');
                    $('.submit_button').prop('type', 'submit');
                    table.ajax.reload();
                },error: function () {

                    $('.loading_button').hide();
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
                'title': 'Confirmation',
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
                    table.ajax.reload();
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
                    table.ajax.reload();
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
