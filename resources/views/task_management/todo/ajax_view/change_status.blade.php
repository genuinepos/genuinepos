<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __("Change Status") }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="todo_change_status_form" action="{{ route('todo.change.status', $todo->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label><strong>{{ __("Status") }}</strong></label>
                    <select required name="status" class="form-control" id="todo_status" data-next="todo_status_save">
                        <option value="">{{ __("Select Status") }}</option>
                        @foreach (\App\Enums\TaskStatus::cases() as $item)
                            <option @selected($item->value == $todo->status) value="{{ $item->value }}">{{ $item->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn todo_status_loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="submit" id="todo_status_save" class="btn btn-sm btn-success change_status_submit_button">{{ __("Save") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.change_status_submit_button').prop('type', 'button');
    });

    $(document).on('click change keypress', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.change_status_submit_button',function () {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }else {

            $(this).prop('type', 'button');
        }
    });

    $('#todo_change_status_form').on('submit',function(e) {
        e.preventDefault();

        $('.todo_status_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url : url,
            type : 'post',
            data: request,
            success:function(data){

                $('.todo_status_loading_button').hide();
                $('.error').html('');

                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                $('#changeStatusModal').modal('hide');
                toastr.success(data);
                todoTable.ajax.reload();
            }, error: function(err) {

                $('.todo_status_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if(err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if(err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }
            }
        });
    });
</script>
