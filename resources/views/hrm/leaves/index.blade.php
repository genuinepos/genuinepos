@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .tab_list_area {padding-bottom: 0px;}
        .card-body { padding: 4px 6px; }
    </style>
@endpush
@section('title', 'Leaves - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __("Leaves") }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}
                            </a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row g-lg-1 g-1">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="tab_list_area">
                                                    <div class="btn-group">
                                                        <a href="#" id="tab_btn" data-show="leaves" class="btn btn-sm btn-primary tab_btn tab_active"> <i class="fas fa-th-large"></i> {{ __("Leaves") }}</a>
                                                        <a href="#" id="tab_btn" data-show="leave_types" class="btn btn-sm btn-primary tab_btn"> <i class="fas fa-code-branch"></i> {{ __("Leave Types") }}</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 text-end">
                                                <a href="{{ route('hrm.leaves.create') }}" class="btn btn-sm btn-success" id="addLeave"><i class="fas fa-plus-square"></i> {{ __("Add Leave") }}</a>
                                                <a href="{{ route('hrm.leave.type.create') }}" class="btn btn-sm btn-success p-1 d-hide" id="addLeaveType"><i class="fas fa-plus-square"></i> {{ __("Add Leave Type") }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                @include('hrm.leaves.body_partials.leaves_body_partial')
                                @include('hrm.leaves.body_partials.leave_types_body_partial')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="delete_leave_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <form id="delete_leave_type_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @include('hrm.leaves.js_partials.common_js_partial')
    @include('hrm.leaves.js_partials.leaves_js_partial')
    @include('hrm.leaves.js_partials.leave_types_js_partial')
@endpush
