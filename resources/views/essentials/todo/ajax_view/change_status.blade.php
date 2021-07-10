 <!--begin::Form-->
 <form id="changes_status_form" action="{{ route('todo.status', $todo->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label><strong>Status : </strong></label>
        <select required name="status" class="form-control">
            <option value="">Select Status</option>
            <option {{ $todo->status == 'New' ? 'SELECTED' : ''  }} value="New">New</option>
            <option {{ $todo->status == 'In-Progress' ? 'SELECTED' : ''  }} value="In-Progress">In-Progress</option>
            <option {{ $todo->status == 'On-Hold' ? 'SELECTED' : ''  }} value="On-Hold">On-Hold</option>
            <option {{ $todo->status == 'Complated' ? 'SELECTED' : ''  }} value="Complated">Complated</option>
        </select>
    </div>
    
    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button2 d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn me-0 btn_blue float-end">Save</button>
            <button type="reset" data-bs-dismiss="modal"
                class="c-btn btn_orange float-end">Close</button>
        </div>
    </div>
</form>