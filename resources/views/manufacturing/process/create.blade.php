@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area { position: relative; background: #ffffff; box-sizing: border-box; position: absolute; width: 100%; z-index: 9999999; padding: 0; left: 0%; display: none; border: 1px solid var(--main-color); margin-top: 1px; border-radius: 0px;}

        .select_area ul { list-style: none; margin-bottom: 0; padding: 4px 4px; }

        .select_area ul li a { color: #000000; text-decoration: none; font-size: 10px; padding: 2px 2px; display: block; border: 1px solid gray; }

        .select_area ul li a:hover { background-color: #999396; color: #fff; }

        .selectProduct{background-color: #746e70; color: #fff!important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        table.display td input {height: 26px!important; padding: 3px;}
        tbody#ingredient_list td { line-height: 1;}
    </style>
@endpush
@section('title', 'Create Process - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __("Create Process/BOM") }}</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>
        <div class="p-1">
            <form id="add_process_form" action="{{ route('manufacturing.process.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['product_id'] }}">
                <input type="hidden" name="variant_id" value="{{ $product['variant_id'] ? $product['variant_id'] : 'noid' }}">
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-12">
                                    @php
                                        $productCode = $product['variant_code'] ? $product['variant_code'] : $product['product_code'];
                                    @endphp
                                    <p> <strong>{{ __("Product") }} : </strong> {{ $product['product_name'].' '.$product['variant_name'].' ('.$productCode.')' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-1">
                                    <div class="card-body p-2">
                                        <div class="row mb-2 align-items-end">
                                            <div class="col-md-3">
                                                <input type="hidden" id="e_unique_id">
                                                <input type="hidden" id="e_item_name">
                                                <input type="hidden" id="e_product_id">
                                                <input type="hidden" id="e_variant_id">
                                                <input type="hidden" id="e_tax_amount">
                                                <input type="hidden" id="e_unit_cost_inc_tax">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">{{ __("Search Ingredient") }}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode text-dark"></i></span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="{{ __("Search Ingredient By Name / Code") }}" autocomplete="off" autofocus>
                                                    </div>
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label class="fw-bold">{{ __('Quantity') }}</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control w-60 fw-bold" id="e_final_quantity" value="0.00" placeholder="0.00" autocomplete="off">
                                                    <select id="e_unit_id" class="form-control w-40">
                                                        <option value="">{{ __('Unit') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label class="fw-bold">{{ __('Unit Cost (Exc. Tax)') }}</label>
                                                <input type="number" step="any" class="form-control fw-bold" id="e_unit_cost_exc_tax" value="0.00" placeholder="0.00" autocomplete="off">
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label class="fw-bold">{{ __('Vat/Tax') }}</label>
                                                <div class="input-group">
                                                    <select id="e_tax_ac_id" class="form-control w-50">
                                                        <option data-product_tax_percent="0.00" value="">{{ __('NoVat/Tax') }}</option>
                                                        @foreach ($taxAccounts as $taxAccount)
                                                            <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                {{ $taxAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <select id="e_unit_tax_type" class="form-control w-50">
                                                        <option value="1">{{ __('Exclusive') }}</option>
                                                        <option value="2">{{ __('Inclusive') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label class="fw-bold">{{ __("Subtotal") }}</label>
                                                <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" placeholder="0.00" tabindex="-1">
                                            </div>

                                            <div class="col-xl-1 col-md-4">
                                                <a href="#" class="btn btn-sm btn-success" id="add_item">{{ __("Add") }}</a>
                                            </div>
                                        </div>

                                        <div class="sale-item-sec">
                                            <div class="sale-item-inner">
                                                <div class="table-responsive">
                                                    <table class="display data__table table-striped">
                                                        <thead class="staky">
                                                            <tr>
                                                                <th>{{ __("Ingredient") }}</th>
                                                                <th>{{ __("Final Quantity") }}</th>
                                                                <th>{{ __("Unit Cost Exc. Tax") }}</th>
                                                                <th>{{ __("Vat/Tax") }}</th>
                                                                <th>{{ __("Unit Cost Inc. Tax") }}</th>
                                                                <th>{{ __("Subtotal") }}</th>
                                                                <th><i class="fas fa-trash-alt"></i></th>
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
                        </div>
                    </div>
                </section>

                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="total_ingredient_cost" id="total_ingredient_cost">
                        <p class="mt-1 float-end clearfix"><strong>{{  __('Total Ingrediant Cost') }} : </strong> <span id="span_total_ingredient_cost">0.00</span></p>
                    </div>
                </div>

                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label><b>{{ __("Total Output Qty") }}: <span class="text-danger">*</span></b></label>
                                    <div class="row">
                                        <div class="input-group">
                                            <input required type="number" step="any" name="total_output_qty" class="form-control fw-bold" autocomplete="off" id="total_output_qty" value="1.00" data-next="additional_production_cost" placeholder="{{ __("Total Output Qty") }}">
                                            <input readonly type="text" class="form-control fw-bold" value="{{ $product['unit_name'] }}">
                                            <input type="hidden" name="unit_id" class="form-control" id="unit_id" value="{{ $product['unit_id'] }}">
                                            <span class="error error_total_output_qty"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label><b>{{ __('Additional Production Cost') }}</b></label>
                                    <input type="number" step="any" name="additional_production_cost" class="form-control fw-bold" autocomplete="off" id="additional_production_cost" data-next="production_instruction" placeholder="{{ __('Additional Production Cost') }}" value="0">
                                </div>

                                <div class="col-md-3">
                                    <label><b>{{ __("Net Cost") }} <span class="text-danger">*</span></b></label>
                                    <input readonly required type="number" step="any" name="net_cost" class="form-control fw-bold" id="net_cost" autocomplete="off" placeholder="{{ __("Net Cost") }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label><b>{{ __("Prodution Instruction") }} </b></label>
                                    <input type="number" step="any" name="production_instruction" class="form-control" id="production_instruction" data-next="save" placeholder="{{ __("Prodution Instruction") }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                                <button type="button" id="save" class="btn px-3 btn-success submit_button">{{ __("Save") }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('manufacturing.process.js_partials.add_js')
@endpush
