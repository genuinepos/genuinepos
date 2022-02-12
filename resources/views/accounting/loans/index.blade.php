@extends('layout.master')
@push('stylesheets')
<link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('title', 'Loans - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-glass-whiskey"></span>
                                <h5>Loans</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="sec-name mt-1">
                            <div class="name-head">
                                <div class="tab_list_area">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a id="tab_btn" data-show="companies" class="tab_btn tab_active" href="#"><i class="fas fa-info-circle"></i> Companies/Peoples</a>
                                        </li>

                                        <li>
                                            <a id="tab_btn" data-show="loans" class="tab_btn" href="#">
                                            <i class="fas fa-scroll"></i> Loans</a>
                                        </li>
                                    </ul>
                                </div>
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
