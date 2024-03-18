<div class="row g-lg-3 g-1 categories tab_contant">
    <div class="col-lg-12">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>{{ __("List of Categories") }}</h6>
                </div>
            </div>

            <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                </div>
                <div class="table-responsive" id="data-list">
                    <table class="display data_tbl data__table">
                        <thead>
                            <tr class="bg-navey-blue">
                                <th class="text-black">{{ __("Category ID") }}</th>
                                <th class="text-black">{{ __("Photo") }}</th>
                                <th class="text-black">{{ __("Name") }}</th>
                                <th class="text-black">{{ __("Description") }}</th>
                                <th class="text-black">{{ __("Action") }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="categoryAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
