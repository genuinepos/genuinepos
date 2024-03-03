<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __("Add Todo") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_todo_form" action="{{ route('todo.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="col-md-12">
                        <label><b>{{ __("Task") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="task" class="form-control" id="todo_task" data-next="todo_user_id" placeholder="{{ __("Task") }}">
                        <span class="error error_todo_name"></span>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <div class="col-md-12">
                        <label><b>{{ __("Assigned To") }}</b> <span class="text-danger">*</span></label>
                        <select required name="user_ids[]" class="form-control select2" id="todo_user_id" multiple="multiple">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->prefix.' '.$user->name.' '.$user->last_name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_user_ids"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label><b>{{ __("Priority") }}</b> <span class="text-danger">*</span></label>
                        <select required name="priority" class="form-control" id="todo_priority" data-next="todo_status">
                            <option value="">{{ __("Select Priority") }}</option>
                            @foreach (\App\Enums\TaskPriority::cases() as $item)
                                <option value="{{ $item->value }}">{{ $item->value }}</option>
                            @endforeach
                        </select>
                        <span class="error error_todo_priority"></span>
                    </div>

                    <div class="col-md-6">
                        <label><strong>{{ __("Status") }}</strong> <span class="text-danger">*</span></label>
                        <select required name="status" class="form-control" id="todo_status" data-next="todo_due_date" autocomplete="off">
                            <option value="">{{ __("Select Satus") }}</option>
                            @foreach (\App\Enums\TaskStatus::cases() as $item)
                                <option value="{{ $item->value }}">{{ $item->value }}</option>
                            @endforeach
                        </select>
                        <span class="error error_todo_status"></span>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <div class="col-md-12">
                        <label><b>{{ __("Due Date") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="due_date" class="form-control" id="todo_due_date" data-next="todo_description" placeholder="{{ __("Due Date") }}" autocomplete="off">
                        <span class="error error_todo_due_date"></span>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <div class="col-md-12">
                        <label><b>{{ __("Short Description") }}</b></label>
                        <input name="description" class="form-control" id="todo_description" data-next="todo_save" placeholder="{{ __("Short Description") }}">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button todo_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="submit" id="todo_save" class="btn btn-sm btn-success todo_submit_button">{{ __("Save") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2();
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.todo_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#'+nextId).focus().select();
        }
    });

    $(document).on('click change keypress', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.todo_submit_button',function () {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_todo_form').on('submit',function(e) {
        e.preventDefault();

        $('.todo_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function(){
                isAjaxIn = true;
            },
            url : url,
            type : 'post',
            data: request,
            success:function(data){

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.todo_loading_btn').hide();
                $('.error').html('');

                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                $('#todoAddOrEditModal').modal('hide');
                toastr.success(data);
                todoTable.ajax.reload();
            }, error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.todo_loading_btn').hide();
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

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_todo_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>

<script>
    var dateFormat = "{{ $generalSettings['business_or_shop__date_format'] }}";
    var _expectedDateFormat = '';
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('todo_due_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat
    });
</script>
