<table class="display data_tbl data__table table-striped">
    <thead>
        <tr>
            <th>Actions</th>
            <th>Supplier ID</th>
            <th>Prefix</th>
            <th>Name</th>
            <th>Business Name</th>
            <th>Phone</th>
            <th>Opening Balance({{ json_decode($generalSettings->business, true)['currency'] }})</th>
            <th>Total Purchase({{ json_decode($generalSettings->business, true)['currency'] }})</th>
            <th>Total Paid({{ json_decode($generalSettings->business, true)['currency'] }})</th>
            <th>Purchase Due({{ json_decode($generalSettings->business, true)['currency'] }})</th>
            <th>Total Return Item Amt.({{ json_decode($generalSettings->business, true)['currency'] }})</th>
            <th>Returnable Due Amt.({{ json_decode($generalSettings->business, true)['currency'] }})</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($suppliers as $supplier)
            <tr>
                <td> 
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{ url('contacts/suppliers/view', $supplier->id) }}"><i class="fas fa-tasks text-primary"></i> Manage</a>
        
                            {{-- @if ($supplier->total_purchase_return_due > 0)

                                <a class="dropdown-item" id="pay_receive_button" href="{{ route('suppliers.return.payment', $supplier->id) }}"><i class="far fa-money-bill-alt text-primary"></i> Receive Purchase Return Amount</a> 
                            @endif --}}

                            {{-- <a class="dropdown-item" id="view_payment" href="{{ route('suppliers.view.payment', $supplier->id) }}"><i class="far fa-trash-alt text-primary"></i> View Payments</a> --}}
                            
                            @if (auth()->user()->permission->contact['supplier_edit'] == '1')

                                <a class="dropdown-item" href="{{ route('contacts.supplier.edit', $supplier->id) }}" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>
                            @endif

                            @if (auth()->user()->permission->contact['supplier_delete'] == '1')

                                <a class="dropdown-item" id="delete" href="{{ route('contacts.supplier.delete', $supplier->id) }}"><i class="far fa-trash-alt text-primary"></i> Delete</a>
                            @endif

                            @if ($supplier->status == 1)

                                <a class="dropdown-item" id="change_status" href="{{ route('contacts.supplier.change.status', $supplier->id) }}"><i class="far fa-thumbs-up text-success"></i> Change Status</a>
                            @else 
                            
                                <a class="dropdown-item" id="change_status" href="{{ route('contacts.supplier.change.status', $supplier->id) }}"><i class="far fa-thumbs-down text-danger"></i> Change Status</a>
                            @endif
                        </div>
                    </div>
                </td>
                <td>{{ $supplier->contact_id ? $supplier->contact_id : '....' }}</td>
                <td>{{ $supplier->prefix }}</td> 
                <td>{{ $supplier->name }}</td> 
                <td>{{ $supplier->business_name ? $supplier->business_name : 'N/A' }}</td> 
                <td>{{ $supplier->phone }}</td>
                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($supplier->opening_balance) }}</td>
                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase) }}</td>
                <td class="text-end">
                    <span class="text-success">{{ App\Utils\Converter::format_in_bdt($supplier->total_paid) }}</span> 
                </td>
                <td class="text-end">
                    <span class="text-danger">{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_due) }}</span>
                </td>
                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($supplier->total_return) }}</td>
                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_return_due) }}</td>
                <td>
                    @if ($supplier->status == 1)
                        <span class="text-success"><b>Active</b></span>
                    @else 
                        <span class="text-danger"><b>Deactivated</b></span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
        ],
        aaSorting: [[0, 'desc']],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
    });
</script>
