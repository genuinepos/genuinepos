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
</style>

<div class="sale_print_template">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if ($jobCard->branch)
                    @if ($jobCard?->branch?->parent_branch_id)

                        @if ($jobCard->branch?->parentBranch?->logo)
                            <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $jobCard->branch?->parentBranch?->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $jobCard->branch?->parentBranch?->name }}</span>
                        @endif
                    @else
                        @if ($jobCard->branch?->logo)
                            <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $jobCard->branch?->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $jobCard->branch?->name }}</span>
                        @endif
                    @endif
                @else
                    @if ($generalSettings['business_or_shop__business_logo'] != null)
                        <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
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

        <div class="sale_product_table pt-2 pb-2">
            <table class="table print-table table-sm table-bordered">
                <tbody>
                    <tr>
                        <th rowspan="3">
                            Date:
                            <span style="font-weight: 100">
                                06/09/2024 03:29
                            </span>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <b>Service type:</b>
                            Pick up
                        </td>
                        <th rowspan="2">
                            <b>
                                Due Date:
                            </b>
                            <span style="font-weight: 100">
                                06/16/2024 03:28
                            </span>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <b>Job sheet number:</b>
                            JOB2024/0001
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong>CUS:</strong><br>
                            <p>
                                Walk-In Customer, <br>Linking Street, <br>Phoenix, Arizona, USA

                                <br> CUS ID:
                                CO0005

                                <br>Mobile:
                                (378) 400-1234
                                {{-- <br> Custom Field 1:

                                <br> Custom Field 2:

                                <br> Custom Field 3:

                                <br> Custom Field 4: --}}

                            </p>
                        </td>
                        <td>
                            <b>Brand:</b>

                            <br>
                            <b>Device:</b>

                            <br>
                            <b>Device Model:</b>
                            A55
                            <br>
                            <b>Serial Number:</b>
                            012858882220
                            <br>
                            <b>Password:</b>
                            123456
                            <br>
                            <b>
                                Security Pattern code:
                            </b>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                Invoice No.:
                            </b>
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                Estimated Cost:
                            </b>
                        </td>
                        <td>
                            <span class="display_currency" data-currency_symbol="true">$ 0.00</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                Status:
                            </b>
                        </td>
                        <td>
                            Completed
                        </td>
                    </tr>
          
                    <tr>
                        <td colspan="2">
                            <b>
                                Technician:
                            </b>
                        </td>
                        <td>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                Comment by technician:
                            </b>
                        </td>
                        <td>
                            Need Repairing
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                Pre Repair Checklist:
                            </b>
                        </td>
                        <td>

                                <span>
                                    <i class="fas fa-check-square text-success fa-lg"></i>
                                    Display
                                </span>
                                <span>
                                    <i class="fas fa-window-close text-danger fa-lg"></i>
                                    Camera
                                </span>
                                <span>
                                    <i class="fas fa-check-square text-success fa-lg"></i>
                                    Motherboard
                                </span>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <b>
                                Pick up/On site address:
                            </b> <br>
                            FF
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <b>
                                Product Configuration:
                            </b> <br>
                            4gb/64gb
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <b>
                                Condition Of The Product:
                            </b> <br>
                            good
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                Label for job sheet custom field 1:
                            </b>
                        </td>
                        <td>
                            field 1
                        </td>
                    </tr>

                    <tr>
                        <th colspan="3">Parts used:</th>
                    </tr>

                    <tr>
                        {{-- <th colspan="2">Parts used:</th> --}}
                        <td colspan="3">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Samsung Galaxy S8 - Internal Memory - 64 GB (AS0014-1): &nbsp;</td>
                                        <td>1.00 Pc(s)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                Label for job sheet custom field 2:
                            </b>
                        </td>
                        <td>
                            field 2
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                Label for job sheet custom field 3:
                            </b>
                        </td>
                        <td>
                            field
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                Label for job sheet custom field 4:
                            </b>
                        </td>
                        <td>
                            field 4
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                Label for job sheet custom field 5:
                            </b>
                        </td>
                        <td>
                            field 5
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <b>
                                Problem Reported By The Customer:
                            </b> <br>
                            ok
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <strong>
                                Terms &amp; Conditions:
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                Customer signature:
                            </b>
                        </td>
                        <td>
                            <b>
                                Authorized signature:
                            </b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- <br><br>

        <div class="row">
            <div class="col-4">
                <div class="details_area text-start">
                    <p class="text-uppercase borderTop fw-bold">{{ __("Customer's Signature") }}</p>
                </div>
            </div>

            <div class="col-4">
                <div class="details_area text-center">
                    <p class="text-uppercase borderTop fw-bold">{{ __('Prepared By') }}</p>
                </div>
            </div>

            <div class="col-4">
                <div class="details_area text-end">
                    <p class="text-uppercase borderTop fw-bold">{{ __('Authorized By') }}</p>
                </div>
            </div>
        </div>

        <br> --}}

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (env('PRINT_SD_SALE') == true)
                        <small style="font-size: 9px!important;" class="d-block">{{ __('Powered By') }} <span class="fw-bold">@lang('SpeedDigit Software Solution').</span></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
