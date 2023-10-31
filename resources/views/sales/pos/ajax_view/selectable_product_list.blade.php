@php
   use Illuminate\Support\Str;
@endphp
    @foreach ($products as $product)
        @php
            $taxPercent = $product->tax_percent ? $product->tax_percent : 0;
            $updateProductCost = $product->update_product_cost != 0 || $product->update_product_cost != null ? $product->update_product_cost : $product->product_cost_with_tax;
            $updateVariantCost = $product->update_variant_cost != 0 || $product->update_variant_cost != null ? $product->update_variant_cost : $product->variant_cost_with_tax;;

            $__updateProductCost = $product->is_variant == 1 ? $updateVariantCost : $updateProductCost;

            $variantName = $product->variant_name ? '-'.$product->variant_name : '';

            $variantImage = $product->variant_image ? asset('uploads/product/variant_image/'.$product->variant_image) : asset('uploads/product/thumbnail/'.$product->thumbnail_photo);

            $impUrl = $product->is_variant == 1 ? $variantImage : asset('uploads/product/thumbnail/'.$product->thumbnail_photo)
        @endphp
        <div class="col-lg-4 col-sm-3 col-4">
            <a href="#" onclick="selectProduct(this); return false;" tabindex="-1" title="{{ $product->product_name . ' - ' . $variantName }}" data-is_manage_stock="{{ $product->is_manage_stock }}" data-p_id="{{ $product->product_id }}" data-v_id="{{ $product->variant_id }}" data-p_name="{{ $product->product_name }}" data-v_name="{{ $variantName }}" data-p_tax_ac_id="{{ $product->tax_ac_id }}" data-unit_id="{{ $product->unit_id }}" data-unit_name="{{ $product->unit_name }}" data-tax_percent="{{ $taxPercent }}" data-tax_type="{{ $product->tax_type }}" data-p_code="{{ $product->variant_code ? $product->variant_code : $product->product_code }}" data-is_show_emi_on_pos="{{ $product->is_show_emi_on_pos }}" data-p_price_exc_tax="{{ $product->variant_price ? $product->variant_price : $product->product_price }}" data-p_cost_inc_tax="{{ $__updateProductCost }}">
                <div class="product">
                    <div class="product-img">
                        <img loading="lazy" src="{{ $impUrl }}">
                    </div>
                    <div class="product-name" id="{{ $product->product_id . ($product->variant_id ? 'vid-'.$product->variant_id : 'no_v_id') }}">
                        <a href="#" tabindex="-1">
                            {{ Str::limit($product->product_name, 15, '').$variantName}}
                        </a>
                    </div>
                </div>
            </a>
        </div>
    @endforeach





