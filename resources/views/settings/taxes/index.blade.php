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
                                <span class="fas fa-hand-holding-usd"></span>
                                <h5>Texes</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->

                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>All Tax</h6>
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
                                                    <th class="text-start">Serial</th>
                                                    <th class="text-start">Tax Name</th>
                                                    <th class="text-start">Tax Percent</th>
                                                    <th class="text-start">Actions</th>
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
                    <h6 class="modal-title" id="exampleModalLabel">Add Tax</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_tax_form" action="{{ route('settings.taxes.store') }}">
                        <div class="form-group">
                            <label><b>Tax Name :</b>  <span class="text-danger">*</span></label>
                            <input type="text" name="tax_name" class="form-control form-control-sm add_input" data-name="Tax name" id="tax_name" placeholder="Tax Name"/>
                            <span class="error error_tax_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>Tax Percent :</b> <span class="text-danger">*</span></label>
                            <input type="number" name="tax_percent" class="form-control form-control-sm add_input" data-name="Tax percent" id="tax_percent" placeholder="Tax percent"/>
                            <span class="error error_tax_percent"></span>
                        </div>

                        <div class="form-group text-right mt-3">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="me-0 c-btn button-success float-end">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     {{-- Edit Modal --}}
     <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Tax</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_tax_form" action="{{ route('settings.taxes.update') }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label><b>Unit Name :</b>  <span class="text-danger">*</span></label>
                            <input type="text" name="tax_name" class="form-control edit_input" data-name="Name" id="e_tax_name" placeholder="Tax Name"/>
                            <span class="error error_e_tax_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>Tax Percent :</b><span class="text-danger">*</span></label>
                            <input type="text" name="tax_percent" class="form-control edit_input" data-name="Tax percent" id="e_tax_percent" placeholder="Branch Name"/>
                            <span class="error error_e_tax_percent"></span>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="me-0 c-btn button-success float-end">Save</button>
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
            url:"{{ route('settings.taxes.get.all.tax') }}",
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
        $('#add_tax_form').on('submit', function(e){
            e.preventDefault();
             $('.loading_button').show();
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
                return;
            }
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    toastr.success(data);
                    $('#add_tax_form')[0].reset();
                    $('.loading_button').hide();
                    getAllUnit();
                    $('#addModal').modal('hide');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            $('#edit_tax_form')[0].reset();
            $('.form-control').removeClass('is-invalid');
            $('.error').html('');
            var taxInfo = $(this).closest('tr').data('info');
            $('#id').val(taxInfo.id);
            $('#e_tax_name').val(taxInfo.tax_name);
            $('#e_tax_percent').val(taxInfo.tax_percent);
            $('#editModal').modal('show');
        });

        // edit branch by ajax
        $('#edit_tax_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
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
                    getAllUnit();
                }
            });
        });

        // Show sweet alert for delete
        // $(document).on('click', '#delete',function(e){
        //     e.preventDefault();
        //     var url = $(this).attr('href');
        //     $('#deleted_form').attr('action', url);
        //     swal({
        //         title: "Are you sure?",
        //         icon: "warning",
        //         buttons: true,
        //         dangerMode: true,
        //     })
        //     .then((willDelete) => {
        //         if (willDelete) {
        //             $('#deleted_form').submit();
        //         }
        //     });
        // });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {
                            // alert('Deleted canceled.')
                        }
                    }
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
                        toastr.error(data.errorMsg);
                    }
                }
            });
        });
    });
</script>
@endpush
