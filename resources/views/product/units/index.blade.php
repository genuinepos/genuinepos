@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-sort-amount-up"></span>
                    <h5>@lang('menu.units')</h5>
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
                            <div class="col-md-6">
                                <h6>@lang('menu.add_unit')</h6>
                            </div>
                        </div>

                        <form id="add_unit_form" class="p-2" action="{{ route('product.units.store') }}">
                            <div class="form-group">
                                <label><b>@lang('menu.unit_name') :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" data-name="Name" id="name" placeholder="@lang('menu.unit_name')"/>
                                <span class="error error_name"></span>
                            </div>

                            <div class="form-group mt-1">
                                <label><b>@lang('menu.short_name') :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control" data-name="Code name" id="code" placeholder="@lang('menu.short_name')"/>
                                <span class="error error_code"></span>
                            </div>

                            <div class="form-group d-flex justify-content-end mt-3">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                    <button type="reset" class="btn btn-sm btn-danger">@lang('menu.reset')</button>
                                    <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card d-hide" id="edit_form">

                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>@lang('menu.all_units')</h6>
                            </div>
                        </div>

                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                            </div>
                            <div class="table-responsive" id="data-list">
                                <table class="display data_tbl data__table unit_table">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.serial')</th>
                                            <th>@lang('menu.short_name')</th>
                                            <th>{{ __('Code Name') }}</th>
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
@endsection
@push('scripts')
<script>
    // Get all units by ajax

    var unit_table = $('.unit_table').DataTable({
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
        ajax: "{{ route('product.units.index') }}",
        columns: [
            {data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'name',name: 'name'},
            {data: 'code_name',name: 'code_name'},
            {data: 'action',name: 'action'},
        ],
    });

    // insert branch by ajax
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).ready(function(){
        // Add Unit by ajax
        $(document).on('submit', '#add_unit_form', function(e) {
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
                    $('#add_unit_form')[0].reset();
                    $('.loading_button').hide();
                    $('#addModal').modal('hide');
                    unit_table.ajax.reload();
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('.loading_button').hide();
                    $('#edit_form').html(data);
                    $('#add_form').hide();
                    $('#edit_form').show();
                    $('#edit_form').removeClass('d-hide');
                }
            });
        });
        // $(document).on('click', '#edit', function(e){
        //     e.preventDefault();
        //     $('#edit_unit_form')[0].reset();
        //     $('.form-control').removeClass('is-invalid');
        //     $('.error').html('');
        //     var unitInfo = $(this).closest('tr').data('info');
        //     $('#id').val(unitInfo.id);
        //     $('#e_name').val(unitInfo.name);
        //     $('#e_code').val(unitInfo.code_name);
        //     $('#add_form').hide();
        //     $('#edit_form').show();
        //     $('#edit_form').removeClass('d-hide');
        //     document.getElementById('e_name').focus();
        // });

        // edit Unit by ajax
        $(document).on('submit', '#edit_unit_form', function(e) {
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
                    unit_table.ajax.reload();
                    $('.loading_button').hide();
                    $('#add_form').show();
                    $('#edit_form').hide();
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

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure?',
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
                type:'delete',
                data:request,
                success:function(data){
                    unit_table.ajax.reload();
                    if($.isEmptyObject(data.errorMsg)){
                        toastr.error(data);
                    }else{
                        toastr.error(data.errorMsg, 'Error');
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
