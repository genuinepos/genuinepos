@extends('layout.master')
@push('stylesheets')
    <style>
        table.display td input {
            height: 26px !important;
            padding: 3px;
        }

        span.input-group-text-custom {
            font-size: 11px;
            padding: 4px;
        }

        label.col-2,
        label.col-3,
        label.col-4,
        label.col-5,
        label.col-6 {
            text-align: right;
            padding-right: 10px;
        }

        .checkbox_input_wrap {
            text-align: right;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Edit Production - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Edit Production') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>
        <div class="p-1">
            <form id="edit_production_form" action="{{ route('manufacturing.productions.update', $production) }}" method="POST">
                <input name="product_id" type="text" id="product_id" class="d-hide" value="{{ $production->product_id }}">
                <input name="variant_id" type="text" id="variant_id" class="d-hide" value="{{ $production->variant_id ? $production->variant_id : 'noid' }}">
                <input name="unit_id" type="text" id="unit_id" class="d-hide" value="{{ $production->unit_id }}">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row gx-2">
                                @if ($generalSettings['subscription']->features['warehouse_count'] > 0)
                                    <div class="col-md-2">
                                        <input type="hidden" name="store_warehouse_count" value="{{ $production->store_warehouses_id ? 1 : 0 }}">
                                        <label><b>{{ __('Store Location') }}</b>
                                            @if ($production->store_warehouses_id)
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                        <select {{ $production->store_warehouses_id ? 'required' : '' }} class="form-control changeable" name="store_warehouse_id" data-name="Warehouse" id="store_warehouse_id" data-next="date" autofocus>
                                            <option value="">{{ __('Select Warehouse') }}</option>
                                            @foreach ($warehouses as $w)
                                                <option {{ $production->store_warehouse_id == $w->id ? 'SELECTED' : '' }} value="{{ $w->id }}">{{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error error_store_warehouse_id"></span>
                                    </div>
                                @endif

                                <div class="col-md-2">
                                    <label><b>{{ __('Voucher No') }}</b></label>
                                    <input readonly type="text" name="voucher_no" class="form-control fw-bold" value="{{ $production->voucher_no }}" placeholder="{{ __('Voucher No') }}" />
                                </div>

                                <div class="col-md-2">
                                    <label><b>{{ __('Date') }}</b></label>
                                    <input type="text" name="date" class="form-control" id="date" data-next="stock_warehouse_id" value="{{ date($generalSettings['business_or_shop__date_format'], strtotime($production->date)) }}" autocomplete="off" autofocus>
                                    <span class="error error_date"></span>
                                </div>

                                @if ($generalSettings['subscription']->features['warehouse_count'] > 0)
                                    <div class="col-md-2">
                                        <label> <b>{{ __('Ingredient Stock Location') }} </b></label>
                                        <select class="form-control" name="stock_warehouse_id" data-name="Warehouse" id="stock_warehouse_id" data-next="process_id">
                                            <option value="">{{ __('Select Warehouse') }}</option>
                                            @foreach ($warehouses as $w)
                                                <option {{ $w->id == $production->stock_warehouse_id ? 'SELECTED' : '' }} value="{{ $w->id }}">{{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error error_warehouse_id"></span>
                                    </div>
                                @endif

                                <div class="col-md-2">
                                    <label><b>{{ __('Product') }}</b> <span class="text-danger">*</span></label>
                                    <select name="process_id" class="form-control" id="process_id" data-next="total_output_quantity">
                                        <option value="">{{ __('Select A Product Process') }}</option>
                                        @foreach ($processes as $process)
                                            @php
                                                $variant_name = $process->variant_name ? $process->variant_name : '';
                                                $product_code = $process->variant_code ? $process->variant_code : $process->product_code;
                                            @endphp
                                            <option {{ $production->process_id == $process->id ? 'SELECTED' : '' }} data-p_id="{{ $process->product_id }}" data-v_id="{{ $process->variant_id }}" data-tax_ac_id="{{ $process->tax_ac_id }}" data-tax_type="{{ $process->tax_type }}" data-unit_id="{{ $process->unit_id }}" data-total_output_qty="{{ $process->total_output_qty }}" data-addl_production_cost="{{ $process->additional_production_cost }}" data-totol_ingredient_cost="{{ $process->total_ingredient_cost }}" data-net_cost="{{ $process->net_cost }}" value="{{ $process->id }}">
                                                {{ $process->product_name . ' ' . $variant_name . ' (' . $product_code . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error error_process_id"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content mb-1">
                        <div class="card p-1">
                            <div class="row">
                                <div class="sale-item-sec">
                                    <div class="sale-item-inner">
                                        <div class="table-responsive">
                                            <table class="display data__table table-striped">
                                                <thead class="staky">
                                                    <tr>
                                                        <th>{{ __('Ingredient') }}</th>
                                                        <th>{{ __('Stock Location') }}</th>
                                                        <th>{{ __('Input Quantity') }}</th>
                                                        <th>{{ __('Unit Cost Inc. Tax') }}</th>
                                                        <th>{{ __('Subtotal') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="ingredient_list">
                                                    @foreach ($production->ingredients as $ingredient)
                                                        @php
                                                            $currentStock = DB::table('product_stocks')
                                                                ->where('product_id', $ingredient->product_id)
                                                                ->where('variant_id', $ingredient->variant_id)
                                                                ->where('branch_id', $production->branch_id)
                                                                ->where('warehouse_id', $production->stock_warehouse_id)
                                                                ->first(['stock']);
                                                        @endphp
                                                        <tr class="text-start">
                                                            <td>
                                                                <span class="product_name">{{ $ingredient?->product?->name }}</span><br>
                                                                <span class="product_variant">{{ $ingredient->variant?->variant_name }}</span>
                                                                <input name="product_ids[]" type="hidden" class="productId-{{ $ingredient->product_id }}" id="product_id" value="{{ $ingredient->product_id }}">
                                                                <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $ingredient->variant_id ? $ingredient->variant_id : 'noid' }}">
                                                                <input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="{{ $ingredient->unit_id }}">
                                                                <input type="hidden" name="production_ingredient_ids[]" value="{{ $ingredient->id }}">
                                                                <input type="hidden" step="any" id="current_qty" value="{{ $ingredient->final_qty }}">
                                                                <input type="hidden" step="any" data-unit="{{ $ingredient?->unit?->name }}" id="qty_limit" value="{{ $currentStock->stock }}">
                                                            </td>

                                                            <td>
                                                                @if ($production->stockWarehouse)
                                                                    {{ $production->stockWarehouse->warehouse_name . '-(' . $production->stockWarehouse->warehouse_code . ')-WH' }}
                                                                @else
                                                                    @if ($production?->branch)
                                                                        @if ($production?->branch?->parentBranch)
                                                                            {{ $production?->branch?->parentBranch?->name . '-(' . $production?->branch?->area_name . ')-' . $production?->branch?->branch_code }}
                                                                        @else
                                                                            {{ $production?->branch?->name . '-(' . $production?->branch?->area_name . ')-' . $production?->branch?->branch_code }}
                                                                        @endif
                                                                    @else
                                                                        {{ $branchName = $generalSettings['business_or_shop__business_name'] }}
                                                                    @endif
                                                                @endif
                                                                <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{ $production->stock_warehouse_id }}">
                                                            </td>

                                                            <td>
                                                                <div class="input-group p-1">
                                                                    <input required type="number" step="any" name="input_quantities[]" class="form-control fw-bold" id="input_quantity" value="{{ $ingredient->final_qty }}">
                                                                    <input type="hidden" name="parameter_input_quantities[]" id="parameter_input_quantity" value="{{ $ingredient->final_qty }}">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text input-group-text-custom">{{ $ingredient?->unit?->name }}</span>
                                                                    </div>
                                                                    &nbsp;<strong>
                                                                        <p class="text-danger m-0 p-0" id="input_qty_error"></p>
                                                                    </strong>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <input required type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $ingredient->unit_cost_exc_tax }}">
                                                                <input required type="hidden" name="tax_ac_ids[]" value="{{ $ingredient->tax_ac_id }}">
                                                                <input required type="hidden" name="unit_tax_types[]" value="{{ $ingredient->unit_tax_type }}">
                                                                <input required type="hidden" name="unit_tax_percents[]" value="{{ $ingredient->unit_tax_percent }}">
                                                                <input required type="hidden" name="unit_tax_amounts[]" value="{{ $ingredient->unit_tax_amount }}">
                                                                <input required type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $ingredient->unit_cost_inc_tax }}">
                                                                <span id="span_unit_cost_inc_tax" class="fw-bold">{{ $ingredient->unit_cost_inc_tax }}</span>
                                                            </td>

                                                            <td>
                                                                <input value="{{ $ingredient->subtotal }}" type="hidden" step="any" name="subtotals[]" id="subtotal">
                                                                <span id="span_subtotal" class="fw-bold">{{ $ingredient->subtotal }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row mb-1">
                    <div class="col-md-12">
                        <input type="text" class="d-hide" name="total_ingredient_cost" id="total_ingredient_cost" value="{{ $production->total_ingredient_cost }}">
                        <p class="float-end clearfix pe-1"><strong>{{ __('Total Ingredient Cost') }} : </strong> <span id="span_total_ingredient_cost">{{ $production->total_ingredient_cost }}</span></p>
                    </div>
                </div>

                <section class="last_section mb-1">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <p><strong>{{ __('Total Production Costing') }}</strong></p>
                                    <hr class="p-0 m-0 mb-1">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Output Quantity') }}</b></label>
                                                <div class="col-7">
                                                    <input type="number" step="any" data-name="Quantity" class="form-control fw-bold" name="total_output_quantity" id="total_output_quantity" data-next="total_wasted_quantity" value="{{ $production->total_output_quantity }}">
                                                    <input type="text" name="total_parameter_quantity" class="d-hide" id="total_parameter_quantity" value="{{ $production->total_parameter_quantity }}">
                                                    <span class="error error_output_quantity"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Wasted Quantity') }} </b></label>
                                                <div class="col-7">
                                                    <input type="number" step="any" name="total_wasted_quantity" class="form-control fw-bold" id="total_wasted_quantity" data-next="additional_production_cost" value="{{ $production->total_wasted_quantity }}">
                                                    <span class="error error_wasted_quantity"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Final Output Quantity') }}</b></label>
                                                <div class="col-7">
                                                    <input readonly type="text" step="any" class="form-control fw-bold" name="total_final_output_quantity" id="total_final_output_quantity" value="{{ $production->total_final_output_quantity }}">
                                                    <span class="error error_final_output_quantity"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Additional Production Cost') }}</b></label>
                                                <div class="col-7">
                                                    <input type="number" step="any" name="additional_production_cost" class="form-control fw-bold" id="additional_production_cost" data-next="tax_ac_id" value="{{ $production->additional_production_cost }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Net Production Cost') }}</b></label>
                                                <div class="col-7">
                                                    <input readonly type="number" step="any" name="net_cost" class="form-control fw-bold" id="net_cost" value="{{ $production->net_cost }}">
                                                    <span class="error error_net_cost"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <p><strong>{{ __('Pricing') }}</strong></p>
                                    <hr class="p-0 m-0 mb-1">
                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Vat/Tax') }}</b> </label>
                                                <div class="col-7">
                                                    <select name="tax_ac_id" id="tax_ac_id" class="form-control" data-next="tax_type">
                                                        <option data-product_tax_percent="0.00" value="">{{ __('NoVat/Tax') }}</option>
                                                        @foreach ($taxAccounts as $taxAccount)
                                                            <option {{ $production->tax_ac_id == $taxAccount->id ? 'SELECTED' : '' }} data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                {{ $taxAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="unit_tax_percent" id="unit_tax_percent" value="{{ $production->unit_tax_percent }}">
                                                    <input type="hidden" name="unit_tax_amount" id="unit_tax_amount" value="{{ $production->unit_tax_amount }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Tax Type') }}</b> </label>
                                                <div class="col-7">
                                                    <select name="tax_type" class="form-control" id="tax_type" data-next="profit_margin">
                                                        <option value="1">{{ __('Exclusive') }}</option>
                                                        <option {{ $production->tax_type == '2' ? 'SELECTED' : '' }} value="2">{{ __('Inclusive') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Per Unit Cost Exc. Tax') }}</b></label>
                                                <div class="col-7">
                                                    <input readonly type="text" name="per_unit_cost_exc_tax" id="per_unit_cost_exc_tax" class="form-control fw-bold" placeholder="{{ __('Per Unit Cost Exc. Tax') }}" autocomplete="off" value="{{ $production->per_unit_cost_exc_tax }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Per Unit Cost Inc. Tax') }}</b></label>
                                                <div class="col-7">
                                                    <input readonly type="text" name="per_unit_cost_inc_tax" class="form-control fw-bold" id="per_unit_cost_inc_tax" placeholder="{{ __('Per Unit Cost Inc. Tax') }}" autocomplete="off" value="{{ $production->per_unit_cost_inc_tax }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Profit Margine') }}(%) </b></label>
                                                <div class="col-7">
                                                    <input type="text" name="profit_margin" id="profit_margin" class="form-control fw-bold" data-next="per_unit_price_exc_tax" value="{{ $production->profit_margin }}" placeholder="{{ __('Profit Margine') }}(%)" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Selling Price Exc. Tax') }}</b></label>
                                                <div class="col-7">
                                                    <input type="text" name="per_unit_price_exc_tax" id="per_unit_price_exc_tax" class="form-control fw-bold" data-next="status" value="{{ $production->per_unit_price_exc_tax }}" placeholder="{{ __('Selling Price Exc. Tax') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12 col-lg-6 offset-lg-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Status') }}</b></label>
                                                <select name="status" class="form-control" id="status" data-next="save_and_print">
                                                    @if ($production->status == \App\Enums\ProductionStatus::Hold->value)
                                                        <option value="0">{{ __('Hold') }}</option>
                                                        <option value="1">{{ __('Final') }}</option>
                                                    @else
                                                        <option value="1">{{ __('Final') }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="submit_button_area">
                                <div class="row mt-2">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="btn-loading">
                                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                            <button type="button" id="save" class="btn btn-sm btn-success submit_button">{{ __('Save Changes') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('manufacturing.production.js_partials.edit_js')
@endpush
