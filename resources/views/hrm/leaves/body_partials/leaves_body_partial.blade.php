<div class="row g-lg-3 g-1 leaves tab_contant">
    <div class="col-lg-12">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>{{ __("List Of Leaves") }}</h6>
                </div>
            </div>

            <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                </div>
                <div class="table-responsive" id="data-list">
                    <table id="leaves_table" class="display data_tbl data__table w-100">
                        <thead>
                            <tr>
                                <th class="text-start">{{ __("Leave No") }}</th>
                                <th class="text-start">{{ __("Shop/Business") }}</th>
                                <th class="text-start">{{ __("Type") }}</th>
                                <th class="text-start">{{ __("Employee") }}</th>
                                <th class="text-start">{{ __("Start Date") }}</th>
                                <th class="text-start">{{ __("End Date") }}</th>
                                <th class="text-start">{{ __("Reason") }}</th>
                                <th class="text-start">{{ __("Status") }}</th>
                                <th class="text-start">{{ __("Action") }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="leaveAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
