<table class="display data_tbl data__table">
    <thead>
        <tr class="text-start">
            <th class="text-start">S/L</th>
            <th class="text-start">Shift Name</th>
            <th class="text-start">Start Time</th>
            <th class="text-start">End Time</th>
            <th class="text-start">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shift as $row)
            <tr data-info="{{ $row }}" class="text-center">
                <td class="text-start">{{ $loop->index + 1 }}</td> 
                <td class="text-start">{{ $row->shift_name }}</td> 
                <td class="text-start">{{ $row->start_time }}</td> 
                <td class="text-start">{{ $row->endtime }}</td> 
                <td class="text-start"> 
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" id="edit" title="Edit details" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href=""" class="action-btn c-delete" id="delete"><span class="fas fa-trash "></span></a>
                    </div>
                </td> 
            </tr>
        @endforeach
    </tbody>
</table>

<!--Data table js active link-->
<script src="{{ asset('public') }}/assets/plugins/custom/data-table/datatable.active.js"></script>
<!--Data table js active link end-->
