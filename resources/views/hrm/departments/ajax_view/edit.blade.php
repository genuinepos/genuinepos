<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Edit Department') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <form id="edit_department_form" action="{{ route('hrm.departments.update', $department->id) }}">
                @csrf
                <div class="form-group">
                    <label class="fw-bold">{{ __('Name') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="department_name" data-next="department_description" value="{{ $department->name }}" placeholder="{{ __('Department Name') }}" />
                    <span class="error error_department_name"></span>
                </div>

                <div class="form-group mt-1">
                    <div class="form-group">
                        <label class="fw-bold">{{ __('Description') }}</label>
                        <input name="description" class="form-control" id="department_description" data-next="department_save_changes_btn" value="{{ $department->description }}" placeholder="{{ __('Description') }}">
                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-3">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button department_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        <button type="submit" id="department_save_changes_btn" class="btn btn-sm btn-success department_submit_btn">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('hrm.departments.ajax_view.js_partials.edit_js')
