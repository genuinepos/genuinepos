<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">Serial</th>
            <th class="text-start">Variant</th>
            <th class="text-start">Childs</th>
            <th class="text-start">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($variants as $variant)
            <tr data-info="{{ $variant }}">
                <td class="text-start">{{ $loop->index + 1 }}</td> 
                <td class="text-start">{{ $variant->bulk_variant_name }}</td> 
                <td class="text-start">
                    @foreach ($variant->bulk_variant_childs as $variant_child)
                        {{ $variant_child->child_name.' ,' }}
                    @endforeach
                </td> 
                <td class="text-start"> 
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
           // {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
    });
</script>