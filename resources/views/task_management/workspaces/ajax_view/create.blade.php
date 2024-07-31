<div class="modal-dialog col-55-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __("Add Project") }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_workspace_form" action="{{ route('workspaces.store') }}" method="post">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <label><b>{{ __("Name") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="workspace_name" data-next="workspace_user_ids" placeholder="{{ __("Project Name") }}">
                        <span class="error error_workspace_name"></span>
                    </div>

                    <div class="col-md-6">
                        <label><b>{{ __("Assign To") }}</b> <span class="text-danger">*</span></label>
                        <select required name="user_ids[]" class="form-control select2" id="workspace_user_ids" multiple="multiple">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->prefix.' '.$user->name.' '.$user->last_name }}</option>
                            @endforeach
                        </select>

                        <span class="error error_workspace_user_ids"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label><b>{{ __("Priority") }}</b> <span class="text-danger">*</span></label>
                        <select required name="priority" class="form-control" id="workspace_priority" data-next="workspace_status">
                            <option value="">{{ __("Select Priority") }}</option>
                            @foreach (\App\Enums\TaskPriority::cases() as $item)
                                <option value="{{ $item->value }}">{{ $item->value }}</option>
                            @endforeach
                        </select>
                        <span class="error error_workspace_priority"></span>
                    </div>

                    <div class="col-md-6">
                        <label><b>{{ __("Status") }}</b> <span class="text-danger">*</span></label>
                        <select required name="status" class="form-control" id="workspace_status" data-next="workspace_start_date">
                            <option value="">{{ __("Select Status") }}</option>
                            @foreach (\App\Enums\TaskStatus::cases() as $item)
                                <option value="{{ $item->value }}">{{ $item->value }}</option>
                            @endforeach
                        </select>
                        <span class="error error_workspace_status"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label><b>{{ __("Start Date") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="start_date" class="form-control" id="workspace_start_date" data-next="workspace_end_date" value="{{ date($generalSettings['business_or_shop__date_format']) }}" placeholder="{{ __("Project Start Date") }}" autocomplete="off">
                        <span class="error error_workspace_start_date"></span>
                    </div>

                    <div class="col-md-6">
                        <label><b>{{ __("End Date") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="end_date" class="form-control" id="workspace_end_date" data-next="workspace_description" placeholder="{{ __("Project End Date") }}" autocomplete="off">
                        <span class="error error_workspace_end_date"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <label><b>{{ __("Description") }}</b></label>
                        <input name="description" class="form-control" id="workspace_description" data-next="workspace_estimated_hours" placeholder="{{ __("Project Description") }}">
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label><b>{{ __("Documents") }}</b></label>
                        <input type="file" name="attachments[]" class="form-control" multiple id="workspace_attachments">
                    </div>

                    <div class="col-md-6">
                        <label><b>{{ __('Estimated Hour') }} </b></label>
                        <input type="text" name="estimated_hours" class="form-control" id="workspace_estimated_hours" data-next="workspace_save" placeholder="{{ __('Estimated Hour') }}">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button workspace_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="submit" id="workspace_save" class="btn btn-sm btn-success workspace_submit_button">{{ __("Save") }}</button>
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

        $('.workspace_submit_button').prop('type', 'button');
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
    $(document).on('click', '.workspace_submit_button',function () {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_workspace_form').on('submit',function(e) {
        e.preventDefault();

        $('.workspace_loading_btn').show();
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
            data: new FormData(this),
            processData: false,
            cache: false,
            contentType: false,
            success:function(data){

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.workspace_loading_btn').hide();
                $('.error').html('');

                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
                $('#workspaceAddOrEditModal').modal('hide');
                workspacesTable.ajax.reload();
            }, error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.workspace_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if(err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if(err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_workspace_' + key + '').html(error[0]);
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
        element: document.getElementById('workspace_start_date'),
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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('workspace_end_date'),
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
