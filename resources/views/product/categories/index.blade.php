@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@section('title', 'Categories/SubCategories - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-cubes"></span>
                                <h5>{{ __("Categories/Subcategories") }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}
                            </a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row g-lg-3 g-1">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="tab_list_area">
                                                    <div class="btn-group">
                                                        <a href="#" id="tab_btn" data-show="categories" class="btn btn-sm btn-primary tab_btn tab_active categoryTab"> <i class="fas fa-th-large"></i> {{ __("Categories") }}</a>

                                                        <a href="#" id="tab_btn" data-show="sub-categories" class="btn btn-sm btn-primary tab_btn subcategoryTab"> <i class="fas fa-code-branch"></i> {{ __("Subcategories") }}</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 text-end">
                                                @if (auth()->user()->can('product_category_add'))
                                                    <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary" id="addCategory"><i class="fas fa-plus-square"></i> {{ __("Add Category") }}</a>

                                                    <a href="{{ route('subcategories.create') }}" class="btn btn-sm btn-primary p-1 d-hide" id="addSubcategory"><i class="fas fa-plus-square"></i> {{ __("Add Subcategory") }}</a>
                                                @endif
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

    <form id="deleted_category_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <form id="deleted_sub_category_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection
@push('scripts')
    <script>
        $('.sub-categories').hide();

        $(document).on('click', '#tab_btn', function() {

            $('#addCategory').hide();
            $('#addSubcategory').hide();
            var showing = $(this).data('show');

            if (showing == 'categories') {

                $('#addCategory').show();
            } else {

                $('#addSubcategory').show();
            }
        });
    </script>
    @include('product.categories.jsPartials.categoriesBodyJs')
    @include('product.categories.jsPartials.subCategoriesBodyJs')
@endpush
