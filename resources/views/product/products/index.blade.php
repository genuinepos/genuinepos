@extends('layout.master')
@push('stylesheets')
    <style>
        .element-body {
            padding: 1px 7px 6px 6px;
        }
    </style>
@endpush
@section('title', 'Product List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h6>{{ __('Products') }}</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-lg-1 p-1">
                        <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                            <div class="element-body">
                                <form action="" method="get">
                                    <div class="form-group row">
                                        {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                        @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                            <div class="col-md-2">
                                                <label><strong>{{ __('Store Acccess') }}</strong></label>
                                                <select name="branch_id" class="form-control submit_able select2" id="branch_id" autofocus>
                                                    <option value="">{{ __('All') }}</option>
                                                    {{-- <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __("Company") }})</option> --}}
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Type') }}</b></label>
                                            <select name="product_type" id="product_type" class="form-control submit_able select2" autofocus>
                                                <option value="">{{ __('All') }}</option>
                                                <option value="1">{{ __('Single') }}</option>
                                                <option value="2">{{ __('Variant') }}</option>
                                                <option value="3">{{ __('Combo') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Category') }}</b></label>
                                            <select id="category_id" name="category_id" class="form-control submit_able select2">
                                                <option value="">{{ __('All') }}</option>
                                                @foreach ($categories as $cate)
                                                    <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Unit') }}</b></label>
                                            <select id="unit_id" name="unit_id" class="form-control submit_able select2">
                                                <option value="">{{ __('All') }}</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name . ' (' . $unit->code_name . ')' }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Tax') }}</b></label>
                                            <select id="tax_ac_id" name="tax_ac_id" class="form-control submit_able select2">
                                                <option value="">{{ __('All') }}</option>
                                                @foreach ($taxAccounts as $tax)
                                                    <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Status') }}</b></label>
                                            <select name="status" id="status" class="form-control submit_able select2">
                                                <option value="">{{ __('All') }}</option>
                                                <option value="1">{{ __('Active') }}</option>
                                                <option value="0">{{ __('In-Active') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Brand.') }}</b></label>
                                            <select id="brand_id" name="brand_id" class="form-control submit_able select2">
                                                <option value="">{{ __('All') }}</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        {{-- <div class="col-md-3">
                                        <p class="mt-4"> <input type="checkbox" name="is_for_sale" class="submit_able me-1" id="is_for_sale" value="1"><b>Not For Selling.</b></p>
                                        </div>  --}}
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-4">
                                    <h6>{{ __('List of Products') }}</h6>
                                </div>

                                @if (auth()->user()->can('product_add'))

                                    <div class="col-md-8 d-flex flex-wrap justify-content-end gap-2">
                                        <a href="{{ route('products.create') }}" class="btn btn-sm btn-success" id="add_btn"><i class="fas fa-plus-square"></i> {{ __('Add Product') }}</a>

                                        @if (auth()->user()->can('product_delete'))
                                            <a href="" class="btn btn-sm btn-danger multipla_delete_btn">{{ __('Delete Selected All') }}</a>
                                        @endif
                                    </div>
                                @endif

                            </div>

                            <div class="widget_content">
                                <!--begin: Datatable-->
                                <form id="multiple_action_form" action="#" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="action" id="action">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner"></i> {{ __('Processing') }}...</h6>
                                    </div>
                                    <div class="table-responsive" id="data_list">
                                        <table class="display table-hover data_tbl data__table">
                                            <thead>
                                                <tr class="bg-navey-blue">
                                                    <th data-bSortable="false">
                                                        <input class="all" type="checkbox" name="all_checked" />
                                                    </th>
                                                    <th>{{ __('Image') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                    <th>{{ __('Product') }}</th>
                                                    <th>{{ location_label() }} {{ __('Access') }}</th>
                                                    <th>{{ __('Unit Cost(Inc.Tax)') }}</th>
                                                    <th>{{ __('Unit Price(Exc. Tax)') }}</th>
                                                    <th>{{ __('Curr. Stock') }}</th>
                                                    <th>{{ __('Type') }}</th>
                                                    <th>{{ __('Category') }}</th>
                                                    <th>{{ __('Brand.') }}</th>
                                                    <th>{{ __('Default Tax') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </form>

                                <form id="deleted_form" action="" method="post">
                                    @method('DELETE')
                                    @csrf
                                </form>
                                <!--end: Datatable-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="details"></div>

    <!-- Opening stock Modal -->
    <div class="modal fade" id="addOrEditOpeningStock" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    <!-- Opening stock Modal-->
@endsection
@push('scripts')
    @include('product.products.js_partials.index_js')
@endpush
