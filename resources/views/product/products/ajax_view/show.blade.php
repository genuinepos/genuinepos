<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            @if (isset($product->thumbnail_photo))
                                <img class="rounded" style="height:120px;width:120px;" src="{{ file_link(fileType: 'productThumbnail', fileName: $product->thumbnail_photo) }}" class="d-block w-100">
                            @else
                                <img class="rounded" style="height:120px;width:120px;" src="{{ asset('images/general_default.png') }}" class="d-block w-100">
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <ul class="list-unstyled">
                            <li style="font-size:10px!important;"><strong>{{ __('Name') }} : </strong> {{ $product->name }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Product Code(SKU)') }} : </strong> {{ $product->product_code }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Brand.') }} : </strong> {{ $product?->brand ? $product?->brand?->name : __('N/A') }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Unit') }} : </strong> {{ $product?->unit ? $product?->unit?->name : __('N/A') }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Barcode Type') }} : </strong> {{ $product->barcode_type }}</li>
                        </ul>
                    </div>

                    <div class="col-md-3">
                        <ul class="list-unstyled">
                            <li style="font-size:10px!important;"><strong>{{ __('Category') }} : </strong> {{ $product?->category ? $product?->category?->name : __('N/A') }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Subcategory') }} : </strong> {{ $product->subcategory ? $product?->subcategory?->name : __('N/A') }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Is For Sale?') }} : </strong>{{ $product->is_for_sale == 1 ? __('Yes') : __('No') }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Alert Quantity') }} : </strong>{{ $product->alert_quantity }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Is Manage Stock?') }} : </strong> {!! $product->is_manage_stock == 1 ? '<span class="text-success">YES</span>' : '<span class="text-danger">' . __('NO') . '</span>' !!}</li>

                        </ul>
                    </div>

                    <div class="col-md-3">
                        <ul class="list-unstyled">
                            <li style="font-size:10px!important;"><strong>{{ __('Tax') }} : </strong>{{ $product?->tax ? $product?->tax->name : 'N/A' }}</li>
                            @if ($product?->tax)
                                <li style="font-size:10px!important;"><strong>{{ __('Tax Type') }} : </strong>{{ $product->tax_type == 1 ? __('Exclusive') : __('Inclusive') }}</li>
                            @endif
                            <li style="font-size:10px!important;"><strong>{{ __('Product Condition') }} : </strong> {{ $product->product_condition }}</li>
                            <li style="font-size:10px!important;">
                                <strong>{{ __('Product Type') }} : </strong>
                                @php
                                    $product_type = '';
                                @endphp

                                @if ($product->type == 1 && $product->is_variant == 1)
                                    @php $product_type = __('Variant'); @endphp
                                @elseif ($product->type == 1 && $product->is_variant == 0)
                                    @php $product_type = __('Single'); @endphp
                                @elseif ($product->type == 2)
                                    @php  $product_type = __('Combo'); @endphp
                                @elseif ($product->type == 3)
                                    @php  $product_type = __('Digital'); @endphp
                                @endif

                                {{ $product_type }}
                            </li>
                            <li style="font-size:10px!important;">
                                <strong class="text-primary">{{ $product->is_manage_stock == 0 ? '(Service related/Digital Item)' : '' }} </strong>
                            </li>
                            <li style="font-size:10px!important;"><strong>{{ __('Warranty') }} : </strong>
                                {{ $product?->warranty ? $product?->warranty?->name . '(' . $product?->warranty?->duration . ' ' . $product?->warranty?->duration_type . ')' : __('N/A') }}
                            </li>
                        </ul>
                    </div>
                </div><br>

                <div class="row">
                    <div class="col-md-12">
                        @if ($product->is_variant == 0)
                            <div class="table-responsive">
                                <table id="" class="table modal-table table-sm">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th style="font-size:10px!important;">{{ __('Default Unit Cost (Exc. Tax)') }}</th>
                                            <th style="font-size:10px!important;">{{ __('Default Unit Cost (Inc. Tax)') }}</th>
                                            <th style="font-size:10px!important;">{{ __('Default Profit Margin(%)') }}</th>
                                            <th style="font-size:10px!important;">{{ __('Default Unit Price (Exc. Tax)') }}</th>
                                            <th style="font-size:10px!important;">{{ __('Default Unit Price (Inx. Tax)') }}</th>
                                            @if (count($priceGroups) > 0)
                                                <th style="font-size:10px!important;">{{ __('Price Group') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($product->product_cost) }}</td>
                                            <td style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($product->product_cost_with_tax) }}</td>
                                            <td style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($product->profit) . '%' }}</td>
                                            <td style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($product->product_price) }}</td>
                                            @php
                                                $taxPercent = $product?->tax ? $product?->tax->tax_percent : 0;
                                                $taxAmount = ($product->product_price / 100) * $taxPercent;
                                                $priceIncTax = $product->product_price + $taxAmount;
                                            @endphp
                                            <td style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($priceIncTax) }}</td>
                                            @if (count($priceGroups) > 0)
                                                <td>
                                                    @foreach ($priceGroups as $priceGroup)
                                                        <p style="font-size:10px!important;">
                                                            <span class="fw-bold">{{ $priceGroup->name }} : </span>
                                                            @php
                                                                $groupPrice = 0;
                                                            @endphp
                                                            @foreach ($product->priceGroups as $productPriceGroup)
                                                                @if ($productPriceGroup->product_id == $product->id && $priceGroup->id == $productPriceGroup->price_group_id)
                                                                    @php
                                                                        $groupPrice = $productPriceGroup->price;
                                                                    @endphp
                                                                @break
                                                            @endif
                                                        @endforeach
                                                        {{ App\Utils\Converter::format_in_bdt($groupPrice) }}
                                                    </p>
                                                @endforeach
                                            </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @elseif($product->is_variant == 1)
                        <p class="fw-bold">{{ __('Variants') }}</p>
                        <div class="table-responsive">
                            <table id="" class="table modal-table table-sm">
                                <thead>
                                    <tr class="bg-primary">
                                        <th style="font-size:10px!important;">{{ __('Variant') }}</th>
                                        <th style="font-size:10px!important;">{{ __('Code') }}</th>
                                        <th style="font-size:10px!important;">{{ __('Defautl Unit Cost (Exc. Tax)') }}</th>
                                        <th style="font-size:10px!important;">{{ __('Defautl Unit Cost (Inc. Tax)') }}</th>
                                        <th style="font-size:10px!important;">{{ __('Defautl Unit Price (Exc. Tax)') }}</th>
                                        <th style="font-size:10px!important;">{{ __('Defautl Unit Price (Inc. Tax)') }}</th>
                                        @if (count($priceGroups) > 0)
                                            <th style="font-size:10px!important;">{{ __('Price Group') }}</th>
                                        @endif
                                        <th style="font-size:10px!important;">{{ __('Variant Image') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->variants as $variant)
                                        <tr>
                                            <td style="font-size:10px!important;">{{ $variant->variant_name }}</td>
                                            <td style="font-size:10px!important;">{{ $variant->variant_code }}</td>
                                            <td style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($variant->variant_cost) }}</td>
                                            <td style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($variant->variant_cost_with_tax) }}</td>
                                            <td style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($variant->variant_price) }}</td>
                                            @php
                                                $taxPercent = $product?->tax ? $product?->tax->tax_percent : 0;
                                                $taxAmount = ($variant->variant_price / 100) * $taxPercent;
                                                $priceIncTax = $variant->variant_price + $taxAmount;
                                            @endphp
                                            <td style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($priceIncTax) }}</td>
                                            @if (count($priceGroups) > 0)
                                                <td>
                                                    @foreach ($priceGroups as $priceGroup)
                                                        <p style="font-size:10px!important;">
                                                            <span class="fw-bold">{{ $priceGroup->name }} : </span>
                                                            @php
                                                                $groupPrice = 0;
                                                            @endphp
                                                            @foreach ($variant->priceGroups as $variantPriceGroup)
                                                                @if ($variantPriceGroup->variant_id == $variant->id && $priceGroup->id == $variantPriceGroup->price_group_id)
                                                                    @php
                                                                        $groupPrice = $variantPriceGroup->price;
                                                                    @endphp
                                                                @break
                                                            @endif
                                                        @endforeach
                                                        {{ App\Utils\Converter::format_in_bdt($groupPrice) }}
                                                    </p>
                                                @endforeach
                                            </td>
                                        @endif
                                        <td style="font-size:10px!important;">
                                            @if ($variant->variant_image)
                                                <img style="width:30px;height:30px;" src="{{ file_link(fileType: 'productVariant', fileName: $variant->variant_image) }}" alt="">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        @if ($ownAndOtherBranchAndWarehouseStocks->where('branch_id', auth()->user()->branch_id)->count() > 0)
            <hr>
            <div class="row">
                <p>
                    @if (session('lang') == 'bn')
                        <span class="fw-bold">{{ __('বর্তমান') }}</span> {{ __('স্থানের পণ্যের মজুদ') }} -
                    @else
                        {{ __('Stock Details of') }} <span class="fw-bold">{{ __('Current') }}</span> {{ __('Location') }} -
                    @endif

                    @php
                        $currentBranchName = '';
                        if (auth()->user()?->branch) {
                            if (auth()->user()?->branch?->parent_branch_id) {
                                $currentBranchName = auth()->user()?->branch?->parentBranch?->name . '-' . auth()->user()?->branch?->branch_code;
                            } else {
                                $currentBranchName = auth()->user()?->branch?->name . '-' . auth()->user()?->branch?->branch_code;
                            }
                        } else {
                            $currentBranchName = $generalSettings['business_or_shop__business_name'];
                        }
                    @endphp
                    (<span class="fw-bold">{{ $currentBranchName }}</span>)
                </p>
                <div class="table-responsive">
                    <table id="" class="table modal-table table-sm">
                        <thead>
                            <tr class="bg-primary">
                                @if ($ownAndOtherBranchAndWarehouseStocks?->first()?->variant_name)
                                    <th style="font-size:10px!important;">{{ __('Variant') }}</th>
                                @endif

                                <th style="font-size:10px!important;">{{ __('Stock Location') }}</th>
                                <th style="font-size:10px!important;">{{ __('Opening Stock') }}</th>
                                <th style="font-size:10px!important;">{{ __('Purchased') }}</th>
                                <th style="font-size:10px!important;">{{ __('Purchase Returned') }}</th>
                                <th style="font-size:10px!important;">{{ __('Production') }}</th>
                                <th style="font-size:10px!important;">{{ __('Used In Production') }}</th>
                                <th style="font-size:10px!important;">{{ __('Sold') }}</th>
                                <th style="font-size:10px!important;">{{ __('Sale Returned') }}</th>
                                <th style="font-size:10px!important;">{{ __('Transferred') }}</th>
                                <th style="font-size:10px!important;">{{ __('Received Stock') }}</th>
                                <th style="font-size:10px!important;">{{ __('Stock Adjustment') }}</th>
                                <th style="font-size:10px!important;">{{ __('Curr. Stock') }}</th>
                                <th style="font-size:10px!important;">{{ __('Stock Value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ownAndOtherBranchAndWarehouseStocks->where('branch_id', auth()->user()->branch_id) as $ownAndOtherBranchAndWarehouseStock)
                                {{-- @if ($ownAndOtherBranchAndWarehouseStock->branch_id == auth()->user()->branch_id) --}}
                                @if ($ownAndOtherBranchAndWarehouseStock->is_global == 0 || $ownAndOtherBranchAndWarehouseStock->is_global == null)
                                    <tr>
                                        @if ($ownAndOtherBranchAndWarehouseStock->variant_name)
                                            <td style="font-size:10px!important;" class="fw-bold">{{ $ownAndOtherBranchAndWarehouseStock->variant_name }}</td>
                                        @endif

                                        <td style="font-size:10px!important;" class="fw-bold">
                                            @if ($ownAndOtherBranchAndWarehouseStock->warehouse_name)
                                                {{ $ownAndOtherBranchAndWarehouseStock->warehouse_name . '(' . __('WH') . ')' }}
                                            @else
                                                @if ($ownAndOtherBranchAndWarehouseStock->branch_id)
                                                    @if ($ownAndOtherBranchAndWarehouseStock->parent_branch_name)
                                                        {{ $ownAndOtherBranchAndWarehouseStock->parent_branch_name . '(' . $ownAndOtherBranchAndWarehouseStock->area_name . ')-' . $ownAndOtherBranchAndWarehouseStock->branch_code }}
                                                    @else
                                                        {{ $ownAndOtherBranchAndWarehouseStock->branch_name . '(' . $ownAndOtherBranchAndWarehouseStock->area_name . ')-' . $ownAndOtherBranchAndWarehouseStock->branch_code }}
                                                    @endif
                                                @else
                                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                                @endif
                                            @endif
                                        </td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_opening_stock) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_purchase) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_purchase_return) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_production) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_used_in_production) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_sale) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_sales_return) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_transferred) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_received) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_stock_adjustment) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">
                                            @if ($product->is_manage_stock == 1)
                                                {{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->stock) . '/' . $product?->unit?->code_name }}
                                            @else
                                                {{ App\Utils\Converter::format_in_bdt(0) . '/' . $product?->unit?->code_name }}
                                            @endif
                                        </td>

                                        @php
                                            $currentStock = $ownAndOtherBranchAndWarehouseStock->stock;
                                            $avgUnitCost = $currentStock > 0 ? $ownAndOtherBranchAndWarehouseStock->total_cost / $currentStock : $product->product_cost;
                                            $stockValue = $avgUnitCost * $currentStock;
                                        @endphp
                                        <td style="font-size:10px!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($product->is_manage_stock == 1 ? $stockValue : $product->product_cost_with_tax) }}</td>
                                    </tr>
                                @endif
                                {{-- @endif --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <hr>
            <div class="row">
                <p>
                    @if (session('lang') == 'bn')
                        <span class="fw-bold">{{ __('বর্তমান') }}</span> {{ __('স্থানের পণ্যের মজুদ') }} -
                    @else
                        {{ __('Stock Details of') }} <span class="fw-bold">{{ __('Current') }}</span> {{ __('Location') }} -
                    @endif

                    @php
                        $currentBranchName = '';
                        if (auth()->user()?->branch) {
                            if (auth()->user()?->branch?->parent_branch_id) {
                                $currentBranchName = auth()->user()?->branch?->parentBranch?->name . '-' . auth()->user()?->branch?->branch_code;
                            } else {
                                $currentBranchName = auth()->user()?->branch?->name . '-' . auth()->user()?->branch?->branch_code;
                            }
                        } else {
                            $currentBranchName = $generalSettings['business_or_shop__business_name'];
                        }
                    @endphp
                    (<span class="fw-bold">{{ $currentBranchName }}</span>)
                </p>
                <div class="table-responsive">
                    <table id="" class="table modal-table table-sm">
                        <thead>
                            <tr class="bg-primary">
                                @if ($ownAndOtherBranchAndWarehouseStocks?->first()?->variant_name)
                                    <th style="font-size:10px!important;">{{ __('Variant') }}</th>
                                @endif

                                <th style="font-size:10px!important;">{{ __('Stock Location') }}</th>
                                <th style="font-size:10px!important;">{{ __('Opening Stock') }}</th>
                                <th style="font-size:10px!important;">{{ __('Purchased') }}</th>
                                <th style="font-size:10px!important;">{{ __('Purchase Returned') }}</th>
                                <th style="font-size:10px!important;">{{ __('Production') }}</th>
                                <th style="font-size:10px!important;">{{ __('Used In Production') }}</th>
                                <th style="font-size:10px!important;">{{ __('Sold') }}</th>
                                <th style="font-size:10px!important;">{{ __('Sale Returned') }}</th>
                                <th style="font-size:10px!important;">{{ __('Transferred') }}</th>
                                <th style="font-size:10px!important;">{{ __('Received Stock') }}</th>
                                <th style="font-size:10px!important;">{{ __('Stock Adjustment') }}</th>
                                <th style="font-size:10px!important;">{{ __('Curr. Stock') }}</th>
                                <th style="font-size:10px!important;">{{ __('Stock Value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-size:10px!important;" class="fw-bold text-danger text-center" colspan="13">{{ __('Product Is Not Available in Current Location') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @php
            $isShowOtherStockInDetails = isset($generalSettings['product__is_show_other_stock_in_details']) ? $generalSettings['product__is_show_other_stock_in_details'] : 1;
        @endphp

        @if ($isShowOtherStockInDetails == 1)
            @if ($ownAndOtherBranchAndWarehouseStocks->where('branch_id', '!=', auth()->user()->branch_id)->count() > 0)

                <hr>
                <div class="row">
                    <p>{{ __('Stock Details of Other Location(s)') }}</p>
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm">
                            <thead>
                                <tr class="bg-primary">
                                    @if ($ownAndOtherBranchAndWarehouseStocks?->first()?->variant_name)
                                        <th style="font-size:10px!important;">{{ __('Variant') }}</th>
                                    @endif

                                    <th style="font-size:10px!important;">{{ __('Stock Location') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Opening Stock') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Purchased') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Purchase Returned') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Production') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Used In Production') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Sold') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Sale Returned') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Transferred') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Received Stock') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Stock Adjustment') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Curr. Stock') }}</th>
                                    <th style="font-size:10px!important;">{{ __('Stock Value') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ownAndOtherBranchAndWarehouseStocks->where('branch_id', '!=', auth()->user()->branch_id) as $ownAndOtherBranchAndWarehouseStock)
                                    {{-- @if ($ownAndOtherBranchAndWarehouseStock->branch_id != auth()->user()->branch_id) --}}
                                    <tr>
                                        @if ($ownAndOtherBranchAndWarehouseStock->variant_name)
                                            <td style="font-size:10px!important;" class="fw-bold">{{ $ownAndOtherBranchAndWarehouseStock->variant_name }}</td>
                                        @endif

                                        <td style="font-size:10px!important;" class="fw-bold">
                                            @php
                                                $branchName = '';
                                                if ($ownAndOtherBranchAndWarehouseStock->branch_id) {
                                                    if ($ownAndOtherBranchAndWarehouseStock->parent_branch_name) {
                                                        $branchName = $ownAndOtherBranchAndWarehouseStock->parent_branch_name . '(' . $ownAndOtherBranchAndWarehouseStock->area_name . ')-' . $ownAndOtherBranchAndWarehouseStock->branch_code;
                                                    } else {
                                                        $branchName = $ownAndOtherBranchAndWarehouseStock->branch_name . '(' . $ownAndOtherBranchAndWarehouseStock->area_name . ')-' . $ownAndOtherBranchAndWarehouseStock->branch_code;
                                                    }
                                                } else {
                                                    $branchName = $generalSettings['business_or_shop__business_name'];
                                                }

                                                $warehouseName = $ownAndOtherBranchAndWarehouseStock->warehouse_name ? ' / ' . $ownAndOtherBranchAndWarehouseStock->warehouse_name . '-' . $ownAndOtherBranchAndWarehouseStock->warehouse_code . '(' . __('WH') . ')' : '';
                                            @endphp

                                            {{ $branchName . $warehouseName }}
                                        </td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_opening_stock) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_purchase) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_purchase_return) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_production) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_used_in_production) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_sale) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_sales_return) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_transferred) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_received) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->total_stock_adjustment) . '/' . $product?->unit?->code_name }}</td>
                                        <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">
                                            @if ($product->is_manage_stock == 1)
                                                {{ App\Utils\Converter::format_in_bdt($ownAndOtherBranchAndWarehouseStock->stock) . '/' . $product?->unit?->code_name }}
                                            @else
                                                {{ App\Utils\Converter::format_in_bdt(0) . '/' . $product?->unit?->code_name }}
                                            @endif
                                        </td>

                                        @php
                                            $currentStock = $ownAndOtherBranchAndWarehouseStock->stock;
                                            $avgUnitCost = $currentStock > 0 ? $ownAndOtherBranchAndWarehouseStock->total_cost / $currentStock : $product->product_cost;
                                            $stockValue = $avgUnitCost * $currentStock;
                                        @endphp
                                        <td style="font-size:10px!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($product->is_manage_stock == 1 ? $stockValue : $product->product_cost_with_tax) }}</td>
                                    </tr>
                                    {{-- @endif --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif

        {{-- @if (count($globalWareHouseStocks) > 0)
            <div class="row">
                <p class="fw-bold">{{ __('Global Warehouse Stock Details') }}</p>
                <div class="table-responsive">
                    <table id="" class="table modal-table table-sm">
                        <thead>
                            <tr class="bg-primary">
                                @if ($globalWareHouseStocks?->first()?->variant_name)
                                    <th style="font-size:10px!important;">{{ __('Variant') }}</th>
                                @endif
                                <th style="font-size:10px!important;">{{ __('Stock Location') }}</th>
                                <th style="font-size:10px!important;">{{ __('Opening Stock') }}</th>
                                <th style="font-size:10px!important;">{{ __('Purchased') }}</th>
                                <th style="font-size:10px!important;">{{ __('Purchase Returned') }}</th>
                                <th style="font-size:10px!important;">{{ __('Production') }}</th>
                                <th style="font-size:10px!important;">{{ __('Used In Production') }}</th>
                                <th style="font-size:10px!important;">{{ __('Sold') }}</th>
                                <th style="font-size:10px!important;">{{ __('Sale Returned') }}</th>
                                <th style="font-size:10px!important;">{{ __('Transferred') }}</th>
                                <th style="font-size:10px!important;">{{ __('Received Stock') }}</th>
                                <th style="font-size:10px!important;">{{ __('Stock Adjustment') }}</th>
                                <th style="font-size:10px!important;">{{ __('Curr. Stock') }}</th>
                                <th style="font-size:10px!important;">{{ __('Stock Value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($globalWareHouseStocks as $globalWareHouseStock)
                                <tr>
                                    @if ($globalWareHouseStock->variant_name)
                                        <td style="font-size:10px!important;" class="fw-bold">{{ $globalWareHouseStock->variant_name }}</td>
                                    @endif

                                    <td style="font-size:10px!important;" class="fw-bold">
                                        @if ($globalWareHouseStock->warehouse_name)
                                            {{ $globalWareHouseStock->warehouse_name }}
                                        @else
                                            @if ($globalWareHouseStock->branch_id)
                                                @if ($globalWareHouseStock->parent_branch_name)
                                                    {{ $globalWareHouseStock->parent_branch_name . '(' . $globalWareHouseStock->area_name . ')-' . $globalWareHouseStock->branch_code }}
                                                @else
                                                    {{ $globalWareHouseStock->branch_name . '(' . $globalWareHouseStock->area_name . ')-' . $globalWareHouseStock->branch_code }}
                                                @endif
                                            @else
                                                {{ $generalSettings['business_or_shop__business_name'] }}
                                            @endif
                                        @endif
                                    </td>
                                    <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($globalWareHouseStock->total_opening_stock) . '/' . $product?->unit?->code_name }}</td>
                                    <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($globalWareHouseStock->total_purchase) . '/' . $product?->unit?->code_name }}</td>
                                    <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($globalWareHouseStock->total_purchase_return) . '/' . $product?->unit?->code_name }}</td>
                                    <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($globalWareHouseStock->total_production) . '/' . $product?->unit?->code_name }}</td>
                                    <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($globalWareHouseStock->total_used_in_production) . '/' . $product?->unit?->code_name }}</td>
                                    <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($globalWareHouseStock->total_sale) . '/' . $product?->unit?->code_name }}</td>
                                    <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($globalWareHouseStock->total_sales_return) . '/' . $product?->unit?->code_name }}</td>
                                    <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($globalWareHouseStock->total_transferred) . '/' . $product?->unit?->code_name }}</td>
                                    <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($globalWareHouseStock->total_received) . '/' . $product?->unit?->code_name }}</td>
                                    <td style="font-size:10px!important;color:#dc3545!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($globalWareHouseStock->total_stock_adjustment) . '/' . $product?->unit?->code_name }}</td>
                                    <td style="font-size:10px!important;color:#198754!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($globalWareHouseStock->stock) . '/' . $product?->unit?->code_name }}</td>

                                    @php
                                        $currentStock = $globalWareHouseStock->stock;
                                        $avgUnitCost = $currentStock > 0 ? $globalWareHouseStock->total_cost / $currentStock : $product->product_cost;
                                        $stockValue = $avgUnitCost * $currentStock;
                                    @endphp
                                    <td style="font-size:10px!important;" class="fw-bold">{{ App\Utils\Converter::format_in_bdt($stockValue) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif --}}

        {{-- @if ($product->is_manage_stock == 1)

                    @if (count($own_warehouse_stocks) > 0)
                        <hr class="m-0">

                        <div class="row">
                            <div class="heading">
                                <label class="p-0 m-0">OWN <strong>@lang('menu.warehouse') : </strong> @lang('menu.stock_details') </label>
                            </div>
                            <div class="table-responsive" id="warehouse_stock_details">
                                <!--Warehouse Stock Details-->
                                @include('product.products.ajax_view.partials.own_warehouse_stock_details')
                                <!--Warehouse Stock Details End-->
                            </div>
                        </div>
                    @endif

                    @if (count($global_warehouse_stocks) > 0)
                        <hr class="m-0">

                        <div class="row">
                            <div class="heading">
                                <label class="p-0 m-0">GLOBAL <strong>@lang('menu.warehouse') : </strong> @lang('menu.stock_details') </label>
                            </div>
                            <div class="table-responsive" id="warehouse_stock_details">
                                <!--Warehouse Stock Details-->
                                @include('product.products.ajax_view.partials.global_warehouse_stock_details')
                                <!--Warehouse Stock Details End-->
                            </div>
                        </div>
                    @endif

                    <hr class="m-0">

                    <div class="row">
                        <div class="heading">
                            <label class="p-0 m-0">@lang('menu.own')<strong>@lang('menu.business_location')</strong> @lang('menu.stock_details') </label>
                        </div>
                        <div class="table-responsive" id="branch_stock_details">
                            @include('product.products.ajax_view.partials.branch_stock_details')
                        </div>
                    </div>

                    <hr class="m-0">



                        <div class="row">
                            <div class="heading">
                                <label class="p-0 m-0">@lang('menu.another') : <strong>@lang('menu.business_location')</strong> @lang('menu.stock_details') </label>
                            </div>
                            <div class="table-responsive" id="branch_stock_details">
                                @include('product.products.ajax_view.partials.another_branch_details')
                            </div>
                        </div>

                @endif --}}

        <div class="modal-footer text-end">
            <div class="row">
                <div class="col-md-12">
                    @if (auth()->user()->can('product_edit'))
                        <a href="{{ route('products.edit', [$product->id]) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                    @endif
                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
