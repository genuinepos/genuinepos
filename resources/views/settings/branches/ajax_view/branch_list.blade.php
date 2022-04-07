<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">Logo</th>
            <th class="text-start">B.Location Name</th>
            <th class="text-start">B.Location Code</th>
            <th class="text-start">Phone</th>
            <th class="text-start">City</th>
            <th class="text-start">State</th>
            <th class="text-start">Zip-Code</th>
            <th class="text-start">Country</th>
            <th class="text-start">Email</th>
            <th class="text-start">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($branches as $branch)
            <tr data-info="{{ $branch }}">
                <td class="text-start"><img style="height: 25px; width:50px" src="{{ asset('public/uploads/branch_logo/'.$branch->logo) }}" alt=""></td> 
                <td class="text-start">{{ $branch->name }}</td> 
                <td class="text-start">{{ $branch->branch_code }}</td> 
                <td class="text-start">{{ $branch->phone }}</td> 
                <td class="text-start">{{ $branch->city }}</td> 
                <td class="text-start">{{ $branch->state }}</td> 
                <td class="text-start">{{ $branch->zip_code }}</td>
                <td class="text-start">{{ $branch->country }}</td>
                <td class="text-start">{{ $branch->email }}</td>
                
                <td nowrap="nowrap" class="text-start">
                    <a href="{{ route('settings.branches.edit', $branch->id) }}" id="edit" class="action-btn c-edit" id="edit">
                        <span class="fas fa-edit"></span>
                    </a>

                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                        <a href="{{ route('settings.branches.delete', $branch->id) }}" id="delete" class="action-btn c-delete">
                            <span class="fas fa-trash "></span>
                        </a>  
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
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
    });
</script>
