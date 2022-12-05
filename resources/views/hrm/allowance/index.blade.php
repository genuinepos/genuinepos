@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'HRM Allowances/Deductions - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-plus"></span>
                    <h6>Allowances/Deductions</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                    class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">
            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-md-6">
                        <h6>Allowances/Deductions</h6>
                    </div>

                    <div class="col-md-6 d-flex justify-content-end">
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i>@lang('menu.add')</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6></div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>@lang('menu.type')</th>
                                    <th>Max leave</th>
                                    <th>Leave Count Interval</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Allowance/Deduction</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_allowance_form" action="{{ route('hrm.allowance.store') }}">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label><b>Description or Title :</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="description" class="form-control" placeholder="Description or Title"/>
                                <span class="error error_description"></span>
                            </div>

                            <div class="col-md-6">
                                <label><b>@lang('menu.type') :</b> <span class="text-danger">*</span></label>
                                <select class="form-control" name="type" required="">
                                    <option value="Allowance">Allowance</option>
                                    <option value="Deduction">Deduction</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-6">
                                <label><b>Amount Type :</b>  <span class="text-danger">*</span></label>
                                <select class="form-control" name="amount_type" id="amount_type">
                                    <option value="1">Fixed (0.0)</option>
                                    <option value="2">Percentage (%)</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label><b>@lang('menu.amount') :</b>  <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="amount" class="form-control" placeholder="@lang('menu.amount')"/>
                                <span class="error error_amount"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                    <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
                                </div>
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
                    <h6 class="modal-title" id="exampleModalLabel">Edit Allowance/Deduction</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body">
                    <!--begin::Form-->
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    // Get all category by ajax
    function getAllAllowance(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('hrm.allowance.all') }}",
            type:'get',
            success:function(data){
                $('.table-responsive').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllAllowance();

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function(){
        // Add department by ajax
        $('#add_allowance_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('#add_allowance_form')[0].reset();
                    $('.loading_button').hide();
                    getAllAllowance();
                    $('#addModal').modal('hide');
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        //console.log(key);
                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $(document).on('click', '#edit', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type:'get',
                success:function (data) {
                    $('#edit_modal_body').html(data);
                    $('#editModal').modal('show');
                }
            });
        });

        // edit submit form by ajax
        $(document).on('submit', '#edit_allowance_form',function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('.loading_button').hide();
                    getAllAllowance();
                    $('#editModal').modal('hide');
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        //console.log(key);
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
                'message': 'Are you sure?',
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
                type:'post',
                async:false,
                data:request,
                success:function(data){
                    getAllAllowance();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });
    });
</script>
@endpush
