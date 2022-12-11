<table id="kt_datatable" class="display data_tbl data__table">
    <thead>
        <tr>
            <th>@lang('menu.serial')</th>
            <th>@lang('menu.name')</th>
            <th>@lang('menu.duration')</th>
            <th>@lang('menu.type')</th>
            <th>@lang('menu.description')</th>
            <th>@lang('menu.action')</th>
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
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
    });
</script>
