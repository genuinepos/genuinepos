<div class="modal-dialog five-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Attendances') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_attendance_form" action="{{ route('hrm.attendances.store') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <label><b>{{ __('Department') }}</b></label>
                        <select onchange="getUsers(this); return false;" class="form-control" id="department_id">
                            <option value="all"> {{ __('All') }} </option>
                            @foreach ($departments as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label><b>{{ __('Employee') }} </b></label>
                        <select onchange="getAttendanceRow(this); return false;" class="form-control" id="user_id">
                            <option disabled selected> {{ __('Select Employee') }} </option>
                            @foreach ($users as $user)
                                @php
                                    $empId = $user->emp_id ? '(' . $user->emp_id . ')' : '';
                                @endphp
                                <option value="{{ $user->id }}">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . $empId }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="attendance_table mt-2">
                    <div class="data_preloader d-hide" id="attendance_row_loader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>
                    <table class="table display modal-table table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Clock In Date') }}</th>
                                <th>{{ __('Clock In Time') }}</th>
                                <th>{{ __('Clock Out Date') }}</th>
                                <th>{{ __('Clock Out Time') }}</th>
                                <th>{{ __('Shift') }}</th>
                                <th>{{ __('Clock In Note') }}</th>
                                <th>{{ __('Clock Out Note') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="table_data"></tbody>
                    </table>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-sm btn-success attendance_submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('hrm.attendances.ajax_view.js_partials.create_js')
