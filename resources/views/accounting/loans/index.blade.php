@extends('layout.master')
@push('stylesheets')
<link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('title', 'Loans - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-glass-whiskey"></span>
                    <h5>@lang('menu.loans')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
                </a>
            </div>

            <div class="p-3">
                <div class="card">
                    <div class="card-body">
                        <div class="tab_list_area">
                            <div class="btn-group">
                                <a id="tab_btn" data-show="companies" class="btn btn-sm btn-primary tab_btn tab_active" href="#"><i class="fas fa-info-circle"></i> @lang('menu.companies')/@lang('menu.peoples')</a>
                                <a id="tab_btn" data-show="loans" class="btn btn-sm btn-primary tab_btn" href="#"><i class="fas fa-scroll"></i> @lang('menu.loans')</a>
                            </div>
                        </div>
                        @include('accounting.loans.bodyPartials.companyBody')
                        @include('accounting.loans.bodyPartials.loanBody')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @include('accounting.loans.jsPartials.companyBodyJs')
    @include('accounting.loans.jsPartials.loanBodyJs')
@endpush
