<form id="change_purchase_status_form" action="{{ route('purchases.change.status', $purchase->id) }}" method="post">
    @csrf
    <div class="form-group">
        <label>{{ __('Purchase Status') }} </label>
        <select name="purchase_status" class="form-control form-control-sm" id="purchase_status">
            <option {{ $purchase->purchase_status == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.receive')</option>
            <option {{ $purchase->purchase_status == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.pending')</option>
            <option {{ $purchase->purchase_status == 3 ? 'SELECTED' : '' }} value="3">@lang('menu.ordered')</option>
        </select>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end">@lang('menu.save')</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('menu.close')</button>
        </div>
    </div>
</form>