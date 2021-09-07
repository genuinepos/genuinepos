<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">S/L</th>
            <th class="text-start">Name</th>
            <th class="text-start">Department ID</th>
            <th class="text-start">description</th>
            <th class="text-start">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($department as $key => $row)
            <tr data-info="{{ $row }}" class="text-center">
                <td class="text-start">{{ $key+1 }}</td> 
                <td class="text-start">{{ $row->department_name }}</td> 
                <td class="text-start">{{ $row->department_id }}</td> 
                <td class="text-start">{{ $row->description }}</td> 
                <td class="text-start"> 
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" id="edit" title="Edit details" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('hrm.department.delete', $row->id) }}" class="action-btn c-delete" id="delete"><span class="fas fa-trash "></span></a>
                    </div>
                </td> 
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable(
        {
            dom: "lBfrtip",
            buttons: [ 
                {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]]
        }
    );
</script>
