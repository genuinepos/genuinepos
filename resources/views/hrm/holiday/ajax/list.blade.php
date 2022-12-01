<table class="display data_tbl data__table">
    <thead>
        <tr class="text-center">
            <th>S/L</th>
            <th>Name</th>
            <th>@lang('menu.date')</th>
            <th>Allowed Branch</th>
            <th>Note</th>
            <th>@lang('menu.action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($holidays as $key => $row)
            <tr data-info="{{ $row }}" class="text-center">
                <td>{{ $key+1 }}</td> 
                <td>{{ $row->holiday_name }}</td> 
                <td>{{date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->start_date)) }} to {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->end_date)) }} </td> 
                <td>
                    @if ($row->is_all)
                        All
                    @elseif($row->branch_id)
                        {{ $row->branch->name.'/'.$row->branch->branch_code }}
                    @else 
                        {{ json_decode($generalSettings->business, true)['shop_name'] }}  (<b>Head Office</b>)
                    @endif
                </td> 
                <td>{{ $row->notes }}</td> 
                <td> 
                    <div class="dropdown table-dropdown">
                        <a href="{{ route('hrm.holidays.edit', $row->id) }}" id="edit" title="Edit details" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('hrm.holidays.delete', $row->id) }}" class="action-btn c-delete" id="delete"><span class="fas fa-trash "></span></a>
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
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        }
    );
</script>
