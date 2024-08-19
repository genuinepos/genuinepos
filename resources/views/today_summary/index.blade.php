@php
    $currency = $generalSettings['business_or_shop__currency_symbol'];
@endphp
<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Today Summery') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="today_summery_modal_body">
            <div class="row align-items-end mb-2">
                {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                    <div class="col-md-6">
                        <label><strong>{{ location_label() }}</strong></label>
                        <select name="branch_id" class="form-control select2" id="today_summary_branch_id" autofocus>
                            <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                            <option {{ $branchId == 'NULL' ? 'SELECTED' : '' }} data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                            @foreach ($branches as $branch)
                                @php
                                    $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                    $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                    $branchCode = '-' . $branch->branch_code;
                                @endphp

                                <option {{ $branchId == $branch->id ? 'SELECTED' : '' }} data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                    {{ $branchName . $areaName . $branchCode }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="col-md-6">
                        <label><strong>{{ location_label() }} </strong></label>
                        @php
                            $branch = '';
                            if (auth()->user()?->branch) {
                                if (auth()->user()?->branch?->parentBranch) {
                                    $branch = auth()->user()?->branch?->parentBranch?->name . '(' . auth()->user()?->branch?->area_name . ')-' . auth()->user()?->branch?->branch_code;
                                } else {
                                    $branch = auth()->user()?->branch?->name . '(' . auth()->user()?->branch?->area_name . ')-' . auth()->user()?->branch?->branch_code;
                                }
                            } else {
                                $branch = $generalSettings['business_or_shop__business_name'] . '(' . __('Company') . ')';
                            }
                        @endphp
                        <input readonly type="text" class="form-control fw-bold" value="{{ $branch }}">
                    </div>
                @endif

                <div class="col-md-6">
                    <p class="loader d-hide">
                        <i class="fas fa-sync fa-spin ts_preloader text-primary"></i> <b>{{ __('Processing') }}...</b>
                    </p>
                </div>
            </div>

            <div class="today_summery_modal_contant">
                <div class="row">
                    <div class="col-md-6">
                        <table class="display table table-sm">
                            <tbody>
                                <tr>
                                    <th class="text-start">{{ __('Total Purchased') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPurchase']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Purchase Shipping Charge') }} </th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPurchaseShipmentCharge']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Purchase Return') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPurchaseReturn']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Purchase After Return') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPurchaseAfterReturn']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Payment') }} </th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPayment']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Purchase Due') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPurchaseDue']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Stock Issue') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalStockIssue']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Stock Adjustment') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalStockAdjustment']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Stock Adjustment Recovered') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalStockAdjustmentRecovered']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Expense') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalExpense']) }}</td>
                                </tr>

                                @if ($generalSettings['subscription']->features['hrm'] == 1)
                                    <tr>
                                        <th class="text-start">{{ __('Total Expense By Payroll') }}</th>
                                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPayrollPayment']) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="display table table-sm">
                            <tbody>
                                <tr>
                                    <th class="text-start">{{ __('Total Sales') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSales']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Sales Discount') }} </th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSaleDiscount']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Sales Shipment Charge') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSaleShipmentCharge']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Sales Return') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSalesReturn']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start fw-bold">
                                        <p class="m-0 p-0" style="line-height: 1!important;">{{ __('Total Sales After Return') }}</p>
                                        <small style="font-size: 8px!important;">(Today Total Sale - Today Total Return)</small>
                                    </th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSalesAfterReturn']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Received') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalReceived']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start">{{ __('Total Sales Due') }}</th>
                                    <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSalesDue']) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-start"><strong>{{ __('Today Profit/Loss') }}</strong></th>
                                    <td class="text-start">:
                                        @if ($todaySummaries['netProfit'] < 0)
                                            (<span class="text-danger">{{ $currency }} {{ App\Utils\Converter::format_in_bdt(abs($todaySummaries['netProfit'])) }}</span>)
                                        @else
                                            <span class="text-success">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['netProfit']) }}</span>
                                        @endif

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="print-button-area">
                <a href="{{ route('today.summary.print') }}" onclick="printTodaySummary(this); return false;" class="btn btn-sm btn-primary float-end" id="todaySummeryPrintBtn">{{ __('Print') }}</a>
            </div>
        </div>
    </div>
</div>

<script>
    $('#today_summary_branch_id').select2();
</script>
