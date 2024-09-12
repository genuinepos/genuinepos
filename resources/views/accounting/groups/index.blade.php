@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
    <style>
        .parent a {
            font-size: 14px !important;
        }

        span.select2-results ul li {
            font-size: 15px;
            font-weight: 450;
            line-height: 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-weight: 700;
        }

        .fw-icon {
            font-size: 11px;
            font-weight: 400;
        }

        .add_btn_frm_group:hover {
            color: rgb(21, 255, 21) !important;
            font-weight: 700 !important;
            font-size: 13px !important
        }

        .delete_group_btn:hover {
            color: rgb(244, 16, 16) !important;
            font-weight: 700 !important;
            font-size: 13px !important
        }

        .parent #parentText {
            text-transform: uppercase !important;
            font-weight: 700 !important;
            font-size: 17px !important;
        }

        .jstree-default .jstree-anchor {
            font-size: 11px !important;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
@endpush

@section('title', 'Account Groups - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h6>{{ __('Account Groups') }}</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-6">
                                    <h6>{{ __('List of Account Groups') }}</h6>
                                </div>

                                <div class="col-6 d-flex justify-content-end">
                                    <a href="{{ route('account.groups.create') }}" class="btn btn-sm btn-primary" id="addAccountGroupBtn"><i class="fas fa-plus-square"></i> @lang('menu.add')</a>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                </div>
                                <div class="card-body" id="list_of_groups">

                                </div>
                            </div>

                            <form id="deleted_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Add/Edit Account Group modal-->
    <div class="modal fade" id="accountGroupAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <!--Add/Edit Account Group modal End-->

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection
@push('scripts')
    @include('accounting.groups.js_partial.index_js')
@endpush
