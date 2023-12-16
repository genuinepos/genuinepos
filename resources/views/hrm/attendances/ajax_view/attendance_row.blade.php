@php
    use Carbon\Carbon;
    $generalSettings = config('generalSettings');
    $dateFormat = $generalSettings['business__date_format'];
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
@if ($attendance)
    <tr data-user_id="{{ $attendance->user_id }}">
        <td>
            <p class="m-0 mt-2">{{ $attendance->prefix . ' ' . $attendance->name . ' ' . $attendance->last_name . '' . __('ID') . ': ' . ($user->emp_id ? $user->emp_id : __('N/A')) . ')' }}</p>
            <input type="hidden" name="user_ids[{{ $attendance->user_id }}]" value="{{ $attendance->user_id }}" />
        </td>

        <td>
            {{ date($dateFormat, strtotime($attendance->clock_in_date)) }}
        </td>

        <td>
            <p class="m-0">{{ date($timeFormat, strtotime($attendance->clock_in)) }}</p>
            @php
                $startTime = Carbon::parse($attendance->clock_in_ts);
                $totalDuration = $startTime->diffForHumans();
            @endphp
            <small class="m-0 text-muted">({{ __('Clock In') }} - {{ $totalDuration }})</small>
            <input required type="hidden" name="clock_ins[{{ $attendance->user_id }}]" value="{{ $attendance->clock_in }}" />
        </td>

        <td>
            <input required type="text" name="clock_out_dates[{{ $attendance->user_id }}]" class="form-control clock_out_date" id="clock_out_date{{$attendance->user_id}}" value="{{ date($dateFormat) }}" />
        </td>

        <td>
            <input type="time" name="clock_outs[{{ $attendance->user_id }}]" placeholder="{{ __('Clock Out') }}" class="form-control" />
        </td>

        <td>
            <select name="shift_ids[{{ $attendance->user_id }}]" class="form-control" id="shift_id">
                <option value="">{{ __("Select Shift") }}</option>
                @foreach ($shifts as $shift)
                    <option {{ $attendance->shift_id == $shift->id ? 'SELECTED' : '' }} value="{{ $shift->id }}">{{ $shift->name }}</option>
                @endforeach
            </select>
        </td>

        <td>
            <input type="text" name="clock_in_notes[{{ $attendance->user_id }}]" class="form-control" value="{{ $attendance->clock_in_note }}" placeholder="{{ __('Clock in note') }}" />
        </td>

        <td>
            <input type="text" name="clock_out_notes[{{ $attendance->user_id }}]" class="form-control" placeholder="{{ __('Clock out note') }}" value="{{ $attendance->clock_out_note }}" />
        </td>

        <td>
            <a onclick="deleteRow(this); return false;" class="btn_remove"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
        </td>
    </tr>
@else
    <tr data-user_id="{{ $user->id }}">
        <td>
            <p class="m-0 mt-2">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '(' . __('ID') . ': ' . ($user->emp_id ? $user->emp_id : __('N/A')) . ')' }}</p>
            <input type="hidden" name="user_ids[{{ $user->id }}]" value="{{ $user->id }}" />
        </td>

        <td>
            {{ date($dateFormat) }}
        </td>

        <td>
            <input required type="time" name="clock_ins[{{ $user->id }}]" class="form-control clock_in_time"/>
        </td>

        <td>
            <input required type="text" name="clock_out_dates[{{ $user->id }}]" class="form-control clock_out_date" id="clock_out_date{{$user->id}}" value="{{ date($dateFormat) }}" placeholder="{{ __('Clock Out Date') }}"/>
        </td>

        <td>
            <input type="time" name="clock_outs[{{ $user->id }}]" class="form-control" placeholder="{{ __('Clock Out') }}"/>
        </td>

        <td>
            <select name="shift_ids[{{ $user->id }}]" class="form-control" id="shift_id">
                <option value="">{{ __("Select Shift") }}</option>
                @foreach ($shifts as $shift)
                    <option {{ $user->shift_id == $shift->id ? 'SELECTED' : '' }} value="{{ $shift->id }}">{{ $shift->name }}</option>
                @endforeach
            </select>
        </td>

        <td>
            <input type="text" name="clock_in_notes[{{ $user->id }}]" class="form-control" placeholder="{{ __('Clock in note') }}" />
        </td>

        <td>
            <input type="text" name="clock_out_notes[{{ $user->id }}]" class="form-control" placeholder="{{ __('Clock out note') }}" />
        </td>

        <td>
            <a href="#" onclick="deleteRow(this); return false;" class="btn_remove"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
        </td>
    </tr>
@endif
