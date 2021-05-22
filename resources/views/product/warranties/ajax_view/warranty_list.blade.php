<table id="kt_datatable" class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-white">Serial</th>
            <th class="text-white">Name</th>
            <th class="text-white">Duration</th>
            <th class="text-white">Type</th>
            <th class="text-white">Description</th>
            <th class="text-white">Action</th>
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
                        <a href="javascript:;" class="action-btn c-edit" id="edit" title="Edit details" data-bs-toggle="modal" data-bs-target="#editModal">
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

<!--Data table js active link-->
<script src="{{ asset('public') }}/assets/plugins/custom/data-table/datatable.active.js"></script>
<!--Data table js active link end-->
