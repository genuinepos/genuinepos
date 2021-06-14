<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">Reference No</th>
            <th class="text-start">Type</th>
            <th class="text-start">Employee</th>
            <th class="text-start">Date</th>
            <th class="text-start">Reason</th>
            <th class="text-start">Status</th>
            <th class="text-start">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leave as $key => $row)
            <tr data-info="{{ $row }}">
                <td class="text-start">{{ $row->reference_number }}</td> 
                <td class="text-start">{{ $row->leave_type->leave_type }}</td> 
                <td class="text-start">{{ $row->admin_and_user->prefix.' '.$row->admin_and_user->name.' '.$row->admin_and_user->last_name }}</td> 
                <td class="text-start">{{ $row->start_date }} to {{ $row->end_date }}</td> 
                <td class="text-start">{{ $row->reason }}</td> 
                <td class="text-start">
                	@if($row->status == 0)
                	   <span class="badge bg-warning">Pending</span>
                	@else
                	  <span class="badge bg-success">Success</span>
                	@endif   
                </td> 
                <td class="text-start"> 
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" id="edit" title="Edit details" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('hrm.leave.delete', $row->id) }}"" class="action-btn c-delete" id="delete"><span class="fas fa-trash "></span></a>
                    </div>
                </td> 
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable(
        {
            dom: "lBfrtip",
            buttons: [ 
                {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            aaSorting: [[0, 'desc']]
        }
    );
</script>
