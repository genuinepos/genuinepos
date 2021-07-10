@extends('layout.master')
@push('stylesheets')@endpush
@section('title', 'SubCategories - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class=" border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-cubes"></span>
                                <h5>SubCategories</h5>
                            </div>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->
                  
                    <div class="row mt-1">
                        @if (auth()->user()->permission->category['category_add'] == '1')
                            <div class="col-md-4">
                                <div class="card" id="add_form">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <h6>Add SubCategory </h6>
                                        </div>
                                    </div>
                                    <div class="form-area px-3 pb-2">
                                        <form id="add_sub_category_form" action="{{ route('product.subcategories.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label><b>Parent category : <span class="text-danger">*</span></b></label> 
                                                <select name="parent_category_id" class="form-control " id="parent_category"
                                                    required>
                                                    <option selected="" disabled="">Select Parent Category</option>
                                                    @foreach ($category as $row)
                                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_parent_category_id"></span>
                                            </div>

                                            <div class="form-group mt-1">
                                                <label><b>Name :</b> <span class="text-danger">*</span></label> 
                                                <input type="text" name="name" class="form-control " id="name"
                                                    placeholder="Sub category name" />
                                                <span class="error error_name"></span>
                                            </div>

                                            <div class="form-group mt-2">
                                                <label><b>Sub-Category photo :</b></label> 
                                                <input type="file" name="photo" class="form-control " id="photo"
                                                    accept=".jpg, .jpeg, .png, .gif">
                                                <span class="error error_photo"></span>
                                            </div>

                                            <div class="form-group mt-2">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                                    <button type="submit" class="c-btn btn_blue float-end me-0 submit_button">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="card d-none" id="edit_form">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <h6>Edit SubCategory </h6>
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
                                        <h6>All SubCategory</h6>
                                    </div>
                                </div>
    
                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>Serial</th>
                                                    <th>Photo</th>
                                                    <th>SubCategory</th>
                                                    <th>Parent Category</th>
                                                    <th>Actions</th>
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
                {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: "{{ route('product.subcategories.index') }}",
            columns: [{data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'photo',name: 'category.photo'},
                {data: 'name',name: 'category.name'},
                {data: 'parentname',name: 'category.parentname'},
                {data: 'action',name: 'action'},
            ]
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Add category by ajax
            $('#add_sub_category_form').on('submit', function(e) {
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
                        $('#add_sub_category_form')[0].reset();
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('.submit_button').prop('type', 'submit');
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
            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $('.data_preloader').show();
                $.get("sub-categories/edit/" + id, function(data) {
                    $("#edit_form_body").html(data);
                    $('#add_form').hide();
                    $('#edit_form').show();
                    $('.data_preloader').hide();
                })
            });

            // edit category by ajax
            $(document).on('submit', '#edit_sub_category_form', function(e) {
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
                        $('.error').html('');
                        toastr.success(data);
                        $('.loading_button').hide();
                        $('#edit_sub_category_form')[0].reset();
                        table.ajax.reload();
                        $('#add_form').show();
                        $('#edit_form').hide();
                    },
                    error: function(err) {
                        $('.loading_button').hide();
                        $('.error').html('');
                        $('.form-control').removeClass('is-invalid');
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
                    'content': 'Are you sure, you want to delete?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-modal-primary',
                            'action': function() {
                                $('#deleted_form').submit();
                            }
                        },
                        'No': {
                            'class': 'no btn-danger',
                            'action': function() {
                                // alert('Deleted canceled.')
                            } 
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
                    async: false,
                    data: request,
                    success: function(data) {
                        $('.data_tbl').DataTable().ajax.reload();
                        toastr.error(data);
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
