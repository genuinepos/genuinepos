<table id="kt_datatable" class="display data_tbl data__table">
    <thead>
        <tr>
            <th>Serial</th>
            <th>Name</th>
            <th>Duration</th>
            <th>Type</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($warranties as $warranty)
            <tr data-info="{{ $warranty }}">
                <td>{{ $loop->index + 1 }}</td> 
                <td>{{ $warranty->name }}</td> 
                <td>{{ $warranty->duration.' '.$warranty->duration_type }}</td> 
                <td>{{ $warranty->type == 1 ? 'Warranty' : 'Guaranty' }}</td>
                <td>{{ $warranty->description }}</td> 
                <td> 
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" class="action-btn c-edit" id="edit" title="Edit details">
                         <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('product.warranties.delete', $warranty->id) }}" class="action-btn c-delete" id="delete">
                            <i class="fa fa-trash"></i> 
                        </a>
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
            //{extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        "lengthMenu" : [25, 100, 500, 1000, 2000],
    });
</script>
