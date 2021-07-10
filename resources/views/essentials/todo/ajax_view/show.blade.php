<div class="row">
    <div class="col-md-4">
        <p><b>Todo ID : </b> {{ $todo->todo_id }}</p>
        <p><b>Entry Date : </b>{{ date('d/m/Y', strtotime($todo->created_at)) }}</p>
        <p><b>Task : </b> {{ $todo->task }}</p>
        
    </div>

    <div class="col-md-4">
        <p><b>Due Date : </b>{{ date('d/m/Y', strtotime($todo->due_date)) }}</p>
        <p><b>Status : </b> {{ $todo->status }}</p>
        <p><b>Priority : </b> {{ $todo->priority }}</p>
    </div>

    <div class="col-md-4">
        <p><b>Assigned By : </b> {{ $todo->admin ? $todo->admin->prefix.' '.$todo->admin->name.' '.$todo->admin->last_name : 'N/A'}}</p>
        <p><b>Assigned To : </b> 
            @foreach ($todo->todo_users as $todo_user)
                {{ $todo_user->user->prefix.' '.$todo_user->user->name.' '.$todo_user->user->last_name }},
            @endforeach
        </p>
    </div>
    <hr class="mt-1">
</div>

<div class="row">
    <p><b>Description :</b> </p>
    <p>{{ $todo->description }}</p>
</div>