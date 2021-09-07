<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">Serial</th>
            <th class="text-start">Unit Name</th>
            <th class="text-start">Code Name</th>
    
            <th class="text-start">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($units as $unit)
            <tr data-info="{{ $unit }}">
                <td class="text-start">{{ $loop->index + 1 }}</td> 
                <td class="text-start">{{ $unit->name }}</td> 
                <td class="text-start">{{ $unit->code_name }}</td> 
        
                <td nowrap="nowrap" class="text-start">
                    <a href="javascript:;" id="edit" title="Edit details" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span>
                    </a>
                    <a href="{{ route('settings.units.delete', $unit->id) }}" id="delete" class="action-btn c-delete"><span class="fas fa-trash "></span>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({"lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]]});
</script>
