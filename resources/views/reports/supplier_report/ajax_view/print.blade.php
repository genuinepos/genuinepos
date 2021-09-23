<style>
    @page {/* size:21cm 29.7cm; */ margin:1cm 1cm 1cm 1cm; *//* margin:20px 20px 10px; */mso-title-page:yes;mso-page-orientation: portrait;mso-header: header;mso-footer: footer;}
</style>
@php
    $allTotalPurchase = 0;
    $allTotalPaid = 0;
    $allTotalOpDue = 0;
    $allTotalDue = 0;
    $allTotalReturnDue = 0;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
        <p><b> {{ json_decode($generalSettings->business, true)['address'] }}</b></p>
        <p><b>Supplier Report</b></p> 
    </div>
</div>
<br/>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">Supplier</th>
                    <th class="text-start">Total Purchase</th>
                    <th class="text-start">Total Paid</th>
                    <th class="text-start">Opening Balance Due</th>
                    <th class="text-start">Total Due</th>
                    <th class="text-start">Total Return Due</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($supplierReports as $report)
                    @php
                        $allTotalPurchase += $report->total_purchase;
                        $allTotalPaid += $report->total_paid;
                        $allTotalOpDue += $report->opening_balance;
                        $allTotalDue += $report->total_purchase_due;
                        $allTotalReturnDue += $report->total_purchase_return_due;
                    @endphp
                    <tr>
                        <td class="text-start">{{ $report->name.' (ID: '.$report->contact_id.')' }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $report->total_purchase }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $report->total_paid }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $report->opening_balance }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $report->total_purchase_due }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $report->total_purchase_return_due }}</td>
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
            <tbody>
                <tr>
                    <th class="text-start">Opening Balance Due :</th>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. bcadd($allTotalOpDue, 0, 2) }}</td>
                </tr>

                <tr>
                    <th class="text-start">Total Purchase :</th>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. bcadd($allTotalPurchase, 0, 2) }}</td>
                </tr>

                <tr>
                    <th class="text-start">Total Paid :</th>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. bcadd($allTotalPaid, 0, 2) }}</td>
                </tr>

                <tr>
                    <th class="text-start">Total Purchase Due :</th>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. bcadd($allTotalDue, 0, 2) }}</td>
                </tr>

                <tr>
                    <th class="text-start">Total Returnable/Refundable Due :</th>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. bcadd($allTotalReturnDue, 0, 2) }}</td>
                </tr>
            </tbody>
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