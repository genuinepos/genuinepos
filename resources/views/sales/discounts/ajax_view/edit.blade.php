<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Add Discount") }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_discount_form" action="{{ route('sales.discounts.update', $discount->id) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <label><strong>{{ __("Name") }}</strong> <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="discount_name" value="{{ $discount->name }}" data-next="discount_priority" placeholder="{{ __("Discount Name") }}" />
                        <span class="error error_discount_name"></span>
                    </div>

                    <div class="col-md-6">
                        <label><strong>{{ __("Priority") }} <i data-bs-toggle="tooltip" data-bs-placement="right" title="Leave empty to auto generate." class="fas fa-info-circle tp"></i> </strong> <span class="text-danger">*</span> </label>
                        <input required type="number" name="priority" class="form-control" id="discount_priority" value="{{ $discount->priority }}" data-next="discount_start_at" placeholder="{{ __("Priority") }}" />
                        <span class="error error_discount_priority"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label><strong>{{ __("Start At") }}</strong> <span class="text-danger">*</span></label>
                        <input required type="text" name="start_at" class="form-control" id="discount_start_at" value="{{ date($generalSettings['business__date_format'], strtotime($discount->start_at)) }}" data-next="discount_end_at" placeholder="{{ __("Ex: YYYY-MM-DD/DD-MM-YYYY") }}" autocomplete="off">
                        <span class="error error_discount_start_at"></span>
                    </div>

                    <div class="col-md-6">
                        <label><strong>{{ __("End At") }}</strong> <span class="text-danger">*</span></label>
                        <input required type="text" name="end_at" class="form-control" id="discount_end_at" value="{{ date($generalSettings['business__date_format'], strtotime($discount->end_at)) }}" data-next="discount_product_ids" placeholder="{{ __("Ex: YYYY-MM-DD/DD-MM-YYYY") }}" autocomplete="off">
                        <span class="error error_discount_end_at"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <label><strong>{{ __("Applicable Products") }} </strong> </label>
                        <select name="product_ids[]" class="form-control select2" multiple="multiple" id="discount_product_ids">
                            @foreach ($products as $product)
                                <option
                                    @foreach ($discount->discountProducts as $discountProduct)
                                        {{ $product->id == $discountProduct->product_id ? 'SELECTED' : '' }}
                                    @endforeach
                                    value="{{ $product->id }}">{{ $product->name . ' (' . $product->product_code . ')' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-1 {{ count($discount->discountProducts) > 0 ? 'd-hide' : '' }}" id="brand_category_area">
                    <div class="col-md-6">
                        <label><strong>{{ __("Brand.") }}</strong> <span class="text-danger">*</span></label>
                        <select required name="brand_id" class="form-control" id="discount_brand_id" data-next="discount_category_id">
                            <option value="">{{ __("Select Brand") }}</option>
                            @foreach ($brands as $brand)
                                <option {{ $discount->brand_id == $brand->id ? 'SELECTED' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label><strong>{{ __("Category") }} </strong> <span class="text-danger">*</span></label>
                        <select required name="category_id" class="form-control" id="discount_category_id" data-next="discount_discount_type">
                            <option value="">{{ __("Select Category") }}</option>
                            @foreach ($categories as $category)
                                <option {{ $discount->category_id == $category->id ? 'SELECTED' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label><strong>{{ __("Discount Type") }}</strong></label>
                        <select name="discount_type" id="discount_discount_type" class="form-control" data-next="discount_discount_amount">
                            @foreach (\App\Enums\DiscountType::cases() as $discountType)
                                <option {{ $discount->discount_type == $discountType->value ? 'SELECTED' : '' }} value="{{ $discountType->value }}">{{ $discountType->name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_discount_discount_type"></span>
                    </div>

                    <div class="col-md-6">
                        <label><strong>{{ __("Discount Amount") }}</strong> <span class="text-danger">*</span></label>
                        <input required type="number" step="any" name="discount_amount" class="form-control fw-bold" id="discount_discount_amount" value="{{ $discount->discount_amount }}" data-next="discount_price_group_id" placeholder="0.00">
                        <span class="error error_discount_discount_amount"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label><strong>{{ __("Selling Price Group") }}</strong> </label>
                        <select name="price_group_id" class="form-control" id="discount_price_group_id" data-next="discount_apply_in_customer_group">
                            <option value="">{{ __("Default Price") }}</option>
                            @foreach ($priceGroups as $priceGroup)
                                <option {{ $discount->price_group_id == $priceGroup->id ? 'SELECTED' : '' }} value="{{ $priceGroup->id }}">{{ $priceGroup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-md-6">
                        <label><strong>{{ __("Apply Customer Group") }}</strong> </label>
                        <select name="apply_in_customer_group" class="form-control" id="discount_apply_in_customer_group" data-next="discount_is_active">
                            <option value="0">{{ __("No") }}</option>
                            <option {{ $discount->apply_in_customer_group == 1 ? 'SELECTED' : '' }} value="1">{{ __("Yes") }}</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label><strong>{{ __("Apply Customer Group") }}</strong></label>
                        <select name="is_active" class="form-control" id="discount_is_active" data-next="discount_save_changes">
                            <option value="1">{{ __("Yes") }}</option>
                            <option {{ $discount->is_active == 0 ? 'SELECTED' : '' }} value="0">{{ __("No") }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button discount_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="button" id="discount_save_changes" class="btn btn-sm btn-success discount_submit_button">{{ __("Save Changes") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2({
        placeholder: "Select a products",
        allowClear: true
    });

    $('#discount_product_ids').on('change', function () {

        checkDiscountProducts();
    });

    function checkDiscountProducts() {

        if ($('#discount_product_ids').val().length > 0) {

            $('#brand_category_area').hide();
            $('#discount_category_id').prop('required', false);
            $('#discount_brand_id').prop('required', false);
        } else {

            $('#brand_category_area').show();
            $('#discount_category_id').prop('required', true);
            $('#discount_brand_id').prop('required', true);
        }
    }
    checkDiscountProducts();

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.discount_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('click change keypress', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.discount_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_discount_form').on('submit', function(e) {
        e.preventDefault();

        $('.discount_loading_btn').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            processData: false,
            cache: false,
            contentType: false,
            success: function(data) {
                $('.discount_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                }

                $('#addOrEditModal').modal('hide');
                table.ajax.reload();
                toastr.success(data);
            },
            error: function(err) {

                $('.discount_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_discount_' + key + '').html(error[0]);
                });
            }
        });
    });

    var dateFormat = "{{ $generalSettings['business__date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('discount_start_at'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('discount_end_at'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
</script>
