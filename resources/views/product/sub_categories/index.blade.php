@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class=" border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-desktop"></span>
                                <h5>SubCategories</h5>
                            </div>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>All SubCategory</h6>
                                    </div>
                                    @if (auth()->user()->permission->category['category_add'] == '1')
                                        <div class="col-md-6">
                                            <div class="btn_30_blue float-end">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                        class="fas fa-plus-square"></i> Add</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="widget_content">
                                    <div class="table-responsive">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>Serial</th>
                                                    <th>Photo</th>
                                                    <th>Category</th>
                                                    <th>Subcategory</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Sub-Category</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_sub_category_form" action="{{ route('product.subcategories.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <b>Parent category :</b>
                            <select name="parent_category_id" class="form-control form-control-sm" id="parent_category"
                                required="">
                                <option selected="" disabled="">Select Parent Category</option>
                                @foreach ($category as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                            <span class="error error_parent_category_id"></span>
                        </div>

                        <div class="form-group mt-2">
                            <b>Name :</b> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control form-control-sm" id="name"
                                placeholder="Sub category name" />
                            <span class="error error_name"></span>
                        </div>

                        <div class="form-group mt-2">
                            <b>Sub-Category photo :</b>
                            <input type="file" name="photo" class="form-control " id="photo"
                                accept=".jpg, .jpeg, .png, .gif">
                            <span class="error error_photo"></span>
                        </div>

                        <div class="form-group mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue float-end">Save</button>
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
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
                    <h6 class="modal-title" id="exampleModalLabel">Edit Sub-Category</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body" id="edit_body">

                </div>
            </div>
        </div>
    </div>
    <!-- Modal-->
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: "{{ route('product.subcategories.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'photo',
                    name: 'category.photo'
                },
                {
                    data: 'parentname',
                    name: 'category.parentname'
                },
                {
                    data: 'name',
                    name: 'category.name'
                },
                {
                    data: 'action',
                    name: 'action'
                },
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
                        $('#addModal').modal('hide');
                    },
                    error: function(err) {
                        $('.loading_button').hide();
                        toastr.error('Please check again all form fields.',
                            'Some thing want wrong.');
                        $('.error').html('');
                        $.each(err.responseJSON.errors, function(key, error) {
                            //console.log(key);
                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.get("sub-categories/edit/" + id, function(data) {
                    $("#edit_body").html(data);
                    $('#editModal').modal('show');
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
                        console.log(data);
                        toastr.success(data);
                        $('.loading_button').hide();
                        $('#edit_sub_category_form')[0].reset();
                        table.ajax.reload();
                        $('#editModal').modal('hide');
                    },
                    error: function(err) {
                        $('.loading_button').hide();
                        toastr.error('Please check again all form fields.',
                            'Some thing want wrong.');
                        $('.error').html('');
                        $('.form-control').removeClass('is-invalid');
                        $.each(err.responseJSON.errors, function(key, error) {
                            //console.log(key);
                            $('.error_e_' + key + '').html(error[0]);
                        });
                    }
                });
            });

            // Show sweet alert for delete
            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                swal({
                        title: "Are you sure ?",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $('#deleted_form').submit();
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
                        toastr.success(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });

    </script>
@endpush
