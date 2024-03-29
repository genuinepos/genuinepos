@if (count($wsTasks) > 0)
    @foreach ($wsTasks as $task)
        <tr class="bg-white">
            <td class="text-start text-muted task_index">{{ $loop->index + 1 }}.</td>

            <td class="text-start task_details">
                <div class="task_area" data-id="{{ $task->id }}">
                    <span id="task_name" class="text-muted"> {{ $task->task_name }} </span>
                    <a href="{{ route('workspaces.task.delete', $task->id) }}" class="text-danger float-end" title="Delete" id="delete"><i class="far fa-trash-alt ms-1"></i></a>
                    <a href="#" class="text-muted" title="Edit" id="edit_task_btn"><i class="fas fa-pencil-alt"></i></a>
                </div>

                <div class="input-group">
                    {{-- <input type="text" name="edit_task_name" class="form-control form-control-sm d-hide edit_task_name" id="edit_task_name" value="{{ $task->task_name }}"> --}}
                    <textarea  name="edit_task_name" class="form-control form-control-sm d-hide edit_task_name" id="edit_task_name" cols="10" rows="2">{{ $task->task_name }}</textarea>
                    <div class="input-group-prepend add_button update_task_button">
                        <span class="input-group-text edit_task_name custom-modify d-hide"><i class="far fa-check-circle text-success"></i></span>
                    </div>
                </div>
            </td>

            <td class="text-start tast_status">
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-sm {{ $task->u_id ? 'btn-primary' : 'btn-warning' }} rounded" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-user {{ $task->u_id ? 'text-white' : 'text-dark' }}"></i> <b class="{{ $task->u_id ? 'text-white' : 'text-dark' }}">{{ $task->u_id ? $task->u_prefix.' '.$task->u_name.' '.$task->u_last_name : __('Not-Assigned') }}</b>
                    </button>

                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item mt-1" href="{{ route('workspaces.task.assign.user', $task->id) }}" id="assign_user" data-user_id=""><i class="fas fa-user text-primary"></i> {{ __("None") }}</a>
                        @foreach ($wsUsers as $wsUser)
                            <a class="dropdown-item mt-1 text-muted" href="{{ route('workspaces.task.assign.user', $task->id) }}" id="assign_user" data-user_id="{{ $wsUser->id }}"><i class="fas fa-user text-primary"></i> {{ $wsUser->prefix .' '. $wsUser->name .' '. $wsUser->last_name }}</a>
                        @endforeach
                    </div>
                </div>
            </td>

            <td class="text-start">
                <div class="btn-group" role="group">
                    @php
                        $btnClass = '';
                        if($task->priority == 'High'){

                            $btnClass = 'btn-danger';
                        }elseif ($task->priority == 'Low') {

                            $btnClass = 'btn-warning';
                        }elseif ($task->priority == 'Medium') {

                            $btnClass = 'btn-secondary';
                        }else {

                            $btnClass = 'btn-1';
                        }
                    @endphp

                    <button title="Priority" id="btnGroupDrop1" type="button" class="btn btn-sm {{ $btnClass }} rounded" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <b>{{ $task->priority }}</b>
                    </button>

                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item mt-1" href="{{ route('workspaces.task.priority', $task->id) }}" data-priority="High" id="change_priority"><i class="fas fa-circle text-danger"></i> <b>{{ __('High Priority') }}</b></a>
                        <a class="dropdown-item mt-1" href="{{ route('workspaces.task.priority', $task->id) }}" data-priority="Low" id="change_priority"><i class="fas fa-circle text-warning"></i> <b>{{ __('Low Priority') }}</b></a>
                        <a class="dropdown-item mt-1" href="{{ route('workspaces.task.priority', $task->id) }}" data-priority="Medium" id="change_priority"><i class="fas fa-circle text-secondary"></i> <b>{{ __('Medium Priority') }}</b></a>
                        <a class="dropdown-item mt-1" href="{{ route('workspaces.task.priority', $task->id) }}" data-priority="Urgent" id="change_priority"><i class="fas fa-circle text-1"></i> <b>{{ __('Urgent Priority') }}</b></a>
                    </div>
                </div>
            </td>

            <td class="text-start">
                <div class="btn-group" role="group">
                    @php
                        $class = "";
                        if ($task->status == 'In-Progress'){

                            $class = "btn-secondary";
                        } elseif($task->status == 'Pending') {

                            $class = "btn-danger";
                        } else {

                            $class = "btn-info";
                        }
                    @endphp

                    <button id="btnGroupDrop1" type="button" class="btn btn-sm {{ $class }}  text-white rounded" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b>{{ $task->status }}</b></button>

                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item mt-1" href="{{ route('workspaces.task.status', $task->id) }}" data-status="Pending" id="change_status"><i class="fas fa-circle text-danger"></i> <b>{{ __("Pending") }}</b></a>
                        <a class="dropdown-item mt-1" href="{{ route('workspaces.task.status', $task->id) }}" data-status="In-Progress" id="change_status"><i class="fas fa-circle text-secondary"></i> <b>{{ __("In-Progress") }}</b></a>
                        <a class="dropdown-item mt-1" href="{{ route('workspaces.task.status', $task->id) }}" data-status="Complated" id="change_status"><i class="fas fa-circle text-info"></i> <b>{{ __("Completed") }}</b></a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <th colspan="3">{{ __('No-Task-Available') }}</th>
    </tr>
@endif

