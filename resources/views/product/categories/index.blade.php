@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@section('title', 'All Categories/SubCategories - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-cubes"></span>
                                <h5>@lang('menu.categories') / @lang('menu.sub_category')</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                                <i class="fas fa-long-arrow-alt-left text-white"></i>@lang('menu.back')
                            </a>
                        </div>
                    </div>

                    <div class="p-lg-3 p-1">
                        <div class="row g-lg-3 g-1">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="tab_list_area">
                                            <div class="btn-group">
                                                <a id="tab_btn" data-show="categories" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                                    <i class="fas fa-th-large"></i> @lang('menu.categories')</a>

                                                <a id="tab_btn" data-show="sub-categories" class="btn btn-sm btn-primary tab_btn" href="#">
                                                    <i class="fas fa-code-branch"></i>  @lang('menu.sub_categories')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                @include('product.categories.bodyPartials.categoriesBody')
                                @include('product.categories.bodyPartials.subCategoriesBody')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <form id="deleted_sub_cate_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection
@push('scripts')
    <script>$('.sub-categories').hide();</script>
    @include('product.categories.jsPartials.categoriesBodyJs')
    @include('product.categories.jsPartials.subCategoriesBodyJs')
@endpush
