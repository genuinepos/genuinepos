@php
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<div class="sale_payment_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            <h3>
                <b>
                    @if ($payment->purchase->branch)
                        {{ $payment->purchase->branch->name . '/' . $payment->purchase->branch->branch_code }}
                    @else
                        {{ $generalSettings['business__shop_name'] }} (<b>HO</b>)
                    @endif
                </b>
            </h3>

            <h6>
                @if ($payment->purchase->branch)
                    {{ $payment->purchase->branch->name . '/' . $payment->purchase->branch->branch_code }}
                    (<b>@lang('menu.branch_concern')</b>) ,<br>
                    {{ $payment->purchase->branch ? $payment->purchase->branch->city : '' }},
                    {{ $payment->purchase->branch ? $payment->purchase->branch->state : '' }},
                    {{ $payment->purchase->branch ? $payment->purchase->branch->zip_code : '' }},
                    {{ $payment->purchase->branch ? $payment->purchase->branch->country : '' }}.
                @else
                    {{ $generalSettings['business__address'] }} <br>
                    <b>@lang('menu.phone')</b> : {{ $generalSettings['business__phone'] }} <br>
                    <b>@lang('menu.email')</b> : {{ $generalSettings['business__email'] }} <br>
                @endif
            </h6>
            <h6>@lang('menu.payment_details')</h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <h6 class="text-navy-blue"><b>@lang('menu.title') : </b>
            @if ($payment->is_advanced == 1)
                <b>@lang('menu.po_advance_payment')</b>
            @else
                {{ $payment->payment_type == 1 ? 'Purchase Payment' : 'Received Return Amt.' }}
            @endif
        </h6>
        <h6 class="text-navy-blue"><b>{{ __('P.Invoice ID') }} : </b> {{ $payment->purchase->invoice_id }}</h6>
        <h6 class="text-navy-blue"><b>@lang('menu.supplier') : </b> {{ $payment->purchase->supplier->name }}</h6>
    </div>

    <div class="total_amount_table_area pt-3">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-md">
                    <tbody>
                        <tr>
                            <th class="text-start" width="50%">@lang('menu.paid_amount') : </th>
                            <td width="50%">
                                {{ $generalSettings['business__currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('menu.payment_method') : </th>
                            <td width="50%">
                                @if ($payment->paymentMethod)
                                    {{ $payment->paymentMethod->name }}
                                @else
                                    {{ $payment->pay_mode }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">@lang('menu.voucher_no') : </th>
                            <td width="50%">
                                {{ $payment->invoice_id }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('menu.paid_on') : </th>
                            <td width="50%" class="text-navy-blue">
                                {{ date($generalSettings['business__date_format'], strtotime($payment->date))  . ' ' . date($timeFormat, strtotime($payment->time)) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('menu.payment_note') : </th>
                            <td width="50%">
                                {{ $payment->note }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>

    <div class="signature_area pt-5 mt-5 d-hide">
        <table class="w-100 pt-5">
            <tbody>
                <tr>
                    <th width="50%">@lang('menu.signature_of_authority')</th>
                    <th width="50%" class="text-end">@lang('menu.signature_of_receiver') : </th>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="2" class="text-center">@lang('menu.software_by') : <b>@lang('menu.speedDigit_pvt_ltd').</b></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>