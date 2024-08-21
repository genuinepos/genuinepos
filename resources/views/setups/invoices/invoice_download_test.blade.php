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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

        @page {
            size: a4;
            margin-top: 0.8cm;
            margin-bottom: 35px;
            margin-left: 20px;
            margin-right: 20px;
        }

        div#footer {
            position: fixed;
            bottom: 22px;
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
                    <td><img src="{{ asset('logo.png') }}" width="40" alt="Logo"></td>
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
                                        <p>: {{ optional($transaction->subscription)->user->persent_address }}</p>
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
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr style="border-bottom: 1px solid">
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Plan') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Store Count') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period Count') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        <tr>
                            <td style="font-size:11px!important;">Basic</td>
                            <td style="font-size:11px!important;">6,580.00</td>
                            <td style="font-size:11px!important;">1</td>
                            <td style="font-size:11px!important;">Years</td>
                            <td style="font-size:11px!important;">1</td>
                            <td style="font-size:11px!important;">6,580.00</td>
                        </tr>
                        <tr>
                            <td style="font-size:11px!important;">{{ __("Multi Store Management System") }}({{ __("Company") }})</td>
                            <td style="font-size:11px!important;">6,580.00</td>
                            <td style="font-size:11px!important;">...</td>
                            <td style="font-size:11px!important;">Years</td>
                            <td style="font-size:11px!important;">1</td>
                            <td style="font-size:11px!important;">6,580.00</td>
                        </tr>

                        <tr style="border-top: 1px solid">
                            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total') }} :{{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction->net_total) }}</td>
                        </tr>

                        <tr>
                            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                            <td style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($transaction->discount) }}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                            <td style="font-size:11px!important;">
                                FREE
                            </td>
                        </tr>

                        <tr>
                            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Payable') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                            <td style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($transaction->total_payable_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Paid') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>

                            <td style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($transaction->paid) }}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Due') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                            <td style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($transaction->due) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>
            <div class="row">
                <div class="col-12 text-center">
                    <p style="font-size: 10px!important;">Thanks For Using Our Service.</p>
                </div>
            </div><br>

            <div id="footer">
                <div class="row mt-1">
                    <table>
                        <tr>
                            <td><small style="font-size: 9px!important;color:black;">{{ __('Generated Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small></td>
                            <td class="text-center">
                                @if (config('speeddigit.show_app_info_in_print') == true)
                                    <small style="font-size: 9px!important;color:black;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __('M:') }} {{ config('speeddigit.phone') }}</small>
                                @endif
                            </td>
                            <td class="text-end"><small style="font-size: 9px!important;color:black;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
