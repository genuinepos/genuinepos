 <!--begin::Form-->
 <form id="changes_status_form" action="{{ route('todo.status', $todo->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label><strong>@lang('menu.status') : </strong></label>
        <select required name="status" class="form-control">
            <option value="">@lang('menu.select_status')</option>
            <option {{ $todo->status == 'New' ? 'SELECTED' : ''  }} value="New">@lang('menu.new')</option>
            <option {{ $todo->status == 'In-Progress' ? 'SELECTED' : ''  }} value="In-Progress">@lang('menu.in_progress')</option>
            <option {{ $todo->status == 'On-Hold' ? 'SELECTED' : ''  }} value="On-Hold">@lang('menu.on_hold')</option>
            <option {{ $todo->status == 'Complated' ? 'SELECTED' : ''  }} value="Complated">@lang('menu.completed')</option>
        </select>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button2 d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
            </div>
        </div>
    </div>
</form>
