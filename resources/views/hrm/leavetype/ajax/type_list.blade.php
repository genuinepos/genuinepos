<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">@lang('menu.serial')</th>
            <th class="text-start">@lang('menu.type')</th>
            <th class="text-start">{{ __('Max leave') }}</th>
            <th class="text-start">{{ __('Leave Count Interval') }}</th>
            <th class="text-start">@lang('menu.action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leavetype as $key => $row)
            <tr data-info="{{ $row }}" class="text-center">
                <td class="text-start">{{ $key+1 }}</td>
                <td class="text-start">{{ $row->leave_type }}</td>
                <td class="text-start">{{ $row->max_leave_count }}</td>
                <td class="text-start">
                	@if($row->leave_count_interval==1)
                	    <span class="badge bg-primary">{{ __('Current Month') }}</span>
                	@elseif($row->leave_count_interval==2)
                	    <span class="badge bg-warning">{{ __('Current Financial Year') }}</span>
                	@else
                	    <span class="badge bg-info">@lang('menu.none')</span>
                	@endif
                </td>
                <td class="text-start">
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" id="edit" title="Edit details" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                         <a href="{{ route('hrm.leavetype.delete', $row->id) }}" class="action-btn c-delete" id="delete"><span class="fas fa-trash "></span></a>
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
        aaSorting: [[0, 'desc']],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
    });
</script>
