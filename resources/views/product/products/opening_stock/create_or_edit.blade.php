<style>
    .modal-table td { font-size: 11px; padding: 2px; }
    .modal-table input { height: 22px; }
    .modal-body { padding: 0.5rem; }
    
    .product_stock_table_area table thead th { background: white!important; }

    .product_stock_table_area table tbody td { background: white!important;}
</style>
<div class="modal-dialog four-col-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">
                {{ __('Add Or Edit Opening Stock') }} -
                <strong>{{ $product->name.' ('.$product->product_code.')' }}</strong>
            </h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="opening_stock_view">
            <form id="add_or_edit_opening_stock_form" action="{{ route('product.opening.stocks.store.or.update') }}"
                method="POST">
                @csrf

                @include('product.products.opening_stock.body_partials.branch_opening_stock')
                @include('product.products.opening_stock.body_partials.warehouse_opening_stock')

                <div class="d-flex justify-content-end mt-3">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button opening_stock_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        <button type="submit" class="btn btn-sm btn-success">{{ __("Save") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('product.products.opening_stock.js_partials.add_or_edit_js')
