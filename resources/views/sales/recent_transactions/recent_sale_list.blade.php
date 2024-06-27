@php
    $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);
@endphp
@if (count($sales))
    @foreach ($sales as $sale)
        @php
            $branchName = '';
            if ($sale->branch_id) {
                if ($sale?->parent_branch_name) {
                    $branchName = $sale?->parent_branch_name . '(' . $sale?->branch_area_name . ')' . '-(' . $sale?->branch_code . ')';
                } else {
                    $branchName = $sale?->branch_name . '(' . $sale?->branch_area_name . ')' . '-(' . $sale?->branch_code . ')';
                }
            } else {
                $branchName = $generalSettings['business_or_shop__business_name'];
            }
        @endphp
        <tr>
            <td class="text-start fw-bold">{{ $loop->index + 1 }}</td>
            <td class="text-start fw-bold">
                @if ($sale->status == \App\Enums\SaleStatus::Final->value)
                    {{ $sale->invoice_id }}
                @elseif($sale->status == \App\Enums\SaleStatus::Quotation->value)
                    {{ $sale->quotation_id }}
                @elseif($sale->status == \App\Enums\SaleStatus::Draft->value)
                    {{ $sale->draft_id }}
                @endif
            </td>
            <td class="text-start">{{ date($__date_format, strtotime($sale->date)) }}</td>
            <td class="text-start">{{ $sale->customer_name }}</td>
            <td class="text-start fw-bold">{{ \App\Utils\Converter::format_in_bdt($sale->total_invoice_amount) }}</td>
            <td class="text-start">
                @if ($sale->status == \App\Enums\SaleStatus::Final->value)
                    @if ($sale->sale_screen == \App\Enums\SaleScreenType::AddSale->value)
                        <a href="{{ route('sales.edit', $sale->id) }}" title="Edit"> <i class="far fa-edit text-info"></i></a>
                    @else
                        <a id="editPosSale" href="{{ route('sales.pos.edit', $sale->id) }}" title="Edit"> <i class="far fa-edit text-info"></i></a>
                    @endif
                @elseif($sale->status == \App\Enums\SaleStatus::Quotation->value)
                    @if ($sale->sale_screen == \App\Enums\SaleScreenType::AddSale->value)
                        <a href="{{ route('sale.quotations.edit', $sale->id) }}" title="Edit"> <i class="far fa-edit text-info"></i></a>
                    @else
                        <a id="editPosSale" href="{{ route('sales.pos.edit', [$sale->id, $sale->sale_screen]) }}" title="Edit"> <i class="far fa-edit text-info"></i></a>
                    @endif
                @elseif($sale->status == \App\Enums\SaleStatus::Draft->value)
                    @if ($sale->sale_screen == \App\Enums\SaleScreenType::AddSale->value)
                        <a href="{{ route('sale.drafts.edit', $sale->id) }}" title="Edit"> <i class="far fa-edit text-info"></i></a>
                    @else
                        <a id="editPosSale" href="{{ route('sales.pos.edit', [$sale->id, $sale->sale_screen]) }}" title="Edit"> <i class="far fa-edit text-info"></i></a>
                    @endif
                @endif

                @php
                    $filename = $sale->invoice_id . $sale->draft_id . $sale->quotation_id . '__' . $sale->date . '__' . $branchName;
                @endphp

                <a href="{{ route('sales.helper.related.voucher.print', $sale->id) }}" onclick="printSalesRelatedVoucher(this); return false;" title="{{ __('Print') }}" data-filename="{{ $filename }}"> <i class="fas fa-print text-secondary"></i></a>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td class="fw-bold text-center" colspan="6">{{ __('Data Not Found') }}</td>
    </tr>
@endif
