<div class="modal-dialog modal-full-display">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title product_name" id="exampleModalLabel">
                {{ $product->name . ' - ' . $product->product_code }}
            </h5>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="carousel-item active product_image">
                        <img  class="rounded" style="height:170px;width:190px;"
                            src="{{ asset('public/uploads/product/thumbnail/' . $product->thumbnail_photo) }}" class="d-block w-100">
                    </div>
                </div>

                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>Code(SKU) : </strong> {{ $product->product_code }}</li>
                        <li><strong>Brand : </strong> {{ $product->brand ? $product->brand->name : 'N/A' }}</li>
                        <li><strong>Unit : </strong> {{ $product->unit->name }}</li>
                        <li><strong>Barcode Type : </strong> {{ $product->barcode_type }}</li>
                        <li><strong>Available Branch: </strong>
                            @if (count($product->product_branches))
                                @foreach ($product->product_branches as $product_branch)
                                     {{ $product_branch->branch->name . '/' . $product_branch->branch->branch_code }},
                                @endforeach
                            @else
                                Yet-to-be-available-in-any-Branch.
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>Category : </strong> {{$product->category ? $product->category->name : 'N/A' }}</li>
                        <li><strong>Sub-Category : </strong> {{ $product->child_category ? $product->child_category->name : 'N/A' }}</li>
                        <li><strong>Is For Sale : </strong>{{ $product->is_for_sale == 1 ? 'Yes' : 'No' }}</li>
                        <li><strong>Alert Quantity : </strong>{{ $product->alert_quantity }}</li>
                        <li><strong>Warranty : </strong>
                            {{ $product->warranty ? $product->warranty->name : 'N/A' }}
                        </li>
                    </ul>
                </div>
                
                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>Expire Date : </strong> {{$product->expire_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($product->expire_date)) : 'N/A' }}
                        </li>
                        <li><strong>Tax : </strong>{{ $product->tax ? $product->tax->tax_name : 'N/A' }}</li>
                        @if ($product->tax)
                            <li><strong>Tax Type: </strong>{{ $product->tax_type == 1 ? 'Exclusive' : 'Inclusive' }}</li>
                        @endif
                        <li><strong>Product Condition : </strong> {{ $product->product_condition }}</li>
                        <li>
                            <strong>Product Type : </strong>
                            @php  $product_type = ''; @endphp
                            @if ($product->type == 1 && $product->is_variant == 1)
                                @php $product_type = 'Variant'; @endphp
                            @elseif ($product->type == 1 && $product->is_variant == 0)
                                @php $product_type = 'Single'; @endphp
                            @elseif ($product->type == 2) {
                                @php  $product_type = 'Combo'; @endphp
                            @elseif ($product->type == 3) {
                                @php  $product_type = 'Digital'; @endphp
                            @endif
                            {{ $product_type }}
                        </li>
                    </ul>
                </div>
            </div><br>
            @php $tax = $product->tax ? $product->tax->tax_percent : 0  @endphp
            @if ($product->is_variant == 0)
                <div class="row">
                    <div class="table-responsive">
                        <!--single_product_pricing_table-->
                        @include('product.products.ajax_view.partials.single_product_pricing_table')
                        <!--single_product_pricing_table End-->
                    </div>
                </div>
            @elseif($product->is_variant == 1)
                <div class="row">
                    <div class="table-responsive">
                        <!--variant_product_pricing_table-->
                        @include('product.products.ajax_view.partials.variant_product_pricing_table')
                        <!--variant_product_pricing_table End-->
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="heading">
                    <label class="p-0 m-0"><strong>Warehouse Stock Details</strong></label>
                </div>
                <div class="table-responsive" id="warehouse_stock_details">
                    <!--Warehouse Stock Details-->
                    @include('product.products.ajax_view.partials.warehouse_stock_details')
                    <!--Warehouse Stock Details End-->
                </div>
            </div>

            <div class="row">
                <div class="heading">
                    <label class="p-0 m-0"><strong>Branch Stock Details</strong></label>
                </div>
                <div class="table-responsive" id="branch_stock_details">
                    @include('product.products.ajax_view.partials.branch_stock_details')
                </div>
            </div>
        </div>

        <div class="modal-footer text-end">
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="c-btn btn_blue print_btn">Print</button>
                    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
