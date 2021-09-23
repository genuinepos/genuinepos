<style>
    @page {/* size:21cm 29.7cm; */ margin:1cm 1cm 1cm 1cm; *//* margin:20px 20px 10px; */mso-title-page:yes;mso-page-orientation: portrait;mso-header: header;mso-footer: footer;}
</style>
<div class="row">
    <div class="col-md-12 text-center">
        <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
        <p>{{ json_decode($generalSettings->business, true)['address'] }}</p>
        <p><b>Financial Report</b></p> 
    </div>
</div>
<br/>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <tbody>
                <tr>
                    <th rowspan="3">Account Summery</th>
                    <td class="text-start"> <b>Total Opening Balance :</b></td>                                     
                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($accounts->sum('total_op_balance'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start"><b>Current Account Balance :</b>  </td>
                    @if ($accounts->sum('total_balance') >= 0)
                        <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($accounts->sum('total_balance'), 0, 2) }}</td>
                    @else 
                        <td class="text-start"> {!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($accounts->sum('total_balance'), 0, 2).'</span>' !!}</td>
                    @endif
                </tr>
            </tbody>

            <tbody>
                <tr>
                    <th rowspan="3">Assets</th>
                    <td class="text-start"><b>Current Asset Value</b></td>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($assets->sum('total_asset_value'), 0, 2) }}</td>
                </tr>
            </tbody>

            <tbody>
                <tr>
                    <th rowspan="11">Purchase</th>
                    <td class="text-start">Total Purchase</td>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($suppliers->sum('total_purchase'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Supplier Payment</td>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($suppliers->sum('total_paid'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Supplier Due </td>
                    <td class="text-start"> {!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($suppliers->sum('total_purchase_due'), 0, 2).'</span>' !!}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Supplier Return</td>
                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($suppliers->sum('total_return'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Supplier Return Due</td>
                    <td class="text-start">{!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($suppliers->sum('total_return_due'), 0, 2).'</span>' !!}</td>
                </tr>
            </tbody> 

            <tbody>
                <tr>
                    <th rowspan="11" class="text-center">Sale</th>
                    <td class="text-start">Total Sale</td>
                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($sales->sum('total_sale'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Customer Payment</td>
                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($sales->sum('total_paid'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Customer Due </td>
                    <td class="text-start"> {!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($sales->sum('total_due'), 0, 2).'</span>' !!}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Customer Return</td>
                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($sales->sum('total_return'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Customer Return Due</td>
                    <td class="text-start">{!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($sales->sum('total_return_due'), 0, 2).'</span>' !!}</td>
                </tr>
            </tbody>

            <tbody>
                <tr>
                    <th rowspan="7">Product</th>
                    <td class="text-start">Current Stock Value</td>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalStockValue, 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Adjustment :</td>
                    <td class="text-start">{!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($adjustments->sum('total_adjust_amount'), 0, 2).'</span>' !!}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Adjustment Recovered:</td>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($adjustments->sum('total_recovered'), 0, 2) }}</td>
                </tr>
            </tbody>

            <tbody>
                <tr>
                    <th rowspan="7">Expense </th>
                    <td class="text-start">Total Expense :</td>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($expenses->sum('total_amount'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Paid :</td>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($expenses->sum('total_paid'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Due :</td>
                    <td class="text-start">{!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($expenses->sum('total_due'), 0, 2).'</span>' !!}</td>
                </tr>
            </tbody>

            <tbody>
                <tr>
                    <th rowspan="13">Loan </th>
                    <td class="text-start">Total Pay Loan :</td>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($loans->sum('total_pay_loan'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Pay Loan Paid :</td>
                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($loans->sum('total_pay_loan_paid'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Pay Loan payment Due :</td>
                    <td class="text-start"> {!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($loans->sum('total_pay_loan_due'), 0, 2).'</span>' !!}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Receive Loan :</td>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($loans->sum('total_receive_loan'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Receive Loan Paid :</td>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($loans->sum('total_receive_loan_paid'), 0, 2) }}</td>
                </tr>

                <tr>
                    <td class="text-start">Total Receive Loan payment Due :</td>
                    <td class="text-start">{!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($loans->sum('total_receive_loan_due'), 0, 2).'</span>' !!}</td>
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