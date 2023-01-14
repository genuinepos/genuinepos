@extends('layout.master')
@push('stylesheets')

@endpush
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <span class="fas fa-user"></span>
                <h6>@lang('menu.view_user')</h6>
            </div>

            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
        </div>
        <div class="container-fluid p-0">
            <form id="add_user_form" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="p-3">
                    <div class="row gx-3">

                        <div class="col-md-6">
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>@lang('menu.role_permission')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>@lang('menu.user_name') </b> {!! $user->username ? $user->username : '<span class="badge bg-secondary">Not-Allowed-to-Login</span>' !!} </p>
                                            <p><b>@lang('menu.role') </b>
                                                @if ($user->role_type == 1)
                                                    Super-Admin
                                                @elseif($user->role_type == 2)
                                                    Admin
                                                @elseif($user->role_type == 3)
                                                    {{ $user?->roles?->first()?->name }}
                                                @else
                                                    <span class="badge bg-warning">No-Role</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>@lang('menu.basic_information')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>@lang('menu.full_name') </b> {{ $user->prefix.' '.$user->name.' '.$user->last_name }} </p>
                                            <p><b>@lang('menu.email') </b> {{ $user->email}} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gx-3">
                        <div class="col-md-6">
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b> @lang('menu.personal_information')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>@lang('menu.date_of_birth') </b> {{ $user->date_of_birth }}</p>
                                            <p><b>@lang('menu.gender') </b> {{ $user->gender }}</p>
                                            <p><b>@lang('menu.marital_status') </b> {{ $user->marital_status }}</p>
                                            <p><b>@lang('menu.blood_group') </b> {{ $user->blood_group }}</p>
                                            <p><b>@lang('menu.phone_number') </b> {{ $user->phone }}</p>
                                            <p><b>@lang('menu.id_proof_name') </b> {{ $user->id_proof_name }}</p>
                                            <p><b>@lang('menu.id_proof_number') </b> {{ $user->id_proof_number }}</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>@lang('menu.other_information')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>@lang('menu.guardian_name') </b> {{ $user->guardian_name }}</p>
                                            <p><b>@lang('menu.facebook_link') </b> {{ $user->facebook_link }}</p>
                                            <p><b>@lang('menu.twitter_link') </b> {{ $user->twitter_link }}</p>
                                            <p><b>@lang('menu.instagram_link') </b> {{ $user->instagram_link }}</p>
                                            <p><b>@lang('menu.custom_field') 1 </b> {{ $user->custom_field_1 }}</p>
                                            <p><b>@lang('menu.permanent_address') </b> {{ $user->permanent_address }}</p>
                                            <p><b>@lang('menu.current_address') </b> {{ $user->current_address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gx-3">
                        <div class="col-md-6">
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>@lang('menu.bank_information')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>@lang('menu.account_holders_name') </b> {{ $user->bank_ac_holder_name }}</p>
                                            <p><b>@lang('menu.account_no') </b> {{ $user->bank_ac_no }}</p>
                                            <p><b>@lang('menu.bank_name') </b> {{ $user->bank_name }}</p>
                                            <p><b>{{ __('Bank Identifier Code') }} </b> {{ $user->bank_identifier_code }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')

@endpush
