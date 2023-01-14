@extends('layout.master')
@push('stylesheets')
<link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@section('title', 'Assets - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-glass-whiskey"></span>
                    <h5>@lang('menu.assets')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
                </a>
            </div>

            <div class="p-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-3 d-none">
                            <div class="element-body">
                                <form id="filter_form" action="" method="get">
                                    @csrf
                                    <div class="form-group row">
                                        @if ($generalSettings['addons__branches'] == 1)
                                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.business_location') </strong></label>
                                                    <select name="branch_id" class="form-control submit_able  select2" id="filter_branch_id" autofocus>
                                                        <option value="">@lang('menu.all')</option>
                                                        <option value="NULL">{{ $generalSettings['business__shop_name'] }} (@lang('menu.head_office'))</option>
                                                        @foreach ($branches as $br)
                                                            <option value="{{ $br->id }}">{{ $br->name.'/'.$br->branch_code }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @else
                                                <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id }}">
                                            @endif
                                        @endif

                                        <div class="col-md-3">
                                            <label><strong>@lang('menu.asset_type') </strong></label>
                                            <select name="type_id" class="form-control submit_able select2" id="filter_type_id" autofocus>

                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label><strong></strong></label>
                                            <div class="input-group">
                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>
                </div>

                <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6></div>
                <div class="card">
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <div class="tab_list_area">
                            <div class="btn-group">
                                <a id="tab_btn" data-show="asset_type" class="btn btn-sm btn-primary tab_btn tab_active asset_hide_heading" href="#"><i class="fas fa-info-circle"></i> @lang('menu.asset_type')</a>
                                <a id="tab_btn" data-show="assets" class="btn btn-sm btn-primary tab_btn asset_show_heading" href="#"><i class="fas fa-scroll"></i> @lang('menu.assets')</a>
                            </div>
                        </div>

                        <div class="card p-2">
                            <div class="tab_contant asset_type">
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAssetTypeModal"><i class="fas fa-plus-square"></i>@lang('menu.add_type')</a>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive" >
                                            <table class="display data_tbl data__table asset_type_table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('menu.sl')</th>
                                                        <th>@lang('menu.type')</th>
                                                        <th>@lang('menu.type_code')</th>
                                                        <th>@lang('menu.action')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            <form id="deleted_asset_type_form" action="" method="post">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab_contant assets" style="display: none;">
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAssetModal"><i class="fas fa-plus-square"></i>@lang('menu.add_asset')</a>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="display data_tbl data__table asset_table w-100">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('menu.sl')</th>
                                                        <th>@lang('menu.asset')</th>
                                                        <th>@lang('menu.type')</th>
                                                        <th>{{ __('Available Location') }}</th>
                                                        <th>@lang('menu.quantity')</th>
                                                        <th>@lang('menu.per_unit_value')</th>
                                                        <th>@lang('menu.total_value')</th>
                                                        <th>@lang('menu.action')</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                            <form id="deleted_asset_form" action="" method="post">
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

    <!-- Add Asset Type Modal -->
    <div class="modal fade" id="addAssetTypeModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_asset_type')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_assset_type_form" action="{{ route('accounting.assets.asset.type.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><b>@lang('menu.type_name') </b> <span class="text-danger">*</span></label>
                                <input type="text" name="asset_type_name" class="form-control" id="asset_type_name"
                                    placeholder="@lang('menu.type_name')" />
                                <span class="error error_asset_type_name"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('menu.type_code') </b> </label>
                                <input type="text" name="asset_type_code" class="form-control" placeholder="@lang('menu.type_code')"/>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
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
    <!-- Add Asset Type Modal -->

    <!-- Edit Asset Type Modal -->
    <div class="modal fade" id="editAssetTypeModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Asset Type') }}</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_asset_type_modal_body">
                    <!--begin::Form-->

                </div>
            </div>
        </div>
    </div>
    <!-- Edit Asset Type Modal -->

     <!-- Add Asset Modal -->
    <div class="modal fade" id="addAssetModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_asset')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_assset_form" action="{{ route('accounting.assets.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><b>@lang('menu.asset_name') </b> <span class="text-danger">*</span></label>
                                <input type="text" name="asset_name" class="form-control" id="asset_name"
                                    placeholder="@lang('menu.asset_type')" autofocus/>
                                <span class="error error_asset_name"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('menu.asset_type') </b> <span class="text-danger">*</span></label>
                                <select name="type_id" class="form-control" id="type_id" >
                                <option value="">@lang('menu.select_asset_type')</option>
                                </select>
                            <span class="error error_type_id"></span>
                            </div>
                        </div>

                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                            <div class="form-group row mt-1">
                                <div class="col-md-12">
                                    <label><b>@lang('menu.branch') </b> <span class="text-danger">*</span></label>
                                    <select name="branch_id" class="form-control" id="branch_id">
                                        <option value="">{{ $generalSettings['business__shop_name'] }} (@lang('menu.head_office'))</option>
                                        @foreach ($branches as $br)
                                            <option value="{{ $br->id }}">{{ $br->name.'/'.$br->branch_code }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error error_branch_id"></span>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('menu.quantity') </b> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="quantity" class="form-control" id="quantity"
                                    placeholder="Asset Quantity"/>
                                <span class="error error_quantity"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('menu.per_unit_value') </b> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="per_unit_value" class="form-control" id="per_unit_value"
                                    placeholder="@lang('menu.per_unit_value')"/>
                                <span class="error error_per_unit_value"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('menu.total_value') </b> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="total_value" class="form-control" id="total_value"
                                    placeholder="Total Asset Value" />
                                <span class="error error_total_value"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
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
 <!-- Add Asset Modal -->

    <!-- Add Asset Modal -->
    <div class="modal fade" id="editAssetModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Asset') }}</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_asset_modal_body">
                    <!--begin::Form-->
                </div>
            </div>
        </div>
    </div>
 <!-- Add Asset Modal -->
@endsection
@push('scripts')
<script>
    var asset_type_table = $('.asset_type_table').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: 'Excel', messageTop: 'Asset types', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf', messageTop: 'Asset types', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print', messageTop: '<b>Asset types</b>', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        "lengthMenu" : [25, 100, 500, 1000, 2000],
        ajax: "{{ route('accounting.assets.index') }}",
        columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'asset_type_name',name: 'asset_type_name'},
            {data: 'asset_type_code',name: 'asset_type_code'},
            {data: 'action',name: 'action'},
        ],
    });

    $(document).on('submit', '#add_assset_type_form', function(e) {
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
                toastr.success(data);
                $('#add_assset_type_form')[0].reset();
                $('.loading_button').hide();
                $('#addAssetTypeModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');
                asset_type_table.ajax.reload();
                getFormAssetTypes();
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
    $(document).on('click', '#edit', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                $('#edit_asset_type_modal_body').html(data);
                $('#editAssetTypeModal').modal('show');
            }
        });
    });

    $(document).on('submit', '#edit_assset_type_form', function(e) {
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
                $('.loading_button').hide();
                $('#editAssetTypeModal').modal('hide');
                $('.error').html('');
                asset_type_table.ajax.reload();
                getFormAssetTypes();
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

    $(document).on('click', '#delete_type',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_asset_type_form').attr('action', url);
        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_asset_type_form').submit();}},
                'No': {'class': 'no btn-modal-primary','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_asset_type_form', function(e) {
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
                asset_type_table.ajax.reload();
                getFormAssetTypes();
                $('#deleted_asset_type_form')[0].reset();
            }
        });
    });

    function getFormAssetTypes(){
        $.ajax({
            url:"{{route('accounting.assets.form.asset.type')}}",
            success:function(types){
                $('#type_id').empty();
                $('#filter_type_id').empty();
                $('#type_id').append('<option value="">Select Asset Type</option>');
                $('#filter_type_id').append('<option value="">@lang('menu.all')</option>');
                $.each(types, function(key, val){
                    $('#type_id').append('<option value="'+val.id+'">'+val.asset_type_name+'</option>');
                    $('#filter_type_id').append('<option value="'+val.id+'">'+val.asset_type_name+'</option>');
                });
            }
        });
    }
    getFormAssetTypes();
