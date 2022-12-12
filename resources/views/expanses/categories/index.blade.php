@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <div class="body-woaper">
        <div class="border-class">
            <div class="main__content">
                <div class="sec-name">
                    <div class="name-head">
                        <span class="fas fa-desktop"></span>
                        <h5>Expense Category</h5>
                    </div>
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                </div>
            </div>

            <div class="p-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card" id="add_form">
                            <div class="section-header">
                                <h6>Add Expanse Category</h6>
                            </div>

                            <div class="form-area px-3 pb-2">
                                <form id="add_category_form" action="{{ route('expanses.categories.store') }}">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label><b>@lang('menu.name') :</b> <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control add_input" data-name="Category name" id="name" placeholder="Expense Category Name"/>
                                            <span class="error error_name"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-12">
                                            <label><b>Code :</b></label>
                                            <input type="text" name="code" class="form-control" data-name="Expanse category Code" placeholder="Code"/>
                                            <span class="error error_code"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                                <button type="reset" class="btn btn-sm btn-danger">@lang('menu.reset')</button>
                                                <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card d-hide" id="edit_form">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>Edit Expanse Category</h6>
                                </div>
                            </div>

                            <div class="form-area px-3 pb-2">
                                <form id="edit_category_form" action="{{ route('expanses.categories.update') }}">
                                    <input type="hidden" name="id" id="id">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label><strong>@lang('menu.name') :</strong>  <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control edit_input" data-name="Category name" id="e_name" placeholder="Expense Category Name"/>
                                            <span class="error error_e_name"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row text-right mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</b></button>
                                                <button type="button" id="close_form" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                                <button type="submit" class="btn btn-sm btn-success">@lang('menu.save_changes')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>All Expense Categories</h6>
                                </div>
                            </div>
                            <!--begin: Datatable-->
                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div class="widget_content">
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">@lang('menu.serial')</th>
                                                    <th class="text-start">@lang('menu.name')</th>
                                                    <th class="text-start">Code</th>
                                                    <th class="text-start">@lang('menu.action')</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
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
@endsection
@push('scripts')
<script>
    // Get all category by ajax
    function getAllCateogry(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('expanses.categories.all.category') }}",
            type:'get',
            success:function(data){
                $('#data-list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllCateogry();

    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).ready(function(){
        // Add category by ajax
        $('#add_category_form').on('submit', function(e){
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

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('#add_category_form')[0].reset();
                    $('.loading_button').hide();
                    getAllCateogry();
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            $('.error').html('');
            var categoryInfo = $(this).closest('tr').data('info');
            $('#id').val(categoryInfo.id);
            $('#e_name').val(categoryInfo.name);
            $('#add_form').hide();
            $('#edit_form').show();
            document.getElementById('e_name').focus();
        });

        // edit category by ajax
        $('#edit_category_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.edit_input');
                inputs.removeClass('is-invalid');
                $('.error').html('');
                var countErrorField = 0;
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    $('#'+inputId).addClass('is-invalid');
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
                    getAllCateogry();
                    $('#add_form').show();
                    $('#edit_form').hide();
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
                    getAllCateogry();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
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
