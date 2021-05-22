<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">Serial</th>
            <th class="text-start">Warehouse Name</th>
            <th class="text-start">Warehouse Code</th>
            <th class="text-start">Phone</th>
            <th class="text-start">Address</th>
            <th class="text-start">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($warehouses as $warehouse)
            <tr data-info="{{ $warehouse }}">
                <td class="text-start">{{ $loop->index + 1 }}</td> 
                <td class="text-start">{{ $warehouse->warehouse_name }}</td> 
                <td class="text-start">{{ $warehouse->warehouse_code }}</td> 
                <td class="text-start">{{ $warehouse->phone }}</td> 
                <td class="text-start">{{ $warehouse->address }}</td> 
                
                <td nowrap="nowrap" class="text-start">
                    <a href="javascript:;" id="edit" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span>
                    </a>
                    <a href="{{ route('settings.warehouses.delete', $warehouse->id) }}" id="delete" class="action-btn c-delete"><span class="fas fa-trash "></span>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable();
</script>
