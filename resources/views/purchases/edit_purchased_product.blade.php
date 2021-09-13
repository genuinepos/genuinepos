@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 94%;z-index: 9999999;padding: 0;left: 3%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 13px;padding: 4px 3px;display: block;}
        .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
        .selectProduct{background-color: #ab1c59; color: #fff!important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
    <link rel="stylesheet" href="{{ asset('public') }}/backend/asset/css/bootstrap-datepicker.min.css">
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="edit_purchase_form" action="" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Edit Purchased Product</h5>
                                        </div>

                                        <div class="col-6">
                                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Supplier :</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" id="supplier_name" class="form-control" value="{{ $purchase->s_name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            @if ($purchase->warehouse_id)
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class="col-4"><b>Warehouse :</b><span
                                                        class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{ $purchase->w_name.'/'.$purchase->w_code }}">
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>
                                            @else 
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><span
                                                        class="text-danger">*</span> <b>B.Location :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Invoice ID :</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control" value="{{ $purchase->invoice_id }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Date :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control datepicker changeable" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>Product</th>
                                                                    <th>Quantity</th>
                                                                    <th>Unit Cost(Before Discount)</th>
                                                                    <th>Discount</th>
                                                                    <th>Unit Cost(Before Tax)</th>
                                                                    <th>SubTotal (Before Tax)</th>
                                                                    <th>Unit Tax</th>
                                                                    <th>Net Unit Cost</th>
                                                                    <th>Line Total</th>
                                                                    @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                        <th>Profit Margin(%)</th>
                                                                        <th>Selling Price</th>
                                                                    @endif
                                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="text-start">
                                                                <td>
                                                                <span class="product_name">{{ $product->p_name }}</span> 
                                                                tr += '<span class="product_variant"></span>';  
                                                                tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                                                                tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                                                                tr += '</td>';
                                                    
                                                                tr += '<td>';
                                                                tr += '<input value="1" required name="quantities[]" type="number" step="any" class="form-control" id="quantity">';
                                                                tr += '<select name="unit_names[]" id="unit_name" class="form-control mt-1">';
                                                                    unites.forEach(function(unit) {
                                                                    if (product_unit == unit) {
                                                                        tr += '<option SELECTED value="'+unit+'">'+unit+'</option>'; 
                                                                    }else{
                                                                        tr += '<option value="'+unit+'">'+unit+'</option>';   
                                                                    }
                                                                })
                                                                tr += '</select>';
                                                                tr += '</td>';
                                                    
                                                                tr += '<td>';
                                                                tr += '<input value="'+product_cost+'" required name="unit_costs[]" type="text" class="form-control" id="unit_cost">';
                                                                @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                                                    tr += '<input name="lot_number[]" placeholder="Lot No" type="text" class="form-control mt-1" id="lot_number" value="">';
                                                                @endif
                                                                tr += '</td>';
                                                    
                                                                tr += '<td>';
                                                                tr += '<input value="0.00" required name="unit_discounts[]" type="text" class="form-control" id="unit_discount">';
                                                                tr += '</td>';
                                                    
                                                                tr += '<td>';
                                                                tr += '<input value="'+product_cost+'" required name="unit_costs_with_discount[]" type="text" class="form-control" id="unit_cost_with_discount">';
                                                                tr += '</td>';
                                                    
                                                                tr += '<td>';
                                                                tr += '<input value="'+product_cost+'" required name="subtotals[]" type="text" class="form-control" id="subtotal">';
                                                                tr += '</td>';
                                                    
                                                                tr += '<td>';
                                                                tr += '<input readonly type="text" name="tax_percents[]"  id="tax_percent" class="form-control" value="'+tax_percent+'">'
                                                                tr += '<input type="hidden" value="'+parseFloat(tax_amount).toFixed(2)+'" name="unit_taxes[]"   id="unit_tax">';
                                                                ;
                                                            
                                                                tr += '</td>';
                                                    
                                                                tr += '<td>';
                                                                tr += '<input type="hidden" value="'+product_cost_with_tax+'" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax">';
                                                                tr += '<input value="'+product_cost_with_tax+'" name="net_unit_costs[]" type="text" class="form-control" id="net_unit_cost">';
                                                                tr += '</td>';
                                                    
                                                                tr += '<td>';
                                                                tr += '<input readonly value="'+product_cost_with_tax+'" type="text" name="linetotals[]" id="line_total" class="form-control">';
                                                                tr += '</td>';
                                                    
                                                                @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                    tr += '<td>';
                                                                    tr += '<input value="'+product_profit+'" type="text" name="profits[]" class="form-control" id="profit">';
                                                                    tr += '</td>';
                                                                
                                                                    tr += '<td>';
                                                                    tr += '<input value="'+product_price+'" type="text" name="selling_prices[]" class="form-control" id="selling_price">';
                                                                    tr += '</td>';
                                                                @endif
                                                    
                                                                tr += '<td class="text-start">';
                                                                tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
                                                                tr += '</td>';
                                                    
                                                                tr += '</tr>';
                                                            </tbody>
                                                        </table>
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

                <div class="submit_button_area pt-1">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button class="btn btn-sm btn-primary submit_button float-end">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')

@endpush