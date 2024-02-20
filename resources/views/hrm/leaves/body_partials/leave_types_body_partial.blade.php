<div class="row g-lg-3 g-1 leave_types tab_contant">
    <div class="col-lg-12">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>{{ __("List Of Leave Types") }}</h6>
                </div>
            </div>

            <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                </div>
                <div class="table-responsive" id="data-list">
                    <table id="leave_types_table" class="display data_tbl data__table w-100">
                        <thead>
                            <tr>
                                <th>{{ __("Serial") }}</th>
                                <th>{{ __("Type") }}</th>
                                <th>{{ __('Max leave') }}</th>
                                <th>{{ __('Leave Count Interval') }}</th>
                                <th>{{ __("Action") }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="leaveTypeAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
