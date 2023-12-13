<div class="row g-lg-3 g-1 payment_methods tab_contant">
    <div class="col-md-12">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>{{ __("List Of Payment Methods") }}</h6>
                </div>
            </div>

            <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                </div>
                <div class="table-responsive" id="data-list">
                    <table class="display data_tbl data__table">
                        <thead>
                            <tr>
                                <th>{{ __("Serial") }}</th>
                                <th>{{ __('Payment Method Name') }}</th>
                                <th>{{ __("Action") }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <form id="deleted_form" action="" method="post">
                @method('DELETE')
                @csrf
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentMethodAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
