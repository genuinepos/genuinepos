<style>
    .set-height{
        position: relative;
    }
</style>
<div class="set-height">
    <div class="data_preloader submit_preloader">
        <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table data__table modal-table table-sm sale-product-table">
            <thead>
                <tr>
                    <th scope="col">{{ __("S/L") }}</th>
                    <th scope="col">{{ __("Product") }}</th>
                    <th scope="col">{{ __("Qty/Weight") }}</th>
                    <th scope="col">{{ __("Unit") }}</th>
                    <th scope="col">{{ __("Price Inc. Tax") }}</th>
                    <th scope="col">{{ __("Subtotal") }}</th>
                    <th scope="col" class="text-start"><i class="fas fa-trash-alt"></i></th>
                </tr>
            </thead>

            <tbody id="product_list">

            </tbody>
        </table>
    </div>
</div>
