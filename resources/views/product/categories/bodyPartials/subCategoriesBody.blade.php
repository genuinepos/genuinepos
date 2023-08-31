<div class="row g-lg-3 g-1 sub-categories tab_contant">
    <div class="col-lg-12">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>{{ __("List Of Subcategories") }}</h6>
                </div>
            </div>

         <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                </div>
                <div class="table-responsive">
                    <table class="display data_tbl2 data__table w-100">
                        <thead>
                            <tr>
                                <th>{{ __("Serial") }}</th>
                                <th>{{ __("Photo") }}</th>
                                <th>{{ __("Subcategory Name") }}</th>
                                <th>{{ __("Parent Category") }}</th>
                                <th>{{ __("Description") }}</th>
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

<div class="modal fade" id="subcategoryAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
