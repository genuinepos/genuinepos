<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
</style>
@php
    $totalExpense = 0;
    $totalPaid = 0;
    $totalDue = 0;
@endphp

<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h5>{{ $generalSettings['business__shop_name'] }} (@lang('menu.head_office'))</h5>
            <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business__address'] }}</p>
            <p><b>@lang('menu.all_business_location')</b></p>
        @elseif ($branch_id == 'NULL')
            <h5>{{ $generalSettings['business__shop_name'] }} (@lang('menu.head_office'))</h5>
            <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business__address'] }}</p>
        @else
            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp
            <h5>{{ $branch->name . ' ' . $branch->branch_code }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
        @endif

        @if ($fromDate && $toDate)
            <p><b>@lang('menu.date') :</b>
                {{ date($generalSettings['business__date_format'], strtotime($fromDate)) }}
                <b>@lang('menu.to')</b> {{ date($generalSettings['business__date_format'], strtotime($toDate)) }}
            </p>
        @endif
        <h6 style="margin-top: 10px;"><b>@lang('menu.expanse_report') </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.date')</th>
                    <th class="text-start">@lang('menu.reference_no')</th>
                    <th class="text-start">@lang('menu.description')</th>
                    <th class="text-start">@lang('menu.b_location')</th>
                    <th class="text-start">{{ __('Expense For') }}</th>
                    <th class="text-start">@lang('menu.total_amount')</th>
                    <th class="text-start">@lang('menu.paid')</th>
                    <th class="text-start">@lang('menu.due')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($expenses as $ex)
                    @php
                        $totalExpense += $ex->net_total_amount;
                        $totalPaid += $ex->paid;
                        $totalDue += $ex->due;
                    @endphp
                    <tr>
                        <td class="text-start">
                            {{ date($__date_format, strtotime($ex->date)) }}
                        </td>

                        <td class="text-start">{{ $ex->invoice_id }}</td>

                        <td class="text-start">
                            @php
                                $expenseDescriptions = DB::table('expense_descriptions')
                                    ->where('expense_id', $ex->id)
                                    ->leftJoin('expanse_categories', 'expense_descriptions.expense_category_id', 'expanse_categories.id')
                                    ->select('expanse_categories.name', 'expanse_categories.code', 'expense_descriptions.amount')
                                    ->get();
                            @endphp
                            @foreach ($expenseDescriptions as $exDescription)
                                {!! '<b>' . $exDescription->name . '(' . $exDescription->code . '):</b>'. $exDescription->amount !!} <br>
                            @endforeach
                        </td>

                        <td class="text-start">
                            @if ($ex->branch_name)
                                {!! $ex->branch_name . '/' . $ex->branch_code . '(<b>BR</b>)' !!}
                            @else
                                {!! $generalSettings['business__shop_name'] . '(<b>HO</b>)' !!}
                            @endif
                        </td>

                        <td>{{ $ex->cr_prefix . ' ' . $ex->cr_name . ' ' . $ex->cr_last_name }}</td>

                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->net_total_amount) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->paid) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->due) }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">@lang('menu.total_expense') : {{ $generalSettings['business__currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalExpense) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_paid') : {{$generalSettings['business__currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalPaid) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_due') : {{ $generalSettings['business__currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalDue) }}
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>


@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">
    <small style="font-size: 5px;" class="text-end">
        @lang('menu.print_date'): {{ date('d-m-Y , h:iA') }}
    </small>
</div>
