
<table class="display data_tbl data__table table-striped">
    <thead>
        <tr>
            <th><b>Invoice</b> </th>
            <th><b>Gross Profit</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_gross_profit = 0;
        @endphp
        @foreach ($invoices as $invoice)
            <tr>
                <td class="text-muted">{{ $invoice->invoice_id }}</td>
                <td>
                    @php
                        $total_unit_cost = 0;
                        foreach ($invoice->sale_products as $sale_product) {
                            $total_unit_cost += $sale_product->unit_cost_inc_tax * $sale_product->quantity;
                        }
                        $gross_profit = $invoice->total_payable_amount - $total_unit_cost - $invoice->order_tax_amount - $invoice->shipment_charge;
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
