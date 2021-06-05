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
                                <h5>Units</h5>
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
                                        <h6>All Units</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="btn_30_blue float-end">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                    class="fas fa-plus-square"></i> Add</a>
                                        </div>
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
                                                    <th>Unit Name</th>
                                                    <th>Code Name</th>
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
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Unit</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_unit_form" action="{{ route('settings.units.store') }}">
                        <div class="form-group">
                            <label><b>Unit Name :</b><span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm add_input" data-name="Name" id="name" placeholder="Unit Name"/>
                            <span class="error error_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>Code Name :</b><span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control form-control-sm add_input" data-name="Code name" id="code" placeholder="Code name"/>
                            <span class="error error_code"></span>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="me-0 c-btn btn_blue float-end">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Unit</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_unit_form" action="{{ route('settings.units.update') }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label><b>Unit Name :</b><span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm edit_input" data-name="Name" id="e_name" placeholder="Unit Name"/>
                            <span class="error error_e_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>Code Name :</b><span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control form-control-sm edit_input" data-name="Code name" id="e_code" placeholder="Branch Name"/>
                            <span class="error error_e_code"></span>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="me-0 c-btn btn_blue float-end">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
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
    // Get all branch by ajax
    function getAllUnit(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('settings.units.get.all.unit') }}",
            type:'get',
            success:function(data){
                console.log(data);
                $('#data-list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllUnit();

    // insert branch by ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method 
    $(document).ready(function(){
        // Add branch by ajax
        $('#add_unit_form').on('submit', function(e){
            e.preventDefault();
             $('.loading_button').show();
             $('.submit_button').hide();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
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
                data:request,
                success:function(data){
                    toastr.success(data);
                    $('#add_unit_form')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').show();
                    getAllUnit();
                    $('#addModal').modal('hide');
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
            console.log(unitInfo);
            $('#id').val(unitInfo.id);
            $('#e_name').val(unitInfo.name);
            $('#e_code').val(unitInfo.code_name);
            $('#editModal').modal('show');
        });

        // edit branch by ajax
        $('#edit_unit_form').on('submit', function(e){
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
                data:request,
                success:function(data){
                    $('#editModal').modal('hide');
                    toastr.success(data);
                    $('.loading_button').hide();
                    $('.submit_button').show();
                    getAllUnit();
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
            })
            .then((willDelete) => {
                if (willDelete) { 
                {{--  swal("Poof! Your imaginary file has been deleted!", {
                    icon: "success",
                });   --}}
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
                type:'delete',
                data:request,
                success:function(data){
                    if($.isEmptyObject(data.errorMsg)){
                        getAllUnit();
                        toastr.success(data);
                    }else{
                        toastr.error(data.errorMsg, 'Error'); 
                    }
                }
            });
        });
    });
</script>
@endpush
