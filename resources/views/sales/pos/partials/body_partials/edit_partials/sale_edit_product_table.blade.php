<style>
    .set-height{
        position: relative;
    }
</style>
<div class="set-height">
    <div class="data_preloader submit_preloader">
        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
    </div>
    <div class="table-responsive">
        <table class="table data__table modal-table table-sm sale-product-table">
            <thead>
                <tr>
                    <th scope="col">@lang('menu.sl')</th>
                    <th scope="col">@lang('menu.name')</th>
                    <th scope="col">@lang('menu.qty_weight')</th>
                    <th scope="col">@lang('menu.unit')</th>
                    <th scope="col">@lang('menu.price_inc_tax')</th>
                    <th scope="col">@lang('menu.sub_total')</th>
                    <th scope="col"><i class="fas fa-trash-alt text-danger"></i></th>
                </tr>
            </thead>

            <tbody id="product_list"></tbody>
        </table>
    </div>
</div>
