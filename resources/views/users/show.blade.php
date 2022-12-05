@extends('layout.master')
@push('stylesheets')

@endpush
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <span class="fas fa-user"></span>
                <h6>View User</h6>
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
                                    <p class="p-1 text-primary"><b>Role Permission</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>User Name :</b> {!! $user->username ? $user->username : '<span class="badge bg-secondary">Not-Allowed-to-Login</span>' !!} </p>
                                            <p><b>Role :</b>
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
                                    <p class="p-1 text-primary"><b>Basic Information</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>Fullname :</b> {{ $user->prefix.' '.$user->name.' '.$user->last_name }} </p>
                                            <p><b>@lang('menu.email') :</b> {{ $user->email}} </p>
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
                                    <p class="p-1 text-primary"><b>Personal Information</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>@lang('menu.date_of_birth') :</b> {{ $user->date_of_birth }}</p>
                                            <p><b>Gender :</b> {{ $user->gender }}</p>
                                            <p><b>Marital Status :</b> {{ $user->marital_status }}</p>
                                            <p><b>Blood Group : </b> {{ $user->blood_group }}</p>
                                            <p><b>Phone Number : </b> {{ $user->phone }}</p>
                                            <p><b>ID proof name : </b> {{ $user->id_proof_name }}</p>
                                            <p><b>ID proof Number : </b> {{ $user->id_proof_number }}</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>Other Information</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>Guardian Name :</b> {{ $user->guardian_name }}</p>
                                            <p><b>Facebook Link :</b> {{ $user->facebook_link }}</p>
                                            <p><b>Twitter Link :</b> {{ $user->twitter_link }}</p>
                                            <p><b>Instagram Link :</b> {{ $user->instagram_link }}</p>
                                            <p><b>@lang('menu.custom_field') 1 :</b> {{ $user->custom_field_1 }}</p>
                                            <p><b>Permanent Address :</b> {{ $user->permanent_address }}</p>
                                            <p><b>Current Address :</b> {{ $user->current_address }}</p>
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
                                    <p class="p-1 text-primary"><b>Bank Information</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>Account Holder's Name :</b> {{ $user->bank_ac_holder_name }}</p>
                                            <p><b>Account No :</b> {{ $user->bank_ac_no }}</p>
                                            <p><b>@lang('menu.bank_name') :</b> {{ $user->bank_name }}</p>
                                            <p><b>Bank Identifier Code :</b> {{ $user->bank_identifier_code }}</p>
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
