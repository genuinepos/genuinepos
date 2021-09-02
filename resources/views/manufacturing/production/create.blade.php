@extends('layout.master')
@push('stylesheets') 
    <style>
        table.display td input {height: 26px!important; padding: 3px;}
        span.input-group-text-custom {font-size: 11px;padding: 4px;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_production_form" action="{{ route('manufacturing.productions.store') }}" method="POST">
                <input type="hidden" id="product_id" value="">
                <input type="hidden" id="variant_id" value="">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6"><h5>Production</h5></div>
                                        <div class="col-6">
                                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"> <b>Ref No :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="reference_no" class="form-control changeable" placeholder="Reference No"/>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Qunatity :</b> <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input type="number" step="any" data-name="Quantity" class="form-control add_input" name="quantity" id="quantity" value="1.00">
                                                    <input type="hidden" step="any" id="parameter_quantity" value="0.00">
                                                    <span class="error error_quantity"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Date :</b></label>
                                                <div class="col-8">
                                                    <input type="date" name="date" class="form-control changeable"
                                                        value="{{ date('Y-m-d') }}" id="date">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            @if (count($warehouses) > 0)
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"> <b>Warehouse :</b> <span
                                                        class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select class="form-control changeable add_input"
                                                            name="warehouse_id" data-name="Warehouse" id="warehouse_id">
                                                            <option value="">Select Warehouse</option>
                                                            @foreach ($warehouses as $w)
                                                                <option value="{{ $w->id }}">{{ $w->warehouse_name.'/'.$w->warehouse_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>B.Location :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="branch_id" class="form-control changeable" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}"/>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Product :</b> <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select name="process_id" data-name="Product" class="form-control add_input"
                                                        id="product_id">
                                                        <option value="">Select Process</option>
                                                        @foreach ($products as $product)
                                                            @php
                                                                $variant_name = $product->v_name ? $product->v_name : '';
                                                                $product_code = $product->v_code ? $product->v_code : $product->p_code;
                                                            @endphp
                                                            <option value="{{ $product->id }}">{{ $product->p_name.' '.$variant_name.' ('.$product_code.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_product_id"></span>
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
                                                                    <th>Ingredient</th>
                                                                    <th>Input Quantity</th>
                                                                    <th>Wastage Percentage</th>
                                                                    <th>Final Qunatity</th>
                                                                    <th>Total Price</th>
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
                    </div>
                </section>
                <input type="hidden" name="total_ingredient_cost" id="total_ingredient_cost">
                <section class="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Wasted Qunatity :</b></label>
                                                <div class="col-8">
                                                    <input type="number" step="any" name="wasted_quantity" class="form-control" id="wasted_quantity" value="0.00"> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Production Cost :</b></label>
                                                <div class="col-8">
                                                    <input name="production_cost" type="number" class="form-control" id="production_cost" value="0.00"> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Total Cost :</b> </label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="total_cost" class="form-control" id="total_cost" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button class="btn btn-sm btn-primary submit_button float-end">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        //Get process data
        $(document).on('change', '#product_id', function (e) {
            e.preventDefault();
            var processId = $(this).val();
            var url = "{{ url('manufacturing/productions/get/process/') }}"+"/"+processId;
            $.get(url, function(data) {
                $('#product_id').val(data.product_id);
                $('#variant_id').val(data.variant_id);
                $('#quantity').val(data.total_output_qty);
                $('#parameter_quantity').val(data.total_output_qty);
                $('#unit_id').val(data.unit_id);
                $('#wasted_quantity').val(data.wastage_percent);
                $('#production_cost').val(data.production_cost);
                $('#total_ingredient_cost').val(data.total_ingredient_cost);
                var product_id = data.product_id;
                var variantId = data.variant_id ? data.variant_id : null;
                var url = "{{ url('manufacturing/productions/get/ingredients') }}"+"/"+processId;
                $.get(url, function(data) {$('#ingredient_list').html(data);});
            });
        });

        $(document).on('input', '#quantity', function () {
            var presentQty = $(this).val() ? $(this).val() : 0;
            var parameterQty = $('#parameter_quantity').val() ? $('#parameter_quantity').val() : 0;
            console.log(meltipilerQty);
            var meltipilerQty = parseFloat(presentQty) / parseFloat(parameterQty);
            var allTr = $('#ingredient_list').find('tr');
            allTr.each(function () {
                var parameterInputQty = $(this).find('#parameter_input_quantity').val();
                var updateInputQty = parseFloat(meltipilerQty) * parseFloat(parameterInputQty);
                $(this).find('#input_quantity').val(parseFloat(updateInputQty).toFixed(2));
                __calculateIngredientsTableAmount($(this));
            });
        });

        $(document).on('input', '#input_quantity', function () {
            var value = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            tr.find('#parameter_input_quantity').val(parseFloat(value).toFixed(2));
            __calculateIngredientsTableAmount(tr);
        });

        $(document).on('input', '#ingredient_wastage_percent', function () {
            var tr = $(this).closest('tr');
            __calculateIngredientsTableAmount(tr);
        });

        var errorCount = 0;
        function __calculateIngredientsTableAmount(tr) {
            var wastePercent = tr.find('#ingredient_wastage_percent').val() ? tr.find('#ingredient_wastage_percent').val() : 0;
            var inputQty = tr.find('#input_quantity').val() ? tr.find('#input_quantity').val() : 0;
            var unitCostIncTax = tr.find('#unit_cost_inc_tax').val();
            var limitQty = tr.find('#qty_limit').val();
            var unitName = tr.find('#qty_limit').data('unit');
            var regexp = /^\d+\.\d{0,2}$/;
            tr.find('#input_qty_error').html('');
            if (regexp.test(parseFloat(inputQty)) == true) {
                tr.find('#input_qty_error').html('Deciaml value is not allowed.');
                errorCount++;
            } else if(parseFloat(inputQty) > parseFloat(limitQty)){
                tr.find('#input_qty_error').html('Only '+limitQty+' '+unitName+' is available.');
                errorCount++;
            } else {
                errorCount = 0;
                var calsWastage = parseFloat(inputQty) / 100 * parseFloat(wastePercent);
                var wastedQuantity = parseFloat(inputQty) - parseFloat(calsWastage);
                tr.find('#final_quantity').val(parseFloat(wastedQuantity).toFixed(2));
                tr.find('#span_final_quantity').html(parseFloat(wastedQuantity).toFixed(2));
                var totalPrice = parseFloat(inputQty) * parseFloat(unitCostIncTax);
                tr.find('#price').val(parseFloat(totalPrice).toFixed(2));
                tr.find('#span_price').html(parseFloat(totalPrice).toFixed(2));
            }
        }
    </script>
@endpush