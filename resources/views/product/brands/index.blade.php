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
                                <h5>Brands</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->

                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>All Brand</h6>
                                    </div>
                                    @if (auth()->user()->permission->brand['brand_add'] == '1')
                                        <div class="col-md-6">
                                            <div class="btn_30_blue float-end">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                        class="fas fa-plus-square"></i> Add</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="widget_content">
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>Serial</th>
                                                    <th>Photo</th>
                                                    <th>Name</th>
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
                    <h6 class="modal-title" id="exampleModalLabel">@lang('brand.add_brand')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_brand_form" action="{{ route('product.brands.store') }}">
                        <div class="form-group">
                            <b>@lang('brand.name') :</b> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control form-control-sm add_input" data-name="Brand name" id="name"
                                placeholder="Brand Name" />
                            <span class="error error_name"></span>
                        </div>

                        <div class="form-group mt-2">
                            <b>@lang('brand.brand_photo') :</b>
                            <input type="file" name="photo" class="form-control form-control-sm dropify" data-max-file-size="2M" id="photo"
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
                    <h6 class="modal-title" id="exampleModalLabel">@lang('brand.edit_brand')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
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
        // Get all brands by ajax
        var table = $('.data_tbl').DataTable({
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: "{{ route('product.brands.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'photo',
                    name: 'category.photo'
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

        // call jquery method 
        $(document).ready(function() {
            // Add brand by ajax
            $('#add_brand_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').removeClass('d-none');
                $('.submit_button').hide();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.add_input');
                inputs.removeClass('is-invalid');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val()
                    if (inputId !== 'parent_category' && inputId !== 'photo') {
                        if (idValue == '') {
                            countErrorField += 1;
                            $('#' + inputId).addClass('is-invalid');
                            var fieldName = $('#' + inputId).data('name');
                            $('.error_' + inputId).html(fieldName +
                                ' is required.');
                        }
                    }
                });

                if (countErrorField > 0) {
                    $('.loading_button').hide();
                    $('.submit_button').show();
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_brand_form')[0].reset();
                        $('.loading_button').hide();
                        $('.submit_button').show();
                        $('.data_tbl').DataTable().ajax.reload();
                        $('#addModal').modal('hide');
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.get("brands/edit/" + id, function(data) {
                    $("#edit_body").html(data);
                    $('#editModal').modal('show');
                })
            });

            // edit brand by ajax
            $(document).on('submit', '#edit_brand_form', function(e){
                e.preventDefault();
                $('.loading_button').show();
                $('.submit_button').hide();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.edit_input');
                    inputs.removeClass('is-invalid');
                    $('.error').html('');  
                    var countErrorField = 0;  
                $.each(inputs, function(key, val){
                    var inputId = $(val).attr('id');
                    var idValue = $('#'+inputId).val()
                    if(idValue == ''){
                        countErrorField += 1;
                        $('#'+inputId).addClass('is-invalid');
                        var fieldName = $('#'+inputId).data('name');
                        $('.error_'+inputId).html(fieldName+' is required.');
                    } 
                });
                if(countErrorField > 0){
                    $('.loading_button').hide();
                    $('.submit_button').show();
                    return;
                }
                $.ajax({
                    url:url,
                    type:'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data){
                        console.log(data);
                        toastr.success(data);
                        $('.loading_button').hide();
                        $('.submit_button').show();
                        $('.data_tbl').DataTable().ajax.reload();
                        $('#editModal').modal('hide'); 
                    }
                });
            });

            // Show sweet alert for delete
            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                swal({
                        title: "@lang('brand.delete_alert')",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $('#deleted_form').submit();
                        } else {
                            swal("@lang('brand.delete_cancel')");
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
                    }
                });
            });
        });

    </script>
@endpush
