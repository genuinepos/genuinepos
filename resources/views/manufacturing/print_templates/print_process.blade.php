@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
@if ($printPageSize == \App\Enums\PrintPageSize::AFourPage->value)
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
    <!-- Print Template-->
    <div class="print_modal_details">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if (auth()->user()->branch)
                        @if (auth()->user()->branch->logo)
                            <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'branch_logo/' . auth()->user()->branch->logo) }}" alt="logo" class="logo__img">
                        @else
                            @php
                                $branchName = auth()->user()?->branch?->parentBranch ? auth()->user()?->branch?->parentBranch->name : auth()->user()?->branch?->name;
                            @endphp
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $branchName }}</span>
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;" class="p-0 m-0">
                        @php
                            $branchName = '';
                        @endphp
                        @if (auth()->user()->branch)
                            @php
                                $branch = auth()->user()?->branch?->parentBranch ? auth()->user()?->branch?->parentBranch->name : auth()->user()?->branch?->name;
                                $branchName = $branch . '(' . auth()->user()?->branch?->area_name . ')';
                            @endphp
                        @else
                            @php
                                $branchName = $generalSettings['business_or_shop__business_name'];
                            @endphp
                        @endif
                        <span class="fw-bold">{{ $branchName }}</span>
                    </p>

                    <p>
                        @if (auth()->user()->branch)
                            {{ auth()->user()->branch->address . ', ' . auth()->user()->branch->city . ', ' . auth()->user()->branch->state . ', ' . auth()->user()->branch->zip_code . ', ' . auth()->user()->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p>
                        @php
                            $email = auth()->user()?->branch ? auth()->user()?->branch->email : $generalSettings['business_or_shop__email'];
                            $phone = auth()->user()?->branch ? auth()->user()?->branch->phone : $generalSettings['business_or_shop__phone'];
                        @endphp

                        <span class="fw-bold">{{ __('Email') }} : </span> {{ $email }},
                        <span class="fw-bold">{{ __('Phone') }} : </span> {{ $phone }}
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 style="text-transform: uppercase;"><span class="fw-bold">{{ __('Bill Of Materials') }}</span></h5>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Product Name') }} : </span>
                            {{ $process->product->name . ' ' . ($process->variant ? $process->variant->variant_name : '') . ' (' . ($process->variant ? $process->variant->variant_code : $process->product->product_code) . ')' }}
                        </li>

                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Created From') }} : </span>
                            @if ($process->branch_id)

                                @if ($process?->branch?->parentBranch)
                                    {{ $process?->branch?->parentBranch?->name . '(' . $process?->branch?->area_name . ')' . '-(' . $process?->branch?->branch_code . ')' }}
                                @else
                                    {{ $process?->branch?->name . '(' . $process?->branch?->area_name . ')' . '-(' . $process?->branch?->branch_code . ')' }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] . '(Business)' }}
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Ingredient') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Quantity') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost Exc. Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost Inc. Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($process->ingredients as $ingredient)
                            <tr>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $ingredient->product->name . ' ' . $ingredient?->variant?->variant_name }}
                                </td>
                                <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->final_qty) . '/' . $ingredient?->unit?->name }}</td>
                                <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_exc_tax) }}</td>
                                <td class="text-start" style="font-size:11px!important;">{{ '(' . $ingredient->unit_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($ingredient->unit_tax_amount) }}</td>
                                <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}</td>
                                <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end" style="font-size:11px!important;">{{ __('Total Ingredients') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                            <th class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($process->total_ingredient_cost) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div><br>

            <div class="row">
                <div class="col-md-6">
                    <table class="table print-table table-sm table-bordered">
                        <tr>
                            <th class="text-start" style="font-size:11px!important;">{{ __('Total Output Quantity') }} : </th>
                            <td class="text-start" style="font-size:11px!important;"> {{ App\Utils\Converter::format_in_bdt($process->total_output_qty) . '/' . $process->unit->name }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table print-table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th class="text-end" style="font-size:11px!important;">{{ __('Addl. Production Cost') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;"> {{ App\Utils\Converter::format_in_bdt($process->additional_production_cost) }}</td>
                            </tr>
                            <tr>
                                <th class="text-end" style="font-size:11px!important;">{{ __('Net Cost') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;"> {{ App\Utils\Converter::format_in_bdt($process->net_cost) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div><br><br>

            <div class="row">
                <div class="col-6">
                    <div class="details_area text-center">
                        <p class="text-uppercase borderTop"><span class="fw-bold">{{ __('Prepared By') }}</span></p>
                    </div>
                </div>

                <div class="col-6">
                    <div class="details_area text-center">
                        <p class="text-uppercase borderTop"><span class="fw-bold">{{ __('Authorized By') }}</span></p>
                    </div>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
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

    @php
        $product = $process->product->name . ' ' . ($process->variant ? $process->variant->variant_name : '') . ' (' . ($process->variant ? $process->variant->variant_code : $process->product->product_code) . ')';
        $filename = __('Bill Of Material') . '__' . $product . '__' . $branchName;
    @endphp
    <span id="title" class="d-none">{{ $filename }}</span>
@else
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
            size: 5.8in 8.3in;
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
    <!-- Print Template-->
    <div class="print_modal_details">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if (auth()->user()->branch)
                        @if (auth()->user()->branch->logo)
                            <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'branch_logo/' . auth()->user()->branch->logo) }}" alt="logo" class="logo__img">
                        @else
                            @php
                                $branchName = auth()->user()?->branch?->parentBranch ? auth()->user()?->branch?->parentBranch->name : auth()->user()?->branch?->name;
                            @endphp
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $branchName }}</span>
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;font-size:9px;" class="p-0 m-0">
                        @php
                            $branchName = '';
                        @endphp
                        @if (auth()->user()->branch)
                            @php
                                $branch = auth()->user()?->branch?->parentBranch ? auth()->user()?->branch?->parentBranch->name : auth()->user()?->branch?->name;
                                $branchName = $branch . '(' . auth()->user()?->branch?->area_name . ')';
                            @endphp
                        @else
                            @php
                                $branchName = $generalSettings['business_or_shop__business_name'];
                            @endphp
                        @endif
                        <span class="fw-bold">{{ $branchName }}</span>
                    </p>

                    <p style="font-size:9px;">
                        @if (auth()->user()->branch)
                            {{ auth()->user()->branch->address . ', ' . auth()->user()->branch->city . ', ' . auth()->user()->branch->state . ', ' . auth()->user()->branch->zip_code . ', ' . auth()->user()->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @php
                            $email = auth()->user()?->branch ? auth()->user()?->branch->email : $generalSettings['business_or_shop__email'];
                            $phone = auth()->user()?->branch ? auth()->user()?->branch->phone : $generalSettings['business_or_shop__phone'];
                        @endphp

                        <span class="fw-bold">{{ __('Email') }} : </span> {{ $email }},
                        <span class="fw-bold">{{ __('Phone') }} : </span> {{ $phone }}
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 style="text-transform: uppercase;"><span class="fw-bold">{{ __('Bill Of Materials') }}</span></h5>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important;"><span class="fw-bold">{{ __('Product Name') }} : </span>
                            {{ $process->product->name . ' ' . ($process->variant ? $process->variant->variant_name : '') . ' (' . ($process->variant ? $process->variant->variant_code : $process->product->product_code) . ')' }}
                        </li>

                        <li style="font-size:9px!important;"><span class="fw-bold">{{ __('Created From') }} : </span>
                            @if ($process->branch_id)

                                @if ($process?->branch?->parentBranch)
                                    {{ $process?->branch?->parentBranch?->name . '(' . $process?->branch?->area_name . ')' . '-(' . $process?->branch?->branch_code . ')' }}
                                @else
                                    {{ $process?->branch?->name . '(' . $process?->branch?->area_name . ')' . '-(' . $process?->branch?->branch_code . ')' }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] . '(Business)' }}
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Ingredient') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Quantity') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Unit Cost Exc. Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Vat/Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Unit Cost Inc. Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($process->ingredients as $ingredient)
                            <tr>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $ingredient->product->name . ' ' . $ingredient?->variant?->variant_name }}
                                </td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->final_qty) . '/' . $ingredient?->unit?->name }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_exc_tax) }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ '(' . $ingredient->unit_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($ingredient->unit_tax_amount) }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end" style="font-size:9px!important;">{{ __('Total Ingredients') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                            <th class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($process->total_ingredient_cost) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div><br>

            <div class="row">
                <div class="col-md-6">
                    <table class="table print-table table-sm table-bordered">
                        <tr>
                            <th class="text-start" style="font-size:9px!important;">{{ __('Total Output Quantity') }} : </th>
                            <td class="text-start" style="font-size:9px!important;"> {{ App\Utils\Converter::format_in_bdt($process->total_output_qty) . '/' . $process->unit->name }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table print-table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Addl. Production Cost') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;"> {{ App\Utils\Converter::format_in_bdt($process->additional_production_cost) }}</td>
                            </tr>
                            <tr>
                                <th class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Net Cost') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;"> {{ App\Utils\Converter::format_in_bdt($process->net_cost) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-4 text-start">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px!important;">
                        {{ __('Prepared By') }}
                    </p>
                </div>

                <div class="col-4 text-center">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px!important;">
                        {{ __('Checked By') }}
                    </p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px!important;">
                        {{ __('Authorized By') }}
                    </p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
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
    @php
        $product = $process->product->name . ' ' . ($process->variant ? $process->variant->variant_name : '') . ' (' . ($process->variant ? $process->variant->variant_code : $process->product->product_code) . ')';
        $filename = __('Bill Of Material') . '__' . $product . '__' . $branchName;
    @endphp
    <span id="title" class="d-none">{{ $filename }}</span>
@endif
