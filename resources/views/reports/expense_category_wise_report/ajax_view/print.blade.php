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
    $totalAmount = 0;
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

                <h4 class="text-uppercase fw-bold">{{ $generalSettings['business__shop_name'] }}</h4>
            @endif
        @elseif($branch_id == 'NULL')
            @if ($generalSettings['business__business_logo'] != null)

                <img style="height: 45px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
            @else

                <h4 class="text-uppercase fw-bold">{{ $generalSettings['business__shop_name'] }}</h4>
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

            <h5 class="text-uppercase fw-bold">{{ $generalSettings['business__shop_name'] }}</h5>
            <p class="text-uppercase fw-bold">@lang('menu.all_business_location')</p>
            <p>{{ $generalSettings['business__address'] }}</p>
            <p><strong>@lang('menu.email') : </strong>{{ $generalSettings['business__email'] }}</p>
            <p><strong>@lang('menu.phone') : </strong>{{ $generalSettings['business__phone'] }}</p>
        @elseif ($branch_id == 'NULL')

            <h5 class="text-uppercase">{{ $generalSettings['business__shop_name'] }}</h5>
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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.category_wise_expense_report') </strong></h6>
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
                    <th class="text-start">@lang('menu.date')</th>
                    <th class="text-start">@lang('menu.reference_no')</th>
                    <th class="text-start">@lang('menu.business_location')</th>
                    <th class="text-end">@lang('menu.total_amount')</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $previousCategoryId = '';
                    $dateTotalAmount = 0;
                    $isSameGroup = true;
                    $lastCategoryId = null;
                    $lastDateTotalAmount = 0;
                @endphp
                @foreach ($expenses as $ex)
                    @php
                        $totalAmount += $ex->amount;
                        $date = date($__date_format, strtotime($ex->report_date));
                        $isSameGroup = (null != $lastCategoryId && $lastCategoryId == $ex->category_id) ? true : false;
                        $lastCategoryId = $ex->category_id;
                    @endphp

                    @if ($isSameGroup == true)

                        @php
                            $dateTotalAmount += $ex->amount;
                        @endphp
                    @else
                        @if ($dateTotalAmount != 0)
                            <tr>
                                <td colspan="3" class="fw-bold text-end">@lang('menu.total') : </td>
                                <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($dateTotalAmount) }}</td>
                            </tr>
                        @endif

                        @php $dateTotalAmount = 0; @endphp
                    @endif

                    @if ($previousCategoryId != $ex->category_id)
                        @php
                            $previousCategoryId = $ex->category_id;
                            $dateTotalAmount += $ex->amount;
                        @endphp

                        <tr>
                            <td class="text-start text-uppercase fw-bold" colspan="4">{{ $ex->category_name.' ('.$ex->category_code.')' }} </td>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ $date }}</td>
                        <td class="text-start fw-bold">{{ $ex->invoice_id }}</td>

                        <td class="text-start">
                            @if ($ex->branch_name)

                                {!! $ex->branch_name . '/' . $ex->branch_code . '(<b>B.L.</b>)' !!}
                            @else

                                {!! $generalSettings['business__shop_name'] . '(<b>HO</b>)' !!}
                            @endif
                        </td>

                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->amount) }}</td>
                    </tr>

                    @php
                        $__veryLastCategoryId = $veryLastCategoryId;
                        $currentCategoryId = $ex->category_id;
                        if ($currentCategoryId == $__veryLastCategoryId) {

                            $lastDateTotalAmount += $ex->amount;
                        }
                    @endphp

                    @if($loop->index == $lastRow)

                        <tr>
                            <td colspan="3" class="fw-bold text-end">@lang('menu.total') : </td>
                            <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($lastDateTotalAmount) }}</td>
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
                    <th class="text-end">@lang('menu.total_amount') : {{ $generalSettings['business__currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalAmount) }}
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
