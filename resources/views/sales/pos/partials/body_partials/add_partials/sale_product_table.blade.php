<style>
    .set-height {
        position: relative;
        height: {{ $saleScreenType == \App\Enums\SaleScreenType::ServicePosSale->value ? '442px!important' : '350px' }};
    }

    .tagify--focus {
        height: auto !important;
    }

    tags.tagify {
        min-width: 100%;
    }

    .tagify__input {
        min-width: 100%;
    }

    span.tagify__tag-text {
        font-size: 9px;
    }

    .tagify__input {
        display: inline-block;
        min-width: 110px;
        margin: 8px 2px;
        padding: var(--tag-pad);
        line-height: 5px;
        position: relative;
        white-space: pre-wrap;
        color: var(--input-color);
        box-sizing: border-box;
        overflow: hidden;
    }
</style>

@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="set-height">
    @if ($saleScreenType == \App\Enums\SaleScreenType::ServicePosSale->value)
        <div class="form-field-area px-2 py-1">
            <div class="row">
                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Delivery Date') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-calendar-week input_i"></i>
                            </span>
                        </div>

                        <input type="text" name="delivery_date" class="form-control" id="delivery_date" value="{{ isset($jobCard) && isset($jobCard->delivery_date_ts) ? date($dateFormat, strtotime($jobCard->delivery_date_ts)) : '' }}" placeholder="{{ __('Delivery Date') }}" autocomplete="off">

                        <div class="input-group-prepend">
                            <span class="input-group-text add_button" id="date_clear" data-clear_date_id="delivery_date">
                                <i class="fa-regular fa-circle-xmark text-danger fw-bold input_i"></i>
                            </span>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Service Completed On') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-calendar-week input_i"></i>
                            </span>
                        </div>

                        <input type="text" name="service_complete_date" class="form-control" id="service_complete_date" placeholder="{{ __('Completed On') }}" autocomplete="off">

                        <div class="input-group-prepend">
                            <span class="input-group-text add_button" id="date_clear" data-clear_date_id="service_complete_date">
                                <i class="fa-regular fa-circle-xmark text-danger fw-bold input_i"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Status') }}</label>
                    <select name="status_id" class="form-control" id="status_id">
                        <option value="">{{ __('Select Status') }}</option>
                        @foreach ($status as $status)
                            @php
                                $defaultStatus = isset($generalSettings['service_settings__default_status_id']) ? $generalSettings['service_settings__default_status_id'] : null;
                                $jobCardStatus = isset($jobCard) ? $jobCard->status_id : $defaultStatus;
                            @endphp
                            <option @selected($jobCardStatus == $status->id) value="{{ $status->id }}" data-icon="fa-solid fa-circle" data-color="{{ $status->color_code }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Brand.') }}</label>
                    <select name="brand_id" class="form-control" id="brand_id">
                        <option value="">{{ __('Select Brand') }}</option>
                        @foreach ($brands as $brand)
                            <option @selected(isset($jobCard) && $jobCard?->brand_id == $brand->id) value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Device') }}</label>
                    <select name="device_id" class="form-control" id="device_id">
                        <option value="">{{ __('Select Device') }}</option>
                        @foreach ($devices as $device)
                            <option @selected(isset($jobCard) && $jobCard?->device_id == $device->id) value="{{ $device->id }}">{{ $device->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Device Model') }}</label>
                    <select name="device_model_id" class="form-control" id="device_model_id">
                        <option value="">{{ __('Select Device Model') }}</option>
                        @foreach ($deviceModels as $deviceModel)
                            <option @selected(isset($jobCard) && $jobCard?->device_model_id == $deviceModel->id) data-checklist="{{ $deviceModel->service_checklist }}" value="{{ $deviceModel->id }}">{{ $deviceModel->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row align-items-end mt-1">
                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Serial No') }}</label>
                    <input type="text" name="serial_no" class="form-control" id="serial_no" value="{{ isset($jobCard) ? $jobCard->serial_no : '' }}" placeholder="{{ __('Serial No') }}" autocomplete="off">
                </div>

                <div class="col-md-4">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#serviceChecklistModal" class="btn btn-sm btn-primary">{{ __('Servicing Checklist') }}</button>
                </div>
            </div>

            <div class="row align-items-end mt-1">
                <div class="col-md-12">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Problem Reported By The Customer') }}</label>
                    <input name="problems_report" id="problems_report" value="{{ isset($jobCard) ? $jobCard->problems_report : '' }}">
                </div>
            </div>
        </div>
    @endif

    <div class="data_preloader submit_preloader">
        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table data__table modal-table table-sm sale-product-table">
            <thead>
                <tr>
                    <th style="font-size: 10px;">{{ __('S/L') }}</th>
                    <th style="font-size: 10px;">{{ __('Product') }}</th>
                    <th style="font-size: 10px;">{{ __('Qty/Weight') }}</th>
                    <th style="font-size: 10px;">{{ __('Unit') }}</th>
                    <th style="font-size: 10px;">{{ __('Price Inc. Tax') }}</th>
                    <th style="font-size: 10px;">{{ __('Subtotal') }}</th>
                    <th style="font-size: 10px;" class="text-start"><i class="fas fa-trash-alt"></i></th>
                </tr>
            </thead>

            <tbody id="product_list">
                @if (isset($jobCard))
                    @php
                        $itemUnitsArray = [];
                    @endphp

                    @foreach ($jobCard->jobCardProducts as $jobCardProduct)
                        @php
                            if (isset($jobCardProduct->product_id)) {
                                $itemUnitsArray[$jobCardProduct->product_id][] = [
                                    'unit_id' => $jobCardProduct->product->unit->id,
                                    'unit_name' => $jobCardProduct->product->unit->name,
                                    'unit_code_name' => $jobCardProduct->product->unit->code_name,
                                    'base_unit_multiplier' => 1,
                                    'multiplier_details' => '',
                                    'is_base_unit' => 1,
                                ];
                            }
                        @endphp

                        <tr class="product_row">
                            <td class="fw-bold" id="serial">1</td>
                            <td class="text-start">
                                @php
                                    $variant = $jobCardProduct->variant_id ? ' -' . $jobCardProduct->variant->variant_name : '';
                                    $variantId = $jobCardProduct->variant_id ? $jobCardProduct->variant_id : 'noid';

                                    $productStock = DB::table('product_stocks')
                                        ->where('branch_id', $jobCard->branch_id)
                                        ->where('warehouse_id', null)
                                        ->where('product_id', $jobCardProduct->product_id)
                                        ->where('variant_id', $jobCardProduct->variant_id)
                                        ->first();

                                    $currentStock = $productStock ? $productStock->stock : 0;

                                    $__currentStock = $jobCardProduct?->product?->is_manage_stock == 0 ? PHP_INT_MAX : $currentStock;

                                    $baseUnitMultiplier = $jobCardProduct?->unit?->base_unit_multiplier ? $jobCardProduct?->unit?->base_unit_multiplier : 1;

                                    $name = strlen($jobCardProduct?->product?->name) > 35 ? Str::limit($jobCardProduct?->product?->name, 35, '...') : $jobCardProduct?->product?->name;
                                    $__name = $name . $variant;
                                @endphp

                                <a href="#" onclick="editProduct(this); return false;" id="edit_product_link" tabindex="-1">{{ $__name }}</a><br />
                                <span><small id="span_description" style="font-size:9px;"></small></span>
                                <input type="hidden" id="is_show_emi_on_pos" value="{{ $jobCardProduct?->product?->is_show_emi_on_pos }}">
                                <input type="hidden" name="descriptions[]" id="description" value="">

                                <input type="hidden" id="product_name" value="{{ $__name }}">
                                <input type="hidden" name="product_ids[]" id="product_id" value="{{ $jobCardProduct->product_id }}">
                                <input type="hidden" id="variant_id" name="variant_ids[]" value="{{ $variantId }}">
                                <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $jobCardProduct->tax_type }}">
                                <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $jobCardProduct->tax_ac_id }}">
                                <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $jobCardProduct->unit_tax_percent }}">
                                <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $jobCardProduct->unit_tax_amount }}">
                                <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $jobCardProduct->unit_discount_type }}">
                                <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $jobCardProduct->unit_discount }}">
                                <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $jobCardProduct->unit_discount_amount }}">
                                <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $jobCardProduct->unit_cost_inc_tax }}">
                                <input type="hidden" id="current_stock" data-product_name="{{ $__name }}" data-unit_name="{{ $jobCardProduct?->unit?->name }}" value="{{ $__currentStock }}">
                                <input type="hidden" class="unique_id" id="{{ $jobCardProduct->product_id . $variantId }}" value="{{ $jobCardProduct->product_id . $variantId }}">
                            </td>

                            <td class="text-start">
                                <span id="span_quantity" class="fw-bold">{{ $jobCardProduct->quantity }}</span>
                                <input type="hidden" name="quantities[]" id="quantity" value="{{ $jobCardProduct->quantity }}">
                            </td>

                            <td class="text-start">
                                <span id="span_unit">{{ $jobCardProduct?->unit?->name }}</span>
                                <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $jobCardProduct?->unit?->id }}">
                            </td>

                            <td class="text-start">
                                <input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="{{ $jobCardProduct->unit_price_exc_tax }}">
                                <input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="{{ $jobCardProduct->unit_price_inc_tax }}">
                                <span id="span_unit_price_inc_tax" class="fw-bold">{{ $jobCardProduct->unit_price_inc_tax }}</span>
                            </td>

                            <td class="text-start">
                                <span class="fw-bold" id="span_subtotal">{{ $jobCardProduct->subtotal }}</span>
                                <input type="hidden" value="{{ $jobCardProduct->subtotal }}" readonly name="subtotals[]" id="subtotal">
                            </td>

                            <td class="text-start"><a href="#" class="action-btn c-delete" id="remove_product_btn" tabindex="-1"><span class="fas fa-trash"></span></a></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
