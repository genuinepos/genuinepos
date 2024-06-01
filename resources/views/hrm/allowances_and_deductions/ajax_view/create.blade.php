<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Allowance/Deduction') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_allowance_deduction_form" action="{{ route('hrm.allowances.deductions.store') }}">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('Name or Title') }} <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="name" data-next="type" placeholder="{{ __('Name or Title') }}" />
                        <span class="error error_name"></span>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bold">{{ __('Type') }}</label>
                        <select class="form-control" name="type" id="type" data-next="amount_type">
                            <option value="1">{{ __('Allowance') }}</option>
                            <option value="2">{{ __('Deduction') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-6">
                        <label>{{ __('Amount Type') }}</label>
                        <select class="form-control" name="amount_type" id="amount_type" data-next="amount">
                            <option value="1">{{ __('Fixed') }} (0.0)</option>
                            <option value="2">{{ __('Percentage') }} (%)</option>
                        </select>
                    </div>

                    <div class="col-6">
                        <label class="fw-bold">{{ __('Amount') }} <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="amount" class="form-control" id="amount" data-next="allowance_deduction_save_btn" placeholder="{{ __('Amount') }}" />
                        <span class="error error_amount"></span>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button allowance_deduction_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="submit" id="allowance_deduction_save_btn" class="btn btn-sm btn-success allowance_deduction_submit_button">{{ __("Save") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('hrm.allowances_and_deductions.ajax_view.js_partials.create_js')

