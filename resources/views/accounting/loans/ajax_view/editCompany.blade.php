<form id="edit_company_form" action="{{ route('accounting.loan.companies.update', $company->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('menu.name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="e_name" autocomplete="off"
                placeholder="Company/People Name" value="{{ $company->name }}"/>
            <span class="error error_e_name"></span>
        </div>
    </div>

    <div class="col-md-12">
        <label><b>@lang('menu.phone') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control" id="e_phone" autocomplete="off"
            placeholder="@lang('menu.phone_number')" value="{{ $company->phone }}"/>
        <span class="error error_e_phone"></span>
    </div>

    <div class="col-md-12">
        <label><b>@lang('menu.address') :</b> </label>
        <textarea name="address" class="form-control" id="e_address" cols="10" rows="3" placeholder="@lang('menu.address')">{{ $company->address }}</textarea>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="button" class="btn btn-sm btn-danger" id="close_com_edit_form">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
            </div>
        </div>
    </div>
</form>
