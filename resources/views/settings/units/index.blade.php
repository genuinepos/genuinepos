@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-sort-amount-up"></span>
                                <h5>Units</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card" id="add_form">
                                    <div class="section-header">
                                        <div class="col-md-6">
                                            <h6>Add Unit</h6>
                                        </div>
                                    </div>

                                    <form id="add_unit_form" class="p-2" action="{{ route('settings.units.store') }}">
                                        <div class="form-group">
                                            <label><b>Unit Name :</b> <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" data-name="Name" id="name" placeholder="Unit Name"/>
                                            <span class="error error_name"></span>
                                        </div>

                                        <div class="form-group mt-1">
                                            <label><b>Short Name :</b> <span class="text-danger">*</span></label>
                                            <input type="text" name="code" class="form-control" data-name="Code name" id="code" placeholder="Short name"/>
                                            <span class="error error_code"></span>
                                        </div>

                                        <div class="form-group d-flex justify-content-end mt-3">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner"></i><span> Loading...</span></button>
                                                <button type="reset" class="btn btn-sm btn-danger">Reset</button>
                                                <button type="submit" class="btn btn-sm btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="card d-none" id="edit_form">
                                    <div class="section-header">
                                        <div class="col-md-6">
                                            <h6>Edit Unit</h6>
                                        </div>
                                    </div>

                                    <form id="edit_unit_form" class="p-2" action="{{ route('settings.units.update') }}">
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group">
                                            <label><b>Unit Name :</b> <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" data-name="Name" id="e_name" placeholder="Unit Name"/>
                                            <span class="error error_e_name"></span>
                                        </div>

                                        <div class="form-group mt-1">
                                            <label><b>Short Name :</b> <span class="text-danger">*</span></label>
                                            <input type="text" name="code" class="form-control" data-name="Code name" id="e_code" placeholder="Short Name"/>
                                            <span class="error error_e_code"></span>
                                        </div>

                                        <div class="form-group d-flex justify-content-end mt-3">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner"></i><span> Loading...</span></button>
                                                <button type="button" id="close_form" class="btn btn-sm btn-danger">Close</button>
                                                <button type="submit" class="btn btn-sm btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card">
                                    <div class="section-header">
                                        <div class="col-md-6">
                                            <h6>All Units</h6>
                                        </div>
                                    </div>

                                    <div class="widget_content">
                                        <div class="data_preloader">
                                            <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                        </div>
                                        <div class="table-responsive" id="data-list">
                                            <table class="display data_tbl data__table">
                                                <thead>
                                                    <tr>
                                                        <th>Serial</th>
                                                        <th>Short Name</th>
                                                        <th>Code Name</th>
                                                        <th>Actions</th>
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
        </div>
    </div>
@endsection
@push('scripts')
<script>
    // Get all units by ajax
    function getAllUnit(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('settings.units.get.all.unit') }}",
            type:'get',
            success:function(data){
                $('#data-list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllUnit();

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
                    getAllUnit();
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
            $('#edit_unit_form')[0].reset();
            $('.form-control').removeClass('is-invalid');
            $('.error').html('');
            var unitInfo = $(this).closest('tr').data('info');
            $('#id').val(unitInfo.id);
            $('#e_name').val(unitInfo.name);
            $('#e_code').val(unitInfo.code_name);
            $('#add_form').hide();
            $('#edit_form').show();
            document.getElementById('e_name').focus();
        });

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
                    $('.loading_button').hide();
                    getAllUnit();
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
                    if($.isEmptyObject(data.errorMsg)){
                        getAllUnit();
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
