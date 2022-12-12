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

        <h6 style="margin-top: 10px;"><b>Sale Return Statements </b></h6>
    </div>
</div>
<br>

@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

    $totalItems = 0;
    $totalIQty = 0;
    $TotalNetTotal = 0;
    $TotalReturnDiscount = 0;
    $TotalReturnTax = 0;
    $TotalReturnAmount = 0;
    $TotalRefundedAmount = 0;
@endphp
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.date')</th>
                    <th class="text-start">@lang('menu.invoice_id')</th>
                    <th class="text-start">Parent Sale</th>
                    <th class="text-start">@lang('menu.stock_location')</th>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-start">@lang('menu.entered_by')</th>
                    <th class="text-end">@lang('menu.total_item')</th>
                    <th class="text-end">Total Qty</th>
                    <th class="text-end">{{ __('Net total Amt') }}.</th>
                    <th class="text-end">Return Discount</th>
                    <th class="text-end">Return Tax</th>
                    <th class="text-end">Total Return Amt.</th>
                    <th class="text-end">Refunded Amt.</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($returns as $return)
                    <tr>
                        <td class="text-start">{{ date($__date_format, strtotime($return->date))}}</td>
                        <td class="text-start">{{ $return->invoice_id }}</td>
                        <td class="text-start">{{ $return->parent_sale }}</td>
                        <td class="text-start">
                            @if ($return->branch_name)

                                 {!! $return->branch_name . '/' . $return->branch_code . '(<b>BL</b>)' !!}
                            @else

                                {!! json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)' !!}
                            @endif
                        </td>

                        <td class="text-start">
                            {{ $return->customer_name ? $return->customer_name : 'Walk-In-Customer' }}
                        </td>

                        <td class="text-start">
                            {{ $return->u_prefix . ' ' . $return->u_name . ' ' . $return->u_last_name }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($return->total_item) }}
                            @php
                                $totalItems += $return->total_item;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($return->total_qty) }}
                            @php
                                $totalIQty += $return->total_qty;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($return->net_total_amount) }}
                            @php
                                $TotalNetTotal += $return->net_total_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($return->return_discount_amount) }}
                            @php
                                $TotalReturnDiscount += $return->return_discount_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($return->return_tax_amount). '(' . $return->return_tax . '%)' }}
                            @php
                                $TotalReturnTax += $return->return_tax_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}
                            @php
                                $TotalReturnAmount += $return->total_return_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($return->total_return_due_pay) }}
                            @php
                                $TotalRefundedAmount += $return->total_return_due_pay;
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
                    <th class="text-end">Total Return Item : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalItems) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">Total Return Qty : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalIQty) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">Total Net Return Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">Total Return Discount : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalReturnDiscount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">Total Return Tax : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalReturnTax) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_return_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalReturnAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">Total Refunded Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalRefundedAmount) }}
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
