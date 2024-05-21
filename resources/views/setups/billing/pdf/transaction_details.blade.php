<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $rtl = app()->isLocale('ar');
@endphp

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>Bill - {{ config('app.name') }}</title>

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <style>
        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(var(--bs-gutter-y) * -1);
            margin-right: calc(var(--bs-gutter-x) * -.5);
            margin-left: calc(var(--bs-gutter-x) * -.5)
        }

        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: calc(var(--bs-gutter-x) * .5);
            padding-left: calc(var(--bs-gutter-x) * .5);
            margin-top: var(--bs-gutter-y)
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-accent-bg: transparent;
            --bs-table-striped-color: #212529;
            --bs-table-striped-bg: rgba(0, 0, 0, 0.05);
            --bs-table-active-color: #212529;
            --bs-table-active-bg: rgba(0, 0, 0, 0.1);
            --bs-table-hover-color: #212529;
            --bs-table-hover-bg: rgba(0, 0, 0, 0.075);
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            vertical-align: top;
            border-color: #dee2e6
        }

        .table>:not(caption)>*>* {
            padding: .5rem .5rem;
            background-color: var(--bs-table-bg);
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg)
        }

        .table>tbody {
            vertical-align: inherit
        }

        .table>thead {
            vertical-align: bottom
        }

        .table>:not(:last-child)>:last-child>* {
            border-bottom-color: currentColor
        }

        .table-sm>:not(caption)>*>* {
            padding: .25rem .25rem
        }

        .table-bordered>:not(caption)>* {
            border-width: 1px 0
        }

        .table-bordered>:not(caption)>*>* {
            border-width: 0 1px
        }

        .mt-1 {
            margin-top: .25rem !important
        }

        .mt-2 {
            margin-top: .5rem !important
        }

        table {
            caption-side: bottom;
            border-collapse: collapse
        }

        th {
            text-align: inherit;
            text-align: -webkit-match-parent
        }

        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-color: inherit;
            border-style: solid;
            border-width: 0
        }

        .text-start {
            text-align: left !important
        }

        .text-end {
            text-align: right !important
        }

        .text-center {
            text-align: center !important
        }
    </style>
</head>

<body id="dashboard-8">
    <style>
        @media print {
            table {
                page-break-after: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        /* @page {
            size: a4;
            margin-top: 0.8cm;
            margin-bottom: 35px;
            margin-left: 20px;
            margin-right: 20px;
        } */

        div#footer {
            position: fixed;
            top: 95%;
            bottom: 5px;
            left: 0px;
            width: 100%;
            height: 0%;
            color: #CCC;
            background: #333;
            padding: 0;
            margin: 0;
        }

        p {
            font-size: 11px;
            padding: 0px;
            margin: 0px;
            line-height: 1.5;
        }
    </style>

    @php
        $dateFormat = $generalSettings['business_or_shop__date_format'];
        $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    @endphp

    <div class="sale_print_template">
        <div class="details_area">
            <table class="table table-sm">
                <tr style="border-bottom: 1px solid;">
                    <td>
                          {{-- <img src="{{ asset('modules/saas/images/logo_black.png') }}" width="100" alt="{{ config('app.name') }}" style="background: gray;boarder-radius:20px;"> --}}
                          <h2 style="text-transform: uppercase;font-weight:bolder;padding:0px;margin:0px;">{{ config('app.name') }}</h2>
                    </td>
                    <td class="text-end">
                        <p style="text-transform: uppercase;padding:0px;">
                            {{ config('speeddigit.name') }}
                        </p>

                        <p>
                            {{ config('speeddigit.address') }}
                        </p>

                        <p>
                            <span class="fw-bold">{{ __('Email') }}</span> : {{ config('speeddigit.email') }},
                            <span class="fw-bold">{{ __('Phone') }}</span> : {{ config('speeddigit.phone') }}
                        </p>
                    </td>
                </tr>
            </table>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 style="text-transform: uppercase;">Bill</h5>
                </div>
            </div>

            <div class="row mt-1">
                <table>
                    <tr>
                        <td class="text-start">
                            <table>
                                <tr>
                                    <th>
                                        <p>{{ __('Customer') }}</p>
                                    </th>
                                    <td>
                                        <p>: {{ optional($transaction->subscription)->user->name }}</p>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <p>{{ __('Address') }}</p>
                                    </th>
                                    <td>
                                        <p>: {{ optional($transaction->subscription)->user->address }}</p>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <p>{{ __('Email') }}</p>
                                    </th>
                                    <td>
                                        <p>: {{ optional($transaction->subscription)->user->email }}</p>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <p>{{ __('Phone') }}</p>
                                    </th>
                                    <td>
                                        <p>: {{ optional($transaction->subscription)->user->phone }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td class="text-start">
                            <table>
                                <tr>
                                    <th>
                                        <p>{{ __('Date') }}</p>
                                    </th>
                                    <td>
                                        <p>: {{ $transaction->created_at->format('d-m-Y') }}</p>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <p>{{ __('Invoice ID') }}</p>
                                    </th>
                                    <td>
                                        <p>: #</p>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <p>{{ __('Transaction Type') }}</p>
                                    </th>
                                    <td>
                                        <p>: {{ App\Enums\SubscriptionTransactionType::tryFrom($transaction->transaction_type)->name }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="sale_product_table pt-2 pb-2">
                @if ($transaction->details_type == 'direct_buy_plan' || $transaction->details_type == 'upgrade_plan_from_trial')
                    @include('setups.billing.pdf.partials.buy_plan_table_details')
                @elseif ($transaction->details_type == 'upgrade_plan_from_real_plan')
                    @include('setups.billing.pdf.partials.upgrade_plan_from_real_plan_table_details')
                @elseif ($transaction->details_type == 'shop_renew')
                    @include('setups.billing.pdf.partials.show_renew_table_details')
                @elseif ($transaction->details_type == 'add_shop')
                    @include('setups.billing.pdf.partials.add_shop_table_details')
                @elseif ($transaction->details_type == 'add_business')
                    @include('setups.billing.pdf.partials.add_business_table_details')
                @endif
            </div>

            <br>
            <div class="row">
                <div class="col-12 text-center">
                    <p style="font-size: 10px!important;">{{ __('Thanks For Using Our Service.') }}</p>
                </div>
            </div><br>

            <div id="footer">
                <div class="row mt-1">
                    <table>
                        <tr>
                            <td><small style="font-size: 9px!important;color:black;">{{ __('Generated Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small></td>
                            <td class="text-center"><small style="font-size: 9px!important;color:black;" class="d-block">{{ __('Powered By') }} <span class="fw-bold">@lang('SpeedDigit Software Solution').</span></small></td>
                            <td class="text-end"><small style="font-size: 9px!important;color:black;">{{ __('Generated Time') }} : {{ date($timeFormat) }}</small></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
