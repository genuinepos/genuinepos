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

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 4%;
        margin-right: 4%;
    }

    .header,
    .header-space,
    .footer,
    .footer-space {
        height: 20px;
    }

    .header {
        position: fixed;
        top: 0;
    }

    .footer {
        position: fixed;
        bottom: 0;
    }

    .noBorder {
        border: 0px !important;
    }

    tr.noBorder td {
        border: 0px !important;
    }

    tr.noBorder {
        border: 0px !important;
        border-left: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }
</style>

<div class="row">
    <div class="col-md-12 text-center">
        @if (!auth()->user()->branch_id)
            <h5>{{ $generalSettings['business_or_shop__business_name'] }} <b>(@lang('menu.head_office'))</b></h5>
            <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business_or_shop__address'] }}</p>
        @else
            @php
                $branch = DB::table('branches')
                    ->where('id', auth()->user()->branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp
            <h5>{{ $branch->name . ' ' . $branch->branch_code }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city . ', ' . $branch->state . ', ' . $branch->zip_code . ', ' . $branch->country }}</p>
        @endif

        @if ($fromDate && $toDate)
            <p><b>@lang('menu.date') : </b>
                {{ date($generalSettings['business_or_shop__date_format'], strtotime($fromDate)) }}
                <b>@lang('menu.to')</b> {{ date($generalSettings['business_or_shop__date_format'], strtotime($toDate)) }}
            </p>
        @endif
        <h6 style="margin-top: 10px;"><b>{{ __('Loan Report') }} </b></h6>
    </div>
</div>

@if ($company_id)
    @php
        $company = DB::table('loan_companies')
            ->where('id', $company_id)
            ->first();
    @endphp
    <div class="customer_details_area">
        <div class="row">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li><strong>@lang('menu.company')/@lang('menu.people') : </strong> {{ $company->name }}</li>
                    <li><strong>@lang('menu.phone') : </strong> </li>
                    <li><strong>@lang('menu.address') : </strong> </li>
                </ul>
            </div>
        </div>
    </div>
    <br>
@endif
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.date')</th>
                    <th class="text-start">@lang('menu.b_location')</th>
                    <th class="text-start">@lang('menu.reference_no')</th>
                    <th class="text-start">@lang('menu.company')/@lang('menu.people')</th>
                    <th class="text-start">@lang('menu.type')</th>
                    <th class="text-start">@lang('menu.loan_by')</th>
                    <th class="text-end">@lang('menu.loan_amount')({{ $generalSettings['business_or_shop__currency'] }})</th>
                    <th class="text-end">@lang('menu.total_paid')({{ $generalSettings['business_or_shop__currency'] }})</th>
                    <th class="text-end">{{ __('Loan Due') }}({{ $generalSettings['business_or_shop__currency'] }})</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @php
                    $totalPayLoan = 0;
                    $totalGetLoan = 0;
                    $totalPaid = 0;
                    $totalReceive = 0;
                    $totalPayLoanDue = 0;
                    $totalGetLoanDue = 0;
                @endphp
                @foreach ($loans as $loan)
                    <tr>
                        <td class="text-start">{{ date($generalSettings['business_or_shop__date_format'], strtotime($loan->report_date)) }}</td>
                        <td class="text-start">
                            @if ($loan->b_name)
                                {!! $loan->b_name . '/' . $loan->b_code . '(<b>BL</b>)' !!}
                            @else
                                {!! $generalSettings['business_or_shop__business_name'] . '(<b>HO</b>)' !!}
                            @endif
                        </td>

                        <td class="text-start">{{ $loan->reference_no }}</td>
                        <td class="text-start">{{ $loan->c_name }}</td>

                        <td class="text-start">
                            @if ($loan->type == 1)
                                {{ __('Pay Loan') }}
                            @else
                                {{ __('Receive Loan') }}
                            @endif
                        </td>

                        <td class="text-start">
                            @if ($loan->loan_by)
                                {{ $loan->loan_by }}
                            @else
                                {{ __('Cash Loan pay') }}
                            @endif
                        </td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($loan->loan_amount) }}
                            @php
                                if ($loan->type == 1) {
                                    $totalPayLoan += $loan->loan_amount;
                                } else {
                                    $totalGetLoan += $loan->loan_amount;
                                }
                            @endphp
                        </td>
                        <td class="text-end">
                            {{ $loan->type == 1 ? App\Utils\Converter::format_in_bdt($loan->total_receive) : App\Utils\Converter::format_in_bdt($loan->total_paid) }}
                            @php
                                $totalPaid += $loan->total_paid;
                                $totalReceive += $loan->total_receive;
                            @endphp
                        </td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($loan->due) }}
                            @php
                                if ($loan->type == 1) {
                                    $totalPayLoanDue += $loan->due;
                                } else {
                                    $totalGetLoanDue += $loan->due;
                                }
                            @endphp
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <p><b>{{ __('Get Laon Details') }}</b></p>
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">{{ __('Total Get loan') }} : {{ $generalSettings['business_or_shop__currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalGetLoan) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_due_paid') : {{ $generalSettings['business_or_shop__currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalPaid) }}</td>
                </tr>

                <tr>
                    <th class="text-end">{{ __('Total Get Loan Due') }} : {{ $generalSettings['business_or_shop__currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalGetLoanDue) }}</td>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-6">
        <p><b>{{ __('Pay Loan Details') }}</b></p>
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">{{ __('Total Pay loan') }} : {{ $generalSettings['business_or_shop__currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalPayLoan) }}</td>
                </tr>

                <tr>
                    <th class="text-end">{{ __('Total Due Receive') }} : {{ $generalSettings['business_or_shop__currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalReceive) }}</td>
                </tr>

                <tr>
                    <th class="text-end">{{ __('Total Pay Loan Due') }} : {{ $generalSettings['business_or_shop__currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalPayLoanDue) }}</td>
                </tr>
            </thead>
        </table>
    </div>
</div>

@if (config('speeddigit.show_app_info_in_print') == true)
    <div class="row">
        <div class="col-md-12 text-center">
            <small>{{ config('speeddigit.app_name_label_name') }} <b>{{ config('speeddigit.name') }}</b> | {{ __("M:") }} {{ config('speeddigit.phone') }}</small>
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">
    <small style="font-size: 5px;" class="text-end">
        @lang('menu.print_date'): {{ date('d-m-Y , h:iA') }}
    </small>
</div>
