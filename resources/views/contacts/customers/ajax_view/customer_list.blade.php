<table class="display data_tbl data__table table-striped">
    <thead>
        <tr>
            <th>Actions</th>
            <th>Contact ID</th>
            <th>Name</th>
            <th>Business Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Tax Number</th>
            <th>Group</th>
            <th>Opening Balance</th>
            <th>Sale Due</th>
            <th>Return Due</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
            <tr data-info="{{ $customer }}">
                <td> 
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Action
                        </button>

                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{ url('contacts/customers/view', $customer->id) }}"><i class="far fa-eye mr-1 text-primary"></i> View</a>

                            @if ($customer->total_sale_due > 0)
                                <a class="dropdown-item" id="pay_button" href="{{ route('customers.payment', $customer->id) }}"><i class="far fa-money-bill-alt mr-1 text-primary"></i> Receive Payment</a>
                            @endif

                            <a class="dropdown-item" id="money_receipt_list" href="{{ route('money.receipt.voucher.list', $customer->id) }}"><i class="far fa-file-alt mr-1 text-primary"></i> Payment Receipt Voucher</a>
                            
                            @if ($customer->total_sale_return_due > 0)
                                <a class="dropdown-item" id="pay_return_button" href="{{ route('customers.return.payment', $customer->id) }}"><i class="far fa-money-bill-alt mr-1 text-primary"></i> Pay Return Due</a>
                            @endif

                            @if (auth()->user()->permission->customers['customer_edit'] == '1')
                                <a class="dropdown-item" href="#" id="edit"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>
                            @endif
                            
                            @if (auth()->user()->permission->customers['customer_delete'] == '1')
                                <a class="dropdown-item" id="delete" href="{{ route('contacts.customer.delete', $customer->id) }}"><i class="far fa-trash-alt mr-1 text-primary"></i> Delete</a>
                            @endif
                            
                            @if ($customer->status == 1)
                                <a class="dropdown-item" id="change_status" href="{{ route('contacts.customer.change.status', $customer->id) }}"><i class="far fa-thumbs-up mr-1 text-success"></i> Change Status</a>
                            @else 
                                <a class="dropdown-item" id="change_status" href="{{ route('contacts.customer.change.status', $customer->id) }}"><i class="far fa-thumbs-down mr-1 text-danger"></i> Change Status</a>
                            @endif
                            <a class="dropdown-item" href="{{ url('contacts/customers/contact/info', $customer->id) }}"><i class="far fa-file-alt mr-1 text-primary"></i> Contact Info</a>
                            <a class="dropdown-item" href="{{ url('contacts/customers/ledger', $customer->id) }}"><i class="far fa-file-alt mr-1 text-primary"></i> Ledger</a>
                        </div>
                    </div>
                </td>
                <td>{{ $customer->contact_id ? $customer->contact_id : 'N/A' }}</td> 
                <td>{{ $customer->name }}</td> 
                <td>{{ $customer->business_name ? $customer->business_name : 'N/A' }}</td> 
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->customer_group ? $customer->customer_group->group_name : '' }}</td>
                <td>{{ $customer->tax_number ? $customer->tax_number : 'N/A' }}</td> 
                <td>{{ $customer->opening_balance }}</td>
                <td><span class="{{ $customer->total_sale_due < 0 ? 'text-danger' : '' }}">{{ $customer->total_sale_due }}</span></td>
                <td>{{ $customer->total_sale_return_due }}</td>
                <td>
                    @if ($customer->status == 1)
                        <i class="far fa-thumbs-up mr-1 text-success"></i>
                    @else 
                        <i class="far fa-thumbs-down mr-1 text-danger"></i>
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
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
        ],
        aaSorting: [[0, 'desc']]
    });
</script>

