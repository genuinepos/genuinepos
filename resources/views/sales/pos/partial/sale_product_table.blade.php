<style>
    .set-height{
        position: relative;
    }
</style>
<div class="set-height">
    <div class="data_preloader submit_preloader">
        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
    </div>
    <div class="table-responsive">
        <table class="table data__table modal-table table-sm sale-product-table">
            <thead>
                <tr>
                    <th scope="col">SL</th>
                    <th scope="col">@lang('menu.name')</th>
                    <th scope="col">Qty/Weight</th>
                    <th scope="col">@lang('menu.unit')</th>
                    <th scope="col">Price.Inc.Tax</th>
                    <th scope="col">@lang('menu.sub_total')</th>
                    <th scope="col"><i class="fas fa-trash-alt"></i></th>
                </tr>
            </thead>

            <tbody id="product_list">
                <tr>
                    <td class="serial">1</td>
                    <td class="text-start">
                        <a href="#" class="product-name text-info" id="edit_product" title="" tabindex="-1">Ms Rod 28MM (500W) 75 Grade</a><br/><input type="" name="descriptions[]" class="form-control description_input scanable" placeholder="IMEI, Serial number or other info.">
                        <input value="" type="hidden" class="" id = "product_id" name="product_ids[]" >
                        <input input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">
                        <input value="1" type="hidden" id="tax_type">
                        <input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="0.00">
                        <input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="0.00">
                        <input value="0.00" name="unit_discount_types[]" type="hidden" id="unit_discount_type">
                        <input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">
                        <input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">
                        <input value="0.00" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">
                        <input type="hidden" id="previous_qty" value="0.00">
                        <input type="hidden" id="qty_limit" value="0.00">
                        <input class="1" type="hidden" id="index">
                    </td>

                    <td>
                        <input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">
                    </td>

                    <td>
                        <b><span class="span_unit">Kilogram</span></b>
                        <input name="units[]" type="hidden" id="unit" value="">
                    </td>

                    <td>
                        <input name="unit_prices_exc_tax[]" type="hidden" value="0.00" id="unit_price_exc_tax">
                        <input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="0.00">
                        <b><span class="span_unit_price_inc_tax">1000.00</span></b>
                    </td>

                    <td>
                        <input value="0.00" name="subtotals[]" type="hidden" id="subtotal">
                        <b><span class="span_subtotal">1000.00</span></b>
                    </td>

                    <td><a href="#" class="action-btn c-delete" id="remove_product_btn" tabindex="-1"><span class="fas fa-trash"></span></a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
