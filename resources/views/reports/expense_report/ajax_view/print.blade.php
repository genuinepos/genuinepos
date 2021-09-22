@php
    $totalExpense = 0;
    $totalPaid = 0;
    $totalDue = 0;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
            <p><b>All Business Location.</b></p> 
        @elseif ($branch_id == 'NULL')
            <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
        @else
            @php
                $branch = DB::table('branches')->where('id', $branch_id)->select('name', 'branch_code')->first();
            @endphp
            {{ $branch->name.' '.$branch->branch_code }}
        @endif

        @if ($fromDate && $toDate)
            <p><b>Date :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
        @endif

        <p><b>Expense Report </b></p>
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
                      
                        <td class="text-start"><b>{{json_decode($generalSettings->business, true)['currency']}}</b>{{ $ex->net_total_amount }}</td>
                        <td class="text-start"><b>{{json_decode($generalSettings->business, true)['currency']}}</b>{{ $ex->paid }}</td>
                        <td class="text-start"><b>{{json_decode($generalSettings->business, true)['currency']}}</b>{{ $ex->due }}</td>
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