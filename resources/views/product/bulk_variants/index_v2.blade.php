@extends('layout.master')
@push('stylesheets')@endpush
@section('title', 'All Variant - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <!-- =====================================================================BODY CONTENT================== -->
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-cubes"></span>
                    <h5>@lang('menu.variants')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                        class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-lg-3 p-1">
            <div class="row g-lg-3 g-1">
                <div class="col-lg-4">
                    <div class="card" id="add_form">
                        <div class="section-header">
                            <div class="col-md-12">
                                <h6>{{ __('Add Variant') }} </h6>
                            </div>
                        </div>

                        <div class="form-area px-3 pb-2">
                            <form id="add_variant_form" action="{{ route('product.variants.store') }}">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label><b>@lang('menu.name') :</b> <span class="text-danger">*</span></label>
                                        <input type="text" name="variant_name" class="form-control add_input"
                                            data-name="Variant name" id="variant_name" placeholder="Variant Name" />
                                        <span class="error error_variant_name"></span>
                                    </div>

                                </div>

                                <div class="form-group row mt-1">
                                    <label><b>@lang('menu.variant_child') </b>(Values) : <span class="text-danger">*</span></label>
                                    <div class="col-10">
                                        <input required type="text" name="variant_child[]" class="form-control"
                                            placeholder="@lang('menu.variant_child')" />
                                    </div>

                                    <div class="col-2 text-end">
                                        <a class="btn btn-sm btn-primary add_more_for_add" href="#">+</a>
                                    </div>
                                </div>

                                <div class="form-group more_variant_child_area">

                                </div>

                                <div class="form-group row mt-3">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="btn-loading">
                                            <button type="button" class="btn loading_button d-hide"><i
                                                    class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                            <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card d-hide" id="edit_form">
                        <div class="section-header">
                            <div class="col-md-12">
                                <h6>Edit Variant </h6>
                            </div>
                        </div>

                        <div class="form-area px-3 pb-2">
                            <form id="edit_variant_form" action="{{ route('product.variants.update') }}">
                                <input type="hidden" name="id" id="id">
                                <div class="form-group">
                                    <b>@lang('menu.name') :</b> <span class="text-danger">*</span>
                                    <input type="text" name="variant_name" class="form-control edit_input"
                                        data-name="Brand name" id="e_variant_name" placeholder="Brand Name" />
                                    <span class="error error_e_variant_name"></span>
                                </div>

                                <div class="form-group row mt-2">
                                    <div class="col-md-12"><b>@lang('menu.variant_child') (Values) :</b> <span class="text-danger">*</span></div>
                                    <div class="col-md-10">
                                        <input type="hidden" name="variant_child_ids[]" id="e_variant_child_id" value="">
                                        <input required type="text" name="variant_child[]" class="form-control"
                                            id="e_variant_child" placeholder="@lang('menu.variant_child')" />
                                    </div>

                                    <div class="col-md-2 text-end">
                                        <a class="btn btn-sm btn-primary add_more_for_edit" href="#">+</a>
                                    </div>
                                </div>

                                <div class="form-group more_variant_child_area_edit">

                                </div>

                                <div class="form-group row mt-2">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="btn-loading">
                                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                            <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-danger" id="close_form">@lang('menu.close')</button>
                                            <button type="submit" class="btn btn-sm btn-success">@lang('menu.save_changes')</button>
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
                                <h6>All Variant</h6>
                            </div>
                        </div>

                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                            </div>
                            <div class="table-responsive" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">@lang('menu.name')</th>
                                            <th class="text-start">@lang('menu.child')</th>
                                            <th class="text-start">@lang('menu.action')</th>
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
    // Get all category by ajax
    function getAllVariant() {
        $('.data_preloader').show();
        $.ajax({
            url: "{{ route('product.variants.all.variant') }}",
            type: 'get',
            success: function(data) {
                $('.table-responsive').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllVariant();

    // Setup ajax for csrf token.
    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    var add_more_index = 0;
    $(document).ready(function() {
        // add more variant child field
        $('.add_more_for_add').on('click', function(e) {
            e.preventDefault();
            var index = add_more_index++;
            var html = '<div class="more_variant_child mt-2 more' + index + '">';
            html += '<div class="row">';
            html += '<div class="col-10"> ';
            html += '<input required type="text" name="variant_child[]" class="form-control " placeholder="Variant child"/>';
            html += '</div>';

            html += '<div class="col-2 text-end">';
            html += '<a class="btn btn-sm btn-danger delete_more_for_add" data-index="' + index +
                '" href="#">×</a>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            $('.more_variant_child_area').append(html);
        });

        // delete add more field for adding
        $(document).on('click', '.delete_more_for_add', function(e) {
            var index = $(this).data('index');
            $('.more' + index).remove();
        })

        // Add variant by ajax
        $('#add_variant_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');

                var idValue = $('#' + inputId).val()

                if (inputId !== 'parent_category' && inputId !== 'photo') {

                    if (idValue == '') {

                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_' + inputId).html(fieldName + ' is required.');
                    }
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
                    $('#add_variant_form')[0].reset();
                    $('.loading_button').hide();
                    getAllVariant();
                    $('.more_variant_child_area').empty();
                    $('.submit_button').prop('type', 'submit');
                },error: function(err) {
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    $('.error').html('');
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }
                }
            });
        });

        // pass editable data to edit modal fields
        var add_more_index_for_edit = 0;
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            add_more_index_for_edit = 0
            $('.form-control').removeClass('is-invalid');
            $('.error').html('');
            var variantInfo = $(this).closest('tr').data('info');
            $('#id').val(variantInfo.id);
            $('#e_variant_name').val(variantInfo.bulk_variant_name);
            $('#e_variant_child_id').val(variantInfo.bulk_variant_child[0].id);
            $('#e_variant_child').val(variantInfo.bulk_variant_child[0].child_name);
            $('.more_variant_child_area_edit').empty();
            $.each(variantInfo.bulk_variant_child, function(key, bulk_variant_child) {
                if (add_more_index_for_edit != 0) {
                    var html = '<div class="more_variant_child mt-2 e_more' +
                        add_more_index_for_edit + '">';
                    html += '<div class="row">';
                    html += '<div class="col-md-10"> ';
                    html += '<input type="hidden" name="variant_child_ids[]" value="' +
                        bulk_variant_child.id + '"/>';
                    html += '<input required type="text" name="variant_child[]" class="form-control " placeholder="Variant child" value="' + bulk_variant_child.child_name + '"/>';
                    html += '</div>';
                    html += '<div class="col-md-2 text-end">';
                    html +='<a class="btn btn-sm btn-danger delete_more_for_edit" data-index="' + add_more_index_for_edit + '" href="#">×</a>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    $('.more_variant_child_area_edit').append(html);
                }
                add_more_index_for_edit++;
            });
            $('#add_form').hide();
            $('#edit_form').show();
            $('#edit_form').removeClass('d-hide');
            document.getElementById('e_variant_name').focus();
        });

        $('.add_more_for_edit').on('click', function(e) {
            e.preventDefault();
            var index = add_more_index_for_edit++;
            var html = '<div class="more_variant_child mt-2 e_more' + index + '">';
            html += '<div class="row">';
            html += '<div class="col-md-10"> ';
            html += '<input type="hidden" name="variant_child_ids[]" value="noid"/>';
            html += '<input required type="text" name="variant_child[]" class="form-control " placeholder="Variant child"/>';
            html += ' </div>';

            html += '<div class="col-md-2 text-end">';
            html += '<a class="btn btn-sm btn-danger delete_more_for_edit" data-index="' + index +
                '" href="#">×</a>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            $('.more_variant_child_area_edit').append(html);
        });


        // delete add more field for adding
        $(document).on('click', '.delete_more_for_edit', function(e) {
            var index = $(this).data('index');
            $('.e_more' + index).remove();
        })

        // edit brand by ajax
        $('#edit_variant_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.edit_input');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val()
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
                    $('#add_form').show();
                    $('#edit_form').hide();
                    getAllVariant();
                }
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.')}
                    }
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
                data: request,
                success: function(data) {
                    getAllVariant();
                    toastr.error(data);
                }
            });
        });

        $(document).on('click', '#close_form', function() {
            $('#add_form').show();
            $('#edit_form').hide();
        });
    });
</script>
@endpush
