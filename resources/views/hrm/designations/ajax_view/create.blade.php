<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Add Designation') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_designation_form" action="{{ route('hrm.designations.store') }}">
                @csrf
                <div class="form-group">
                    <label class="fw-bold">{{ __("Name") }} <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" id="designation_name" data-next="designation_description" placeholder="{{ __("Designation name") }}" />
                    <span class="error error_designation_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label class="fw-bold">{{ __('Designation') }}</label>
                    <input name="description" class="form-control" id="designation_description" data-next="designation_save_btn" placeholder="{{ __('Designation Details') }}">
                </div>

                <div class="form-group d-flex justify-content-end mt-3">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button designation_loading_btn d-hide">
                            <i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span>
                        </button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        <button type="submit" id="designation_save_btn" class="btn btn-sm btn-success designation_submit_button">{{ __("Save") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('hrm.designations.ajax_view.js_partials.create_js')
