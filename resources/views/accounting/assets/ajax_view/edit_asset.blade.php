<form id="edit_assset_form" action="{{ route('accounting.assets.update', $asset->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('menu.asset_name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="asset_name" class="form-control" id="e_asset_name"
                placeholder="@lang('menu.type_name')" value="{{ $asset->asset_name }}"/>
            <span class="error error_e_asset_name"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>@lang('menu.asset_type') :</b> <span class="text-danger">*</span></label>
            <select name="type_id" class="form-control" id="e_type_id" >
            <option value="">@lang('menu.select_asset_type')</option>
                @foreach ($types as $type)
                    <option {{ $type->id == $asset->type_id ? 'SELECTED' : '' }} value="{{ $type->id }}">{{ $type->asset_type_name }}</option>
                @endforeach
            </select>
        <span class="error error_e_type_id"></span>
        </div>
    </div>

    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
        <div class="form-group row mt-1">
            <div class="col-md-12">
                <label><b>@lang('menu.branch') :</b> <span class="text-danger">*</span></label>
                <select name="branch_id" class="form-control" id="e_branch_id">
                    <option value="">{{ $generalSettings['business__shop_name'] }} (@lang('menu.head_office'))</option>
                    @foreach ($branches as $br)
                        <option {{ $br->id == $asset->branch_id ? 'SELECTED' : '' }} value="{{ $br->id }}">{{ $br->name.'/'.$br->branch_code }}</option>
                    @endforeach
                </select>
                <span class="error error_e_branch_id"></span>
            </div>
        </div>
    @else
        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
    @endif


    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>@lang('menu.quantity') :</b> <span class="text-danger">*</span></label>
            <input type="number" step="any" name="quantity" class="form-control" id="e_quantity"
                placeholder="@lang('menu.quantity')" value="{{ $asset->quantity }}"/>
            <span class="error error_e_quantity"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>@lang('menu.per_unit_value') :</b> <span class="text-danger">*</span></label>
            <input type="number" step="any" name="per_unit_value" class="form-control" id="e_per_unit_value"
                placeholder="@lang('menu.per_unit_value')" value="{{ $asset->per_unit_value }}"/>
            <span class="error error_e_per_unit_value"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>@lang('menu.total_value') :</b> <span class="text-danger">*</span></label>
            <input type="number" step="any" name="total_value" class="form-control" id="e_total_value"
                placeholder="Total Asset Value" value="{{ $asset->total_value }}"/>
            <span class="error error_e_total_value"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success">@lang('menu.save_change')</button>
            </div>
        </div>
    </div>
</form>
