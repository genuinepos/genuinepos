<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:9px!important; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

    .print_table th { font-size:11px!important; font-weight: 550!important; line-height: 12px!important}
    .print_table tr td{color: black; font-size:10px!important; line-height: 12px!important}

    .print_area { font-family: Arial, Helvetica, sans-serif; }
    .print_area h6 { font-size: 14px!important; }
    .print_area p { font-size: 11px!important; }
    .print_area small{font-size: 8px!important;}
</style>

<div class="print_area">
    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-4 mb-1">
            @if (auth()->user()?->branch)
                @if (auth()->user()?->branch?->parent_branch_id)

                    @if (auth()->user()?->branch?->parentBranch?->logo != 'default.png')

                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else

                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else

                    @if (auth()->user()?->branch?->logo != 'default.png')

                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->logo) }}">
                    @else

                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business__business_logo'] != null)

                    <img style="height: 45px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                @else

                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__shop_name'] }}</span>
                @endif
            @endif
        </div>

        <div class="col-8 text-end">

            <p style="text-transform: uppercase;" class="p-0 m-0">
                <strong>
                    @if (auth()->user()?->branch)
                        @if (auth()->user()?->branch?->parent_branch_id)

                            {{ auth()->user()?->branch?->parentBranch?->name }}
                        @else

                            {{ auth()->user()?->branch?->name }}
                        @endif
                    @else

                        {{ $generalSettings['business__shop_name'] }}
                    @endif
                </strong>
            </p>

            <p>
                @if (auth()->user()?->branch)

                    {{ auth()->user()?->branch?->city . ', ' . auth()->user()?->branch?->state. ', ' . auth()->user()?->branch?->zip_code. ', ' . auth()->user()?->branch?->country }}
                @else

                    {{ $generalSettings['business__address'] }}
                @endif
            </p>

            <p>
                @if (auth()->user()?->branch)

                    <strong>{{ __("Email") }} : </strong> {{ auth()->user()?->branch?->email }},
                    <strong>{{ __("Phone") }} : </strong> {{ auth()->user()?->branch?->phone }}
                @else

                    <strong>{{ __("Email") }} : </strong> {{ $generalSettings['business__email'] }},
                    <strong>{{ __("Phone") }} : </strong> {{ $generalSettings['business__phone'] }}
                @endif
            </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 text-center">
            <h6 style="text-transform:uppercase;"><strong>{{ __("Payments Against Purchase Report") }}</strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            @if ($fromDate && $toDate)
                <p>
                    <strong>{{ __("From") }} :</strong>
                    {{ date($generalSettings['business__date_format'], strtotime($fromDate)) }}
                    <strong>{{ __("To") }} : </strong> {{ date($generalSettings['business__date_format'], strtotime($toDate)) }}
                </p>
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-6">
            @php
                $ownOrParentbranchName = $generalSettings['business__shop_name'];
                if (auth()->user()?->branch) {

                    if (auth()->user()?->branch->parentBranch) {

                        $ownOrParentbranchName = auth()->user()?->branch->parentBranch?->name . '(' . auth()->user()?->branch->parentBranch?->area_name . ')';
                    } else {

                        $ownOrParentbranchName = auth()->user()?->branch?->name . '(' . auth()->user()?->branch?->area_name . ')';
                    }
                }
            @endphp
            <p><strong>{{ __("Shop/Business") }} : </strong> {{ $filteredBranchName ? $filteredBranchName : $ownOrParentbranchName }} </p>
        </div>

        <div class="col-6">
            <p><strong>{{ __("Supplier") }} : </strong> {{ $filteredSupplierName }} </p>
        </div>
    </div>

    @php
        $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);
        $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
        $totalPaidAmount = 0;
    @endphp

    <div class="row mt-1">
        <div class="col-12">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th>{{ __("Payment Voucher") }}</th>
                        <th>{{ __("Payment Date") }}</th>
                        <th>{{ __("Shop/Business") }}</th>
                        <th>{{ __('Purchase/Order') }}</th>
                        <th>{{ __('date') }}</th>
                        <th>{{ __("Supplier") }}</th>
                        <th class="text-end">{{ __("Purchased Amount") }}</th>
                        <th>{{ __("Credit A/c") }}</th>
                        <th>{{ __("Type/Method") }}</th>
                        <th class="text-end">{{ __("Paid Amount") }}</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @foreach ($paidAgainstPurchases as $paidAgainstPurchase)
                        <tr>
                            <td class="text-start fw-bold">{{ $paidAgainstPurchase->voucherDescription?->accountingVoucher?->voucher_no }}</td>

                            <td class="text-start">
                                @php
                                    $receiptDate = $paidAgainstPurchase->voucherDescription?->accountingVoucher?->date;
                                @endphp
                                {{ date($__date_format, strtotime($receiptDate)) }}
                            </td>

                            <td class="text-start">
                                @php
                                    $branchName = '';
                                    $branch_id = $paidAgainstPurchase?->voucherDescription?->accountingVoucher?->branch_id;
                                    if ($branch_id) {

                                        $branch = $paidAgainstPurchase?->voucherDescription?->accountingVoucher?->branch;
                                        $parentBranch = $paidAgainstPurchase?->voucherDescription?->accountingVoucher?->branch?->parentBranch;
                                        if ($parentBranch) {

                                            $branchName = $parentBranch->name . '(' . $branch->area_name . ')';
                                        } else {

                                            $branchName = $branch->name . '(' . $branch->area_name . ')';
                                        }
                                    } else {

                                        $branchName = $generalSettings['business__shop_name'];
                                    }
                                @endphp

                                {{ $branchName }}
                            </td>

                            <td class="text-start fw-bold">
                                @php
                                    $purchaseId = $paidAgainstPurchase?->purchase?->id;
                                    $invoiceId = $paidAgainstPurchase?->purchase?->invoice_id;
                                    $purchaseStatus = $paidAgainstPurchase?->purchase?->purchase_status;

                                    $purchaseVoucherNo = '';
                                    if ($purchaseStatus == App\Enums\PurchaseStatus::Purchase->value) {

                                        $purchaseVoucherNo = __('Purchase') . ':' .  $invoiceId;
                                    } else if ($purchaseStatus == App\Enums\PurchaseStatus::PurchaseOrder->value) {

                                        $purchaseVoucherNo = __('P/o') . ':' . $invoiceId;
                                    }
                                @endphp

                                {{ $purchaseVoucherNo }}
                            </td>

                            <td class="text-start">
                                @php
                                    $purchaseDate = $paidAgainstPurchase?->purchase?->date;
                                @endphp

                                {{ date($__date_format, strtotime($purchaseDate)) }}
                            </td>

                            <td class="text-start">
                                {{ $paidAgainstPurchase?->purchase?->supplier?->name; }}
                            </td>

                            <td class="text-end fw-bold">
                                @php
                                    $purchasedAmount = $paidAgainstPurchase?->purchase ? $paidAgainstPurchase?->purchase?->total_purhcase_amount : 0;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($purchasedAmount) }}
                            </td>

                            <td class="text-end fw-bold">
                                @php
                                    $accountName = $paidAgainstPurchase?->voucherDescription?->accountingVoucher?->voucherCreditDescription?->account?->name;
                                    $accountNumber = $paidAgainstPurchase?->voucherDescription?->accountingVoucher?->voucherCreditDescription?->account?->account_number;

                                    $__accountNumber = $accountNumber ? ' / ' . $accountNumber : '';
                                @endphp
                                {{ $accountName . $__accountNumber }}
                            </td>

                            <td class="text-end fw-bold">
                                {{ $paidAgainstPurchase?->voucherDescription?->accountingVoucher?->voucherCreditDescription?->paymentMethod?->name }}
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($paidAgainstPurchase->amount) }}
                                @php
                                    $totalPaidAmount += $paidAgainstPurchase->amount;
                                @endphp
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-6 offset-6">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-end">{{ __("Total Paid Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalPaidAmount) }}
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="footer">
        <div class="row">
            <div class="col-4 text-start">
                <small>{{ __("Print Date") }} : {{ date($__date_format) }}</small>
            </div>

            <div class="col-4 text-center">
                @if (config('company.print_on_sale'))
                    <small>{{ __("Powered By") }} <strong>{{ __("Speed Digit Software Solution") }}.</strong></small>
                @endif
            </div>

            <div class="col-4 text-end">
                <small>{{ __("Print Time") }} : {{ date($timeFormat) }}</small>
            </div>
        </div>
    </div>
</div>