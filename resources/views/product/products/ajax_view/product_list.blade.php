<table id="kt_datatable" class="table table-bordered table-striped">
    <thead>
        <tr class="text-left bg-navey-blue">
            @if (auth()->user()->can('product_delete'))
                <th data-bSortable="false">
                    <input class="all" type="checkbox" name="all_checked"/>
                </th>
            @endif
            <th class="text-white">@lang('menu.image')</th>
            <th class="text-white">@lang('menu.action')</th>
            <th class="text-white">@lang('menu.product')</th>
            <th class="text-white">@lang('menu.purchase_cost')</th>
            <th class="text-white">{{ __('Selling Price') }}</th>
            <th class="text-white">@lang('menu.current_stock')</th>
            <th class="text-white">{{ __('Product Type') }}</th>
            <th class="text-white">@lang('menu.category')</th>
            <th class="text-white">@lang('menu.brand')</th>
            <th class="text-white">@lang('menu.tax')</th>
            <th class="text-white">@lang('menu.expire_date')</th>
            <th class="text-white">@lang('menu.status')</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr data-info="{{ $product }}" class="clickable_row text-left">
                @if (auth()->user()->can('product_delete'))
                    <td>
                        <input id="{{ $loop->index }}" class="data_id" type="checkbox" name="data_ids[]" value="{{ $product->id }}"/>
                    </td>
                @endif

                <td><img loading="lazy" class="rounded" width="50" height="50" src="{{ asset('uploads/product/thumbnail/'.$product->thumbnail_photo) }}" alt=""></td>

                <td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('menu.action')
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" id="check_pur_and_gan_bar_button" href="{{ route('products.check.purchase.and.generate.barcode', $product->id) }}"><i class="fas fa-barcode mr-1 text-primary"></i>@lang('menu.barcode')</a>
                            <a class="dropdown-item details_button" href="#"><i class="far fa-eye mr-1 text-primary"></i>View</a>
                            @if (auth()->user()->can('product_edit'))
                                <a class="dropdown-item" href="{{ route('products.edit', $product->id) }}"><i class="far fa-edit mr-1 text-primary"></i>Edit</a>
                            @endif

                            @if (auth()->user()->can('product_delete'))
                                <a class="dropdown-item" id="delete" href="{{ route('products.delete', $product->id) }}"><i class="far fa-trash-alt mr-1 text-primary"></i>@lang('menu.delete')</a>
                            @endif

                            @if ($product->status == 1)
                                <a class="dropdown-item" id="change_status" href="{{ route('products.change.status', $product->id) }}"><i class="far fa-thumbs-up mr-1 text-success"></i>@lang('menu.change_status')</a>
                            @else
                                <a class="dropdown-item" id="change_status" href="{{ route('products.change.status', $product->id) }}"><i class="far fa-thumbs-down mr-1 text-danger"></i>@lang('menu.change_status')</a>
                            @endif

                            @if (auth()->user()->can('openingStock_add'))
                                <a class="dropdown-item" id="opening_stock" href="{{ route('products.opening.stock', $product->id) }}"><i class="fas fa-database mr-1 text-primary"></i>{{ __('Add or edit opening stock') }}</a>
                            @endif
                        </div>
                    </div>
                </td>

                <td>{{ $product->name }}</td>

                <td>
                   <b>{{ json_decode($generalSettings->business, true)['currency'] .' '. $product->product_cost_with_tax }}</b>
                </td>

                <td>
                    <b>{{ json_decode($generalSettings->business, true)['currency'] .' '. $product->product_price}} </b>
                </td>

                <td>
                    <b> {!! $product->quantity <= $product->alert_quantity ? '<span class="text-danger">'. $product->quantity .'</span>' : '<span class="text-success">'. $product->quantity .'</span>' !!} </b>
                </td>

                <td>
                    @if ($product->type == 1 && $product->is_variant == 1)
                        <span class="text-primary">@lang('menu.variant')</span>
                    @elseif($product->type == 1 && $product->is_variant == 0)
                        <span class="text-success">@lang('menu.single')</span>
                    @elseif($product->type == 2)
                        <span class="text-info">@lang('menu.combo')</span>
                    @elseif($product->type == 3)
                        <span class="text-info">{{ __('Digital') }}</span>
                    @endif
                </td>

                <td>
                    {{ $product->category ? $product->category->name : 'N/A' }} {!! $product->child_category ? '<br>--'.$product->child_category->name : '' !!}
                </td>

                <td>
                    {{ $product->brand ? $product->brand->name : 'N/A' }}
                </td>

                <td>
                    {{ $product->tax ? $product->tax->tax_name : 'NoTax' }}
                </td>
                <td>{{ $product->expire_date ? date('d/m/Y', strtotime($product->expire_date)) : 'N/A' }}</td>
                <td>
                    @if ($product->status == 1)
                        <i class="far fa-thumbs-up mr-1 text-success"></i>
                    @else
                        <i class="far fa-thumbs-down mr-1 text-danger"></i>
                    @endif
                </td>

            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="12">
                @if (auth()->user()->can('product_delete'))
                    <a href="" class="btn btn-sm btn-danger multipla_delete_btn">@lang('menu.delete_selected')</a>
                @endif
                <a href="" class="btn btn-sm btn-primary">@lang('menu.remove_form_branch')</a>
                <a href="" class="btn btn-sm btn-warning multipla_deactive_btn">@lang('menu.deactivate_selected')</a>
            </th>
        </tr>
    </tfoot>
</table>

<!--Data table js active link-->
<script src="{{ asset('assets/plugins/custom/data-table/datatable.active.js') }}"></script>
<!--Data table js active link end-->
