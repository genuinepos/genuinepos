<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">SL</th>
            <th class="text-start">Name</th>
            <th class="text-start">Remark</th>
            <th class="text-start">Status</th>
            <th class="text-start">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($types as $type)
            <tr data-info="{{ $type }}" class="text-center">
                <td class="text-start">{{ $loop->index + 1 }}</td> 
                <td class="text-start">{{ $type->name }}</td> 
                <td class="text-start">{{ $type->remark  }}</td> 
                <td class="text-start">
                    @if ($type->status == 1)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Deactivated</span>
                    @endif
                </td> 
                <td class="text-start"> 
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" id="edit" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>

                        <a href="{{ route('accounting.types.delete', $type->id) }}"  class="action-btn c-delete" id="delete"><span class="fas fa-trash "></span>
                        </a>

                        @if ($type->status == 1)
                            <a id="change_status" class="action-btn btn-icon" href="{{ route('accounting.types.change.status', $type->id) }}"><i class="far fa-thumbs-up"></i></a>
                        @else 
                            <a id="change_status" class="action-btn btn-icon" href="{{ route('accounting.types.change.status', $type->id) }}"><i class="far fa-thumbs-down text-danger"></i></a>
                        @endif
                    </div>
                </td> 
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable();
</script>
