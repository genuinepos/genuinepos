<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:10px; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
    th { font-size:9px!important; font-weight: 550!important;}
    td { font-size:8px;}
</style>

<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')

            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} (@lang('menu.head_office'))</h5>
            <p style="width: 60%; margin:0 auto;">
                {{ json_decode($generalSettings->business, true)['address'] }}
            </p>
            <p><b>@lang('menu.all_business_location')</b></p>
        @elseif ($branch_id == 'NULL')

            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} (@lang('menu.head_office'))</h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
        @else

            @php

                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp
            <h5>{{ $branch->name . ' ' . $branch->branch_code }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
        @endif

        @if ($fromDate && $toDate)

            <p><b>@lang('menu.date') :</b>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <b>@lang('menu.to')</b> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif

        <h6 style="margin-top: 10px;"><b>Sale Statements </b></h6>
    </div>
</div>
<br>

@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

    $totalItems = 0;
    $TotalNetTotal = 0;
    $TotalOrderDiscount = 0;
    $TotalOrderTax = 0;
    $TotalShipmentCharge = 0;
    $TotalSaleAmount = 0;
    $TotalPaymentReceived = 0;
    $TotalReturnedAmount = 0;
    $TotalSaleDue = 0;
@endphp
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.date')</th>
                    <th class="text-start">@lang('menu.invoice_id')</th>
                    <th class="text-start">@lang('menu.stock_location')</th>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-start">@lang('menu.entered_by')</th>
                    <th class="text-end">@lang('menu.total_item')</th>
                    <th class="text-end">{{ __('Net total Amt') }}.</th>
                    <th class="text-end">@lang('menu.order_discount')</th>
                    <th class="text-end">@lang('menu.order_tax')</th>
                    <th class="text-end">@lang('menu.shipment_charge')</th>
                    <th class="text-end">{{ __('Total Payable Amt') }}.</th>
                    <th class="text-end">@lang('menu.paid_amount').</th>
                    <th class="text-end">@lang('menu.return_amount')</th>
                    <th class="text-end">{{ __('Due Amt') }}.</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($sales as $sale)
                    <tr>
                        <td class="text-start">{{ date($__date_format, strtotime($sale->date))}}</td>
                        <td class="text-start">{{ $sale->invoice_id }}</td>
                        <td class="text-start">
                            @if ($sale->branch_name)

                                 {!! $sale->branch_name . '/' . $sale->branch_code . '(<b>BL</b>)' !!}
                            @else

                                {!! json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)' !!}
                            @endif
                        </td>

                        <td class="text-start">
                            {{ $sale->customer_name ? $sale->customer_name : 'Walk-In-Customer' }}
                        </td>

                        <td class="text-start">
                            {{ $sale->u_prefix . ' ' . $sale->u_name . ' ' . $sale->u_last_name }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->total_item) }}
                            @php
                                $totalItems += $sale->total_item;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                            @php
                                $TotalNetTotal += $sale->net_total_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                            @php
                                $TotalOrderDiscount += $sale->order_discount_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount). '(' . $sale->order_tax_percent . '%)' }}
                            @php
                                $TotalOrderTax += $sale->order_tax_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                            @php
                                $TotalShipmentCharge += $sale->shipment_charge;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                            @php
                                $TotalSaleAmount += $sale->total_payable_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                            @php
                                $TotalPaymentReceived += $sale->paid;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->sale_return_amount) }}
                            @php
                                $TotalReturnedAmount += $sale->sale_return_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                            @php
                                $TotalSaleDue += $sale->due;
                            @endphp
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- <div style="page-break-after: {{ count($sales) > 30 ? 'always' : '' }};"></div> --}}
<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered">
            <thead>

                <tr>
                    <th class="text-end">Total Sold Item : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalItems) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">{{ __('Total Net Total Amount') }} : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">{{ __('Total Order Discount') }} : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderDiscount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">{{ __('Total Order Tax') }} : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderTax) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">Total @lang('menu.shipment_charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalShipmentCharge) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">Total Sale Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalSaleAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_payment_received') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalPaymentReceived) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_return') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalReturnedAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalSaleDue) }}
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>


@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">
    <small style="font-size: 5px;" class="text-end">
        @lang('menu.print_date'): {{ date('d-m-Y , h:iA') }}
    </small>
</div>
