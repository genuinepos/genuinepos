<table id="kt_datatable" class="display data_tbl data__table">
    <thead>
        <tr class="bg-navey-blue">
            <th class="text-white">Serial</th>
            <th class="text-white">Variant</th>
            <th class="text-white">Childs</th>
            <th class="text-white">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($variants as $variant)
            <tr data-info="{{ $variant }}">
                <td>{{ $loop->index + 1 }}</td> 
                <td>{{ $variant->bulk_variant_name }}</td> 
                <td>
                    @foreach ($variant->bulk_variant_childs as $variant_child)
                        {{ $variant_child->child_name.' ,' }}
                    @endforeach
                </td> 
                <td> 
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" class="action-btn c-edit" id="edit" title="Edit details" data-bs-toggle="modal" data-bs-target="#editModal">
                            <span class="fas fa-edit"></span>
                        </a>
                        <a href="{{ route('product.variants.delete', $variant->id) }}" class="action-btn c-delete" id="delete">
                            <span class="fas fa-trash "></span>
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
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
    });
</script>