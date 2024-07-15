@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
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

    /* div#footer {
        position: fixed;
        bottom: 22px;
        left: 0px;
        width: 100%;
        height: 0%;
        color: #CCC;
        background: #333;
        padding: 0;
        margin: 0;
    } */
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
            </div>
        </div>

        <div class="sale_product_table pt-1">
            <table class="table print-table table-sm table-bordered">
                <tbody>
                    <tr>
                        <td rowspan="3">
                            <p><span class="fw-bold">{{ __('Date') }} : </span> {{ date($dateFormat, strtotime($jobCard->date_ts)) }}</p>
                            <p><span class="fw-bold">{{ __('Delivery Date') }} : </span> {{ $jobCard->delivery_date_ts ? date($dateFormat, strtotime($jobCard->delivery_date_ts)) : '' }}</p>
                        </td>
                        <td colspan="2" class="fw-bold text-center">{{ __('Job Card Label') }}</td>
                    </tr>

                    <tr>
                        <td colspan="3" class="fw-bold text-center">
                            <img style="width: {{ isset($generalSettings['service_settings_pdf_label__label_width']) ? $generalSettings['service_settings_pdf_label__label_width'] : '75' }}mm; height:{{ isset($generalSettings['service_settings_pdf_label__label_height']) ? $generalSettings['service_settings_pdf_label__label_height'] : '55' }}mm; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($jobCard->job_no, $generator::TYPE_CODE_128)) }}">
                            @if (isset($generalSettings['service_settings_pdf_label__barcode_in_label']) && $generalSettings['service_settings_pdf_label__barcode_in_label'] == '1')
                                <p>{{ $jobCard->job_no }}</p>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span class="fw-bold">{{ __('Service type') }} : </span> {{ str(\App\Enums\ServiceType::tryFrom($jobCard->service_type)->name)->headline() }}
                        </td>

                        @if (isset($generalSettings['service_settings_pdf_label__due_date_in_label']) && $generalSettings['service_settings_pdf_label__due_date_in_label'] == '1')
                            <td rowspan="2">
                                <span class="fw-bold">{{ __('Due Date') }}</span> : {{ $jobCard->due_date_ts ? date($dateFormat, strtotime($jobCard->due_date_ts)) : '' }}
                            </td>
                        @endif
                    </tr>

                    <tr>
                        @if (isset($generalSettings['service_settings_pdf_label__job_card_no_in_label']) && $generalSettings['service_settings_pdf_label__job_card_no_in_label'] == '1')
                            <td><span class="fw-bold">{{ __('Job No') }} :</span> {{ $jobCard->job_no }}</td>
                        @endif

                        @if (isset($generalSettings['service_settings_pdf_label__sales_person_in_label']) && $generalSettings['service_settings_pdf_label__sales_person_in_label'] == '1')
                            <td><span class="fw-bold">{{ __('Sales Person') }} :</span> {{ $jobCard?->createdBy?->prefix . ' ' . $jobCard?->createdBy?->name . ' ' . $jobCard?->createdBy?->last_name }}</td>
                        @endif
                    </tr>


                    <tr>
                        <td colspan="2">
                            @if (isset($generalSettings['service_settings_pdf_label__customer_name_in_label']) && $generalSettings['service_settings_pdf_label__show_contact_id'] == '1')
                                <p>
                                    <span class="fw-bold">{{ isset($generalSettings['service_settings_pdf_label_customer_name_in_label']) ? $generalSettings['service_settings_pdf_label__customer_label_name'] : __('Customer') }} :</span>
                                    {{ $jobCard?->customer?->name }}
                                </p>
                            @endif

                            @if (isset($generalSettings['service_settings_pdf_label__customer_name_in_label']) && $generalSettings['service_settings_pdf_label__customer_name_in_label'] == '1')
                                <p>
                                    <span class="fw-bold">{{ __('Address') }} :</span>
                                    {{ $jobCard?->customer?->address }}
                                </p>
                            @endif

                            @if (isset($generalSettings['service_settings_pdf_label__customer_phone_in_label']) && $generalSettings['service_settings_pdf_label__customer_name_in_label'] == '1')
                                <p>
                                    <span class="fw-bold">{{ __('Phone') }} :</span>
                                    {{ $jobCard?->customer?->phone }}
                                </p>
                            @endif

                            @if (isset($generalSettings['service_settings_pdf_label__customer_alt_phone_in_label']) && $generalSettings['service_settings_pdf_label__customer_alt_phone_in_label'] == '1')
                                <p>
                                    <span class="fw-bold">{{ __('Phone') }} :</span>
                                    {{ $jobCard?->customer?->contact?->alternative_phone }}
                                </p>
                            @endif

                            @if (isset($generalSettings['service_settings_pdf_label__customer_email_in_label']) && $generalSettings['service_settings_pdf_label__customer_email_in_label'] == '1')
                                <p>
                                    <span class="fw-bold">{{ __('Email') }} :</span>
                                    {{ $jobCard?->customer?->contact?->email }}
                                </p>
                            @endif
                        </td>

                        <td>
                            @if (isset($generalSettings['service_settings_pdf_label__model_in_label']) && $generalSettings['service_settings_pdf_label__model_in_label'] == '1')
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
                            @endif

                            @if (isset($generalSettings['service_settings_pdf_label__serial_in_label']) && $generalSettings['service_settings_pdf_label__serial_in_label'] == '1')
                                <p>
                                    <span class="fw-bold">{{ __('Serial Number') }} :</span>
                                    {{ $jobCard?->serial_number }}
                                </p>
                            @endif

                            @if (isset($generalSettings['service_settings_pdf_label__password_in_label']) && $generalSettings['service_settings_pdf_label__password_in_label'] == '1')
                                <p>
                                    <span class="fw-bold">{{ __('Password') }} :</span>
                                    {{ $jobCard?->password }}
                                </p>
                            @endif
                        </td>
                    </tr>

                    @if (isset($generalSettings['service_settings_pdf_label__status_in_label']) && $generalSettings['service_settings_pdf_label__status_in_label'] == '1')
                        <tr>
                            <td colspan="2" class="fw-bold">{{ __('Status') }} :</td>
                            <td> {{ $jobCard?->status?->name }}</td>
                        </tr>
                    @endif

                    @if (isset($generalSettings['service_settings_pdf_label__technician_in_label']) && $generalSettings['service_settings_pdf_label__technician_in_label'] == '1')
                        <tr>
                            <td colspan="2" class="fw-bold">{{ __('Comment By Technician') }}:</td>
                            <td>{{ $jobCard->technician_comment }}</td>
                        </tr>
                    @endif

                    @if (isset($generalSettings['service_settings_pdf_label__problems_in_label']) && $generalSettings['service_settings_pdf_label__problems_in_label'] == '1')
                        <tr>
                            <td colspan="3">
                                <p><span class="fw-bold">{{ __('Problem Reported By The Customer') }} : </span> {{ $jobCard->problems_report }}</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div id="footer">
            <div class="row">
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
