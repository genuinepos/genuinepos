@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-desktop"></span>
                                <h5>Account Types</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->

                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>All Account Types</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="btn_30_blue float-end">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                    class="fas fa-plus-square"></i> Add</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget_content">
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">SL</th>
                                                    <th class="text-start">Bank Name</th>
                                                    <th class="text-start">Branch Name</th>
                                                    <th class="text-start">Address</th>
                                                    <th class="text-start">Action</th>
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
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Account Type</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_type_form" action="{{ route('accounting.types.store') }}">
                        <div class="form-group">
                            <label><b>Name</b> : <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control add_input" data-name="Type name" id="name" placeholder="Type name"/>
                            <span class="error error_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>Remark</b>  : </label>
                            <input type="text" name="remark" class="form-control" placeholder="Remark Type"/>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="me-0 c-btn btn_blue float-end">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
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
                    <h6 class="modal-title" id="exampleModalLabel">Edit Account Type</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_type_form" action="{{ route('accounting.types.update') }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label><b>Name</b> : <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control edit_input" data-name="Type name" id="e_name" placeholder="Type name"/>
                            <span class="error error_e_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>Remark</b> : </label>
                            <input type="text" name="remark" class="form-control" id="e_remark" placeholder="Remark Type"/>
                        </div>

                        <div class="form-group text-right mt-3">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="me-0 c-btn btn_blue float-end ">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Get all account by ajax
        function getAllType(){
            $('.data_preloader').show();
            $.ajax({
                url:"{{ route('accounting.types.all.type') }}",
                type:'get',
                success:function(data){
                    $('.table-responsive').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getAllType();

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method 
        $(document).ready(function(){
            // Add bank by ajax
            $('#add_type_form').on('submit', function(e){
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
                console.log(countErrorField);
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
                        $('#add_type_form')[0].reset();
                        $('.loading_button').hide();
                        getAllType();
                        $('#addModal').modal('hide');
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e){
                e.preventDefault();
                $('.form-control').removeClass('is-invalid');
                $('.error').html('');
                var type = $(this).closest('tr').data('info');
                console.log(type);
                $('#id').val(type.id);
                $('#e_name').val(type.name);
                $('#e_remark').val(type.remark);
                $('#editModal').modal('show');
            });

            // edit account type by ajax
            $('#edit_type_form').on('submit', function(e){
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.edit_input');
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
                        console.log(data);
                        toastr.success(data);
                        $('.loading_button').hide();
                        getAllType();
                        $('#editModal').modal('hide'); 
                    }
                });
            });

            // Show sweet alert for delete
            $(document).on('click', '#delete',function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                swal({
                    title: "Are you sure?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) { 
                        $('#deleted_form').submit();
                    } else {
                        swal("Your imaginary file is safe!");
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
                    data:request,
                    success:function(data){
                        getAllType();
                        toastr.success(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });

            $(document).on('click', '#change_status',function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url:url,
                    type:'get',
                    success:function(data){
                        getAllType();
                        toastr.success(data);
                    }
                });
            });
        });
    </script>
@endpush