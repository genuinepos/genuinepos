<form id="edit_todo_form" action="{{ route('todo.update', $todo->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <div class="col-md-12">
            <label><b>Task :</b></label>
            <input required type="text" name="task" class="form-control" placeholder="Task" value="{{ $todo->task }}">
        </div>
    </div>

    <div class="form-group mt-1">
        <div class="col-md-12">
            <label><b>Assigned To :</b></label>
            <select required name="user_ids[]" class="form-control select2" multiple="multiple">
                <option disabled value=""> Select Please </option>
                @foreach ($users as $user)
                <option
                    @foreach ($todo->todo_users as $todo_user)
                        {{ $todo_user->user_id == $user->id ? "SELECTED" : '' }}
                    @endforeach
                 value="{{ $user->id }}">{{ $user->prefix.' '.$user->name.' '.$user->last_name }}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>Priority : </b></label>
            <select required name="priority" class="form-control">
                <option value="">Select Priority</option>
                <option {{ $todo->priority == 'Low' ? 'SELECTED' : ''  }} value="Low">Low</option>
                <option {{ $todo->priority == 'Medium' ? 'SELECTED' : ''  }} value="Medium">Medium</option>
                <option {{ $todo->priority == 'High' ? 'SELECTED' : ''  }} value="High">High</option>
                <option {{ $todo->priority == 'Urgent' ? 'SELECTED' : ''  }} value="Urgent">Urgent</option>
            </select>
        </div>

        <div class="col-md-6">
            <label><strong>Status : </strong></label>
            <select required name="status" class="form-control">
                <option value="">Select Status</option>
                <option {{ $todo->status == 'New' ? 'SELECTED' : ''  }} value="New">New</option>
                <option {{ $todo->status == 'In-Progress' ? 'SELECTED' : ''  }} value="In-Progress">In-Progress</option>
                <option {{ $todo->status == 'On-Hold' ? 'SELECTED' : ''  }} value="On-Hold">On-Hold</option>
                <option {{ $todo->status == 'Complated' ? 'SELECTED' : ''  }} value="Complated">Complated</option>
            </select>
        </div>
    </div>


    <div class="form-group mt-1">
        <div class="col-md-12">
            <label><b>Due Date : </b></label>
            <input required type="text" name="due_date" class="form-control datepicker" id="due_date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($todo->due_date)) }}">
        </div>
    </div>

    <div class="form-group mt-1">
        <div class="col-md-12">
            <label><b>Description : </b></label>
            <textarea name="description" class="form-control" id="description" cols="10" rows="3" placeholder="Workspace Description.">{{ $todo->description }}</textarea>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner"></i><span> Loading...</span></button>
                <button type="button" class="btn btn-sm btn-danger" id="close_form">Close</button>
                <button type="submit" class="btn btn-sm btn-success">Save Changes</button>
            </div>
        </div>
    </div>
</form>
<script>
    $('.select2').select2();
    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_due_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
</script>
