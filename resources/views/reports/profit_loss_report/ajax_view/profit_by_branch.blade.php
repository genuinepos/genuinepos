<table class="display data_tbl data__table table-striped">
    <thead>
        <tr>
            <th><b>Branch</b></th>
            <th><b>Gross Profit</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_gross_profit = 0;
        @endphp
        @foreach ($branches as $branch)
            <tr>
                <td>{{ $branch->name.' - '.$branch->branch_code }}</td>
                <td>
                    @php
                        $purchases = '';
                        $sales = '';
                        if ($by_profit_range == 'current_year') {
                            $purchases = DB::table('purchases')
                                ->where('branch_id', $branch->id)
                                ->whereYear('report_date', date('Y'))
                                ->get();
                            $sales = DB::table('sales')
                                ->where('branch_id', $branch->id)
                                ->whereYear('report_date', date('Y'))
                                ->get();
                        } else {
                            $purchases = DB::table('purchases')
                                ->whereBetween('report_date', [$form_date. ' 00:00:00', $to_date. ' 00:00:00'])
                                ->where('branch_id', $branch->id)
                                ->get();
                        
                            $sales = DB::table('sales')
                                ->whereBetween('report_date', [$form_date. ' 00:00:00', $to_date. ' 00:00:00'])
                                ->where('branch_id', $branch->id)
                                ->get();
                        }

                        //dd($sale_products);
                        $total_purchase = 0;
                        $total_sale = 0;
                        foreach ($purchases as $purchase) {
                            $total_purchase += $purchase->total_purchase_amount - $purchase->purchase_return_amount;
                        }
                        
                        foreach ($sales as $sale) {
                            $total_sale += $sale->total_payable_amount - $sale->sale_return_amount;
                        }
                        $gross_profit = $total_sale - $total_purchase;
                        $total_gross_profit += $gross_profit;
                    @endphp
                
                    {{ json_decode($generalSettings->business, true)['currency'] }}
                    {{ number_format((float) $gross_profit, 2, '.', '') }}
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="bg-primary text-white">
            <th><b>Total :</b>  </th>
            <th>
                <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                {{ number_format((float) $total_gross_profit, 2, '.', '') }}</b>
            </th>
        </tr>
    </tfoot>
</table>
<script>
    $('.data_tbl').DataTable();
</script>