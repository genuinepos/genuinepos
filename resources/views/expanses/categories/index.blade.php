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
                        <h5>@lang('menu.expense_category')</h5>
                    </div>
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                </div>
            </div>

            <div class="p-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card" id="add_form">
                            <div class="section-header">
                                <h6>{{ __('Add Expanse Category') }}</h6>
                            </div>

                            <div class="form-area px-3 pb-2">
                                <form id="add_category_form" action="{{ route('expenses.categories.store') }}">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label><b>@lang('menu.name') :</b> <span class="text-danger">*</span></label>
                                            <input required type="text" name="name" class="form-control add_input" data-name="Category name" id="name" placeholder="@lang('menu.expense_category')"/>
                                            <span class="error error_name"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-12">
                                            <label><b>@lang('menu.code') :</b></label>
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
                            
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>{{ __('All Expense Categories') }}</h6>
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
     var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',className: 'btn btn-primary',autoPrint: true,exportOptions: {columns: ':visible'}}
        ],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('expenses.categories.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name',name: 'name'},
            {data: 'code',name: 'code'},
            {data: 'action'},
        ],
    });

    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).ready(function(){

        $('#add_category_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            $('.submit_button').prop('type', 'button');
            var request = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    
                    table.ajax.reload();
                    toastr.success('Expense category added successfully');
                    $('#add_category_form')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    $('.error').html('');
                },error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');
                    $('.submit_button').prop('type', 'submit');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $(document).on('click', '#edit', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#edit_form').html(data);
                    $('.data_preloader').hide();
                    $('#add_form').hide();
                    $('#edit_form').show();
                    document.getElementById('name').focus();
                },error:function(err){

                    $('.data_preloader').hide();

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    }else{

                        toastr.error('Server Error, Please contact to the support team.');
                    }
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

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    table.ajax.reload();
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
