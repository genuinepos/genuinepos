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
        size: a4 portrait landscape;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 5px;
        margin-right: 5px;
    }

    div#footer {
        position: fixed;
        bottom: 0px;
        left: 0px;
        width: 100%;
        height: 0%;
        color: #CCC;
        background: #333;
        padding: 0;
        margin: 0;
    }

    .print_table th {
        font-size: 11px !important;
        font-weight: 550 !important;
        line-height: 12px !important
    }

    .print_table tr td {
        color: black;
        font-size: 10px !important;
        line-height: 12px !important
    }

    .print_area {
        font-family: Arial, Helvetica, sans-serif;
    }

    .print_area h6 {
        font-size: 14px !important;
    }

    .print_area p {
        font-size: 11px !important;
    }

    .print_area small {
        font-size: 8px !important;
    }
</style>

<div class="print_area">
    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-4 mb-1">
            @if (auth()->user()?->branch)
                @if (auth()->user()?->branch?->parent_branch_id)

                    @if (auth()->user()?->branch?->parentBranch?->logo)
                        <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo)
                        <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->name }}</span>
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

            <p style="text-transform: uppercase;" class="p-0 m-0">
                <strong>
                    @if (auth()->user()?->branch)
                        @if (auth()->user()?->branch?->parent_branch_id)
                            {{ auth()->user()?->branch?->parentBranch?->name }}
                        @else
                            {{ auth()->user()?->branch?->name }}
                        @endif
                    @else
                        {{ $generalSettings['business_or_shop__business_name'] }}
                    @endif
                </strong>
            </p>

            <p>
                @if (auth()->user()?->branch)
                    {{ auth()->user()?->branch?->address . ', ' . auth()->user()?->branch?->city . ', ' . auth()->user()?->branch?->state . ', ' . auth()->user()?->branch?->zip_code . ', ' . auth()->user()?->branch?->country }}
                @else
                    {{ $generalSettings['business_or_shop__address'] }}
                @endif
            </p>

            <p>
                @if (auth()->user()?->branch)
                    <strong>{{ __('Email') }} : </strong> {{ auth()->user()?->branch?->email }},
                    <strong>{{ __('Phone') }} : </strong> {{ auth()->user()?->branch?->phone }}
                @else
                    <strong>{{ __('Email') }} : </strong> {{ $generalSettings['business_or_shop__email'] }},
                    <strong>{{ __('Phone') }} : </strong> {{ $generalSettings['business_or_shop__phone'] }}
                @endif
            </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 text-center">
            <h6 style="text-transform:uppercase;"><strong>{{ __('Production Report') }}</strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            @if ($fromDate && $toDate)
                <p>
                    <strong>{{ __('From') }} :</strong>
                    {{ date($generalSettings['business_or_shop__date_format'], strtotime($fromDate)) }}
                    <strong>{{ __('To') }} : </strong> {{ date($generalSettings['business_or_shop__date_format'], strtotime($toDate)) }}
                </p>
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-4">
            @php
                $ownOrParentbranchName = $generalSettings['business_or_shop__business_name'];
                if (auth()->user()?->branch) {
                    if (auth()->user()?->branch->parentBranch) {
                        $ownOrParentbranchName = auth()->user()?->branch->parentBranch?->name . '(' . auth()->user()?->branch->parentBranch?->area_name . ')';
                    } else {
                        $ownOrParentbranchName = auth()->user()?->branch?->name . '(' . auth()->user()?->branch?->area_name . ')';
                    }
                }
            @endphp
            <p><strong>{{ location_label() }} : </strong> {{ $filteredBranchName ? $filteredBranchName : $ownOrParentbranchName }} </p>
        </div>

        <div class="col-4">
            <p><strong>{{ __('Product') }} : </strong> {{ $filteredProductName ? $filteredProductName : __('All') }} </p>
        </div>

        <div class="col-4">
            <p><strong>{{ __('Status') }} : </strong> {{ $filteredStatusName }} </p>
        </div>
    </div>

    @php
        $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);
        $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

        $totalOutputQty = 0;
        $totalWastedQty = 0;
        $totalFinalQty = 0;
        $totalIngredientCost = 0;
        $totalAdditionalCost = 0;
        $totalNetCost = 0;
    @endphp

    <div class="row mt-1">
        <div class="col-12">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-start">{{ __('Voucher No') }}</th>
                        <th class="text-start">{{ location_label() }}</th>
                        <th class="text-start">{{ __('Mfd. Product') }}</th>
                        <th class="text-start">{{ __('Status') }}</th>
                        <th class="text-end">{{ __('Unit Cost(Exc. Tax)') }}</th>
                        <th class="text-end">{{ __('Vat/Tax') }}</th>
                        <th class="text-end">{{ __('Unit Cost(Inc. Tax)') }}</th>
                        <th class="text-end">{{ __('Selling Price(Exc. Tax)') }}</th>
                        <th class="text-end">{{ __('Output Qty') }}</th>
                        <th class="text-end">{{ __('Wasted Qty') }}</th>
                        <th class="text-end">{{ __('Final Qty') }}</th>
                        <th class="text-black">{{ __('Total Ingred. Cost') }}</th>
                        <th class="text-black">{{ __('Addl. Cost') }}</th>
                        <th class="text-black">{{ __('Net Cost') }}</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @php
                        $previousDate = '';
                    @endphp
                    @foreach ($productions as $production)
                        @php
                            $totalOutputQty += $production->total_output_quantity;
                            $totalWastedQty += $production->total_wasted_quantity;
                            $totalFinalQty += $production->total_final_output_quantity;
                            $totalIngredientCost += $production->total_ingredient_cost;
                            $totalAdditionalCost += $production->additional_production_cost;
                            $totalNetCost = $production->net_cost;

                            $date = date($__date_format, strtotime($production->date_ts));
                        @endphp
                        @if ($previousDate != $date)
                            @php
                                $previousDate = $date;
                            @endphp

                            <tr>
                                <th class="text-start" colspan="11">{{ $date }}</th>
                            </tr>
                        @endif

                        <tr>
                            <td>{{ $production->voucher_no }}</td>

                            <td class="text-start">
                                @if ($production->branch_id)
                                    @if ($production->parent_branch_name)
                                        {{ $production->parent_branch_name . '(' . $production->branch_area_name . ')' }}
                                    @else
                                        {{ $production->branch_name . '(' . $production->branch_area_name . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            </td>

                            <td>
                                @php
                                    $variantName = $production->variant_name ? ' _ ' . $production->variant_name : '';
                                @endphp
                                {{ Str::limit($production->product_name, 35, '') . $variantName }}
                            </td>

                            <td class="text-start">{{ \App\Enums\ProductionStatus::tryFrom($production->status) }}</td>
                            <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($production->per_unit_cost_exc_tax) }}</td>
                            <td class="text-end fw-bold">{{ '(' . $production->unit_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt($production->unit_tax_amount) }}</td>
                            <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($production->per_unit_cost_inc_tax) }}</td>
                            <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($production->per_unit_price_exc_tax) }}</td>
                            <td class="text-start fw-bold">{!! App\Utils\Converter::format_in_bdt($production->total_output_quantity) . '/' . $production->unit_name !!}</td>
                            <td class="text-start fw-bold">{!! App\Utils\Converter::format_in_bdt($production->total_wasted_quantity) . '/' . $production->unit_name !!}</td>
                            <td class="text-start fw-bold">{!! App\Utils\Converter::format_in_bdt($production->total_final_output_quantity) . '/' . $production->unit_name !!}</td>
                            <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($production->total_ingredient_cost) }}</td>
                            <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($production->additional_production_cost) }}</td>
                            <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($production->net_cost) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div style="page-break-after: {{ count($sales) > 30 ? 'always' : '' }};"></div> --}}
    <div class="row">
        {{-- <div class="col-6"></div> --}}
        <div class="col-6 offset-6">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-end">{{ __('Total Output Qty') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalOutputQty) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Wasted Qty') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalWastedQty) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Final Output Qty') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalFinalQty) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Ingredient Cost') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalIngredientCost) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Additional Production Cost') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalAdditionalCost) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Net Production Cost') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalNetCost) }}
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="footer">
        <div class="row">
            <div class="col-4 text-start">
                <small>{{ __('Print Date') }} : {{ date($__date_format) }}</small>
            </div>

            <div class="col-4 text-center">
                @if (config('speeddigit.show_app_info_in_print') == true)
                    <small style="font-size: 9px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __('M:') }} {{ config('speeddigit.phone') }}</small>
                @endif
            </div>

            <div class="col-4 text-end">
                <small>{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
            </div>
        </div>
    </div>
</div>
