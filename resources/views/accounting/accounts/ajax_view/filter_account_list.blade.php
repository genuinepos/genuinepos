<table class="display data_tbl data__table">
    <thead>
        <tr class="bg-navey-blue">
            <th class="text-white">Name</th>
            <th class="text-white">Account Number</th>
            <th class="text-white">Bank Name</th>
            <th class="text-white">Account Type</th>
            <th class="text-white">Remark</th>
            <th class="text-white">Balance</th>
            <th class="text-white">Created By</th>
            <th class="text-white">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($filteredAccounts as $account)
            <tr data-info="{{ $account }}">
                <td>{{ $account->name }}</td> 
                <td>{{ $account->account_number  }}</td> 
                <td>{{ $account->bank->name }}({{ $account->bank->branch_name }})</td> 
                <td>{{ $account->account_type ? $account->account_type->name : 'N/A' }}</td>
                <td>{{ $account->remark }}</td>
                <td>{{ $account->balance }}</td>
                <td>{{ $account->admin ? $account->admin->name : 'N/A' }}</td>

                <td> 
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a id="edit" title="Edit details" data-bs-target="#editModal" class="dropdown-item" href="javascript:;" ><i class="far fa-edit text-primary"></i> Edit</a>

                            <a class="dropdown-item" href="{{ route('accounting.accounts.book', $account->id) }}"><i class="fas fa-book text-primary"></i> Account Book</a>

                            <a class="dropdown-item" id="delete" href="{{ route('accounting.accounts.delete', $account->id) }}"><i class="fas fa-trash-alt text-primary"></i> Delete</a>
                            
                            @if ($account->status == 1)
                                <a id="fund_transfer" data-toggle="modal" data-bs-target="#fundTransferModal" data-id="{{ $account->id }}" data-ac_name="{{ $account->name.' ('.$account->account_number.')' }}" data-balance="{{ $account->balance }}" class="dropdown-item" href="#"><i class="far fa-money-bill-alt text-primary"></i> Fund Transfer</a>

                                <a id="deposit" data-id="{{ $account->id }}" data-ac_name="{{ $account->name.' ('.$account->account_number.')' }}" data-balance="{{ $account->balance }}" class="dropdown-item" href="#"><i class="fas fa-wallet text-primary"></i> Deposit</a>

                                <a id="change_status" class="dropdown-item" href="{{ route('accounting.accounts.change.status', $account->id) }}"><i class="far fa-times-circle text-danger"></i> Close</a> 
                            @else  
                                <a id="change_status" class="dropdown-item" href="{{ route('accounting.accounts.change.status', $account->id) }}"> <i class="far fa-check-circle text-success"></i> Active</a>   
                            @endif
                        </div>
                    </div>
                </td> 
            </tr>
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
        "lengthMenu" : [25, 100, 500, 1000, 2000],
    });
</script>
