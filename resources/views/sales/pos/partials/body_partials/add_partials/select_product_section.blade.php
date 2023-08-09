<div class="col-lg-5">
    <div class="row g-1">
        <div class="category-sec col-lg-4 col-md-3">
            <div class="left-cat-pos">
                <div class="all-cat">
                    <a href="#" data-id="" class="cat-button active" tabindex="-1">@lang('menu.all')</a>
                    @foreach ($categories as $cate)
                        <a href="#" data-id="{{ $cate->id }}" class="cat-button" tabindex="-1">{{ $cate->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-9">
            <div class="show-product">
                <div class="product-inner">
                    <div class="category-head">
                        <div class="cat-ban-sec">
                            <div class="row g-1">
                                <div class="col-6">
                                    <select name="category_id" id="category_id" class="form-control cat-bg-1 common_submitable" tabindex="-1">
                                        <option value="">@lang('menu.all_categories')</option>
                                        @foreach ($categories as $cate)
                                            <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <select id="brand_id" id="brand_id" class=" form-control cat-bg-2 bg common_submitable" tabindex="-1">
                                        <option value="">@lang('menu.all_brands')</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-area">
                        <div class="data_preloader select_product_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="product-ctn">
                            <div class="row g-2" id="select_product_list">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
