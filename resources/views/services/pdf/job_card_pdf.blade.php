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
    @php
        $dateFormat = $generalSettings['business_or_shop__date_format'];
        $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    @endphp

<body id="dashboard-8">
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
                page-break-after: auto, font-size:9px !important;
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
            margin-left: 5px;
            margin-right: 5px;
        }

        div#footer {
            position: fixed;
            bottom: 20px;
            left: 0px;
            width: 100%;
            height: 0%;
            color: #CCC;
            background: #333;
            padding: 0;
            margin: 0;
        }

        /* Pdf Css */
        * {
            background-color: #fff;
            font-size: 10px;
        }

        table thead tr th {
            color: black !important;
        }

        table thead tr {
            background-color: black !important;
        }

        .fw-bold {
            font-weight: 600 !important;
        }

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
            text-align: -webkit-match-parent;
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

        .d-block {
            display: block;
        }

        .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .col-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }

        .col-10 {
            flex: 0 0 83.33333%;
            max-width: 83.33333%;
        }


        .bordered {
            border: 2px solid black;
        }

        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .col-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .col-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }

        .col-2 {
            flex: 0 0 20%;
            max-width: 20%;
        }

        .borderTop {
            border-top: 1px solid rgb(44, 42, 42);
            padding-top: 4px;
            display: inline;
            padding: 0 8px;
        }

        .pt-3 {
            padding-top: 1rem !important;
        }

        .pb-3 {
            padding-bottom: 1rem !important;
        }

        .text-end {
            text-align: right !important;
        }

        .text-start {
            text-align: left !important;
        }

        .text-center {
            text-align: center !important;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        table {
            border-collapse: collapse;
        }

        .table-bordered {
            border: 1px solid #000000;
            padding-top: 4px;
        }

        table-sm th,
        .table-sm td {
            padding: 1px !important;
        }

        .list-unstyled {
            padding-left: 0;
            list-style: none;
        }

        .me {
            margin-right: 2%;
        }

        .table-sm>:not(caption)>*>* {
            padding: .25rem .25rem
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem !important;
        }

        /* .sale_product_table table tbody tr td{font-size: 13px;} */
        .footer_area table thead th {
            line-height: 15px;
            font-size: 7px;
        }

        .description_area {
            font-weight: 400;
        }

        .footer_text_area th {
            line-height: 10px;
            font-size: 8px;
        }

        .d-body tr th {
            font-weight: 300 !important;
            font-size: 10px;
        }

        /* .d-body tr th{border-bottom: 1px solid rgb(46, 45, 45);} */
        .t-head tr th {
            font-size: 9px;
            border-bottom: 1px solid rgb(46, 45, 45);
        }

        /* Pos print Design End*/
        /* Pdf Css End */
    </style>

    <div class="sale_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black;">
                <table class="table table-sm">
                    <tr>
                        <td>
                            @if ($jobCard->branch)
                                @if ($jobCard?->branch?->parent_branch_id)

                                    @if ($jobCard->branch?->parentBranch?->logo)
                                        <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $jobCard->branch?->parentBranch?->logo) }}">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:rgb(29, 28, 28);text-transform:uppercase;">{{ $jobCard->branch?->parentBranch?->name }}</span>
                                    @endif
                                @else
                                    @if ($jobCard->branch?->logo)
                                        <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $jobCard->branch?->logo) }}">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:rgb(10, 10, 10);text-transform:uppercase;">{{ $jobCard->branch?->name }}</span>
                                    @endif
                                @endif
                            @else
                                @if ($generalSettings['business_or_shop__business_logo'] != null)
                                    <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:rgb(16, 16, 16);text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                                @endif
                            @endif
                        </td>
                        <td class="text-end">
                            <p style="text-transform: uppercase;" class="p-0 m-0 fw-bold">
                                @if ($jobCard?->branch)
                                    @if ($jobCard?->branch?->parent_branch_id)
                                        {{ $jobCard?->branch?->parentBranch?->name }}
                                    @else
                                        {{ $jobCard?->branch?->name }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            </p>

                            <p>
                                @if ($jobCard?->branch)
                                    {{ $jobCard->branch->address . ', ' }}
                                    {{ $jobCard->branch->city . ', ' }}
                                    {{ $jobCard->branch->state . ', ' }}
                                    {{ $jobCard->branch->zip_code . ', ' }}
                                    {{ $jobCard->branch->country }}
                                @else
                                    {{ $generalSettings['business_or_shop__address'] }}
                                @endif
                            </p>

                            <p>
                                @php
                                    $email = $jobCard?->branch ? $jobCard?->branch?->email : $generalSettings['business_or_shop__email'];
                                    $phone = $jobCard?->branch ? $jobCard?->branch?->phone : $generalSettings['business_or_shop__phone'];
                                @endphp

                                <span class="fw-bold">{{ __('Email') }}</span> : {{ $email }},

                                <span class="fw-bold">{{ __('Phone') }}</span> : {{ $phone }}
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <h5 style="text-transform: uppercase;">{{ __("Job Card") }}</h5>
                </div>
            </div>

            <div class="sale_product_table pt-2 pb-2">
                <table class="table print-table table-sm">
                    <tbody>
                        <tr>
                            <td rowspan="3">
                                <p><span class="fw-bold">{{ __('Date') }} : </span> {{ date($dateFormat, strtotime($jobCard->date_ts)) }}</p>
                                <p><span class="fw-bold">{{ __('Delivery Date') }} : </span> {{ $jobCard->delivery_date_ts ? date($dateFormat, strtotime($jobCard->delivery_date_ts)) : '' }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="fw-bold">{{ __('Service type') }} : </span> {{ str(\App\Enums\ServiceType::tryFrom($jobCard->service_type)->name)->headline() }}
                            </td>
                            <td rowspan="2">
                                <span class="fw-bold">{{ __('Due Date') }}</span> : {{ $jobCard->due_date_ts ? date($dateFormat, strtotime($jobCard->due_date_ts)) : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td><span class="fw-bold">{{ __('Job No') }} :</span> {{ $jobCard->job_no }}</td>
                        </tr>
                        <tr>
                            @if (isset($generalSettings['service_settings_pdf_label__show_customer_info']) && $generalSettings['service_settings_pdf_label__show_customer_info'] == '1')
                                <td colspan="2">
                                    <p>
                                        <span class="fw-bold">{{ isset($generalSettings['service_settings_pdf_label__customer_label_name']) ? $generalSettings['service_settings_pdf_label__customer_label_name'] : __('Customer') }} :</span>
                                        {{ $jobCard?->customer?->name }}
                                    </p>

                                    @if (isset($generalSettings['service_settings_pdf_label__show_contact_id']) && $generalSettings['service_settings_pdf_label__show_contact_id'] == '1')
                                        <p>
                                            <span class="fw-bold">{{ isset($generalSettings['service_settings_pdf_label__customer_id_label_name']) ? $generalSettings['service_settings_pdf_label__customer_id_label_name'] : __('Customer ID') }} :</span>
                                            {{ $jobCard?->customer?->contact?->contact_id }}
                                        </p>
                                    @endif

                                    @if (isset($generalSettings['service_settings_pdf_label__show_customer_tax_no']) && $generalSettings['service_settings_pdf_label__show_customer_tax_no'] == '1')
                                        <p>
                                            <span class="fw-bold">{{ isset($generalSettings['service_settings_pdf_label__customer_tax_no_label_name']) ? $generalSettings['service_settings_pdf_label__customer_tax_no_label_name'] : __('Tax No.') }} :</span>
                                            {{ $jobCard?->customer?->contact?->tax_number }}
                                        </p>
                                    @endif

                                    <p>
                                        <span class="fw-bold">{{ __('Address') }} :</span>
                                        {{ $jobCard?->customer?->address }}
                                    </p>

                                    <p>
                                        <span class="fw-bold">{{ __('Phone') }} :</span>
                                        {{ $jobCard?->customer?->phone }}
                                    </p>
                                </td>
                            @endif

                            <td>
                                <p>
                                    <span class="fw-bold">{{ __('Brand.') }} :</span>
                                    {{ $jobCard?->brand?->name }}
                                </p>

                                <p>
                                    <span class="fw-bold">{{ __('Device') }} :</span>
                                    {{ $jobCard?->device?->name }}
                                </p>

                                <p>
                                    <span class="fw-bold">{{ __('Device Model') }} :</span>
                                    {{ $jobCard?->deviceModel?->name }}
                                </p>

                                <p>
                                    <span class="fw-bold">{{ __('Serial Number') }} :</span>
                                    {{ $jobCard?->serial_number }}
                                </p>

                                <p>
                                    <span class="fw-bold">{{ __('Password') }} :</span>
                                    {{ $jobCard?->password }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="fw-bold">{{ __('Invoice ID') }} : </td>
                            <td>{{ $jobCard?->sale?->invoice_id }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="fw-bold">{{ __('Status') }} :</td>
                            <td> {{ $jobCard?->status?->name }}</td>
                        </tr>

                        <tr>
                            <td colspan="2" class="fw-bold">{{ __('Comment By Technician') }}:</td>
                            <td>{{ $jobCard->technical_comment }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="fw-bold">{{ __('Pre Service Checklist') }} :</td>
                            <td>
                                @if (isset($jobCard->service_checklist) && is_array($jobCard->service_checklist))
                                    @foreach ($jobCard->service_checklist as $key => $value)
                                        <span>
                                            @if ($value == 'yes')
                                                OK
                                            @elseif ($value == 'no')
                                                X
                                            @else
                                                N/A
                                            @endif
                                            {{ $key }}
                                        </span>
                                    @endforeach
                                @elseif (isset($jobCard->service_checklist) && is_string($jobCard->service_checklist))
                                    @php
                                        $checklist = json_decode($jobCard->service_checklist, true);
                                    @endphp

                                    @if ($checklist === null)
                                        <p>Error decoding JSON: {{ json_last_error_msg() }}</p>
                                    @else
                                        @foreach ($checklist as $key => $value)
                                            <span>
                                                @if ($value == 'yes')
                                                    OK
                                                @elseif ($value == 'no')
                                                    ❌
                                                @else
                                                    🚫
                                                @endif
                                                {{ $key }}
                                            </span>
                                        @endforeach
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <span class="fw-bold">{{ __('Pick Up/On Site Address') }} : </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <span class="fw-bold">{{ __('Product Configuration') }} : </span> {{ $jobCard->product_configuration }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <span class="fw-bold">{{ __('Condition Of The Product') }} : </span> {{ $jobCard->product_condition }}
                            </td>
                        </tr>


                        <tr>
                            <th colspan="3">{{ __('Service Changes') }}:</th>
                        </tr>

                        <tr>
                            <td colspan="3">
                                @php
                                    $serviceProducts = $jobCard->jobCardProducts
                                        ->filter(function ($jobCardProduct) {
                                            return $jobCardProduct?->product?->is_manage_stock == 0;
                                        })
                                        ->values();
                                @endphp
                                @if (count($serviceProducts) > 0)
                                    <table class="table print-table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('S/L') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Description') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Qty') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Price (Exc. Tax)') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Discount') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Vat/Tax') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Price (Inc. Tax)') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Subtotal') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="sale_print_product_list">

                                            @foreach ($serviceProducts as $index => $jobCardProduct)
                                                <tr>
                                                    @php
                                                        $variant = $jobCardProduct->variant ? ' - ' . $jobCardProduct->variant->variant_name : '';
                                                    @endphp

                                                    <td class="text-start" style="font-size:10px!important;">{{ $index + 1 }}
                                                    </td>

                                                    <td class="text-start" style="font-size:10px!important;">{{ Str::limit($jobCardProduct->product->name, 25) . ' ' . $variant }}
                                                    </td>
                                                    <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->quantity) }}</td>
                                                    <td class="text-start" style="font-size:10px!important;">
                                                        {{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_exc_tax) }}
                                                    </td>
                                                    <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_discount) }} </td>
                                                    <td class="text-start" style="font-size:10px!important;">{{ '(' . $jobCardProduct->unit_tax_percent . '%)=' . $jobCardProduct->unit_tax_amount }}</td>
                                                    <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_inc_tax) }}</td>
                                                    <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->subtotal) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <table class="table print-table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">{{ __('No Available') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="3">{{ __('Parts Description') }}:</th>
                        </tr>

                        <tr>
                            <td colspan="3">
                                @php
                                    $parts = $jobCard->jobCardProducts
                                        ->filter(function ($jobCardProduct) {
                                            return $jobCardProduct?->product?->is_manage_stock == 1;
                                        })
                                        ->values();
                                @endphp
                                @if (count($parts) > 0)
                                    <table class="table print-table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('S/L') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Description') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Qty') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Price (Exc. Tax)') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Discount') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Vat/Tax') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Price (Inc. Tax)') }}</th>
                                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Subtotal') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="sale_print_product_list">

                                            @foreach ($parts as $index => $jobCardProduct)
                                                <tr>
                                                    @php
                                                        $variant = $jobCardProduct->variant ? ' - ' . $jobCardProduct->variant->variant_name : '';
                                                    @endphp

                                                    <td class="text-start" style="font-size:10px!important;">{{ $index + 1 }}
                                                    </td>

                                                    <td class="text-start" style="font-size:10px!important;">{{ Str::limit($jobCardProduct->product->name, 25) . ' ' . $variant }}
                                                    </td>
                                                    <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->quantity) }}</td>
                                                    <td class="text-start" style="font-size:10px!important;">
                                                        {{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_exc_tax) }}
                                                    </td>
                                                    <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_discount) }} </td>
                                                    <td class="text-start" style="font-size:10px!important;">{{ '(' . $jobCardProduct->unit_tax_percent . '%)=' . $jobCardProduct->unit_tax_amount }}</td>
                                                    <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_inc_tax) }}</td>
                                                    <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->subtotal) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <table class="table print-table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">{{ __('No Available') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">
                                {{ isset($generalSettings['service_settings__custom_field_1_label']) ? $generalSettings['service_settings__custom_field_1_label'] : __('Custom Field 1') }} :
                            </th>
                            <td>{{ $jobCard->custom_field_1 }}</td>
                        </tr>

                        <tr>
                            <th colspan="2">
                                {{ isset($generalSettings['service_settings__custom_field_2_label']) ? $generalSettings['service_settings__custom_field_2_label'] : __('Custom Field 2') }} :
                            </th>
                            <td>{{ $jobCard->custom_field_2 }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">
                                {{ isset($generalSettings['service_settings__custom_field_3_label']) ? $generalSettings['service_settings__custom_field_3_label'] : __('Custom Field 3') }} :
                            </th>
                            <td>{{ $jobCard->custom_field_3 }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">
                                {{ isset($generalSettings['service_settings__custom_field_4_label']) ? $generalSettings['service_settings__custom_field_4_label'] : __('Custom Field 4') }} :
                            </th>
                            <td>
                                {{ $jobCard->custom_field_4 }}
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2">
                                {{ isset($generalSettings['service_settings__custom_field_5_label']) ? $generalSettings['service_settings__custom_field_5_label'] : __('Custom Field 5') }} :
                            </th>
                            <td>
                                {{ $jobCard->custom_field_5 }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <p><span class="fw-bold">{{ __('Problem Reported By The Customer') }} : </span> {{ $jobCard->problems_report }}</p>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <p> <span class="fw-bold">{{ __('Terms & Conditions') }} : </span> {!! isset($generalSettings['service_settings__terms_and_condition']) ? $generalSettings['service_settings__terms_and_condition'] : __('Customer ID') !!}</p>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2" style="height: 50px; vertical-align: bottom; width: 50%;">
                                {{ __('Customer signature') }}:
                            </th>
                            <th style="height: 50px; vertical-align: bottom; width: 50%;">
                                {{ __('Authorized signature') }}:
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>

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
