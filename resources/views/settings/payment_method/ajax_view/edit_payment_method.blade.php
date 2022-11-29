<form id="edit_payment_method_form" class="p-2" action="{{ route('settings.payment.method.update', $method->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>Method Name :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="e_name"
                placeholder="Payment Method Name" value="{{ $method->name }}"/>
            <span class="error error_e_name"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="submit" class="btn btn-sm btn-success submit_button">Save Changes</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>
