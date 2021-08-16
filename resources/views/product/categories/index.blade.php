@extends('layout.master')
@push('stylesheets')@endpush
@section('title', 'All Category - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-cubes"></span>
                                <h5>Categories</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->
                    <div class="row mt-1">
                        @if (auth()->user()->permission->category['category_add'] == '1')
                            <div class="col-md-4">
                                <div class="card" id="add_form">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <h6>Add Category </h6>
                                        </div>
                                    </div>

                                    <div class="form-area px-3 pb-2">
                                        <form id="add_category_form" action="{{ route('product.categories.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label><b>Name :</b> <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" id="name"
                                                        placeholder="Category name" />
                                                <span class="error error_name"></span>
                                            </div>
                    
                                            <div class="form-group mt-1">
                                                <label><b>Photo :</b> <small class="text-danger"><b>Photo size 400px * 400px.</b></small></label>
                                                <input type="file" name="photo" class="form-control" id="photo">
                                                <span class="error error_photo"></span>
                                            </div>
                    
                                            <div class="form-group row mt-2">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                                    <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="card d-none" id="edit_form">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <h6>Edit Category </h6>
                                        </div>
                                    </div>

                                    <div class="form-area px-3 pb-2" id="edit_form_body">

                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-8">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>All Category</h6>
                                    </div>
                                </div>
    
                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                    </div>
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr class="bg-navey-blue">
                                                    <th class="text-black">Serial</th>
                                                    <th class="text-black">Photo</th>
                                                    <th class="text-black">Name</th>
                                                    <th class="text-black">Actions</th>
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
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [ 
                //{extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                // {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {
                    extend: 'print',
                    autoPrint: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: "{{ route('product.categories.index') }}",
            columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
                {data: 'photo',name: 'photo'},
                {data: 'name',name: 'name'},
                {data: 'action',name: 'action'},
            ],
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        // call jquery method 
        $(document).ready(function() {
            // Add category by ajax
            $(document).on('submit', '#add_category_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                $('.submit_button').prop('type', 'button');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_category_form')[0].reset();
                        $('.loading_button').hide();
                        $('.submit_button').prop('type', 'submit');
                        table.ajax.reload();
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
                $('.data_preloader').show();
                var url = $(this).closest('tr').data('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#edit_form_body').html(data);
                        $('#add_form').hide();
                        $('#edit_form').show();
                        $('.data_preloader').hide();
                    }
                });
            });

            $(document).on('click', '#update_btn',function(e){
                e.preventDefault(); 
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);       
                $.confirm({
                    'title': 'Edit Confirmation',
                    'content': 'Are you sure to edit?',
                    'buttons': {
                        'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#edit_category_form').submit();}},
                        'No': {'class': 'no btn-danger','action': function() {console.log('Edit canceled.');}}
                    }
                });
            });

            // edit category by ajax
            $(document).on('submit', '#edit_category_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('.loading_button').hide();
                        table.ajax.reload();
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
                    'title': 'Delete Confirmation',
                    'content': 'Are you sure?',
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
                    data: request,
                    success: function(data) {
                        toastr.error(data);
                        table.ajax.reload();
                        $('#deleted_form')[0].reset();
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