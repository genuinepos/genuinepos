<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Leave Type') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="edit_leave_type_form" action="{{ route('hrm.leave.type.update', $leaveType->id) }}">
                <input type="hidden" name="id" id="id">
                <div class="form-group">
                    <label><b>@lang('menu.leave_type') :</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="leave_type" class="form-control edit_input" data-name="leave type" id="e_leave_type" value="{{ $leaveType->leave_type }}" placeholder="@lang('menu.leave_type')" required="" />
                    <span class="error error_e_leave_type"></span>
                </div>

                 <div class="form-group">
                    <label><b>{{ __('Max leave count') }} :</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="max_leave_count" class="form-control edit_input" data-name="max leave count" id="e_max_leave_count" value="{{ $leaveType->max_leave_count }}" placeholder="{{ __('Max leave count') }}"  />
                    <span class="error error_e_max_leave_count"></span>
                </div>

                <div class="form-group">
                    <label><b>{{ __('Leave Count Interval') }} :</b></label>
                    <select name="leave_count_interval" class="form-control" id="e_leave_count_interval">
                        <option {{ $leaveType->leave_count_interval == 0 ? 'SELECTED' : '' }} value="0">None</option>
                        <option {{ $leaveType->leave_count_interval == 1 ? 'SELECTED' : '' }} value="1">Current Month</option>
                        <option {{ $leaveType->leave_count_interval == 2 ? 'SELECTED' : '' }} value="2">Current Financial Year</option>
                    </select>
                </div>

                <div class="form-group d-flex justify-content-end mt-3">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button d-hide">
                            <i class="fas fa-spinner text-primary"></i><span> @lang('menu.loading')...</span>
                        </button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                        <button type="submit" class="btn btn-sm btn-success">@lang('menu.save_change')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
     $('#edit_leave_type_form').on('submit', function(e){
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url : url,
                type : 'post',
                data : request,
                success:function(data){

                    toastr.success(data);
                    $('.loading_button').hide();
                    table.ajax.reload();
                    $('#editModal').modal('hide');
                }, error : function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error');
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_e_' + key + '').html(error[0]);
                    });
                }
            });
        });
</script>