<div class="tab_contant brands d-hide" id="tab_contant">
    <div class="section-header">
        <div class="col-md-6">
            <h6>{{ __('List of Brand') }}</h6>
        </div>

        <div class="col-6 d-flex justify-content-end">
            @if (auth()->user()->can('product_brand_add'))
                <a href="{{ route('brands.create') }}" class="btn btn-sm btn-primary" id="addBrand"><i class="fas fa-plus-square"></i> {{ __('Add Brand') }}</a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table_area">
                <div class="table-responsive">
                    <table id="brands-table" class="display data_tbl data__table common-reloader w-100">
                        <thead>
                            <tr>
                                <th>{{ __('Brand ID') }}</th>
                                <th>{{ __('Photo') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if (auth()->user()->can('product_brand_delete'))
    <form id="delete_brand_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endif
