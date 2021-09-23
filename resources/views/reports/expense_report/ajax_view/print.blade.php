<style>
      @page {margin:1.5cm 1.2cm 0.1cm 1.2cm;mso-title-page:yes;mso-page-orientation: portrait;mso-header: header;mso-footer: footer;
    } 
     /* @page {margin:1cm 1cm 1cm 1cm; mso-title-page:yes;mso-page-orientation: portrait;mso-header: header;mso-footer: footer;} */
     /* @page {
        margin-top: 0;
    } */
</style>
@php
    $totalExpense = 0;
    $totalPaid = 0;
    $totalDue = 0;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
            <p><b>All Business Location.</b></p> 
        @elseif ($branch_id == 'NULL')
            <p>{{ json_decode($generalSettings->business, true)['shop_name'] }}</p>
        @else
            @php
                $branch = DB::table('branches')->where('id', $branch_id)->select('name', 'branch_code')->first();
            @endphp
            {{ $branch->name.' '.$branch->branch_code }}
        @endif

        @if ($fromDate && $toDate)
            <p><b>Date :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
        @endif
        <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
        <h6 style="margin-top: 10px;"><b>Expense Report </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">Date</th>
                    <th class="text-start">Reference No</th>
                    <th class="text-start">Description</th>
                    <th class="text-start">B.Location</th>
                    <th class="text-start">Expense For</th>
                    <th class="text-start">Total Amount</th>
                    <th class="text-start">Paid</th>
                    <th class="text-start">Due</th>
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
                        <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ex->date)) }}</td>
                        <td class="text-start">{{ $ex->invoice_id }}</td>
                        <td class="text-start">
                            @php
                                $expenseDescriptions = DB::table('expense_descriptions')
                                ->where('expense_id', $ex->id)
                                ->leftJoin('expanse_categories', 'expense_descriptions.expense_category_id', 'expanse_categories.id')
                                ->select(
                                    'expanse_categories.name', 'expanse_categories.code', 'expense_descriptions.amount'
                                    )->get();
                            @endphp
                            @foreach ($expenseDescriptions as $exDescription)
                                {!! '<b>'.$exDescription->name.'('.$exDescription->code.'):</b>'.json_decode($generalSettings->business, true)['currency'].$exDescription->amount  !!} <br>
                            @endforeach
                        </td>
                        <td class="text-start">
                            @if ($ex->branch_name) 
                                {!! $ex->branch_name . '/' . $ex->branch_code . '(<b>BR</b>)' !!}
                            @else 
                                {!! json_decode($generalSettings->business, true)['shop_name'].'(<b>HO</b>)' !!}
                            @endif
                        </td>

                        <td>{{ $ex->cr_prefix . ' ' . $ex->cr_name . ' ' . $ex->cr_last_name }}</td>
                      
                        <td class="text-start">{{ $ex->net_total_amount }}</td>
                        <td class="text-start">{{ $ex->paid }}</td>
                        <td class="text-start">{{ $ex->due }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row" >
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">Total Expense :</th>
                    <th class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalExpense, 0, 2) }}</th>
                </tr>

                <tr>
                    <th class="text-end">Total Paid :</th>
                    <th class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalPaid, 0, 2) }}</th>
                </tr>

                <tr>
                    <th class="text-end">Total Due :</th>
                    <th class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalDue, 0, 2) }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>Software By <b>SpeedDigit Pvt. Ltd.</b></small> 
        </div>
    </div>
@endif

<small id="page_info" style="position:fixed;bottom:0px;left:0px;width:100%;color:#CCC;background:#333;" class="text-end">
    print Date : {{ date('d-m-Y h:i:s a') }}
</small>
