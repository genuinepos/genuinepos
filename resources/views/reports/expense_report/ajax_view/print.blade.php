<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    .print_table th { font-size:9px!important; font-weight: 550!important;}
    .print_table tr td{font-size: 9px!important;}
</style>

@php
    $totalExpense = 0;
    $totalPaid = 0;
    $totalDue = 0;
    $branch = '';
    $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
    <div class="col-4 align-items-center">
        @if ($branch_id == '')
            @if ($generalSettings['business__business_logo'] != null)

                <img style="height: 45px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
            @else

                <h4 class="text-uppercase fw-bold">{{ $generalSettings['business__business_name'] }}</h4>
            @endif
        @elseif($branch_id == 'NULL')
            @if ($generalSettings['business__business_logo'] != null)

                <img style="height: 45px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
            @else

                <h4 class="text-uppercase fw-bold">{{ $generalSettings['business__business_name'] }}</h4>
            @endif
        @else
            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'logo', 'email', 'phone', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp

            @if ($branch->logo != null)

                <img style="height: 45px; width:200px;" src="{{ asset('uploads/branch_logo/' . $branch->logo) }}" class="logo__img">
            @else

                <h4 class="text-uppercase fw-bold">{{ $branch->name }}</h4>
            @endif
        @endif
    </div>

    <div class="col-8 text-end">
        @if ($branch_id == '')

            <h5 class="text-uppercase fw-bold">{{ $generalSettings['business__business_name'] }}</h5>
            <p class="text-uppercase fw-bold">@lang('menu.all_business_location')</p>
            <p>{{ $generalSettings['business__address'] }}</p>
            <p><strong>@lang('menu.email') : </strong>{{ $generalSettings['business__email'] }}</p>
            <p><strong>@lang('menu.phone') : </strong>{{ $generalSettings['business__phone'] }}</p>
        @elseif ($branch_id == 'NULL')

            <h5 class="text-uppercase">{{ $generalSettings['business__business_name'] }}</h5>
            <p>{{ $generalSettings['business__address'] }}</p>
            <p><strong>@lang('menu.email') : </strong>{{ $generalSettings['business__email'] }}</p>
            <p><strong>@lang('menu.phone') : </strong>{{ $generalSettings['business__phone'] }}</p>
        @else

            <h5 class="text-uppercase fw-bold">{{ $branch->name }}</h5>
            <p>{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
            <p><strong>@lang('menu.email') : </strong>{{ $branch->email }}</p>
            <p><strong>@lang('menu.phone') : </strong>{{ $branch->phone }}</p>
        @endif
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.expense_report') </strong></h6>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 text-center">
        @if ($fromDate && $toDate)
            <p>
                <strong>@lang('menu.from') :</strong>
                {{ date($generalSettings['business__date_format'], strtotime($fromDate)) }}
                <strong>@lang('menu.to')</strong> {{ date($generalSettings['business__date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.reference_no')</th>
                    <th class="text-start">@lang('menu.description')</th>
                    <th class="text-start">@lang('menu.b_location')</th>
                    <th class="text-end">@lang('menu.total_amount')</th>
                    <th class="text-end">@lang('menu.paid')</th>
                    <th class="text-end">@lang('menu.due')</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $previousDate = '';
                    $dateTotalExpense = 0;
                    $dateTotalPaid = 0;
                    $dateTotalDue = 0;
                    $isSameGroup = true;
                    $lastDate = null;
                    $lastDateTotalExpense = 0;
                    $lastDateTotalPaid = 0;
                    $lastDateTotalDue = 0;
                @endphp
                @foreach ($expenses as $ex)
                    @php
                        $totalExpense += $ex->net_total_amount;
                        $totalPaid += $ex->paid;
                        $totalDue += $ex->due;

                        $date = date($__date_format, strtotime($ex->report_date));
                        $isSameGroup = (null != $lastDate && $lastDate == $date) ? true : false;
                        $lastDate = $date;
                    @endphp

                    @if ($isSameGroup == true)

                        @php
                            $dateTotalExpense += $ex->net_total_amount;
                            $dateTotalPaid += $ex->paid;
                            $dateTotalDue += $ex->due;
                        @endphp
                    @else
                        @if ($dateTotalExpense != 0 || $dateTotalPaid != 0 || $dateTotalDue != 0)
                            <tr>
                                <td colspan="3" class="fw-bold text-end">@lang('menu.total') : </td>
                                <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($dateTotalExpense) }}</td>
                                <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($dateTotalPaid) }}</td>
                                <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($dateTotalDue) }}</td>
                            </tr>
                        @endif

                        @php $sum = 0; @endphp
                    @endif

                    @if ($previousDate != $date)
                        @php
                            $previousDate = $date;
                            $dateTotalExpense += $ex->net_total_amount;
                            $dateTotalPaid += $ex->paid;
                            $dateTotalDue += $ex->due;
                        @endphp

                        <tr>
                            <th class="text-start" colspan="6">{{ $date }} </th>
                        </tr>
                    @endif

                    <tr>
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
                                {!! '<b>' . $exDescription->name . '(' . $exDescription->code . ') : </b>'. App\Utils\Converter::format_in_bdt($exDescription->amount) !!} <br>
                            @endforeach
                        </td>

                        <td class="text-start">
                            @if ($ex->branch_name)
                                {!! $ex->branch_name . '/' . $ex->branch_code . '(<b>B.L.</b>)' !!}
                            @else
                                {!! $generalSettings['business__business_name'] . '(<b>HO</b>)' !!}
                            @endif
                        </td>

                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->net_total_amount) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->paid) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->due) }}</td>
                    </tr>

                    @php
                        $__veryLastDate = date($__date_format, strtotime($veryLastDate));
                        $currentDate = $date;
                        if ($currentDate == $__veryLastDate) {

                            $lastDateTotalExpense += $ex->net_total_amount;
                            $lastDateTotalPaid += $ex->paid;
                            $lastDateTotalDue += $ex->due;
                        }
                    @endphp

                    @if($loop->index == $lastRow)

                        <tr>
                            <td colspan="3" class="fw-bold text-end">@lang('menu.total') : </td>
                            <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($lastDateTotalExpense) }}</td>
                            <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($lastDateTotalPaid) }}</td>
                            <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($lastDateTotalDue) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered print_table">
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

<div id="footer">
    <div class="row">
        <div class="col-4 text-start">
            <small>@lang('menu.print_date') : {{ date($generalSettings['business__date_format']) }}</small>
        </div>

        @if (env('PRINT_SD_OTHERS') == 'true')
            <div class="col-4 text-center">
                <small>@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
            </div>
        @endif

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
        </div>
    </div>
</div>

