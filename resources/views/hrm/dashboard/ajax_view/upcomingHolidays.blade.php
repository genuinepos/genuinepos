@if (count($holidays) > 0)
    @foreach ($holidays as $holidays)
    <tr>
        <td>{{ $holidays->holiday_name }}</td>
        <td></td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="2" class="text-center">No Data Found.</td>
    </tr>
@endif