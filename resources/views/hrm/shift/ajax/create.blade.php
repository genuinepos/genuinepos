<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Add Shift') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_shift_form" action="{{ route('hrm.shift.store') }}">
                <div class="form-group row">
                    <div class="col-md-12">
                        <label>{{ __('Shift Name') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="shift_name" data-next="shift_start_date" placeholder="{{ __('Shift Name') }}"/>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="form-group col-12">
                        <label>{{ __('Start Time') }} <span class="text-danger">*</span></label>
                        <input required type="time" name="start_time" class="form-control" id="shift_start_date" data-next="shift_end_date" placeholder="{{ __('Start Time') }}" />
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="form-group col-12">
                        <label>{{ __('End Time') }} <span class="text-danger">*</span></label>
                        <input required type="time" name="end_time" class="form-control" id="shift_end_date" data-next="shift_save_btn" placeholder="{{ __('End Time') }}"/>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button shift_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="submit" id="shift_save_btn" class="btn btn-sm btn-success shift_submit_button">{{ __("Save") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
