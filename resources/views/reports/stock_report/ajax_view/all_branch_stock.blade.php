<table class="display data_tbl data__table">
    <thead>
        <tr class="bg-navey-blue">
            <th>P.Code(SKU)</th>
            <th>Product</th>
            <th>B.Location</th>
            <th>Current Stock</th>
            <th>Unit Price Exc.Tax</th>
            <th>Current Stock Value <b><small>(By Purchase Cost)</small></b></th>
            <th>Total Unit Sold</th>
            <th>Total Adjusted</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mb_stocks as $row)
            @if ($row->variant_name)
                <tr>
                    <td>{{ $row->variant_code }}</td>
                    <td>{{ $row->name.'-'.$row->variant_name }}</td>
                    <td>{!! json_decode($generalSettings->business, true)['shop_name'] .'<b>(HO)</b>' !!}</td>
                    <td>{{ $row->v_mb_stock.'('.$row->code_name.')' }}</td>
                    <td>{{ $row->variant_price }}</td>
                    <td>
                        @php
                            $currentStockValue = $row->variant_cost_with_tax * $row->v_mb_stock;
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                    </td>
                    <td>{{ $row->v_mb_total_sale.'('.$row->code_name.')' }}</td>
                    <td>0.00</td>
                </tr>
            @else 
                <tr>
                    <td>{{ $row->product_code }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{!! json_decode($generalSettings->business, true)['shop_name'] .'<b>(HO)</b>' !!}</td>
                    <td>{{ $row->mb_stock.'('.$row->code_name.')' }}</td>
                    <td>{{ $row->product_price }}</td>
                    <td>
                        @php
                            $currentStockValue = $row->product_cost_with_tax * $row->mb_stock;
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                    </td>
                    <td>{{ $row->mb_total_sale.'('.$row->code_name.')' }}</td>
                    <td>0.00</td>
                </tr>
            @endif
        @endforeach

        @foreach ($branch_stock as $row)
            @if ($row->variant_name)
                <tr>
                    <td>{{ $row->variant_code }}</td>
                    <td>{{ $row->name.'-'.$row->variant_name }}</td>
                    <td>{!! $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' !!}</td>
                    <td>{{ $row->variant_quantity.'('.$row->code_name.')' }}</td>
                    <td>{{ $row->variant_price }}</td>
                    <td>
                        @php
                            $currentStockValue = $row->variant_cost_with_tax * $row->variant_quantity;
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                    </td>
                    <td>{{ $row->v_total_sale.'('.$row->code_name.')' }}</td>
                    <td>0.00</td>
                </tr>
            @else 
                <tr>
                    <td>{{ $row->product_code }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{!! $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' !!}</td>
                    <td>{{ $row->product_quantity.'('.$row->code_name.')' }}</td>
                    <td>{{ $row->product_price }}</td>
                    <td>
                        @php
                            $currentStockValue = $row->product_cost_with_tax * $row->product_quantity;
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                    </td>
                    <td>{{ $row->total_sale.'('.$row->code_name.')' }}</td>
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
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        ordering: false,
    });
</script>