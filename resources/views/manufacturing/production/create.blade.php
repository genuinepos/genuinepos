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
@section('title', 'Add Production - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name g-0">
                <div class="col-md-7">
                    <div class="name-head">
                        <h6>{{ __('Add Production') }}</h6>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="row g-0">
                        <div class="col-md-10">
                            <div class="input-group">
                                <label class="col-4 offset-md-6"><b>{{ __('Print') }}</b></label>
                                <div class="col-2">
                                    <select id="select_print_page_size" class="form-control">
                                        @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                            <option @selected($generalSettings['print_page_size__bom_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button d-inline"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-1">
            <form id="add_production_form" action="{{ route('manufacturing.productions.store') }}" method="POST">
                <input type="hidden" name="print_page_size" id="print_page_size" value="{{ $generalSettings['print_page_size__bom_voucher_page_size'] }}">
                <input name="action_type" type="text" id="action_type" class="d-hide" value="">
                <input name="product_id" type="text" id="product_id" class="d-hide" value="">
                <input name="variant_id" type="text" id="variant_id" class="d-hide" value="">
                <input name="unit_id" type="text" id="unit_id" class="d-hide" value="">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row gx-2">
                                @if ($generalSettings['subscription']->features['warehouse_count'] > 0)
                                    <div class="col-md-2">
                                        <input type="hidden" name="store_warehouse_count" value="{{ count($warehouses) }}">
                                        <label><b>{{ __('Store Location') }}</b>
                                            @if (count($warehouses) > 0)
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                        <select {{ count($warehouses) > 0 ? 'required' : '' }} class="form-control changeable" name="store_warehouse_id" data-name="Warehouse" id="store_warehouse_id" data-next="date" autofocus>
                                            <option value="">{{ __('Select Warehouse') }}</option>
                                            @foreach ($warehouses as $w)
                                                <option value="{{ $w->id }}">{{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error error_store_warehouse_id"></span>
                                    </div>
                                @endif

                                <div class="col-md-2">
                                    <label><b>{{ __('Voucher No') }}</b></label>
                                    <input readonly type="text" name="voucher_no" class="form-control" placeholder="{{ __('Voucher No') }}" />
                                </div>

                                <div class="col-md-2">
                                    <label><b>{{ __('Date') }}</b></label>
                                    <input type="text" name="date" class="form-control" id="date" data-next="stock_warehouse_id" value="{{ date($generalSettings['business_or_shop__date_format']) }}" autofocus>
                                    <span class="error error_date"></span>
                                </div>

                                @if ($generalSettings['subscription']->features['warehouse_count'] > 0)
                                    <div class="col-md-2">
                                        <label><b>{{ __('Ingredient Stock Location') }}</b></label>
                                        <select class="form-control" name="stock_warehouse_id" data-name="Warehouse" id="stock_warehouse_id" data-next="process_id">
                                            <option value="">{{ __('Select Warehouse') }}</option>
                                            @foreach ($warehouses as $w)
                                                <option value="{{ $w->id }}">{{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error error_warehouse_id"></span>
                                    </div>
                                @endif

                                <div class="col-md-2">
                                    <label><b>{{ __('Product') }} </b> <span class="text-danger">*</span></label>
                                    <select name="process_id" class="form-control" id="process_id" data-next="total_output_quantity">
                                        <option value="">{{ __('Select A Product Process') }}</option>
                                        @foreach ($processes as $process)
                                            @php
                                                $variant_name = $process->variant_name ? $process->variant_name : '';
                                                $product_code = $process->variant_code ? $process->variant_code : $process->product_code;
                                            @endphp
                                            <option data-p_id="{{ $process->product_id }}" data-v_id="{{ $process->variant_id }}" data-tax_ac_id="{{ $process->tax_ac_id }}" data-tax_type="{{ $process->tax_type }}" data-unit_id="{{ $process->unit_id }}" data-total_output_qty="{{ $process->total_output_qty }}" data-addl_production_cost="{{ $process->additional_production_cost }}" data-totol_ingredient_cost="{{ $process->total_ingredient_cost }}" data-net_cost="{{ $process->net_cost }}" value="{{ $process->id }}">
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
                                                <tbody id="ingredient_list"></tbody>
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
                        <input type="text" class="d-hide" name="total_ingredient_cost" id="total_ingredient_cost">
                        <p class="float-end clearfix pe-1"><strong>{{ __('Total Ingredient Cost') }} : </strong> <span id="span_total_ingredient_cost">0.00</span></p>
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
                                                    <input type="number" step="any" data-name="Quantity" class="form-control fw-bold" name="total_output_quantity" id="total_output_quantity" data-next="total_wasted_quantity" value="1.00">
                                                    <input type="text" name="total_parameter_quantity" class="d-hide" id="total_parameter_quantity" value="0.00">
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
                                                    <input type="number" step="any" name="total_wasted_quantity" class="form-control fw-bold" id="total_wasted_quantity" data-next="additional_production_cost" value="0.00">
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
                                                    <input readonly type="text" step="any" class="form-control fw-bold" name="total_final_output_quantity" id="total_final_output_quantity" value="1.00">
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
                                                    <input type="number" step="any" name="additional_production_cost" class="form-control fw-bold" id="additional_production_cost" data-next="tax_ac_id" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Net Production Cost') }}</b></label>
                                                <div class="col-7">
                                                    <input readonly type="number" step="any" name="net_cost" class="form-control fw-bold" id="net_cost" value="0.00">
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
                                                            <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                {{ $taxAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="unit_tax_percent" id="unit_tax_percent" value="0">
                                                    <input type="hidden" name="unit_tax_amount" id="unit_tax_amount" value="0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Tax Type') }}</b> </label>
                                                <div class="col-7">
                                                    <select name="tax_type" class="form-control" id="tax_type" data-next="profit_margin">
                                                        <option value="1">{{ __('Exclusive') }}</option>
                                                        <option value="2">{{ __('Inclusive') }}</option>
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
                                                    <input readonly type="text" name="per_unit_cost_exc_tax" id="per_unit_cost_exc_tax" class="form-control fw-bold" placeholder="{{ __('Per Unit Cost Exc. Tax') }}" autocomplete="off" value="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Per Unit Cost Inc. Tax') }}</b></label>
                                                <div class="col-7">
                                                    <input readonly type="text" name="per_unit_cost_inc_tax" class="form-control fw-bold" id="per_unit_cost_inc_tax" placeholder="{{ __('Per Unit Cost Inc. Tax') }}" autocomplete="off" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Profit Margine') }}(%) </b></label>
                                                <div class="col-7">
                                                    <input type="text" name="profit_margin" id="profit_margin" class="form-control fw-bold" data-next="per_unit_price_exc_tax" placeholder="{{ __('Profit Margine') }}(%)" autocomplete="off" value="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Selling Price Exc. Tax') }}</b></label>
                                                <div class="col-7">
                                                    <input type="text" name="per_unit_price_exc_tax" id="per_unit_price_exc_tax" class="form-control fw-bold" data-next="status" placeholder="{{ __('Selling Price Exc. Tax') }}" autocomplete="off" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12 col-lg-6 offset-lg-6">
                                            <div class="input-group">
                                                <label class="col-5"><b>{{ __('Status') }}</b></label>
                                                <select name="status" class="form-control" id="status" data-next="save_and_print">
                                                    <option value="0">{{ __('Hold') }}</option>
                                                    <option value="1">{{ __('Final') }}</option>
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
                                            <button type="button" value="save_and_print" id="save_and_print" class="btn btn-sm btn-success submit_button">{{ __('Save & Print') }}</button>
                                            <button type="button" value="save" id="save" class="btn btn-sm btn-success submit_button">{{ __('Save') }}</button>
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
    @include('manufacturing.production.js_partials.add_js')
@endpush
