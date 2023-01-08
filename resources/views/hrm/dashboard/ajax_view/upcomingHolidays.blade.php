@if (count($holidays) > 0)
    @foreach ($holidays as $holiday)
        <li class="list-group-item list-group-item-warning">
            <b>{{ $holiday->holiday_name }}</b>
            ({{ date($generalSettings['business__date_format'], strtotime($holiday->start_date)) }} <b>@lang('menu.to')</b>  
            {{ date($generalSettings['business__date_format'], strtotime($holiday->end_date)) }})
        </li>
    @endforeach
@else
    <li class="list-group-item list-group-item-warning">@lang('menu.no_data_found').</li>
@endif