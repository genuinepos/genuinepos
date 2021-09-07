@php
    $allTotalSale = 0;
    $allTotalPaid = 0;
    $allTotalOpDue = 0;
    $allTotalDue = 0;
    $allTotalReturnDue = 0;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
        <p><b> {{ json_decode($generalSettings->business, true)['address'] }}</b></p>
        <p><b>Customer Report</b></p> 
    </div>
</div>
<br/>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">Customer</th>
                    <th class="text-start">Total Sale</th>
                    <th class="text-start">Total Paid</th>
                    <th class="text-start">Opening Balance Due</th>
                    <th class="text-start">Total Due</th>
                    <th class="text-start">Total Return Due</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($customerReports as $report)
                    @php
                        $allTotalSale += $report->total_sale;
                        $allTotalPaid += $report->total_paid;
                        $allTotalOpDue += $report->opening_balance;
                        $allTotalDue += $report->total_sale_due;
                        $allTotalReturnDue += $report->total_sale_return_due;
                    @endphp
                    <tr>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $report->name }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $report->total_sale }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $report->total_paid }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $report->opening_balance }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $report->total_sale_due }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $report->total_sale_return_due }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-start">Total</th>
                    <th class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. bcadd($allTotalSale, 0, 2) }}</th>
                    <th class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. bcadd($allTotalPaid, 0, 2) }}</th>
                    <th class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. bcadd($allTotalOpDue, 0, 2) }}</th>
                    <th class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. bcadd($allTotalDue, 0, 2) }}</th>
                    <th class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. bcadd($allTotalReturnDue, 0, 2) }}</th>
                </tr>
            </tfoot>
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