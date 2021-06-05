<table class="display data_tbl data__table table-striped">
    <thead>
        <tr>
            <th>Actions</th>
            <th>Contact ID</th>
            <th>Prefix</th>
            <th>Name</th>
            <th>Business Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Tax ID</th>
            <th>Opening Balance</th>
            <th>Purchase Due</th>
            <th>Return Due</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($suppliers as $supplier)
            <tr data-info="{{ $supplier }}">
                 
                <td> 
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            @if ($supplier->total_purchase_due > 0)
                                <a class="dropdown-item" id="pay_button" href="{{ route('suppliers.payment', $supplier->id) }}"><i class="far fa-money-bill-alt mr-1 text-primary"></i> Pay</a>
                            @endif

                            @if ($supplier->total_purchase_return_due > 0)
                                <a class="dropdown-item" id="pay_receive_button" href="{{ route('suppliers.return.payment', $supplier->id) }}"><i class="far fa-money-bill-alt mr-1 text-primary"></i> Receive Purchase Return Amount</a> 
                            @endif
                            
                            <a class="dropdown-item" href="{{ url('contacts/suppliers/view', $supplier->id) }}"><i class="far fa-eye mr-1 text-primary"></i> View</a>

                            @if (auth()->user()->permission->supplier['supplier_edit'] == '1')
                                <a class="dropdown-item" href="#" id="edit"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>
                            @endif

                            @if (auth()->user()->permission->supplier['supplier_delete'] == '1')
                                <a class="dropdown-item" id="delete" href="{{ route('contacts.supplier.delete', $supplier->id) }}"><i class="far fa-trash-alt mr-1 text-primary"></i> Delete</a>
                            @endif

                            @if ($supplier->status == 1)
                                <a class="dropdown-item" id="change_status" href="{{ route('contacts.supplier.change.status', $supplier->id) }}"><i class="far fa-thumbs-up mr-1 text-success"></i> Change Status</a>
                            @else 
                                <a class="dropdown-item" id="change_status" href="{{ route('contacts.supplier.change.status', $supplier->id) }}"><i class="far fa-thumbs-down mr-1 text-danger"></i> Change Status</a>
                            @endif

                            <a class="dropdown-item" href="{{ url('contacts/suppliers/contact/info', $supplier->id) }}"> <i class="fas fa-info-circle mr-1 text-primary"></i> Contact Info</a>

                            <a class="dropdown-item" href="{{ url('contacts/suppliers/ledger', $supplier->id) }}"><i class="far fa-file-alt mr-1 text-primary"></i> Ledger</a>
                        </div>
                    </div>
                </td>
                <td>{{ $supplier->contact_id ? $supplier->contact_id : '....' }}</td>
                <td>{{ $supplier->prefix }}</td> 
                <td>{{ $supplier->name }}</td> 
                <td>{{ $supplier->business_name ? $supplier->business_name : 'N/A' }}</td> 
                <td>{{ $supplier->phone }}</td>
                <td>{{ $supplier->email }}</td>
                <td>{{ $supplier->tax_number ? $supplier->tax_number : 'N/A' }}</td> 
                <td><b>{{ json_decode($generalSettings->business, true)['currency'] .' '. $supplier->opening_balance }}</b></td>
                <td><b>{{ json_decode($generalSettings->business, true)['currency'] .' '. $supplier->total_purchase_due }}</b></td>
                <td><b>{{ json_decode($generalSettings->business, true)['currency'] .' '. $supplier->total_purchase_return_due }}</b></td>
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
    $('.data_tbl').DataTable();
</script>
