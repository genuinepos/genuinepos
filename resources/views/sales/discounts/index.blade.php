@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}"/>
@endpush
@section('title', 'Discount - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-people-arrows"></span>
                                <h5>@lang('menu.manage_offers')</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-6">
                                    <h6>{{ __('Offer List') }}</h6>
                                </div>

                                <div class="col-6 d-flex justify-content-end">
                                    <a href="#" data-bs-toggle="modal" class="btn btn-sm btn-primary" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> @lang('menu.add')</a>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr class="text-start">
                                                <th>@lang('menu.offer_name')</th>
                                                <th>@lang('menu.business_location')</th>
                                                <th>@lang('menu.status')</th>
                                                <th>@lang('menu.start_at')</th>
                                                <th>@lang('menu.end_at')</th>
                                                <th>@lang('menu.discount_type')</th>
                                                <th>@lang('menu.discount_amount')</th>
                                                <th>@lang('menu.priority')</th>
                                                <th>@lang('menu.brand')</th>
                                                <th>@lang('menu.category')</th>
                                                <th>@lang('menu.applicable_products')</th>
                                                <th>@lang('menu.action')</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" role="dialog" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_offer')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_discount_form" action="{{ route('sales.discounts.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><strong>@lang('menu.name') :</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control add_input"
                                    data-name="Discount name" id="name" placeholder="@lang('menu.offer_name')" />
                                <span class="error error_name"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><strong>@lang('menu.priority') <i data-bs-toggle="tooltip" data-bs-placement="right" title="Leave empty to auto generate." class="fas fa-info-circle tp"></i> :</strong> <span class="text-danger">*</span> </label>
                                <input type="number" name="priority" class="form-control add_input"
                                    data-name="Priority" id="priority" placeholder="Priority" />
                                <span class="error error_priority"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.start_at') :</strong><span class="text-danger">*</span></label>
                                <input type="text" name="start_at" id="start_at" class="form-control add_input" autocomplete="off">
                                <span class="error error_start_at"></span>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.end_at') :</strong><span class="text-danger">*</span></label>
                                <input type="text" name="end_at" id="end_at" class="form-control add_input" autocomplete="off">
                                <span class="error error_end_at"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><strong>@lang('menu.products') :</strong> </label>
                                <select name="product_ids[]" class="form-control select2" multiple="multiple" id="product_ids">

                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name.' ('.$product->product_code.')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1 brand_category_area">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.brand'):</strong><span class="text-danger">*</span></label>
                                <select name="brand_id" id="brand_id" class="form-control add_input">
                                    <option value="">@lang('menu.please_select') </option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.category') :</strong><span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control add_input">
                                    <option value="">@lang('menu.please_select') </option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.discount_type') :</strong> </label>
                                <select name="discount_type" id="discount_type" class="form-control add_input">
                                    <option value="1">@lang('menu.fixed')(0.00)</option>
                                    <option value="1">@lang('menu.percentage')(%)</option>
                                </select>
                                <span class="error error_discount_type"></span>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.discount_amount') :</strong><span class="text-danger">*</span></label>
                                <input type="number" name="discount_amount" id="discount_amount" class="form-control add_input">
                                <span class="error error_discount_amount"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.selling_price_group') :</strong> </label>
                                <select name="price_group_id" id="price_group_id" class="form-control">
                                    <option value="">@lang('menu.default_price')</option>
                                    @foreach ($price_groups as $price_group)
                                        <option value="{{ $price_group->id }}">{{ $price_group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-6">
                                <div class="input-group mt-1">
                                    <div class="col-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="apply_in_customer_group" id="apply_in_customer_group"> &nbsp; @lang('menu.apply_customer_group')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group mt-1">
                                    <div class="col-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input CHECKED type="checkbox" name="is_active" id="is_active"> &nbsp; @lang('menu.is_active') </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_offer')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit-modal-form-body"></div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
    <script>
        $('.select2').select2({
            placeholder: "Select a products",
            allowClear: true
        });

        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
            ],
            "processing": true,
            "serverSide": true,
            aaSorting: [[0, 'asc']],
            ajax: "{{ route('sales.discounts.index') }}",
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            columnDefs: [{"targets": [2, 10, 11],"orderable": false,"searchable": false}],
            columns: [

                {data: 'name',name: 'discounts.name'},
                {data: 'branch',name: 'branches.name'},
                {data: 'status',name: 'status'},
                {data: 'start_at',name: 'discounts.start_at'},
                {data: 'end_at',name: 'discounts.end_at'},
                {data: 'discount_type', name: 'discount_type'},
                {data: 'discount_amount', name: 'discounts.discount_amount'},
                {data: 'priority',name: 'priority'},
                {data: 'b_name',name: 'brands.name'},
                {data: 'cate_name',name: 'categories.name'},
                {data: 'products',name: 'products'},
                {data: 'action',name: 'action'},
            ]
        });

        $('#product_ids').on('change', function () {

            if ($(this).val().length > 0) {

                $('.brand_category_area').hide();
            }else{

                $('.brand_category_area').show();
            }
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        // call jquery method
        $(document).ready(function() {
            // Add discount by ajax
            $('#add_discount_form').on('submit', function(e) {
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
                        $('#add_discount_form')[0].reset();
                        table.ajax.reload();
                        $('.loading_button').hide();
                        $('#addModal').modal('hide');
                        $('.submit_button').prop('type', 'submit');
                        $('.brand_category_area').show();
                    },error: function(err) {

                        $('.submit_button').prop('type', 'sumbit');
                        $('.loading_button').hide();
                        $('.error').html('');

                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        }else if (err.status == 500){

                            toastr.error('Server error please contact to the support.');
                            return;
                        }

                        $.each(err.responseJSON.errors, function(key, error) {

                            $('.error_' + key + '').html(error[0]);
                        });
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
                                    url: url,
                                    type: 'get',
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
        });

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '' ;
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

        new Litepicker({
            singleMode: true,
            element: document.getElementById('start_at'),
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
            format: _expectedDateFormat,
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('end_at'),
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
            format: _expectedDateFormat,
        });

        document.onkeyup = function () {

            var e = e || window.event; // for IE to cover IEs window event-object

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
