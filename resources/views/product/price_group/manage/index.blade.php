@extends('layout.master')
@push('stylesheets')
 <style>
    .input-group-text{font-size: 10px; font-weight: 600;}
 </style>
@endpush
@section('title', 'Manage Price Group - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Manage Selling Price Group') }}</h5>
                </div>

                <div class="col-6">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                </div>
            </div>
        </div>
        <div class="p-1">
            <form id="add_product_price_group_form" action="{{ route('selling.price.groups.manage.store.or.update') }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" id="action_type" value="">
                <div class="form_element rounded mt-0 mb-3">
                    <div class="element-body">
                        <div class="form_part">
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="heading_area">
                                        <p><strong>{{ __('Product') }} : </strong> {{ $product->name . ' (' . $product->product_code . ')' }} </p>
                                        <small class="text-danger">{{ __('Tax (If Exists) will be added to all price group') }}.</small>
                                    </div>
                                    <div class="table-responsive mt-1">
                                        <table class="table modal-table table-sm manage-price-group-table">
                                            <thead>
                                                <tr class="bg-secondary">
                                                    @if ($type == 1)
                                                        <th class="text-white text-start fw-bold" scope="col">{{ __('Variant') }}</th>
                                                    @endif

                                                    <th class="text-white text-center fw-bold" scope="col">

                                                        {{ __('Default Selling Price Inc. Tax') }}
                                                    </th>

                                                    @foreach ($priceGroups as $pg)
                                                        <th class="text-white text-start fw-bold" scope="col">{{ $pg->name }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($product->variants) > 0)
                                                    @php
                                                        $lastIndex = count($product->variants) - 1;
                                                    @endphp
                                                    @foreach ($product->variants as $variant)
                                                        @php
                                                            $variantIndex = $loop->index;
                                                        @endphp
                                                        <tr>
                                                            <td class="text-start">
                                                                <input type="hidden" name="product_ids[]" value="{{ $variant->product_id }}">
                                                                <input type="hidden" name="variant_ids[]" value="{{ $variant->id }}">
                                                                {{ $variant->variant_name }}
                                                            </td>
                                                            <td class="text-center fw-bold">

                                                                {{ \App\Utils\Converter::format_in_bdt($variant->variant_price) }}
                                                            </td>
                                                            @php
                                                                $groupLastIndex = count($priceGroups) - 1;
                                                            @endphp
                                                            @foreach ($priceGroups as $pg)
                                                                <td class="text-start">
                                                                    @php
                                                                        $existsPrice = DB::table('price_group_products')
                                                                            ->where('price_group_id', $pg->id)
                                                                            ->where('product_id', $variant->product_id)
                                                                            ->where('variant_id', $variant->id)
                                                                            ->first(['id','price']);

                                                                        $isGroupLastIndex = $groupLastIndex == $loop->index ? 1 : 0;
                                                                        $isLastIndex = $variantIndex == $lastIndex && $isGroupLastIndex == 1 ? 1 : 0;
                                                                    @endphp

                                                                    @if ($existsPrice)
                                                                        <input type="number" name="group_prices[{{ $pg->id }}][{{ $variant->product_id }}][{{ $variant->id }}]" step="any" class="form-control fw-bold group_price" data-is_last_input="{{ $isLastIndex }}" placeholder="0.00" value="{{ $existsPrice->price }}">
                                                                    @else
                                                                        <input type="number" name="group_prices[{{ $pg->id }}][{{ $variant->product_id }}][{{ $variant->id }}]" step="any" class="form-control fw-bold group_price" data-is_last_input="{{ $isLastIndex }}" placeholder="0.00" value="0.00">
                                                                    @endif

                                                                    @if (count($variant->variantUnits) > 0)
                                                                        @foreach ($variant->variantUnits as $variantUnit)
                                                                            <div class="input-group mt-1">
                                                                                <span class="input-group-text bg-white w-25">{{ $variantUnit?->assignedUnit?->name }}</span>
                                                                                <input type="hidden" name="multiple_unit_assigned_unit_ids[{{ $pg->id }}][{{ $variant->id }}][]" value="{{ $variantUnit->assigned_unit_id }}">
                                                                                @php
                                                                                    $priceGroupProductId = isset($existsPrice) ? $existsPrice->id : null;
                                                                                    $priceGroupUnit = DB::table('price_group_units')->where('price_group_product_id', $priceGroupProductId)->where('assigned_unit_id', $variantUnit->assigned_unit_id)->first();
                                                                                @endphp

                                                                                @if ($priceGroupUnit)
                                                                                    <input type="number" name="multiple_unit_prices_exc_tax[{{ $pg->id }}][{{ $variant->id }}][]" class="form-control" value="{{ $priceGroupUnit->unit_price_exc_tax }}" placeholder="0.00">
                                                                                @else
                                                                                    <input type="number" name="multiple_unit_prices_exc_tax[{{ $pg->id }}][{{ $variant->id }}][]" class="form-control w-75" placeholder="0.00">
                                                                                @endif
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-center fw-bold">
                                                            <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                                            <input type="hidden" name="variant_ids[]" value="noid">
                                                            {{ \App\Utils\Converter::format_in_bdt($product->product_price) }}
                                                        </td>
                                                        @php
                                                            $groupLastIndex = count($priceGroups) - 1;
                                                        @endphp
                                                        @foreach ($priceGroups as $pg)
                                                            <td>
                                                                @php
                                                                    $existsPrice = DB::table('price_group_products')
                                                                        ->where('price_group_id', $pg->id)
                                                                        ->where('product_id', $product->id)
                                                                        ->first(['id','price']);

                                                                    $isLastIndex = $groupLastIndex == $loop->index ? 1 : 0;
                                                                @endphp
                                                                @if ($existsPrice)
                                                                    <input type="number" name="group_prices[{{ $pg->id }}][{{ $product->id }}][noid]" step="any" class="form-control fw-bold group_price" data-is_last_input="{{ $isLastIndex }}" value="{{ $existsPrice->price }}" placeholder="0.00">
                                                                @else
                                                                    <input type="number" name="group_prices[{{ $pg->id }}][{{ $product->id }}][noid]" step="any" class="form-control fw-bold group_price" data-is_last_input="{{ $isLastIndex }}" value="0.00" placeholder="0.00">
                                                                @endif

                                                                @if (count($product->productUnits) > 0)
                                                                    @foreach ($product->productUnits as $productUnit)
                                                                        <div class="input-group mt-1">
                                                                            <span class="input-group-text bg-white w-25">{{ $productUnit?->assignedUnit?->name }}</span>
                                                                            <input type="hidden" name="multiple_unit_assigned_unit_ids[{{ $pg->id }}][]" value="{{ $productUnit->assigned_unit_id }}">
                                                                            @php
                                                                                $priceGroupProductId = isset($existsPrice) ? $existsPrice->id : null;
                                                                                $priceGroupUnit = DB::table('price_group_units')->where('price_group_product_id', $priceGroupProductId)->where('assigned_unit_id', $productUnit->assigned_unit_id)->first();
                                                                            @endphp

                                                                            @if ($priceGroupUnit)
                                                                                <input type="number" name="multiple_unit_prices_exc_tax[{{ $pg->id }}][]" class="form-control" value="{{ $priceGroupUnit->unit_price_exc_tax }}" placeholder="0.00">
                                                                            @else
                                                                                <input type="number" name="multiple_unit_prices_exc_tax[{{ $pg->id }}][]" class="form-control w-75" placeholder="0.00">
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button btn-sm d-hide"><i class="fas fa-spinner"></i><span>{{ __('Loanding') }}</span> </button>
                        <button type="button" id="save_btn" name="action" value="save" class="btn btn-success manage_pg_submit_button btn-sm">{{ __('Save') }}</button>

                        <button type="submit" id="save_and_new_btn" name="action" value="save_and_new" class="btn btn-success submit_button btn-sm">{{ __('Save And Add Another') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.manage_pg_submit_button').prop('type', 'button');
        });

        var isAllowSubmit = true;
        $(document).on('click', '.manage_pg_submit_button', function() {

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

        $('#add_product_price_group_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            isAjaxIn = false;
            isAllowSubmit = false;

            $.ajax({
                beforeSend: function() {
                    isAjaxIn = true;
                },
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.loading_button').hide();
                    if (!$.isEmptyObject(data.saveMsg)) {

                        toastr.success(data.saveMsg);
                        window.location = "{{ route('products.index') }}";
                    } else if (!$.isEmptyObject(data.saveAndAnotherMsg)) {

                        toastr.success(data.saveAndAnotherMsg);
                        window.location = "{{ route('products.create') }}";
                    }
                },
                error: function(err) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });

            if (isAjaxIn == false) {

                isAllowSubmit = true;
            }
        });

        $(document).on('click', '.manage_pg_submit_button', function(e) {
            var value = $(this).val();
            $('#action_type').val(value);
        });

        $(document).on('click keypress', '.group_price', function(e) {

            var is_last_input = $(this).data('is_last_input');

            if (e.keyCode == 13) {

                e.preventDefault();

                if (is_last_input == 1) {

                    $('#save_btn').focus();
                    return;
                }

                var nextI = $("input").index(this) + 1;
                next = $("input").eq(nextI);
                next.focus().select();
            }
        });

        $('.group_price')[0].focus();
        $('.group_price')[0].select();
    </script>
@endpush
