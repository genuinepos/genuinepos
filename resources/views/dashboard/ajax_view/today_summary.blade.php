<!--begin::Form-->
@php
    $currency = $generalSettings['business__currency'];
@endphp
<div class="form-group row">
    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
        <div class="col-md-6">
            <select name="branch_id" id="today_branch_id" class="form-control">
                <option value="">@lang('menu.all_business_locations')</option>
                <option {{ $branch_id == 'HF' ? 'SELECTED' : '' }} value="HF">{{ $generalSettings['business__shop_name'] }}(@lang('menu.head_office'))</option>
                @foreach ($branches as $br)
                    <option {{ $branch_id == $br->id ? 'SELECTED' : '' }} value="{{ $br->id }}">{{ $br->name.'/'.$br->branch_code }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="col-md-6">
        <div class="loader d-hide">
            <i class="fas fa-sync fa-spin ts_preloader text-primary"></i> <b>{{ __("Processing") }}...</b>
        </div>
    </div>
</div>

<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed;top: 0;}
    .footer {position: fixed;bottom: 0;}
</style>

<div class="print_body">
    <div class="today_summery_area mt-2">
        <div class="print_today_summery_header d-hide">
            <div class="row text-center">
                @if ($generalSettings['addons__branches'] == 1)
                    <h4>
                        @if ($branch_id == 'HF')
                            {{ $generalSettings['business__shop_name'] }} <strong>(@lang('menu.head_office'))</strong>
                        @elseif($branch_id == '')
                        @lang('menu.all_business_locations').
                        @else
                            {{ $branch->name.'/'.$branch->branch_code }}
                        @endif
                    </h4>
                @else
                    <h4>{{ $generalSettings['business__shop_name'] }} <strong>(HO)</strong></h4>
                @endif
            </div>

            <div class="row text-center">
                <p>{{ __('Today Summery') }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="display table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">{{ __("Total Purchased") }}</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPurchase) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __('Total Payment') }} </th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPayment) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __("Total Purchase Due") }}</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPurchaseDue) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __("Total Stock Adjustment") }}</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($total_adjustment) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __("Total Expense") }}</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalExpense) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __('Total Sale Discount') }} </th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSaleDiscount) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __('Transfer Shipping Charge') }} </th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalTransferShippingCost) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __('purchase Shipping Charge') }} </th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($purchaseTotalShipmentCost) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total Sales Return</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSalesReturn) }}</td>
                        </tr>

                        @if ($generalSettings['addons__hrm'] == 1)
                            <tr>
                                <th class="text-start">{{ __("Total Payroll") }}</th>
                                <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPayroll) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="display table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">{{ __("Current Stock") }}</th>
                            <td class="text-start">{{ $currency }} 0.00</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __("Total Sale") }}</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSales) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __("Total Received") }}</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalReceive) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __("Total Sale Due") }}</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSaleDue) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __("Total Stock Recovered") }}</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($total_recovered) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __("Total Purchased Return") }}</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPurchaseReturn) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __('Total Sale Shipping Charge') }}</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSalesShipmentCost) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">{{ __('Total Round Off') }}</th>
                            <td class="text-start">{{ $currency }} 0.00 (P)</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">{{ __('Today Profit/Loss') }} </th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($todayProfit) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="print_today_summery_footer d-hide">
            <br><br>
            <div class="row">
                <div class="col-6">
                    <p><strong>{{ __("Checked By") }}</strong></p>
                </div>
                <div class="col-6 text-end">
                    <p><strong>{{ __("Approved") }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
