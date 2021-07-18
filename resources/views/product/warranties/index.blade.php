@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Warrantites - ')
@section('content')
<br><br><br>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <div class="card card-custom">
                    <div class="sec-name">
                        <div class="name-head">
                            <span class="fas fa-desktop"></span>
                            <h5>Warranty/Guaranty</h5>
                        </div>
                        <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                    </div>

                    <div class="section-header ">
                        <div class="col-md-6">
                            <h6>All Warranty/Guaranty</h6>
                        </div>
            
                        <div class="col-md-6">
                            <div class="btn_30_blue float-end">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> Add</a>
                            </div>
                        </div>
                    </div>


                    <div class="card-body">
                        <!--begin: Datatable-->
                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                            </div>
                            <div class="table-responsive" id="data-list">
                                <table class="table table-hover table-sm table-checkable" id="kt_datatable" >
                                    <thead>
                                        <tr class="text-center">
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Duration</th>
                                            <th>Description</th>
                                            <th>Action</th>
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
                    <h6 class="modal-title" id="exampleModalLabel">Add Warranty/Guaranty</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_warranty_form" action="{{ route('product.warranties.store') }}">
                        <div class="form-group">
                            <strong>Name :</strong> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control add_input" data-name="Warranty name" id="name" placeholder="Warranty name"/>
                            <span class="error error_name"></span>
                        </div>

                        <div class="row mt-1">
                            <div class="col-lg-4">
                                <strong>Type :</strong> <span class="text-danger">*</span>
                                <select name="type" class="form-control" id="type">
                                    <option value="1">Warranty</option>
                                    <option value="2">Guaranty</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-8">
                                <strong>Duration :</strong> <span class="text-danger">*</span>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <input type="number" name="duration" class="form-control w-50 add_input" data-name="Warranty duration" id="duration" placeholder="Warranty duration">
                                            <select name="duration_type" class="form-control w-50" id="duration_type">
                                                <option value="Months">Months</option>
                                                <option value="Days">Days</option>
                                                <option value="Year">Year</option>
                                            </select>
                                        </div> 
                                        <span class="error error_duration"></span>
                                    </div>
                                </div>
                            </div>  
                        </div>

                        <div class="form-group mt-1">
                            <strong>Description :</strong> 
                            <textarea name="description" id="description" class="form-control" cols="10" rows="3" placeholder="Warranty description"></textarea>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue float-end me-0 submit_button">Save</button>
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
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
                    <h6 class="modal-title" id="exampleModalLabel">Edit Warranty/Guaranty</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_warranty_form" action="{{ route('product.warranties.update') }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <strong>Name :</strong> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control edit_input" data-name="Bank name" id="e_name" placeholder="Bank name"/>
                            <span class="error error_e_name"></span>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-4">
                                <strong>Type :</strong> <span class="text-danger">*</span>
                                <select name="type" class="form-control" id="e_type">
                                    <option value="1">Warranty</option>
                                    <option value="2">Guaranty</option>
                                </select>
                            </div>
                          
                            <div class="col-md-8">
                                <strong>Duration :</strong> <span class="text-danger">*</span>
                                <div class="col-md-12">
                                    <div class="row">
                                        <input type="number" name="duration" class="form-control w-50 edit_input" data-name="Warranty duration" id="e_duration">
                                        <select name="duration_type" class="form-control w-50" id="e_duration_type">
                                            <option value="Months">Months</option>
                                            <option value="Days">Days</option>
                                            <option value="Year">Year</option>
                                        </select>
                                    </div> 
                                    <span class="error error_e_duration"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <strong>Description :</strong>
                            <textarea name="description" id="e_description" class="form-control form-control-sm" cols="10" rows="3" placeholder="Warranty description"></textarea>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue float-end submit_button">Save</button>
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
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
    //Get all category by ajax
    function getAllWarranty(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('product.warranties.all.warranty') }}",
            type:'get',
            success:function(data){
                $('.table-responsive').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllWarranty();

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method 
    $(document).ready(function(){
        // Add bank by ajax
        $('#add_warranty_form').on('submit', function(e){
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

            if(countErrorField > 0){
                $('.loading_button').hide();
                return;
            }

            $('.submit_button').prop('type', 'button');
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('#add_warranty_form')[0].reset();
                    $('.loading_button').hide();
                    getAllWarranty();
                    $('#addModal').modal('hide');
                    $('.submit_button').prop('type', 'submit');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            $('.form-control').removeClass('is-invalid');
            $('.error').html('');
            var warranty = $(this).closest('tr').data('info');
            console.log(warranty);
            $('#id').val(warranty.id);
            $('#e_name').val(warranty.name);
            $('#e_type').val(warranty.type);
            $('#e_duration').val(warranty.duration);
            $('#e_duration_type').val(warranty.duration_type);
            $('#e_description').val(warranty.description);
        });

        // edit bank by ajax
        $('#edit_warranty_form').on('submit', function(e){
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
                    getAllWarranty();
                    $('#editModal').modal('hide'); 
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
                    getAllWarranty();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });
    });
</script>
 @endpush 