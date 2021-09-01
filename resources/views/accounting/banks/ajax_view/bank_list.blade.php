<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">SL</th>
            <th class="text-start">Bank Name</th>
            <th class="text-start">Branch Name</th>
            <th class="text-start">Address</th>
            <th class="text-start">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($banks as $bank)
            <tr data-info="{{ $bank }}">
                <td class="text-start">{{ $loop->index + 1 }}</td> 
                <td class="text-start">{{ $bank->name }}</td> 
                <td class="text-start">{{ $bank->branch_name  }}</td> 
                <td class="text-start">{{ $bank->address  }}</td> 
                <td class="text-start"> 
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" id="edit" title="Edit details" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('accounting.banks.delete', $bank->id) }}" class="action-btn c-delete" id="delete"><span class="fas fa-trash "></span></a>
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
