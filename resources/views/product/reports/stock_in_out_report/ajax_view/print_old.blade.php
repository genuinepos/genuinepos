<style>
    @media print {
        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }
    }

    div#footer {
        position: fixed;
        bottom: 24px;
        left: 0px;
        width: 100%;
        height: 0%;
        color: #CCC;
        background: #333;
        padding: 0;
        margin: 0;
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 20px;
        margin-right: 20px;
    }
</style>
@php
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
    $totalStockInQty = 0;
    $totalStockOutQty = 0;
@endphp

<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h5>{{ $generalSettings['business_or_shop__business_name'] }} </h5>
            <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business_or_shop__address'] }}</p>
            <p><b>@lang('menu.all_business_location')</b></p>
        @elseif ($branch_id == 'NULL')
            <h5>{{ $generalSettings['business_or_shop__business_name'] }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business_or_shop__address'] }}</p>
        @else
            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp
            <h5>{{ $branch->name }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city . ', ' . $branch->state . ', ' . $branch->zip_code . ', ' . $branch->country }}</p>
        @endif

        <h6 style="margin-top: 10px;"><b>@lang('menu.stock_in_out_report') </b></h6>

        @if ($fromDate && $toDate)
            <p style="margin-top: 10px;"><b>@lang('menu.from') </b>
                {{ date($generalSettings['business_or_shop__date_format'], strtotime($fromDate)) }}
                <b>@lang('menu.to')</b> {{ date($generalSettings['business_or_shop__date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.product')</th>
                    <th class="text-start">@lang('menu.sale')</th>
                    <th class="text-start">@lang('menu.sale_date')</th>
                    <th class="text-start">{{ __('B. Location') }}</th>
                    <th class="text-end">{{ __('Sold/Out Qty') }}</th>
                    <th class="text-end">{{ __('Sold Price') }}({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>

                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-start">{{ __('Stock In By') }}</th>
                    <th class="text-start">{{ __('Stock In Date') }}</th>
                    <th class="text-end">@lang('menu.unit_cost')({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($stockInOuts as $row)
                    @php
                        $totalStockInQty += $row->stock_in_qty;
                        $totalStockOutQty += $row->sold_qty;
                    @endphp
                    <tr>
                        <td class="text-start">
                            @php
                                $variant = $row->variant_name ? '/' . $row->variant_name : '';
                            @endphp
                            {{ Str::limit($row->name, 20, '') . $variant }}
                        </td>
                        <td class="text-start">{{ $row->invoice_id }}</td>
                        <td class="text-start">
                            {{ date($__date_format, strtotime($row->date)) }}
                        </td>
                        <td class="text-start">
                            @if ($row->branch_name)
                                {{ $row->branch_name }}
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </td>

                        <td class="text-end">{{ $row->sold_qty }}</td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->unit_price_inc_tax) }}
                        </td>

                        <td class="text-start">{{ $row->customer_name ? $row->customer_name : 'Walk-In-Customer' }}</td>

                        <td class="text-start">
                            @if ($row->purchase_inv)
                                {{ 'Purchase:' . $row->purchase_inv }}
                            @elseif ($row->production_voucher_no)
                                {{ 'Production:' . $row->production_voucher_no }}
                            @elseif ($row->pos_id)
                                {{ 'Opening Stock' }}
                            @elseif ($row->sale_return_id)
                                {{ 'Sale Returned Stock:' . $row->sale_return_invoice }}
                            @else
                                {{ __('Non-Manageable-Stock') }}
                            @endif
                        </td>

                        <td class="text-start">
                            {{ date($__date_format, strtotime($row->stock_in_date)) }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->net_unit_cost) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">{{ __('Total Stock In Qty') }} </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalStockInQty) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">{{ __('Total Stock Out Qty') }} </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalStockOutQty) }}
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="footer">
    <div class="row mt-1">
        <div class="col-4 text-start">
            <small>@lang('menu.print_date') : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
        </div>

        <div class="col-4 text-center">
            @if (env('PRINT_SD_SALE') == true)
                <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
