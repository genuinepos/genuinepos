<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($allowance as $row)
            <tr data-info="{{ $row }}">
                <td>{{ $row->description }}</td> 
                <td>
                	@if($row->type=="Allowance")
                	    <span class="badge bg-success"> {{ $row->type }} </span>
                	@else
                	    <span class="badge bg-danger"> {{ $row->type }} </span>
                	@endif 
                </td> 
                <td>{{ $row->amount }} {{ $row->amount_type == 1 ? json_decode($generalSettings->business, true)['currency'] : '%' }}</td> 
              
                <td> 
                    <div class="dropdown table-dropdown">
                        <a href="{{ route('hrm.allowance.edit', $row->id) }}" id="edit" title="Edit details" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('hrm.allowance.delete', $row->id) }}" class="action-btn c-delete" id="delete"><span class="fas fa-trash "></span></a>
                    </div>
                </td> 
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({"lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]]});
</script>
