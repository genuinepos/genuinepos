@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
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
            line-height: 1 !important;
            padding: 0px !important;
            margin: 0px !important;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }
    }

    @page {
        size: a4 portrait landscape;
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
</style>

<div class="sale_print_template">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if ($jobCard->branch)
                    @if ($jobCard?->branch?->parent_branch_id)

                        @if ($jobCard->branch?->parentBranch?->logo)
                            <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $jobCard->branch?->parentBranch?->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $jobCard->branch?->parentBranch?->name }}</span>
                        @endif
                    @else
                        @if ($jobCard->branch?->logo)
                            <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $jobCard->branch?->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $jobCard->branch?->name }}</span>
                        @endif
                    @endif
                @else
                    @if ($generalSettings['business_or_shop__business_logo'] != null)
                        <img style="height: 40px; width:100px;" src="{{ file_link('businessLogo', $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                    @endif
                @endif
            </div>

            <div class="col-8 text-end">
                <p style="text-transform: uppercase;font-size:10px;" class="p-0 m-0 fw-bold">
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

                <p style="font-size:10px;">
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

                <p style="font-size:10px;">
                    @php
                        $email = $jobCard?->branch ? $jobCard?->branch?->email : $generalSettings['business_or_shop__email'];
                        $phone = $jobCard?->branch ? $jobCard?->branch?->phone : $generalSettings['business_or_shop__phone'];
                    @endphp

                    <span class="fw-bold">{{ __('Email') }}</span> : {{ $email }},

                    <span class="fw-bold">{{ __('Phone') }}</span> : {{ $phone }}
                </p>
            </div>
        </div>

        <div class="sale_product_table pt-2 pb-2">
            <table class="table print-table table-sm table-bordered">
                <tbody>
                    <tr>
                        <td rowspan="3" style="font-size:10px;">
                            <p><span class="fw-bold">{{ __('Date') }} : </span> {{ date($dateFormat, strtotime($jobCard->date_ts)) }}</p>
                            <p><span class="fw-bold">{{ __('Delivery Date') }} : </span> {{ $jobCard->delivery_date_ts ? date($dateFormat, strtotime($jobCard->delivery_date_ts)) : '' }}</p>
                        </td>
                        <td colspan="2" class="fw-bold text-center" style="font-size:10px;">{{ __('Job Card') }}</td>
                    </tr>
                    <tr>
                        <td style="font-size:10px;">
                            <span class="fw-bold">{{ __('Service type') }} : </span> {{ str(\App\Enums\ServiceType::tryFrom($jobCard->service_type)->name)->headline() }}
                        </td>
                        <td rowspan="2" style="font-size:10px;">
                            <span class="fw-bold">{{ __('Due Date') }}</span> : {{ $jobCard->due_date_ts ? date($dateFormat, strtotime($jobCard->due_date_ts)) : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:10px;"><span class="fw-bold">{{ __('Job No') }} :</span> {{ $jobCard->job_no }}</td>
                    </tr>
                    <tr>
                        @if (isset($generalSettings['service_settings_pdf_label__show_customer_info']) && $generalSettings['service_settings_pdf_label__show_customer_info'] == '1')
                            <td colspan="2" style="font-size:10px;">
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

                        <td style="font-size:10px;">
                            <p>
                                <span class="fw-bold">{{ __('Brand.') }} :</span>
                                {{ $jobCard?->brand?->name }}
                            </p>

                            <p>
                                <span class="fw-bold">{{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Device') }} :</span>
                                {{ $jobCard?->device?->name }}
                            </p>

                            <p>
                                <span class="fw-bold">{{ isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] : __('Device Model') }} :</span>
                                {{ $jobCard?->deviceModel?->name }}
                            </p>

                            <p>
                                <span class="fw-bold">{{ isset($generalSettings['service_settings__serial_number_label']) ? $generalSettings['service_settings__serial_number_label'] : __('Serial Number') }} :</span>
                                {{ $jobCard?->serial_no }}
                            </p>

                            <p>
                                <span class="fw-bold">{{ __('Password') }} :</span>
                                {{ $jobCard?->password }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold" style="font-size:10px;">{{ __('Quotation ID') }} : </td>
                        <td style="font-size:10px;">{{ $jobCard?->quotation?->quotation_id }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold" style="font-size:10px;">{{ __('Invoice ID') }} : </td>
                        <td style="font-size:10px;">{{ $jobCard?->sale?->invoice_id }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold" style="font-size:10px;">{{ __('Status') }} :</td>
                        <td style="font-size:10px;"> {{ $jobCard?->status?->name }}</td>
                    </tr>

                    <tr>
                        <td colspan="2" class="fw-bold" style="font-size:10px;">{{ __('Comment By Technician') }}:</td>
                        <td style="font-size:10px;">{{ $jobCard->technician_comment }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold" style="font-size:10px;">{{ __('Pre Service Checklist') }} :</td>
                        <td style="font-size:10px;">
                            @if (isset($jobCard->service_checklist) && is_array($jobCard->service_checklist))
                                @foreach ($jobCard->service_checklist as $key => $value)
                                    <span>
                                        @if ($value == 'yes')
                                            ✔
                                        @elseif ($value == 'no')
                                            ❌
                                        @else
                                            🚫
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
                                                ✔
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
                        <td colspan="3" style="font-size:10px;">
                            <p><span class="fw-bold">{{ __('Pick Up/On Site Address') }} : </span> {{ $jobCard->address }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-size:10px;">
                            <p><span class="fw-bold">{{ __('Product Configuration') }} : </span> {{ $jobCard->product_configuration }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-size:10px;">
                            <p><span class="fw-bold">{{ __('Condition Of The Product') }} : </span> {{ $jobCard->product_condition }}</p>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" class="fw-bold" style="font-size:10px;">{{ __('Service Changes') }}:</td>
                    </tr>

                    <tr>
                        <td colspan="3" style="font-size:10px;">
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
                                            <th class="fw-bold text-start" style="font-size:10px!important; width:25%;">{{ __('Description') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Qty') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Price (Exc. Tax)') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Discount') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Vat/Tax') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Price (Inc. Tax)') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Subtotal') }}</th>
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
                                                <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->quantity) }}</td>
                                                <td class="text-end" style="font-size:10px!important;">
                                                    {{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_exc_tax) }}
                                                </td>
                                                <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_discount) }} </td>
                                                <td class="text-end" style="font-size:10px!important;">{{ '(' . $jobCardProduct->unit_tax_percent . '%)=' . $jobCardProduct->unit_tax_amount }}</td>
                                                <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_inc_tax) }}</td>
                                                <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->subtotal) }}</td>
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
                        <td colspan="3" class="fw-bold" style="font-size:10px;">{{ __('Parts Description') }}:</td>
                    </tr>

                    <tr>
                        <td colspan="3" style="font-size:10px;">
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
                                            <th class="fw-bold text-start" style="font-size:10px!important; width:25%;">{{ __('Description') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Qty') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Price (Exc. Tax)') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Discount') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Vat/Tax') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Price (Inc. Tax)') }}</th>
                                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Subtotal') }}</th>
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
                                                <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->quantity) }}</td>
                                                <td class="text-end" style="font-size:10px!important;">
                                                    {{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_exc_tax) }}
                                                </td>
                                                <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_discount) }} </td>
                                                <td class="text-end" style="font-size:10px!important;">{{ '(' . $jobCardProduct->unit_tax_percent . '%)=' . $jobCardProduct->unit_tax_amount }}</td>
                                                <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_inc_tax) }}</td>
                                                <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->subtotal) }}</td>
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
                        <td colspan="2" class="fw-bold" style="font-size:10px;">
                            {{ isset($generalSettings['service_settings__custom_field_1_label']) ? $generalSettings['service_settings__custom_field_1_label'] : __('Custom Field 1') }} :
                        </td>
                        <td style="font-size:10px;">{{ $jobCard->custom_field_1 }}</td>
                    </tr>

                    <tr>
                        <td colspan="2" class="fw-bold" style="font-size:10px;">
                            {{ isset($generalSettings['service_settings__custom_field_2_label']) ? $generalSettings['service_settings__custom_field_2_label'] : __('Custom Field 2') }} :
                        </td>
                        <td style="font-size:10px;">{{ $jobCard->custom_field_2 }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold" style="font-size:10px;">
                            {{ isset($generalSettings['service_settings__custom_field_3_label']) ? $generalSettings['service_settings__custom_field_3_label'] : __('Custom Field 3') }} :
                        </td>
                        <td style="font-size:10px;">{{ $jobCard->custom_field_3 }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold" style="font-size:10px;">
                            {{ isset($generalSettings['service_settings__custom_field_4_label']) ? $generalSettings['service_settings__custom_field_4_label'] : __('Custom Field 4') }} :
                        </td>
                        <td style="font-size:10px;">
                            {{ $jobCard->custom_field_4 }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold" style="font-size:10px;">
                            {{ isset($generalSettings['service_settings__custom_field_5_label']) ? $generalSettings['service_settings__custom_field_5_label'] : __('Custom Field 5') }} :
                        </td>
                        <td style="font-size:10px;">
                            {{ $jobCard->custom_field_5 }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-size:10px;">
                            <p><span class="fw-bold">{{ __('Problem Reported By The Customer') }} : </span> {{ $jobCard->problems_report }}</p>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" style="font-size:10px;">
                            <p> <span class="fw-bold">{{ __('Terms & Conditions') }} : </span> {!! isset($generalSettings['service_settings__terms_and_condition']) ? $generalSettings['service_settings__terms_and_condition'] : '' !!}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold text-center" style="height: 50px; vertical-align: bottom; width: 50%;font-size:10px;">
                            {{ __('Customer signature') }}
                        </td>
                        <td class="fw-bold text-center" style="height: 50px; vertical-align: bottom; width: 50%;font-size:10px;">
                            {{ __('Authorized signature') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('speeddigit.show_app_info_in_print') == true)
                        <small style="font-size: 9px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __('M:') }} {{ config('speeddigit.phone') }}</small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
