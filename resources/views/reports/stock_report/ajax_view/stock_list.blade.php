<table class="display data_tbl data__table">
    <thead>
        <tr class="bg-navey-blue">
            <th>P.Code(SKU)</th>
            <th>Product</th>
            <th>Unit Price Exc.Tax</th>
            <th>B.Location</th>
            <th>Warehouse</th>
            <th>Current Stock</th>
            <th>Current Stock Value <b><small>(By Purchase Price)</small></b></th>
            <th>Total Unit Sold</th>
            <th>Total Adjusted</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productStock as $row)
            @if ($row->variant_name)
                <tr>
                    <td>{{ $row->variant_code }}</td>
                    <td>{{ $row->name.'-'.$row->variant_name }}</td>
                    <td>{{ $row->product_price }}</td>
                    <td>{{ json_decode($generalSettings->business, true)['shop_name'] }}<b>(HO)</b> </td>
                    <td>---</td>
                    <td>{{ $row->v_mb_stock }}</td>
                    <td>
                        @php
                            $currentStockValue = $row->product_cost_with_tax * $row->v_mb_stock;
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                    </td>
                    <td>{{ $row->v_mb_total_sale }}</td>
                    <td>0.00</td>
                </tr> 
            @endif
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
    });
</script>