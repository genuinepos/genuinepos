@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Warrantites - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-desktop"></span> <h5> @lang('menu.warranties')/@lang('menu.guaranties')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i>@lang('menu.back')
                </a>
            </div>
        </div>

        <div class="p-lg-3 p-1">
            <div class="row g-lg-3 g-1">
                <div class="col-lg-4">
                    <div class="card" id="add_form">
                        <div class="section-header">
                            <h6>@lang('menu.add_warranty')/@lang('menu.guaranty')</h6>
                        </div>

                        <div class="form-area px-3 pb-2">
                            <form id="add_warranty_form" action="{{ route('product.warranties.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <strong>@lang('menu.name') :</strong> <span class="text-danger">*</span>
                                    <input type="text" name="name" class="form-control add_input" data-name="Warranty name"
                                        id="name" placeholder="Warranty name" />
                                    <span class="error error_name"></span>
                                </div>

                                <div class="form-group row mt-1">
                                    <div class="col-lg-4">
                                        <strong>Type :</strong> <span class="text-danger">*</span>
                                        <select name="type" class="form-control" id="type">
                                            <option value="1">@lang('menu.warranty')</option>
                                            <option value="2">@lang('menu.guaranty')</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-8">
                                        <strong>@lang('menu.duration') :</strong> <span class="text-danger">*</span>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="number" name="duration" class="form-control add_input"
                                                    data-name="Warranty duration" id="duration" placeholder="Warranty duration">
                                                <span class="error error_duration"></span>
                                            </div>

                                            <div class="col-md-6">
                                                <select name="duration_type" class="form-control" id="duration_type">
                                                    <option value="Months">@lang('menu.months')</option>
                                                    <option value="Days">@lang('menu.days')</option>
                                                    <option value="Years">@lang('menu.years')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-2">
                                    <strong>@lang('menu.description') :</strong>
                                    <textarea name="description" id="description" class="form-control" cols="10" rows="3"
                                        placeholder="Warranty description"></textarea>
                                </div>

                                <div class="form-group row mt-3">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="btn-loading">
                                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                            <button type="reset" class="btn btn-sm btn-danger">@lang('menu.reset')</button>
                                            <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card d-hide" id="edit_form">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>@lang('menu.edit_warranty')/@lang('menu.guaranty')</h6>
                            </div>
                        </div>

                        <div class="form-area px-3 pb-2">
                            <form id="edit_warranty_form" action="{{ route('product.warranties.update') }}">
                                <input type="hidden" name="id" id="id">
                                <div class="form-group">
                                    <strong>@lang('menu.name') :</strong> <span class="text-danger">*</span>
                                    <input type="text" name="name" class="form-control edit_input" data-name="Bank name" id="e_name"
                                        placeholder="@lang('menu.bank_name')" />
                                    <span class="error error_e_name"></span>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-4">
                                        <strong>Type :</strong> <span class="text-danger">*</span>
                                        <select name="type" class="form-control" id="e_type">
                                            <option value="1">@lang('menu.warranty')</option>
                                            <option value="2">@lang('menu.guaranty')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-8">
                                        <strong>@lang('menu.duration') :</strong> <span class="text-danger">*</span>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="number" name="duration" class="form-control edit_input"
                                                data-name="Warranty duration" id="e_duration">
                                                <span class="error error_e_duration"></span>
                                            </div>

                                            <div class="col-md-6">
                                                <select name="duration_type" class="form-control" id="e_duration_type">
                                                    <option value="Months">@lang('menu.months')</option>
                                                    <option value="Days">@lang('menu.days')</option>
                                                    <option value="Years">@lang('menu.years')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-2">
                                    <strong>@lang('menu.description') :</strong>
                                    <textarea name="description" id="e_description" class="form-control form-control-sm" cols="10"
                                        rows="3" placeholder="Warranty description"></textarea>
                                </div>

                                <div class="form-group row mt-3">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="btn-loading">
                                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                            <button type="button" class="btn btn-sm btn-danger" id="close_form">@lang('menu.close')</button>
                                            <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save_changes')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>@lang('menu.warranty')/@lang('menu.guaranty_list')</h6>
                            </div>
                        </div>
                        <!--begin: Datatable-->
                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                            </div>
                            <div class="table-responsive" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr class="text-center">
                                            <th>@lang('menu.sl')</th>
                                            <th>@lang('menu.name')</th>
                                            <th>@lang('menu.duration')</th>
                                            <th>@lang('menu.description')</th>
                                            <th>@lang('menu.action')</th>
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
@endsection
@push('scripts')
    <script>
        //Get all category by ajax
        function getAllWarranty() {
            $('.data_preloader').show();
            $.ajax({
                url: "{{ route('product.warranties.all.warranty') }}",
                type: 'get',
                success: function(data) {
                    $('.table-responsive').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getAllWarranty();

        // Setup ajax for csrf token.
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        // call jquery method
        $(document).ready(function() {
            // Add Customar group by ajax
            $('#add_warranty_form').on('submit', function(e) {
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
                        $('#add_warranty_form')[0].reset();
                        $('.loading_button').hide();
                        getAllWarranty();
                        $('.submit_button').prop('type', 'submit');
                    },error:function(err){
                        $('.loading_button').hide();
                        $('.submit_button').prop('type', 'submit');
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                        }else{
                            toastr.error('Server Error, Please contact to the support team.');
                        }
                    }
                });
            });

            // Pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.error').html('');
                var warranty = $(this).closest('tr').data('info');
                $('#id').val(warranty.id);
                $('#e_name').val(warranty.name);
                $('#e_type').val(warranty.type);
                $('#e_duration').val(warranty.duration);
                $('#e_duration_type').val(warranty.duration_type);
                $('#e_description').val(warranty.description);
                $('#add_form').hide();
                $('#edit_form').show();
                $('#edit_form').removeClass('d-hide');
                document.getElementById('e_name').focus();
            });

            // edit bank by ajax
            $('#edit_warranty_form').on('submit', function(e) {
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
                        toastr.success(data);
                        $('.loading_button').hide();
                        getAllWarranty();
                        $('#add_form').show();
                        $('#edit_form').hide();
                    },error:function(err){
                        $('.loading_button').hide();
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                        }else{
                            toastr.error('Server Error, Please contact to the support team.');
                        }
                    }
                });
            });

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': 'Confirmation',
                    'content': 'Are you sure, you want to delete?',
                    'buttons': {
                        'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                        'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
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
                        getAllWarranty();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    },error: function(err) {
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                        }else{
                            toastr.error('Server Error. Please contact to the support team.');
                        }
                    }
                });
            });

            $(document).on('click', '#close_form', function() {
                $('#add_form').show();
                $('#edit_form').hide();
                $('.error').html('');
            });
        });
    </script>
@endpush
