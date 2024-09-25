@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            /* width: 100%; */
            width: 173%;
            z-index: 9999999;
            padding: 0;
            left: 0%;
            display: none;
            border: 1px solid var(--main-color);
            margin-top: 1px;
            border-radius: 0px;
        }

        .select_area ul {
            list-style: none;
            margin-bottom: 0;
            padding: 4px 4px;
        }

        .select_area ul li a {
            color: #000000;
            text-decoration: none;
            font-size: 10px;
            padding: 2px 2px;
            display: block;
            border: 1px solid gray;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selectProduct {
            background-color: #746e70;
            color: #fff !important;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        h6.collapse_table:hover {
            background: lightgray;
            padding: 3px;
            cursor: pointer;
        }

        .c-delete:focus {
            border: 1px solid gray;
            padding: 2px;
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

        .big_amount_field {
            height: 36px;
            font-size: 24px !important;
            margin-bottom: 3px;
        }
    </style>
@endpush
@section('title', 'Edit Stock Issue - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-7">
                    <div class="name-head">
                        <h6>{{ __('Edit Stock Issue') }}</h6>
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
                                            <option {{ $generalSettings['print_page_size__purchase_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
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
            <form id="edit_stock_issue_form" action="{{ route('stock.issues.update', $stockIssue->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-2">
                        <div class="element-body">
                            <div class="row gx-2">
                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Date') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input required type="text" name="date" class="form-control" id="date" value="{{ date($generalSettings['business_or_shop__date_format'], strtotime($stockIssue->date_ts)) }}" data-next="department_id" placeholder="dd-mm-yyyy" autofocus autocomplete="off">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>
                                </div>

                                @if ($generalSettings['subscription']->features['hrm'] == 1)
                                    <div class="col-lg-3 col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Department') }}</b></label>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <div class="input-group flex-nowrap">
                                                        <select name="department_id" class="form-control select2" id="department_id" data-next="reported_by_id">
                                                            <option value="">{{ __('None') }}</option>
                                                            @foreach ($departments as $department)
                                                                <option @selected($stockIssue->department_id == $department->id) value="{{ $department->id }}">{{ $department->name }}</option>
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text {{ !auth()->user()->can('departments_create') ? 'disabled_element' : '' }} add_button" id="{{ auth()->user()->can('departments_create') ? 'addDepartment' : '' }}"><i class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Reported By') }}</b></label>
                                        <div class="col-8">
                                            <select name="reported_by_id" class="form-control select2" id="reported_by_id" data-next="search_product">
                                                <option value="">{{ __('None') }}</option>
                                                @foreach ($users as $user)
                                                    <option @selected($stockIssue->reported_by_id == $user->id) value="{{ $user->id }}">
                                                        {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card ps-1 pb-1 pe-1">
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <div class="row align-items-end p-1">
                                    <input type="hidden" id="e_unique_id">
                                    <input type="hidden" id="e_item_name">
                                    <input type="hidden" id="e_product_id">
                                    <input type="hidden" id="e_variant_id">
                                    <input type="hidden" id="e_current_quantity" value="0">
                                    <input type="hidden" id="e_current_warehouse_id">

                                    <div class="col-xl-3 col-md-4">
                                        <div class="searching_area" style="position: relative;">
                                            <label class="fw-bold">{{ __('Search Product') }}</label>
                                            <div class="input-group">
                                                <input type="text" name="search_product" class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="{{ __('Search Product By Name/Code') }}">
                                            </div>

                                            <div class="select_area">
                                                <ul id="list" class="variant_list_area"></ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Quantity') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_quantity" value="0.00" placeholder="0.00" autocomplete="off">
                                            <select id="e_unit_id" class="form-control w-40">
                                                <option value="">{{ __('Unit') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Unit Cost (Inc. Tax)') }}</label>
                                        <input type="number" step="any" class="form-control fw-bold" id="e_unit_cost_inc_tax" value="0.00" placeholder="0.00" autocomplete="off">
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label class="fw-bold">{{ __('Stock Location') }}</label>
                                        <select class="form-control" id="e_warehouse_id">
                                            <option value="">{{ $branchName }}</option>
                                            @if ($generalSettings['subscription']->features['warehouse_count'] > 0)
                                                @foreach ($warehouses as $w)
                                                    @php
                                                        $isGlobal = $w->is_global == 1 ? ' (' . __('Global Access') . ')' : '';
                                                    @endphp
                                                    <option data-w_name="{{ $w->warehouse_name . '/' . $w->warehouse_code . $isGlobal }}" value="{{ $w->id }}">{{ $w->warehouse_name . '/' . $w->warehouse_code . $isGlobal }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Subtotal') }}</label>
                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" placeholder="0.00" tabindex="-1">
                                    </div>

                                    <div class="col-xl-1 col-md-4">
                                        <a href="#" class="btn btn-sm btn-success me-2" id="add_item">{{ __('Add') }}</a>
                                        <a href="#" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger"><i class="fas fa-redo-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="sale-item-sec">
                                <div class="sale-item-inner">
                                    <div class="table-responsive">
                                        <table class="display data__table table sale-product-table">
                                            <thead class="staky">
                                                <tr>
                                                    <th class="text-start">{{ __('Product') }}</th>
                                                    <th class="text-start">{{ __('Stock Location') }}</th>
                                                    <th class="text-start">{{ __('Quantity') }}</th>
                                                    <th class="text-start">{{ __('Unit') }}</th>
                                                    <th class="text-start">{{ __('Unit Cost(Inc. Tax)') }}</th>
                                                    <th class="text-start">{{ __('Subtotal') }}</th>
                                                    <th class="text-start"><i class="fas fa-trash-alt"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="stock_issue_product_list">
                                                @php
                                                    $itemUnitsArray = [];
                                                @endphp

                                                @foreach ($stockIssue->stockIssuedProducts as $issuedProduct)
                                                    @php
                                                        if (isset($issuedProduct->product_id)) {
                                                            $itemUnitsArray[$issuedProduct->product_id][] = [
                                                                'unit_id' => $issuedProduct->product->unit->id,
                                                                'unit_name' => $issuedProduct->product->unit->name,
                                                                'unit_code_name' => $issuedProduct->product->unit->code_name,
                                                                'base_unit_multiplier' => 1,
                                                                'multiplier_details' => '',
                                                                'is_base_unit' => 1,
                                                            ];
                                                        }
                                                    @endphp

                                                    <tr id="select_item">
                                                        <td class="text-start">
                                                            @php
                                                                $variant = $issuedProduct->variant_id ? ' -' . $issuedProduct->variant->variant_name : '';

                                                                $variantId = $issuedProduct->variant_id ? $issuedProduct->variant_id : 'noid';

                                                                $productCode = $issuedProduct?->variant ? $issuedProduct?->variant?->variant_code : $issuedProduct->product->product_code;

                                                                $baseUnitMultiplier = $issuedProduct?->unit?->base_unit_multiplier ? $issuedProduct?->unit?->base_unit_multiplier : 1;
                                                            @endphp

                                                            <span class="product_name">{{ $issuedProduct->product->name . $variant . ' (' . $productCode . ')' }}</span>
                                                            <input type="hidden" id="item_name" value="{{ $issuedProduct->product->name . $variant . ' (' . $productCode . ')' }}">
                                                            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $issuedProduct->product_id }}">
                                                            <input type="hidden" name="variant_ids[]" value="{{ $variantId }}" id="variant_id">
                                                            <input type="hidden" name="stock_issue_product_ids[]" value="{{ $issuedProduct->id }}">
                                                            <input type="hidden" id="current_quantity" value="{{ $issuedProduct->quantity }}">
                                                            <input type="hidden" class="unique_id" id="{{ $issuedProduct->product_id . $variantId . $issuedProduct->warehouse_id }}" value="{{ $issuedProduct->product_id . $variantId . $issuedProduct->warehouse_id }}">
                                                        </td>

                                                        <td class="text-start">
                                                            <input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="{{ $issuedProduct->warehouse_id }}">
                                                            <input type="hidden" id="current_warehouse_id" value="{{ $issuedProduct->warehouse_id }}">

                                                            @php
                                                                $stockLocationName = '';
                                                                if ($issuedProduct?->stockWarehouse) {
                                                                    $stockLocationName = $issuedProduct?->stockWarehouse?->warehouse_name . '-(' . $issuedProduct?->stockWarehouse?->warehouse_code . ')';
                                                                } else {
                                                                    if ($stockIssue?->branch) {
                                                                        if ($stockIssue?->branch?->parentBranch) {
                                                                            $stockLocationName = $stockIssue?->branch?->parentBranch->name . '(' . $stockIssue?->branch?->area_name;
                                                                        } else {
                                                                            $stockLocationName = $stockIssue?->branch?->name . '(' . $stockIssue?->branch?->area_name;
                                                                        }
                                                                    } else {
                                                                        $stockLocationName = $generalSettings['business_or_shop__business_name'];
                                                                    }
                                                                }
                                                            @endphp

                                                            <span id="stock_location_name">{{ $stockLocationName }}</span>
                                                        </td>

                                                        <td class="text-start">
                                                            <span id="span_quantity" class="fw-bold">{{ $issuedProduct->quantity }}</span>
                                                            <input type="hidden" name="quantities[]" id="quantity" value="{{ $issuedProduct->quantity }}">
                                                        </td>

                                                        <td class="text-start">
                                                            <span id="span_unit">{{ $issuedProduct?->unit?->name }}</span>
                                                            <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $issuedProduct?->unit?->id }}">
                                                        </td>

                                                        <td class="text-start">
                                                            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $issuedProduct->unit_cost_inc_tax }}">
                                                            <span id="span_unit_cost_inc_tax" class="fw-bold">{{ $issuedProduct->unit_cost_inc_tax }}</span>
                                                        </td>

                                                        <td class="text-start">
                                                            <strong><span id="span_subtotal">{{ $issuedProduct->subtotal }}</span></strong>
                                                            <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $issuedProduct->subtotal }}">
                                                        </td>

                                                        <td class="text-start">
                                                            <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
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
                </section>

                <section>
                    <div class="row g-3 py-2">
                        <div class="col-md-6">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Total Item') }}</b></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="{{ $stockIssue->total_item }}" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Total Quantity') }}</b></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="{{ $stockIssue->total_qty }}" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>{{ __('Net Total Amount') }}</b> {{ $generalSettings['business_or_shop__currency_symbol'] }}</label>
                                                        <div class="col-8">
                                                            <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control fw-bold" value="{{ $stockIssue->net_total_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Remarks') }}</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="remarks" id="remarks" class="form-control" data-next="save_changes" value="{{ $stockIssue->remarks }}" placeholder="{{ __('Remarks') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>

                <div class="row justify-content-center">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>{{ __('Loading') }}...</span> </button>
                            <button type="submit" id="save_changes" class="btn btn-success submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="departmentAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    @include('product.stock_issues.js_partials.edit_js')
@endpush
