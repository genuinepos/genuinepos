<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 20px;margin-right: 20px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed;top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
<div class="row">
    <div class="col-12 text-center">

        @if ($branch_id == '')
            <h5>{{ $generalSettings['business']['shop_name'] }} </h5>
            <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business']['address'] }}</p>

            @if ($addons->branches == 1)

                <p><strong>@lang('menu.all_business_location')</strong></p>
            @endif

        @elseif ($branch_id == 'NULL')

            <h5>{{ $generalSettings['business']['shop_name'] }} </h5>
            <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business']['address'] }}</p>
        @else

            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp
            <h5>{{ $branch->name }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
        @endif

        @if ($fromDate && $toDate)
            <p><b>@lang('menu.date') :</b> {{date($generalSettings['business']['date_format'] ,strtotime($fromDate)) }} <b>@lang('menu.to')</b> {{ date($generalSettings['business']['date_format'] ,strtotime($toDate)) }} </p>
        @endif

        <p class="mt-2"><b>@lang('menu.customer_payments') </b></p>
    </div>
</div>

<div class="supplier_details_area mt-1">
    <div class="row">
        <div class="col-8">
            <ul class="list-unstyled">
                <li><strong>@lang('menu.customer') : </strong> {{ $customer->name }} (ID: {{ $customer->contact_id }})</li>
                <li><strong>@lang('menu.phone') : </strong> {{ $customer->phone }}</li>
                <li><strong>@lang('menu.address') : </strong> {{ $customer->address  }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.date')</th>
                    <th class="text-start">@lang('menu.voucher_no')</th>
                    <th class="text-start">@lang('menu.reference')</th>
                    <th class="text-start">@lang('menu.against_invoice')</th>
                    {{-- <th>@lang('menu.created_by')</th> --}}
                    <th class="text-start">@lang('menu.payment_status')</th>
                    <th class="text-start">@lang('menu.payment_type')</th>
                    <th class="text-start">@lang('menu.account')</th>
                    <th class="text-end">@lang('menu.less_amount')</th>
                    <th class="text-end">@lang('menu.paid_amount')</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($payments as $row)
                    <tr>
                        <td class="text-start">
                            @php
                                $dateFormat = $generalSettings['business']['date_format'];
                                $__date_format = str_replace('-', '/', $dateFormat);
                            @endphp

                            {{ date($__date_format, strtotime($row->report_date)) }}
                        </td>

                        <td class="text-start">
                            {{ $row->customer_payment_voucher . $row->sale_payment_voucher }}
                        </td>

                        <td class="text-start">{{ $row->reference }}</td>

                        <td class="text-start">
                            @if ($row->sale_inv || $row->return_inv)

                                @if ($row->sale_inv)

                                    {{ 'Sale : ' . $row->sale_inv}}
                                @else

                                    {{ 'Sale Return : ' . $row->return_inv }}
                                @endif
                            @endif
                        </td>

                        <td class="text-start">
                            @if ($row->voucher_type == 3 || $row->voucher_type == 5)

                                {{ 'Received Payment' }}
                            @else

                                {{ 'Return Payment' }}
                            @endif
                        </td>

                        <td class="text-start">
                            {{  $row->cp_pay_mode . $row->cp_payment_method . $row->sp_pay_mode . $row->sp_payment_method }}
                        </td>

                        <td class="text-start">
                            @if ($row->sp_account)

                                {{ $row->cp_account . '(A/C:' . $row->cp_account_number . ')' }}
                            @else

                                {{ $row->sp_account . '(A/C:' . $row->sp_account_number . ')' }}
                            @endif
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->less_amount) }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->amount) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-12 text-center">
            <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer">
    <small style="font-size: 5px;float:right;" class="text-end">
        @lang('menu.print_date'): {{ date('d-m-Y , h:iA') }}
    </small>
</div>
