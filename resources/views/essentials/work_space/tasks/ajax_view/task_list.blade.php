@if (count($ws_tasks) > 0)
    @foreach ($ws_tasks as $task)
    <tr class="bg-white">
        <td class=" text-start">
            <div class="task_area" data-id="{{ $task->id }}">
                <b>{{ $loop->index + 1 }}.</b> <span id="task_name" class="text-muted"> {{ $task->task_name }} </span>  
                <a href="{{ route('workspace.task.delete', $task->id) }}" class="text-danger float-end" title="Delete" id="delete"><i class="far fa-trash-alt ms-1"></i></a>
                <a href="#" class="float-end text-muted" title="Edit" id="edit_task_btn"><i class="fas fa-pencil-alt"></i></a>
            </div>

            <div class="input-group">
                <input type="text" name="edit_task_name" class="form-control form-control-sm d-none edit_task_name" id="edit_task_name" value="{{ $task->task_name }}">
                <div class="input-group-prepend add_button update_task_button">
                    <span class="input-group-text edit_task_name custom-modify d-none"><i class="far fa-check-circle text-success"></i></span>
                </div>
            </div>
        </td>
        <td class="text-start">
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-sm {{ $task->u_id ? 'btn-primary' : 'btn-warning' }} rounded" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-user {{ $task->u_id ? 'text-white' : 'text-dark' }}"></i> <b class="{{ $task->u_id ? 'text-white' : 'text-dark' }}">{{ $task->u_id ? $task->u_prefix.' '.$task->u_name.' '.$task->u_last_name : 'Not-Assigned' }}</b>
                </button>

                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item mt-1" href="{{ route('workspace.task.assign.user', $task->id) }}" id="assign_user" data-user_id=""><i class="far fa-user"></i> None</a>
                    @foreach ($ws_users as $ws_user)
                        <a class="dropdown-item mt-1 text-muted" href="{{ route('workspace.task.assign.user', $task->id) }}" id="assign_user" data-user_id="{{ $ws_user->id }}"><i class="far fa-user"></i> {{ $ws_user->prefix.' '.$ws_user->name.' '.$ws_user->last_name }}</a>
                    @endforeach
                
                </div>
            </div>
        </td>
        <td class="text-start">
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-sm {{ $task->status == 'In-Progress' ? 'btn-secondary' : 'btn-info' }}  text-white rounded" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <b>{{ $task->status }}</b>
                </button>

                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item mt-1 text-secondary" href="{{ route('workspace.task.status', $task->id) }}" data-status="In-Progress" id="change_status"><b>In-Progress</b></a>
                    <a class="dropdown-item mt-1" href="{{ route('workspace.task.status', $task->id) }}" data-status="Complated" id="change_status"><b>Complated</b></a>
                </div>
            </div>
        </td>
    </tr>  
    @endforeach
@else 
    <tr>
        <th colspan="3">No-Task-Available</th>
    </tr>
@endif

