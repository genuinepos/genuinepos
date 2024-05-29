@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Role List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('User Roles') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __('List of User Roles') }}</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        @if (auth()->user()->can('role_add'))
                            <a href="{{ route('users.role.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> {{ __('Add Role') }}</a>
                        @endif
                    </div>
                </div>

                <div class="widget_content">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th class="text-start">{{ __('Serial') }}</th>
                                    <th class="text-start">{{ __('Name') }}</th>
                                    <th class="text-start">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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
    @include('users.roles.js_partials.index_js')
@endpush