</script>

<script>
    $('.asset_show_heading').on('click', function(e) {
        e.preventDefault();
        $('.form_element').removeClass('d-none');

    });
    $('.asset_hide_heading').on('click', function(e) {
        e.preventDefault();
        $('.form_element').addClass('d-none');

    });
    var asset_table = $('.asset_table').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        "lengthMenu" : [25, 100, 500, 1000, 2000],
        "ajax": {
            "url": "{{ route('accounting.assets.all') }}",
            "data": function(d) {
                d.branch_id = $('#filter_branch_id').val();
                d.type_id = $('#filter_type_id').val();
            }
        },
        columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'asset_name',name: 'asset_name'},
            {data: 'asset_type',name: 'asset_type'},
            {data: 'branch',name: 'branch'},
            {data: 'quantity',name: 'quantity'},
            {data: 'per_unit_value',name: 'per_unit_value'},
            {data: 'total_value',name: 'total_value'},
            {data: 'action',name: 'action'},
        ],fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        asset_table.ajax.reload();
    });

    $(document).on('submit', '#add_assset_form', function(e) {
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
                toastr.success(data);
                $('#add_assset_form')[0].reset();
                $('.loading_button').hide();
                $('#addAssetModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');
                asset_table.ajax.reload();
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
    $(document).on('click', '#edit_asset', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                $('#edit_asset_modal_body').html(data);
                $('#editAssetModal').modal('show');
            }
        });
    });

    $(document).on('submit', '#edit_assset_form', function(e) {
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
                $('.loading_button').hide();
                $('#editAssetModal').modal('hide');
                $('.error').html('');
                asset_table.ajax.reload();
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

    $(document).on('click', '#delete_asset',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_asset_form').attr('action', url);
        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_asset_form').submit();}},
                'No': {'class': 'no btn-modal-primary','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_asset_form', function(e) {
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
                asset_table.ajax.reload();
                $('#deleted_asset_form')[0].reset();
            }
        });
    });

    function calculateAddAssetValue() {
        var asset_qty = $('#quantity').val() ? $('#quantity').val() : 0;
        var per_unit_value = $('#per_unit_value').val() ? $('#per_unit_value').val() : 0;
        var total_value = parseFloat(asset_qty) * parseFloat(per_unit_value);
        $('#total_value').val(parseFloat(total_value).toFixed(2))
    }

    $('#quantity').on('input', function () {
        calculateAddAssetValue();
    });

    $('#per_unit_value').on('input', function () {
        calculateAddAssetValue();
    });

    function calculateEditAssetValue() {
        var asset_qty = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var per_unit_value = $('#e_per_unit_value').val() ? $('#e_per_unit_value').val() : 0;
        var total_value = parseFloat(asset_qty) * parseFloat(per_unit_value);
        $('#e_total_value').val(parseFloat(total_value).toFixed(2))
    }

    $(document).on('input', '#e_quantity', function () {
        calculateEditAssetValue();
    });

    $(document).on('input', '#e_per_unit_value', function () {
        calculateEditAssetValue();
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
@endpush
