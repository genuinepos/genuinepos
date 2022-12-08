<div class="row">
    <div class="col-md-4">
        <p><b>@lang('menu.todo_id') : </b> {{ $todo->todo_id }}</p>
        <p><b>@lang('menu.entry_date') : </b>{{ date('d/m/Y', strtotime($todo->created_at)) }}</p>
        <p><b>@lang('menu.task') : </b> {{ $todo->task }}</p>

    </div>

    <div class="col-md-4">
        <p><b>@lang('menu.due_date') : </b>{{ date('d/m/Y', strtotime($todo->due_date)) }}</p>
        <p><b>@lang('menu.status') : </b> {{ $todo->status }}</p>
        <p><b>@lang('menu.priority') : </b> {{ $todo->priority }}</p>
    </div>

    <div class="col-md-4">
        <p><b>@lang('menu.assigned_by') : </b> {{ $todo->admin ? $todo->admin->prefix.' '.$todo->admin->name.' '.$todo->admin->last_name : 'N/A'}}</p>
        <p><b>@lang('menu.assigned_to') : </b>
            @foreach ($todo->todo_users as $todo_user)
                {{ $todo_user->user->prefix.' '.$todo_user->user->name.' '.$todo_user->user->last_name }},
            @endforeach
        </p>
    </div>
    <hr class="mt-1">
</div>

<div class="row">
    <p><b>@lang('menu.description') :</b> </p>
    <p>{{ $todo->description }}</p>
</div>
