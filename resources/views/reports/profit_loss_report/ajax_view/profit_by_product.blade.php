<table class="display data_tbl data__table table-striped">
    <thead>
        <tr>
            <th><b>Product</b></th>
            <th><b>Gross Profit</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_gross_profit = 0;
        @endphp
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>
                    @php
                        $purchase_products = '';
                        $sale_products = '';
                        if ($by_profit_range == 'current_year') {
                            $purchase_products = DB::table('purchase_products')
                                ->where('product_id', $product->id)
                                ->whereYear('created_at', date('Y'))
                                ->get();
                            $sale_products = DB::table('sale_products')
                                ->where('product_id', $product->id)
                                ->whereYear('created_at', date('Y'))
                                ->get();
                        } else {
                            $purchase_products = DB::table('purchase_products')
                                ->whereBetween('created_at', [$form_date. ' 00:00:00', $to_date. ' 00:00:00'])
                                ->where('product_id', $product->id)
                                ->get();
                        
                            $sale_products = DB::table('sale_products')
                                ->whereBetween('created_at', [$form_date. ' 00:00:00', $to_date. ' 00:00:00'])
                                ->where('product_id', $product->id)
                                ->get();
                        }

                        //dd($sale_products);
                        $total_purchase = 0;
                        $total_sale = 0;
                        foreach ($purchase_products as $purchase_product) {
                            $total_purchase += $purchase_product->line_total;
                        }
                        
                        foreach ($sale_products as $sale_product) {
                            $total_sale += $sale_product->subtotal;
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
