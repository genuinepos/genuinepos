<!--begin::Form-->
<div class="form-group row">
    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
        <div class="col-md-6">
            <select name="branch_id" id="today_branch_id" class="form-control">
                <option value="">All Branch</option>
                <option {{ $branch_id == 'HF' ? 'SELECTED' : '' }} value="HF">{{ json_decode($generalSettings->business, true)['shop_name'] }}(HO)</option>
                @foreach ($branches as $br)
                    <option {{ $branch_id == $br->id ? 'SELECTED' : '' }} value="{{ $br->id }}">{{ $br->name.'/'.$br->branch_code }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="col-md-6">
        <div class="loader d-none">
            <i class="fas fa-sync fa-spin ts_preloader text-primary"></i> <b>Processing...</b>  
        </div>
    </div>
</div>

<div class="today_summery_area mt-2">
    <div class="row">
        <div class="col-md-6">
            <table class="table modal-table table-sm">
                <tbody>
                    <tr>
                        <th class="text-start">Total Purchase :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ bcadd($totalPurchase, 0,2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Adjustment :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ bcadd($total_adjustment, 0,2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Expense :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ bcadd($totalExpense, 0,2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Sale Discount :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00 (P)</td>
                    </tr>

                    <tr>
                        <th class="text-start">Transfer Shiping Charge :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00 (P)</td>
                    </tr>

                    <tr>
                        <th class="text-start">Purchanse Shiping Charge :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00 (P)</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Customer Reward :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00 (P)</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Sale Return :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ bcadd($totalSalesReturn, 0,2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Payroll :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00 (P)</td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table modal-table table-sm">
                <tbody>
                    <tr>
                        <th class="text-start">Current Stock :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total sale :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ bcadd($totalSales, 0,2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Stock Recovered :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ bcadd($total_recovered, 0,2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Purchase Return :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00 (P)</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Sale Shipping Charge :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00 (P)</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Round Off :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00 (P)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>