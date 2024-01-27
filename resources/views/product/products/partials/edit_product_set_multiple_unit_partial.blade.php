<hr class="p-0 m-0 my-1 mx-1">
<div class="row align-items-end">

    <div class="col-md-6">
        <p class="fw-bold" style=" background: #6ce0cf; display: inline; padding: 2px 7px;">{{ __('Set Multiple Unit') }}</p>
    </div>

    <div class="col-md-6">
        <div class="add_more_btn">
            <a href="#" id="add_more_unit_btn" class="btn btn-sm btn-primary float-end">{{ __('Add More') }}</a>
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
                        <th class="text-start">{{ __('Unit Cost Int. Tax') }}</th>
                        <th class="text-start">{{ __('Price (Exc. Tax)') }}</th>
                        <th><i class="fas fa-trash-alt text-white"></i></th>
                    </tr>
                </thead>

                <tbody id="multiple_unit_body">
                    @if ($product->has_multiple_unit)
                        @foreach ($product->productUnits as $productUnit)
                            <tr>
                                <td class="text-start" style="min-width: 100px;">
                                    <span id="span_base_unit_name" class="fw-bold base_unit_name">{{ __("1") }} {{ $productUnit?->baseUnit?->name }}</span>
                                    <input type="hidden" name="base_unit_ids[]" id="base_unit_id" value="{{  $productUnit->base_unit_id }}">
                                    <input type="hidden" name="product_unit_ids[]" id="base_unit_id" value="{{  $productUnit->id }}">
                                </td>

                                <td class="text-start">
                                    <p class="fw-bold">X</p>
                                </td>

                                <td class="text-start">
                                    <input required type="number" step="any" name="assigned_unit_quantities[]" class="form-control fw-bold" id="assigned_unit_quantity" value="{{ $productUnit->assigned_unit_quantity }}" placeholder="{{ __('Quantity') }}">
                                    <input type="hidden" name="base_unit_multiplier" id="base_unit_multiplier" value="{{ $productUnit->base_unit_multiplier }}">
                                </td>

                                <td class="text-start" style="min-width: 127px;">
                                    <div class="row align-items-end">
                                        <div class="col-md-2">
                                            <p class="fw-bold p-1">{{ __("1") }}</p>
                                        </div>
                                        <div class="col-md-10">
                                            <select required name="assigned_unit_ids[]" class="form-control assigned_unit_id select2" id="assigned_unit_id" style="min-width: 110px !important;">
                                                <option data-assigned_unit_name="" value="">{{ __('Unit') }}</option>
                                                @foreach ($units as $unit)
                                                    <option data-assigned_unit_name="{{ $unit->name }}" {{ $productUnit->assigned_unit_id == $unit->id ? 'SELECTED' : '' }} value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-start">
                                    <input required type="number" step="any" name="assigned_unit_costs_exc_tax[]" class="form-control fw-bold" id="assigned_unit_cost_exc_tax" value="{{ $productUnit->unit_cost_exc_tax }}" placeholder="{{ __('0.00') }}">
                                </td>

                                <td class="text-start">
                                    <input required readonly type="number" step="any" name="assigned_unit_costs_inc_tax[]" class="form-control fw-bold" id="assigned_unit_cost_inc_tax" value="{{ $productUnit->unit_cost_inc_tax }}" placeholder="{{ __('0.00') }}">
                                </td>

                                <td class="text-start">
                                    <input required type="number" step="any" name="assigned_unit_prices_exc_tax[]" class="form-control fw-bold" id="assigned_unit_price_exc_tax" value="{{ $productUnit->unit_price_exc_tax }}" placeholder="{{ __('0.00') }}">
                                </td>

                                <td class="text-start">
                                    <a href="#" id="unit_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-start" style="min-width: 100px;">
                                <span id="span_base_unit_name" class="fw-bold base_unit_name">{{ __("1") }} {{ $product?->unit?->name }}</span>
                                <input type="hidden" name="base_unit_ids[]" id="base_unit_id" value="{{ $product->unit_id }}">
                            </td>

                            <td class="text-start">
                                <p class="fw-bold">X</p>
                            </td>

                            <td class="text-start">
                                <input required type="number" step="any" name="assigned_unit_quantities[]" class="form-control fw-bold" id="assigned_unit_quantity" placeholder="{{ __('Quantity') }}">
                                <input type="hidden" name="base_unit_multiplier" id="base_unit_multiplier">
                            </td>

                            <td class="text-start" style="min-width: 127px;">
                                <div class="row align-items-end">
                                    <div class="col-md-2">
                                        <p class="fw-bold p-1">{{ __("1") }}</p>
                                    </div>
                                    <div class="col-md-10">
                                        <select required name="assigned_unit_ids[]" class="form-control assigned_unit_id select2" id="assigned_unit_id" style="min-width: 110px !important;">
                                            <option data-assigned_unit_name="" value="">{{ __('Unit') }}</option>
                                            @foreach ($units as $unit)
                                                <option data-assigned_unit_name="{{ $unit->name }}" value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </td>

                            <td class="text-start">
                                <input required type="number" step="any" name="assigned_unit_costs_exc_tax[]" class="form-control fw-bold" id="assigned_unit_cost_exc_tax" placeholder="{{ __('0.00') }}">
                            </td>

                            <td class="text-start">
                                <input required readonly type="number" step="any" name="assigned_unit_costs_inc_tax[]" class="form-control fw-bold" id="assigned_unit_cost_inc_tax" placeholder="{{ __('0.00') }}">
                            </td>

                            <td class="text-start">
                                <input required type="number" step="any" name="assigned_unit_prices_exc_tax[]" class="form-control fw-bold" id="assigned_unit_price_exc_tax" placeholder="{{ __('0.00') }}">
                            </td>

                            <td class="text-start">
                                <a href="#" id="unit_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
