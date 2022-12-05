@extends('layout.master')
@push('stylesheets')

@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <!-- =====================================================================BODY CONTENT================== -->
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-receipt"></span>
                    <h5>Invoice Schemas</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <!-- =========================================top section button=================== -->

        <div class="p-3">
            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-7">
                        <h6>All Invoice Schemas</h6>
                    </div>

                    <div class="col-5 d-flex justify-content-end">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addModal" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i>@lang('menu.add')</a>
                    </div>
                </div>

                    <div class="widget_content">
                        <div class="table-responsive" id="data-list">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th class="text-start">Name</th>
                                        <th class="text-start">Prefix</th>
                                        <th class="text-start">Start From</th>
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

     <!-- Add Modal -->
     <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Invoice Schema</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_schema_form" action="{{ route('invoices.schemas.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label><b>Preview :</b> <span id="schema_preview"></span></label>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label>@lang('menu.name') :<span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-sm" id="name" placeholder="Schema name"/>
                                <span class="error error_name"></span>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <p class="checkbox_input_wrap mt-4"> <input type="checkbox" name="set_as_default" autocomplete="off" id="set_as_default">&nbsp;&nbsp;Set as default.</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label>Format : <span class="text-danger">*</span></label>
                                <select name="format" class="form-control form-control-sm" id="format">
                                    <option value="1">FORMAT-XXXX</option>
                                    <option value="2">FORMAT-{{ date('Y') }}/XXXX</option>
                                </select>
                                <span class="error error_format"></span>
                            </div>

                            <div class="col-md-6">
                                <label>Prefix : <span class="text-danger">*</span></label>
                                <input type="text" name="prefix" class="form-control form-control-sm" id="prefix" placeholder="Prefix"/>
                                <span class="error error_prefix"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label>Start From :</label>
                                <input type="number" name="start_from" class="form-control form-control-sm" id="start_from" placeholder="Start From" value="0"/>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-end mt-3">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
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
                    <h6 class="modal-title" id="exampleModalLabel">Edit Invoice Schema</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body">
                    <!--begin::Form-->
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End-->

@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [[3, 'asc']],
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            ajax: "{{ route('invoices.schemas.index') }}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'prefix', name: 'prefix'},
                {data: 'start_from', name: 'start_from'},
                {data: 'action', name: 'action'},
            ]
        });

        $(document).on('change', '#format', function () {
            var val = $(this).val();
            if (val == 2) {
                $('#prefix').val("{{ date('Y') }}"+'/');
                $('#prefix').prop('readonly', true);
            }else{
                $('#prefix').val("");
                $('#prefix').prop('readonly', false);
            }
            previewInvoieId();
        });

        $(document).on('change', '#e_format', function () {
            var val = $(this).val();
            if (val == 2) {
                $('#e_prefix').val("{{ date('Y') }}"+'/');
                $('#e_prefix').prop('readonly', true);
            }else{
                $('#e_prefix').val("");
                $('#e_prefix').prop('readonly', false);
            }
            previewInvoieId();
        });

        $(document).on('input', '#prefix', function () {
            previewInvoieId();
        });

        $(document).on('input', '#e_prefix', function () {
            previewInvoieId();
        });

        $(document).on('input', '#start_from', function () {
            previewInvoieId();
        });

        $(document).on('input', '#e_start_from', function () {
            previewInvoieId();
        });

        function previewInvoieId() {
            var prefix = $('#prefix').val();
            var start_from = $('#start_from').val();
            $('#schema_preview').html('#'+prefix+start_from);

            var prefix = $('#e_prefix').val();
            var start_from = $('#e_start_from').val();
            $('#e_schema_preview').html('#'+prefix+start_from);
        }

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function(){
            // Add category by ajax
            $(document).on('submit', '#add_schema_form',function(e){
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url:url,
                    type:'post',
                    data: request,
                    success:function(data){
                        toastr.success(data);
                        $('#add_schema_form')[0].reset();
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#addModal').modal('hide');
                        $('#schema_preview').html('');
                        $('#prefix').prop('readonly', false);
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
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url:url,
                    type:'get',
                    success:function(data){
                        $('.data_preloader').hide();
                        $('#edit_modal_body').html(data);
                        $('#editModal').modal('show');
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#set_default_btn', function(e){
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url:url,
                    type:'get',
                    success:function(data){
                        table.ajax.reload();
                        toastr.success(data);
                        $('.data_preloader').hide();
                    }
                });
            });

            // edit category by ajax
            $(document).on('submit', '#edit_schema_form',function(e){
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url:url,
                    type:'post',
                    data: request,
                    success:function(data){
                        toastr.success(data);
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#editModal').modal('hide');
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
                    type:'post',
                    async:false,
                    data:request,
                    success:function(data){
                        toastr.error(data);
                        table.ajax.reload();
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });
    </script>
@endpush
