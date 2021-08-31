@foreach ($ingredients as $ingredient)
    @php
        $stock = 0;
        if(auth()->user()->branch_id){
            if ($ingredient->variant_id) {
                $productBranch = DB::table('product_branches')->where('branch_id', auth()->user()->branch_id)
                ->where('product_id', $ingredient->product_id)->first();
                if ($productBranch) {
                    $productBranchVariant = DB::table('product_branch_variants')->where('product_branch_id', $productBranch->id)
                    ->where('product_variant_id', $ingredient->variant_id)->first();
                    $stock = $productBranchVariant ? $productBranchVariant->variant_quantity : 0;
                }
            }else {
                $productBranch = DB::table('product_branches')->where('branch_id', auth()->user()->branch_id)
                ->where('product_id', $ingredient->product_id)->first();
                $stock = $productBranch ? $productBranch->product_quantity : 0;
            }
        } else {
            if ($ingredient->variant_id) {
                $mb_v_stock = DB::table('product_variants')->where('id', $ingredient->variant_id)->first();
                $stock = $mb_v_stock ? $mb_v_stock->mb_stock : 0;
            }else {
                $mb_p_stock = DB::table('products')->where('id', $ingredient->product_id)->first();
                $stock = $mb_p_stock ? $mb_p_stock->mb_stock : 0;
            }
        }
    @endphp
    <tr class="text-start">
        <td>
            <span class="product_name">{{ $ingredient->p_name }}</span><br>
            <span class="product_variant">{{ $ingredient->v_name }}</span>  
            <input value="{{ $ingredient->p_id }}" type="hidden" class="productId-{{ $ingredient->p_id }}" id="product_id" name="product_ids[]">
            <input value="{{ $ingredient->v_id ? $ingredient->v_id : 'noid' }}" type="hidden" id="variant_id" name="variant_ids[]">
            <input value="{{ $ingredient->unit_cost_inc_tax }}" required name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">
            <input value="{{ $ingredient->u_id }}" name="unit_ids[]" type="hidden" step="any" id="unit_id">
            <input value="{{ bcadd($stock, 0 ,2) }}" type="hidden" step="any" data-unit="{{ $ingredient->u_name }}" id="qty_limit">
        </td>

        <td>
            <div class="input-group p-2">
                <input value="{{ $ingredient->final_qty }}" required name="input_quantities[]" type="number" class="form-control" id="input_quantity">
                <input value="{{ $ingredient->final_qty }}" type="hidden" id="parameter_input_quantity">
                <div class="input-group-prepend">
                    <span class="input-group-text input-group-text-custom">{{ $ingredient->u_name }}</span>
                </div>
            </div>

            <div>
                <span class="text-danger" id="input_qty_error"></span>
            </div>
        </td>

        <td>
            <div class="input-group p-2">
                <input type="number" step="any" name="ingredient_wastage_percents[]" class="form-control" id="ingredient_wastage_percent" placeholder="Wastage" value="{{ $ingredient->wastage_percent }}">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-percentage input_i"></i></span>
                </div>
            </div>
        </td>
        
        <td>
            @php
                $wastage = $ingredient->final_qty / 100  * $ingredient->wastage_percent;
                $finalQuantity = $ingredient->final_qty - $wastage;
            @endphp
            <div class="input-group p-2">
                <input type="hidden" step="any" name="final_quantities[]" id="final_quantity" value="{{ bcadd($finalQuantity, 0, 2) }}">
                <div>
                    <span id="span_final_quantity">{{ bcadd($finalQuantity, 0, 2) }}</span> 
                    <span>{{ $ingredient->u_name }}</span>
                </div>
            </div>
        </td>

        <td>
            <input value="{{ $ingredient->subtotal }}" type="hidden" step="any" name="prices[]" id="price">
            <span id="span_price">{{ $ingredient->subtotal }}</span>
        </td>
    </tr>
@endforeach