@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    
    <div class="body-woaper" style="margin-top: 64px;">
        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3 style="color: #32325d"> Variants</h3>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-info float-right"><i
                                class="fas fa-long-arrow-alt-left"></i> Back</a>
                    </div>
                </div>

                <div class="card card-custom">
                    <div class="section-header mt-4">
                        <div class="col-md-6">
                            <h6>All Variant</h6>
                        </div>
                        @if (auth()->user()->permission->category['category_add'] == '1')
                            <div class="col-md-6">
                                <div class="btn_30_blue float-end">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> Add</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <div class="table_area">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner"></i> Processing...</h6>
                            </div>
                            <div class="table-responsive" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Name</th>
                                            <th>Childs</th>
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
                        <!--end: Datatable-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Variant</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_variant_form" action="{{ route('product.variants.store') }}">
                        <div class="form-group ">
                            <b>Name :</b> <span class="text-danger">*</span>
                            <input type="text" name="variant_name" class="form-control form-control-sm add_input"
                                data-name="Variant name" id="variant_name" placeholder="Variant Name" />
                            <span class="error error_variant_name"></span>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12"><b>Variant Childs (Values)</b> : <span class="text-danger">*</span></div>
                            <div class="col-md-11">
                                <input required type="text" name="variant_childs[]" class="form-control form-control-sm"
                                    placeholder="Variant child" />
                            </div>

                            <div class="col-md-1 text-right">
                                <a class="btn btn-sm btn-primary add_more_for_add" href="#">+</a>
                            </div>
                        </div>

                        <div class="form-group more_variant_childs_area">

                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue float-end">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Variant</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_variant_form" action="{{ route('product.variants.update') }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <b>Name :</b> <span class="text-danger">*</span>
                            <input type="text" name="variant_name" class="form-control form-control-sm edit_input"
                                data-name="Brand name" id="e_variant_name" placeholder="Brand Name" />
                            <span class="error error_e_variant_name"></span>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12"><b>Variant Childs (Values) :</b> <span class="text-danger">*</span></div>
                            <div class="col-md-11">
                                <input type="hidden" name="variant_child_ids[]" id="e_variant_child_id" value="">
                                <input required type="text" name="variant_childs[]" class="form-control form-control-sm"
                                    id="e_variant_child" placeholder="Variant child" />
                            </div>

                            <div class="col-md-1 text-right">
                                <a class="btn btn-sm btn-primary add_more_for_edit" href="#">+</a>
                            </div>
                        </div>

                        <div class="form-group more_variant_childs_area_edit">

                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue float-end">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal-->
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method 
        var add_more_index = 0;
        $(document).ready(function() {
            // add more variant child field
            $('.add_more_for_add').on('click', function(e) {
                e.preventDefault();
                var index = add_more_index++;
                var html = '<div class="more_variant_child mt-2 more' + index + '">';
                html += '<div class="row">';
                html += '<div class="col-md-11"> ';
                html +=
                    '<input required type="text" name="variant_childs[]" class="form-control form-control-sm" placeholder="Variant child"/>';
                html += ' </div>';

                html += '<div class="col-md-1 text-right">';
                html += '<a class="btn btn-sm btn-danger delete_more_for_add" data-index="' + index +
                    '" href="#">X</a>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                $('.more_variant_childs_area').append(html);
            });

            // delete add more field for adding
            $(document).on('click', '.delete_more_for_add', function(e) {
                var index = $(this).data('index');
                console.log(index);
                $('.more' + index).remove();
            })

            // Add variant by ajax
            $('#add_variant_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                $('.submit_button').hide();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.add_input');
                inputs.removeClass('is-invalid');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    console.log(inputId);
                    var idValue = $('#' + inputId).val()
                    if (inputId !== 'parent_category' && inputId !== 'photo') {
                        if (idValue == '') {
                            countErrorField += 1;
                            $('#' + inputId).addClass('is-invalid');
                            var fieldName = $('#' + inputId).data('name');
                            $('.error_' + inputId).html(fieldName + ' is required.');
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
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_variant_form')[0].reset();
                        $('.loading_button').hide();
                        $('.submit_button').show();
                        getAllVariant();
                        $('#addModal').modal('hide');
                        $('.more_variant_childs_area').empty();
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
                console.log(variantInfo);
                $('#id').val(variantInfo.id);
                $('#e_variant_name').val(variantInfo.bulk_variant_name);
                $('#e_variant_child_id').val(variantInfo.bulk_variant_childs[0].id);
                $('#e_variant_child').val(variantInfo.bulk_variant_childs[0].child_name);
                $('.more_variant_childs_area_edit').empty();
                $.each(variantInfo.bulk_variant_childs, function(key, bulk_variant_child) {
                    if (add_more_index_for_edit != 0) {
                        var html = '<div class="more_variant_child mt-2 e_more' +
                            add_more_index_for_edit + '">';
                        html += '<div class="row">';
                        html += '<div class="col-md-11"> ';
                        html += '<input type="hidden" name="variant_child_ids[]" value="' +
                            bulk_variant_child.id + '"/>';
                        html +=
                            '<input required type="text" name="variant_childs[]" class="form-control form-control-sm" placeholder="Variant child" value="' +
                            bulk_variant_child.child_name + '"/>';
                        html += '</div>';

                        html += '<div class="col-md-1 text-right">';
                        html +=
                            '<a class="btn btn-sm btn-danger delete_more_for_edit" data-index="' +
                            add_more_index_for_edit + '" href="#">X</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        $('.more_variant_childs_area_edit').append(html);
                    }
                    add_more_index_for_edit++;
                })
            });

            $('.add_more_for_edit').on('click', function(e) {
                e.preventDefault();
                var index = add_more_index_for_edit++;
                var html = '<div class="more_variant_child mt-2 e_more' + index + '">';
                html += '<div class="row">';
                html += '<div class="col-md-11"> ';
                html += '<input type="hidden" name="variant_child_ids[]" value="noid"/>';
                html +=
                    '<input required type="text" name="variant_childs[]" class="form-control form-control-sm" placeholder="Variant child"/>';
                html += ' </div>';

                html += '<div class="col-md-1 text-right">';
                html += '<a class="btn btn-sm btn-danger delete_more_for_edit" data-index="' + index +
                    '" href="#">X</a>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                $('.more_variant_childs_area_edit').append(html);
            });


            // delete add more field for adding
            $(document).on('click', '.delete_more_for_edit', function(e) {
                var index = $(this).data('index');
                console.log(index);
                $('.e_more' + index).remove();
            })

            // edit brand by ajax
            $('#edit_variant_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                $('.submit_button').hide();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.edit_input');
                inputs.removeClass('is-invalid');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val()
                    if (idValue == '') {
                        countErrorField += 1;
                        $('#' + inputId).addClass('is-invalid');
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_' + inputId).html(fieldName + ' is required.');
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
                    data: request,
                    success: function(data) {
                        console.log(data);
                        toastr.success(data);
                        $('.loading_button').hide();
                        $('.submit_button').show();
                        getAllVariant();
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
                        title: "Are you sure to delete ?",
                        text: "Once deleted, you will not be able to recover this imaginary file!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $('#deleted_form').submit();
                        } else {
                            swal("Your imaginary file is safe!");
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
                        getAllVariant();
                        toastr.success(data);
                    }
                });
            });
        });

    </script>
@endpush
