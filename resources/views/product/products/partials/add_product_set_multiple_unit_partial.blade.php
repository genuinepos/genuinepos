<hr class="p-0 m-0 my-1 mx-1">
<div class="row align-items-end">

    <div class="col-md-6">
        <p class="fw-bold" style=" background: #6ce0cf; display: inline; padding: 2px 7px;">{{ __('Set Multiple Unit') }}</p>
    </div>

    <div class="col-md-6">
        <div class="add_more_btn">
            <a href="#" id="add_more_unit_btn" class="btn btn-sm btn-success float-end">{{ __('Add More') }}</a>
        </div>
    </div>

    <div class="col-md-12">
        <div class="table-responsive mt-1">
            <table class="table modal-table table-sm">
                <thead>
                    <tr>
                        <th class="text-start">{{ __('By') }}</th>
                        <th class="text-start"></th>
                        <th class="text-start">{{ __('Quantity') }}</th>
                        <th class="text-start">{{ __('To') }}</th>
                        <th class="text-start">{{ __('Unit Cost (Exc. Tax)') }}</th>
                        <th class="text-start">{{ __('Unit Cost (Inc. Tax)') }}</th>
                        <th class="text-start">{{ __('Price (Exc. Tax)') }}</th>
                        <th><i class="fas fa-trash-alt text-white"></i></th>
                    </tr>
                </thead>

                <tbody id="multiple_unit_body">
                    @isset($unitId)
                        <tr>
                            <td class="text-start" style="min-width: 100px;">
                                <span id="span_base_unit_name" class="fw-bold base_unit_name">{{ __("1") }} {{ $defaultUnitNeme }}</span>
                                <input type="hidden" name="base_unit_ids[]" id="base_unit_id" value="{{ $unitId }}">
                            </td>

                            <td class="text-start">
                                <p class="fw-bold">X</p>
                            </td>

                            <td class="text-start">
                                <input type="number" step="any" name="assigned_unit_quantities[]" class="form-control fw-bold multiple_unit_required_sometimes" id="assigned_unit_quantity" placeholder="{{ __('Quantity') }}">
                                <input type="hidden" name="base_unit_multipliers[]" id="base_unit_multiplier">
                            </td>

                            <td class="text-start" style="min-width: 127px;">
                                <div class="row align-items-end">
                                    <div class="col-md-2">
                                        <p class="fw-bold p-1">{{ __("1") }}</p>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="assigned_unit_ids[]" class="form-control assigned_unit_id multiple_unit_required_sometimes select2" id="assigned_unit_id" style="min-width: 110px !important;">
                                            <option data-assigned_unit_name="" value="">{{ __('Unit') }}</option>
                                            @foreach ($units as $unit)
                                                <option data-assigned_unit_name="{{ $unit->name }}" value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </td>

                            <td class="text-start">
                                <input type="number" step="any" name="assigned_unit_costs_exc_tax[]" class="form-control fw-bold" id="assigned_unit_cost_exc_tax" placeholder="{{ __('0.00') }}">
                            </td>

                            <td class="text-start">
                                <input readonly type="number" step="any" name="assigned_unit_costs_inc_tax[]" class="form-control fw-bold" id="assigned_unit_cost_inc_tax" placeholder="{{ __('0.00') }}">
                            </td>

                            <td class="text-start">
                                <input type="number" step="any" name="assigned_unit_prices_exc_tax[]" class="form-control fw-bold" id="assigned_unit_price_exc_tax" placeholder="{{ __('0.00') }}">
                            </td>

                            <td class="text-start">
                                <a href="#" id="unit_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
                            </td>
                        </tr>
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
</div>
