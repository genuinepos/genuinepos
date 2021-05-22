<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">Name</th>
            <th class="text-start">Account Number</th>
            <th class="text-start">Bank Name</th>
            <th class="text-start">Account Type</th>
            <th class="text-start">Remark</th>
            <th class="text-start">Balance</th>
            <th class="text-start">Created By</th>
            <th class="text-start">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($accounts as $account)
            <tr data-info="{{ $account }}">
                <td class="text-start">{{ $account->name }}</td> 
                <td class="text-start">{{ $account->account_number }}</td> 
                <td class="text-start">{{ $account->bank->name }}({{ $account->bank->branch_name }})</td> 
                <td class="text-start">{{ $account->account_type ? $account->account_type->name : 'N/A' }}</td>
                <td class="text-start">{{ $account->remark }}</td>
                <td class="text-start">{{ $account->balance }}</td>
                <td class="text-start">{{ $account->admin ? $account->admin->name.' '.$account->admin->last_name : 'N/A' }}</td>

                <td class="text-start"> 
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a id="edit" title="Edit details" data-bs-target="#editModal" class="dropdown-item" href="javascript:;" ><i class="far fa-edit text-primary"></i>Edit</a>

                            <a class="dropdown-item" href="{{ route('accounting.accounts.book', $account->id) }}"><i class="fas fa-book text-primary"></i> Account Book</a>

                            <a class="dropdown-item" href="{{ route('accounting.accounts.delete', $account->id) }}" id="delete"><i class="fas fa-book text-primary"></i> Delete</a>

                            <a id="fund_transfer" class="dropdown-item" data-toggle="modal" data-bs-target="#fundTransferModal" data-id="{{ $account->id }}" data-ac_name="{{ $account->name.' ('.$account->account_number.')' }}" data-balance="{{ $account->balance }}" href="#"><i class="far fa-money-bill-alt text-primary"></i> Fund Transfer</a>

                            <a id="deposit" data-toggle="modal" data-bs-target="#depositModal" data-id="{{ $account->id }}" data-ac_name="{{ $account->name.' ('.$account->account_number.')' }}" data-balance="{{ $account->balance }}" class="dropdown-item" href="#"><i class="fas fa-wallet text-primary"></i> Deposit </a>

                            <a id="change_status" class="dropdown-item" href="{{ route('accounting.accounts.change.status', $account->id) }}"><i class="far fa-times-circle text-danger"></i> Close</a>
                        </div>
                    </div>
                </td> 
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable();
</script>

