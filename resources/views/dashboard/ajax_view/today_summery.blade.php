<!--begin::Form-->
<div class="form-group row">
    <div class="col-md-6">
        <select name="branch_id" id="today_branch_id" class="form-control">
            <option value="">All Branch</option>
        </select>
    </div>
</div>

<div class="today_summery_area mt-2">
    <div class="row">
        <div class="col-md-6">
            <table class="table modal-table table-sm">
                <tbody>
                    <tr>
                        <th class="text-start">Total Purchase :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Adjustment :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Expense :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Sale Return :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Payroll :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table modal-table table-sm">
                <tbody>
                    <tr>
                        <th class="text-start">Total sale :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Stock Recovered :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                    </tr>

                    <tr>
                        <th class="text-start">Total Purchase Return :</th>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>