@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'User List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-6">
                    <h6>{{ __('Users') }}</h6>
                </div>

                <div class="col-md-6">

                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                        <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="p-1">
            {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_area == 0) --}}
            @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <form action="" method="get">
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>{{ location_label() }}</strong></label>
                                            <select name="branch_id" class="form-control submit_able select2" id="branch_id">
                                                <option value="">{{ __('All') }}</option>
                                                <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        @php
                                                            $parentBranchName = $branch?->parentBranch?->name;
                                                            $areaName = $branch?->area_name ? ' (' . $branch->area_name . ')' : '';
                                                            $branchCode = $branch?->branch_code ? '-(' . $branch->branch_code . ')' : '';
                                                        @endphp
                                                        {{ $branch->name . $parentBranchName . $areaName . $branchCode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Type') }}</strong></label>
                                            <select name="user_type" class="form-control submit_able" id="user_type">
                                                <option value="">{{ __('All') }}</option>
                                                @foreach (\App\Enums\UserType::cases() as $userType)
                                                    <option value="{{ $userType->value }}">{{ $userType->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __('Username') }}</th>
                                    <th>{{ __('Allow Login') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ location_label() }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <form id="deleted_form" action="" method="post">
                @method('DELETE')
                @csrf
            </form>
        </div>
    </div>
    </div>
@endsection
@push('scripts')
    @include('users.partials.js_partials.user_index_js')
@endpush
