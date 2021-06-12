<table id="att_table" class="display data__table data_tble stock_table compact" width="100%">
    <thead>
        <tr>
            <th>Employee</th>
            <th>Clock-in Time</th>
            <th>Clock-out Time</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($todayAttendances as $att)
            <tr>
                <td>{{ $att->prefix.' '.$att->name.' '.$att->last_name }}</td>
                <td>{{ date('h:i a', strtotime($att->clock_in)) }}</td>
                <td>{{ $att->clock_out ? date('h:i a', strtotime($att->clock_out)) : ''}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('#att_table').DataTable({
        dom: "Bfrtip",
        buttons: ["excel", "pdf", "print"],
        pageLength: 10,
    });
</script>