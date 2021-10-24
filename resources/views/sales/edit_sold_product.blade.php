@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="edit_sold_product_form" action="{{ route('sales.update.sold.product', $sale->id) }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Edit Sold Product</h5>
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
                                                <label for="inputEmail3" class=" col-4"><b>Customer :</b> </label>
                                                <div class="col-8">
                                                    <div class="input-group width-60">
                                                        <input readonly type="text" value="{{ $sale->c_name ? $sale->c_name : 'Walk-In-Customer' }}" id="customer_name" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"> <b>Location :</b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"><b>Invoice ID :</b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control" value="{{$sale->invoice_id}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"> <b> Date :</b> <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control" id="datepicker"
                                                        value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($sale->date)) }}">
                                                    <span class="error error_date"></span>
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
                            <div class="col-md-9">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-start">Product</th>
                                                                    <th></th>
                                                                    <th class="text-center">Quantity</th>
                                                                    <th>Unit</th>
                                                                    <th class="text-center">Price Inc.Tax</th>
                                                                    <th>SubTotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="sale_list">
                                                                <tr>
                                                                    <td colspan="2" class="text-start">
                                                                        <a href="#" class="text-success" id="edit_product">
                                                                        <span class="product_name">{{ $sold_product->p_name }}</span>
                                                                        @php
                                                                            $variant = $sold_product->variant_name != null ? ' -'.$sold_product->variant_name.'- ' : ''; 
                                                                            $code = $sold_product->variant_code != null ? $sold_product->variant_code : $sold_product->product_code;
                                                                        @endphp
                                                                        
                                                                        <span class="product_variant">{{ $variant }}</span>
                                                                        <span class="product_code">{{ $code }}</span>
                                                                        </a><br/><input type="{{ $sold_product->is_show_emi_on_pos == 1 ? 'text' : 'hidden'}}" name="description" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here." value="{{ $sold_product->description ? $sold_product->description : '' }}">
                                                                        <input value="{{$sold_product->product_id}}" type="hidden" id="product_id" name="product_id">
                                                    
                                                                        @if ($sold_product->product_variant_id != null) 
                                                                            <input value="{{ $sold_product->product_variant_id }}" type="hidden" id="variant_id" name="variant_id">
                                                                        @else
                                                                            <input value="noid" type="hidden" id="variant_id" name="variant_id"> 
                                                                        @endif  
                                                    
                                                                        <input type="hidden" id="tax_type" value="{{ $sold_product->tax_type }}">
                                                                        <input name="unit_tax_percent" type="hidden" id="unit_tax_percent" value="{{ $sold_product->unit_tax_percent }}">
                                                                        <input name="unit_tax_amount" type="hidden" id="unit_tax_amount" value="{{ $sold_product->unit_tax_amount }}">
                                                                        <input value="{{ $sold_product->unit_discount_type }}" name="unit_discount_type" type="hidden" id="unit_discount_type">
                                                                        <input value="{{ $sold_product->unit_discount }}" name="unit_discount" type="hidden" id="unit_discount">
                                                                        <input value="{{ $sold_product->unit_discount_amount }}" name="unit_discount_amount" type="hidden" id="unit_discount_amount">
                                                                        <input value="{{ $sold_product->unit_cost_inc_tax }}" name="unit_cost_inc_tax" type="hidden" id="unit_cost_inc_tax">
                                                                        <input type="hidden" id="previous_quantity" value="{{ $sold_product->quantity }}">
                                                                        <input type="hidden" id="qty_limit" value="{{ $qty_limit }}">
                                                                    </td>
                                                
                                                                    <td>
                                                                        <input value="{{ $sold_product->quantity }}" required name="quantity" type="number" step="any" class="form-control text-center" id="quantity" autofocus>
                                                                    </td>

                                                                    <td class="text">
                                                                        <span class="span_unit">{{ $sold_product->unit }}</span>
                                                                        <input name="unit" type="hidden" id="unit" value="{{ $sold_product->unit }}">
                                                                    </td>

                                                                    <td>
                                                                        <input name="unit_price_exc_tax" type="hidden" value="{{ $sold_product->unit_price_exc_tax}}" id="unit_price_exc_tax">
                                                                        <input readonly name="unit_price" type="text" class="form-control text-center" id="unit_price" value="{{ $sold_product->unit_price_inc_tax}}">
                                                                    </td>

                                                                    <td class="text text-center">
                                                                        <strong><span class="span_subtotal">{{ $sold_product->subtotal }}</span></strong>
                                                                        <input value="{{ $sold_product->subtotal }}" readonly name="subtotal" type="hidden" id="subtotal">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="submitBtn">
                                    <div class="row justify-content-center">
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i> <strong>Loading...</strong> </button>
                                            <button type="submit" class="btn btn-sm btn-primary submit_button">Save Change </button>
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
   
    <!-- Edit selling product modal-->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="product_info"></h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="update_selling_product" action="">
                        <div class="form-group">
                            <label> <strong>Quantity</strong>  : <span class="text-danger">*</span></label>
                            <input type="number" step="any" readonly class="form-control edit_input" data-name="Quantity" id="e_quantity" placeholder="Quantity"/>
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>Unit Price Exc.Tax</strong>  : <span class="text-danger">*</span></label>
                            <input type="number" step="any" {{ auth()->user()->permission->sale['edit_price_sale_screen'] == '1' ? '' : 'readonly' }} step="any" class="form-control edit_input" data-name="Unit price" id="e_unit_price" placeholder="Unit price"/>
                            <span class="error error_e_unit_price"></span>
                        </div>

                        @if (auth()->user()->permission->sale['edit_discount_sale_screen'] == '1')
                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><strong>Discount Type</strong>  :</label>
                                    <select class="form-control " id="e_unit_discount_type">
                                        <option value="2">Percentage</option>
                                        <option value="1">Fixed</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label><strong>Discount</strong>  :</label>
                                    <input type="number" step="any" class="form-control " id="e_unit_discount" value="0.00"/>
                                    <input type="hidden" id="e_discount_amount"/>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>Tax</strong> :</label>
                                <select class="form-control" id="e_unit_tax">
                                    <option value="0.00">NoTax</option>
                                    @foreach ($taxes as $tax)
                                        <option value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>Tax Type</strong>  :</label>
                                <select class="form-control" id="e_tax_type">
                                    <option value="1">Exclusive</option>
                                    <option value="2">Inclusive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Sale Unit</strong> :</label>
                            <select class="form-control" id="e_unit">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="submit" class="c-btn btn_blue float-end me-0">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    <!-- Edit selling product modal End-->
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
         // Show selling product's update modal
        var tableRowIndex = 0;
        $(document).on('click', '#edit_product', function(e) {
            e.preventDefault();
            var parentTableRow = $(this).closest('tr');
            tableRowIndex = parentTableRow.index();
            var quantity = parentTableRow.find('#quantity').val();
            var product_name = parentTableRow.find('.product_name').html();
            var product_variant = parentTableRow.find('.product_variant').html();
            var product_code = parentTableRow.find('.product_code').html();
            var unit_price_exc_tax = parentTableRow.find('#unit_price_exc_tax').val();
            var unit_tax_percent = parentTableRow.find('#unit_tax_percent').val();
            var unit_tax_amount = parentTableRow.find('#unit_tax_amount').val();
            var unit_tax_type = parentTableRow.find('#tax_type').val();
            var unit_discount_type = parentTableRow.find('#unit_discount_type').val();
            var unit_discount = parentTableRow.find('#unit_discount').val();
            var unit_discount_amount = parentTableRow.find('#unit_discount_amount').val();
            var product_unit = parentTableRow.find('#unit').val();
            // Set modal heading
            var heading = product_name + (product_variant ? product_variant : '') +' '+ product_code;
            $('#product_info').html(heading);
            $('#e_quantity').val(parseFloat(quantity).toFixed(2));
            $('#e_unit_price').val(parseFloat(unit_price_exc_tax).toFixed(2));
            $('#e_unit_discount_type').val(unit_discount_type);
            $('#e_unit_discount').val(unit_discount);
            $('#e_discount_amount').val(unit_discount_amount);
            $('#e_unit_tax').val(unit_tax_percent);
            $('#e_tax_type').val(unit_tax_type);
            $('#e_unit').val(product_unit);
            $('#editProductModal').modal('show');
        });

        // Calculate unit discount
        $('#e_unit_discount').on('input', function () {
            var discountValue = $(this).val() ? $(this).val() : 0.00;
            if ($('#e_unit_discount_type').val() == 1) {
                $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
            }else{
                var unit_price = $('#e_unit_price').val();
                var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
                $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
            }
        });

        // change unit discount type var productTableRow 
        $('#e_unit_discount_type').on('change', function () {
            var type = $(this).val();
            var discountValue = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
            if (type == 1) {
                $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
            }else {
                var unit_price = $('#e_unit_price').val();
                var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
                $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
            }
        });

         // change unit price
        $('#e_unit_price').on('input', function () {
            var unit_price = $(this).val() ? $(this).val() : 0.00;
            var discountValue = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
            if ($('#e_unit_discount_type').val() == 1) {
                $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
            }else{
                var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
                $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
            }
        });
        
        //Update Selling producdt
        $('#update_selling_product').on('submit', function (e) {
            e.preventDefault();
            var inputs = $('.edit_input');
            $('.error').html('');  
            var countErrorField = 0;  
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){
                return;
            }

            var e_quantity = $('#e_quantity').val();
            var e_unit_price = $('#e_unit_price').val();
            var e_unit_discount_type = $('#e_unit_discount_type').val() ? $('#e_unit_discount_type').val() : 1;
            var e_unit_discount = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
            var e_unit_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0.00;
            var e_unit_tax_percent = $('#e_unit_tax').val() ? $('#e_unit_tax').val() : 0.00;
            var e_unit_tax_type = $('#e_tax_type').val() ? $('#e_tax_type').val() : 1;
            var e_unit = $('#e_unit').val();

            var productTableRow = $('#sale_list tr:nth-child(' + (tableRowIndex + 1) + ')');
            // calculate unit tax 
            productTableRow.find('.span_unit').html(e_unit);
            productTableRow.find('#unit').val(e_unit);
            productTableRow.find('#unit').val(e_unit);
            productTableRow.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
            productTableRow.find('#unit_price_exc_tax').val(parseFloat(e_unit_price).toFixed(2));
            productTableRow.find('#unit_discount_type').val(e_unit_discount_type);
            productTableRow.find('#unit_discount').val(parseFloat(e_unit_discount).toFixed(2));
            productTableRow.find('#unit_discount_amount').val(parseFloat(e_unit_discount_amount).toFixed(2));

            var calcUnitPriceWithDiscount = parseFloat(e_unit_price) - parseFloat(e_unit_discount_amount);
            var calsUninTaxAmount = parseFloat(calcUnitPriceWithDiscount) / 100 * parseFloat(e_unit_tax_percent);
            if (e_unit_tax_type == 2) {
                var inclusiveTax = 100 + parseFloat(e_unit_tax_percent);
                var calc = parseFloat(calcUnitPriceWithDiscount) / parseFloat(inclusiveTax) * 100;
                calsUninTaxAmount = parseFloat(calcUnitPriceWithDiscount) - parseFloat(calc);
            }
            productTableRow.find('#unit_tax_percent').val(parseFloat(e_unit_tax_percent).toFixed(2));
            productTableRow.find('#tax_type').val(e_unit_tax_type);
            productTableRow.find('#unit_tax_amount').val(parseFloat(calsUninTaxAmount).toFixed(2));

            var calcUnitPriceIncTax = parseFloat(calcUnitPriceWithDiscount) + parseFloat(calsUninTaxAmount);
        
            productTableRow.find('#unit_price').val(parseFloat(calcUnitPriceIncTax).toFixed(2));

            var calcSubtotal = parseFloat(calcUnitPriceIncTax) * parseFloat(e_quantity);
            productTableRow.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            productTableRow.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
            $('#editProductModal').modal('hide');
        });

        // Quantity increase or dicrease and clculate row amount
        $(document).on('input', '#quantity', function(){
            var qty = $(this).val() ? $(this).val() : 0;
            if (parseFloat(qty) >= 0) {
                var tr = $(this).closest('tr');
                var previousQty = tr.find('#previous_quantity').val();
                var limit = tr.find('#qty_limit').val()
                var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                if(parseInt(qty) > parseInt(qty_limit)){
                    alert('Quantity exceeds stock quantity!');
                    $(this).val(parseFloat(qty_limit).toFixed(2));
                    var unitPrice = tr.find('#unit_price').val();
                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                    tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                    tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                    calculateTotalAmount();  
                    return;
                }
                var unitPrice = tr.find('#unit_price').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty);
                tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        
            }
        });

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '' ;
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: _expectedDateFormat,
        });

        //Add purchase request by ajax
        $('#edit_sold_product_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var request = $(this).serialize();
            var url = $(this).attr('action');
            var inputs = $('.add_input');
                inputs.removeClass('is-invalid');
                $('.error').html('');  
                var countErrorField = 0;  
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.','Some thing want wrong.'); 
                return;
            }

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    $('.loading_button').hide();
                    toastr.success(data); 
                    window.location = "{{ route('sales.product.list') }}";
                }, error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.'); 
                    }else{
                        toastr.error('Server error please contact to the support team.');
                    }
                }
            });
        });
    </script>
@endpush
