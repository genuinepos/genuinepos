<!--begin::Form-->
@php
    $currency = json_decode($generalSettings->business, true)['currency'];
@endphp
<div class="form-group row">
    @if ($addons->branches == 1)
        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
            <div class="col-md-6">
                <select name="branch_id" id="today_branch_id" class="form-control">
                    <option value="">All Business Locations</option>
                    <option {{ $branch_id == 'HF' ? 'SELECTED' : '' }} value="HF">{{ json_decode($generalSettings->business, true)['shop_name'] }}(Head Office)</option>
                    @foreach ($branches as $br)
                        <option {{ $branch_id == $br->id ? 'SELECTED' : '' }} value="{{ $br->id }}">{{ $br->name.'/'.$br->branch_code }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    @endif

    <div class="col-md-6">
        <div class="loader d-none">
            <i class="fas fa-sync fa-spin ts_preloader text-primary"></i> <b>Processing...</b>  
        </div>
    </div>
</div>

<div class="print_body">
    <div class="today_summery_area mt-2">
        <div class="print_today_summery_header d-none">
            <div class="row text-center">
                @if ($addons->branches == 1)
                    <h4>
                        @if ($branch_id == 'HF')
                            {{ json_decode($generalSettings->business, true)['shop_name'] }} <strong>(Head Office)</strong>
                        @elseif($branch_id == '')
                            All Business Locations.
                        @else 
                            {{ $branch->name.'/'.$branch->branch_code }}
                        @endif
                    </h4>
                @else 
                    <h4>{{ json_decode($generalSettings->business, true)['shop_name'] }} <strong>(HO)</strong></h4>
                @endif
            </div>

            <div class="row text-center">
                <p>Today Summery</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">Total Purchase :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPurchase) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total Adjustment :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($total_adjustment) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total Expense :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalExpense) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total Sale Discount :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSaleDiscount) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Transfer Shiping Charge :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalTransferShippingCost) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Purchanse Shiping Charge :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($purchaseTotalShipmentCost) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total Customer Reward :</th>
                            <td class="text-start">{{ $currency }} 0.00 (P)</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total Sale Return :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSalesReturn) }}</td>
                        </tr>

                        @if ($addons->hrm == 1)
                            <tr>
                                <th class="text-start">Total Payroll :</th>
                                <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPayroll) }}</td>
                            </tr>
                        @endif
                        

                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">Current Stock :</th>
                            <td class="text-start">{{ $currency }} 0.00</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total sale :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSales) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total Stock Recovered :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($total_recovered) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total Purchase Return :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPurchaseReturn) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total Sale Shipping Charge :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSalesShipmentCost) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total Round Off :</th>
                            <td class="text-start">{{ $currency }} 0.00 (P)</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">Today Net Profit :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($todayProfit) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="print_today_summery_footer d-none">
            <br><br>
            <div class="row">
                <div class="col-6">
                    <p><strong>CHECKED BY :</strong></p>
                </div>
                <div class="col-6 text-end">
                    <p><strong>APPROVED BY :</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>