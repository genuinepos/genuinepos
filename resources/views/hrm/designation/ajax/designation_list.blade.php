<table class="display data_tbl data__table">
    <thead>
        <tr class="text-center">
            <th class="text-start">S/L</th>
            <th class="text-start">Name</th>
            <th class="text-start">Description</th>
            <th class="text-start">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($designation as $key => $row)
            <tr data-info="{{ $row }}">
                <td class="text-start">{{ $key+1 }}</td> 
                <td class="text-start">{{ $row->designation_name }}</td> 
                <td class="text-start">{{ $row->description }}</td> 
                <td class="text-start"> 
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" id="edit" title="Edit details" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('hrm.designations.delete', $row->id) }}" class="action-btn c-delete" id="delete"><span class="fas fa-trash "></span></a>
                    </div>
                </td> 
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable();
</script>
