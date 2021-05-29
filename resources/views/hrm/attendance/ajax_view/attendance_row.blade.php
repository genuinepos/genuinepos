
@php use Carbon\Carbon;  @endphp
@if ($attendance)
    <tr data-user_id="{{ $attendance->user_id }}">
        <td>
            <p class="m-0 mt-2">{{ $attendance->prefix . ' ' . $attendance->name . ' ' . $attendance->last_name }}</p>
            <input type="hidden" name="user_ids[{{ $attendance->user_id }}]" value="{{ $attendance->user_id }}" />
        </td>

        <td>
            <p class="m-0">{{ $attendance->clock_in_ts }}</p>
            @php
                $startTime = Carbon::parse($attendance->clock_in_ts);
                $totalDuration = $startTime->diffForHumans();
            @endphp
            <small class="m-0 text-muted">(Clock In - {{ $totalDuration }})</small>
            <input type="hidden" name="clock_ins[{{ $attendance->user_id }}]" value="{{ $attendance->clock_in }}" />
        </td>

        <td>
            <input type="time" name="clock_outs[{{ $attendance->user_id }}]" placeholder="Clock Out"
                class="form-control"/>
        </td>

        <td>
            <select required class="form-control" name="shift_ids[{{ $attendance->user_id }}]">
                <option value="">Select Shift</option>
                @foreach ($shifts as $row)
                    <option {{ $attendance->shift_id == $row->id ? 'SELECTED' : '' }} value="{{ $row->id }}">{{ $row->shift_name }}</option>
                @endforeach
            </select>
        </td>

        <td>
            <input type="text" name="clock_in_notes[{{ $attendance->user_id }}]" class="form-control" value="{{ $attendance->clock_in_note }}" placeholder="Clock in note" />
        </td>

        <td>
            <input type="text" name="clock_out_notes[{{ $attendance->user_id }}]" class="form-control" placeholder="clock out note" value="{{ $attendance->clock_out_note }}" />
        </td>

        <td>
            <a href="" class="btn_remove"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
        </td>
    </tr>
@else
<tr data-user_id="{{ $employee->id }}">
    <td>
        <p class="m-0 mt-2">{{ $employee->prefix . ' ' . $employee->name . ' ' . $employee->last_name }}</p>
        <input type="hidden" name="user_ids[{{ $employee->id }}]" value="{{ $employee->id }}" />
    </td>

    <td>
        <input required type="time" name="clock_ins[{{ $employee->id }}]"
        class="form-control"/>
    </td>

    <td>
        <input type="time" name="clock_outs[{{ $employee->id }}]" placeholder="Clock Out"
            class="form-control"/>
    </td>

    <td>
        <select required class="form-control" name="shift_ids[{{ $employee->id }}]">
            <option value="">Select Shift</option>
            @foreach ($shifts as $row)
                <option value="{{ $row->id }}">{{ $row->shift_name }}</option>
            @endforeach
        </select>
    </td>

    <td>
        <input type="text" name="clock_in_notes[{{ $employee->id }}]" class="form-control" placeholder="Clock in note" />
    </td>

    <td>
        <input type="text" name="clock_out_notes[{{ $employee->id }}]" class="form-control" placeholder="clock out note"/>
    </td>

    <td>
        <a href="" class="btn_remove"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
    </td>
</tr>
@endif
