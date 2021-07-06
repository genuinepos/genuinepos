<form id="edit_work_space_form" action="{{ route('workspace.update', $ws->id) }}" method="post">
    @csrf
    <div class="form-group row">
        <div class="col-md-6">
            <label><b>Name :</b></label>
            <input required type="text" name="name" class="form-control" placeholder="Workspace Name" value="{{ $ws->name }}">
        </div>

        <div class="col-md-6">
            <label><b>Assigned To :</b></label>
            <select required name="user_ids[]" class="form-control select2" id="user_ids" multiple="multiple">
                <option disabled value=""> Select Please </option>
                @foreach ($users as $user)
                    <option 
                        @foreach ($ws->ws_users as $ws_user)
                            {{ $ws_user->user_id == $user->id ? "SELECTED" : '' }}
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
            <select required name="priority" class="form-control" id="priority">
                <option value="">Select Priority</option>
                <option {{ $ws->priority == 'Low' ? 'SELECTED' : '' }} value="Low">Low</option>
                <option {{ $ws->priority == 'Medium' ? 'SELECTED' : '' }} value="Medium">Medium</option>
                <option {{ $ws->priority == 'High' ? 'SELECTED' : '' }} value="High">High</option>
                <option {{ $ws->priority == 'Urgent' ? 'SELECTED' : '' }} value="Urgent">Urgent</option>
            </select>
        </div>

        <div class="col-md-6">
            <label><strong>Status : </strong></label>
            <select required name="status" class="form-control" id="status">
                <option value="">Select Status</option>
                <option {{ $ws->status == 'New' ? 'SELECTED' : '' }} value="New">New</option>
                <option {{ $ws->status == 'In-Progress' ? 'SELECTED' : '' }} value="In-Progress">In-Progress</option>
                <option {{ $ws->status == 'On-Hold' ? 'SELECTED' : '' }} value="On-Hold">On-Hold</option>
                <option {{ $ws->status == 'Complated' ? 'SELECTED' : '' }} value="Complated">Complated</option>
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>Start Date : </b></label>
            <input required type="date" name="start_date" class="form-control" id="start_date" value="{{ date('Y-m-d', strtotime($ws->start_date)) }}">
        </div>

        <div class="col-md-6">
            <label><b>End Date : </b></label>
            <input required type="date" name="end_date" class="form-control" id="end_date" value="{{ date('Y-m-d', strtotime($ws->end_date)) }}">
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>Description : </b></label>
            <textarea name="description" class="form-control" id="description" cols="10" rows="3" placeholder="Workspace Description.">{{ $ws->description }}</textarea>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>Documents : </b></label>
            <input type="file" name="documents[]" class="form-control" multiple id="documents" placeholder="Workspace Description.">
        </div>

        <div class="col-md-6">
            <label><b>Estimated Hours : </b></label>
            <input type="text" name="estimated_hours" class="form-control" id="estimated_hours" placeholder="Estimated Hours" value="{{ $ws->estimated_hours }}">
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn me-0 btn_blue float-end">Save Changes</button>
            <button type="reset" data-bs-dismiss="modal"
                class="c-btn btn_orange float-end">Close</button>
        </div>
    </div>
</form>
<script>
     $('.select2').select2();
</script>