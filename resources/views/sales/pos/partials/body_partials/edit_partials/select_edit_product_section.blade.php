<div class="col-lg-5">
    <div class="row g-1">
        <div class="category-sec col-lg-4 col-md-3">
            <div class="left-cat-pos">
                <div class="all-cat">
                    <a href="#" data-id="" class="cat-button active" tabindex="-1">{{ __("All") }}</a>
                    @foreach ($categories as $cate)
                        <a href="#" data-id="{{ $cate->id }}" class="cat-button" tabindex="-1">{{ Str::limit($cate->name, 30, '') }}</a>
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
                                    <select id="pos_category_id" class="form-control cat-bg-1" tabindex="-1">
                                        <option value="">{{ __("All Categories") }}</option>
                                        @foreach ($categories as $cate)
                                            <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <select id="pos_brand_id" class=" form-control cat-bg-2 bg" tabindex="-1">
                                        <option value="">{{ __("All Brands") }}</option>
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
                            <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
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
