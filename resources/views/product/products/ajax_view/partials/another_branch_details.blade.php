<table id="single_product_branch_stock_table" class="table modal-table table-sm">
    <thead>
        <tr class="bg-secondary">
            <th class="text-white text-start">@lang('menu.product_code')(SKU)</th>
            <th class="text-white text-start">@lang('menu.product')</th>
            <th class="text-white text-start">@lang('menu.business_location')</th>
            <th class="text-white text-start">@lang('menu.current_stock')</th>
            <th class="text-white text-start">@lang('menu.stock_value')</th>
            <th class="text-white text-start">@lang('menu.total_sale')</th>
        </tr>
    </thead>
    <tbody>
        @if (count($another_branch_stocks) > 0)
            @foreach ($another_branch_stocks as $row)
                @if ($row->branch_id != auth()->user()->branch_id)
                    @if ($row->variant_name)
                        <tr>
                            <td class="text-start">{{ $row->variant_code }}</td>
                            <td class="text-start">{{ $product->name.'-'.$row->variant_name }}</td>
                            <td class="text-start">
                                {!! $row->b_name ? $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' : json_decode($generalSettings->business, true)['shop_name'].'(<b>Head Office</b>)' !!}
                            </td>
                            <td class="text-start"><b>{{ $row->variant_quantity.'('.$product->unit->code_name.')' }}</b></td>
                            <td class="text-start">
                                @php
                                    $currentStockValue = $row->variant_cost_with_tax * $row->variant_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td>
                            <td class="text-start">{{ $row->v_total_sale.'('.$product->unit->code_name.')' }}</td>
                        </tr>
                    @else 
                        <tr>
                            <td class="text-start">{{ $product->product_code }}</td>
                            <td class="text-start">{{ $product->name }}</td>
                            <td class="text-start">
                                {!! $row->b_name ? $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' : json_decode($generalSettings->business, true)['shop_name'].'(<b>Head Office</b>)' !!}
                            </td>
                            <td class="text-start"><b>{{ $row->product_quantity.'('.$product->unit->code_name.')' }}</b></td>
                            <td class="text-start">
                                @php
                                    $currentStockValue = $product->product_cost_with_tax * $row->product_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td>
                            <td class="text-start">{{ $row->total_sale.'('.$product->unit->code_name.')' }}</td>
                        </tr>
                    @endif
                @endif
            @endforeach
        @else 
            <td colspan="6" class="text-center"><b>@lang('menu.no_data_found')</b></td>        
        @endif
        
    </tbody>
</table>