@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <span class="fas fa-user"></span>
                <h6>@lang('menu.view_profile')</h6>
            </div>

            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
        </div>
        <section class="p-3">
            <div class="row g-3">
                <div class="col-xl-3 col-lg-4 col-md-5">
                    <div class="card">
                        <div class="card-body p-2">
                            <div class="profile-sidebar">
                                <div class="profile-top">
                                    <div class="part-img">
                                        <img src="{{ asset('uploads/user_photo') }}/{{ $user->photo }}" alt="Not found">
                                    </div>
                                    <div class="part-txt text-center">
                                        <h4>{!! $user->username ? $user->username : '<span class="badge bg-secondary">Not-Allowed-to-Login</span>' !!}</h4>
                                    </div>
                                </div>
                                <ul class="profile-short-info">
                                    <li>@lang('menu.role')<span>{{ $user?->roles()?->first()?->name }}</span></li>
                                    <li>@lang('menu.designation')<span>@lang('menu.faculty')</span></li>
                                    <li>@lang('menu.departments')<span>@lang('menu.academic')</span></li>
                                    <li>@lang('menu.basic_salary')<span>21000</span></li>
                                    <li>@lang('menu.contract_type')<span>@lang('menu.permanent')</span></li>
                                    <li>@lang('menu.work_shift')<span>@lang('menu.morning')</span></li>
                                    <li>@lang('menu.date_of_joining')<span>...</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <nav>
                                <div class="nav nav-tabs pb-2 mb-2 btn-group" id="nav-tab" role="tablist">
                                    <button class="btn btn-sm btn-primary active" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="true">@lang('menu.profile')</button>
                                    <button class="btn btn-sm btn-primary" id="nav-payroll-tab" data-bs-toggle="tab" data-bs-target="#nav-payroll" type="button" role="tab" aria-controls="nav-payroll" aria-selected="false">@lang('menu.payroll')</button>
                                    <button class="btn btn-sm btn-primary" id="nav-leaves-tab" data-bs-toggle="tab" data-bs-target="#nav-leaves" type="button" role="tab" aria-controls="nav-leaves" aria-selected="false">@lang('menu.leaves')</button>
                                    <button class="btn btn-sm btn-primary" id="nav-attendance-tab" data-bs-toggle="tab" data-bs-target="#nav-attendance" type="button" role="tab" aria-controls="nav-attendance" aria-selected="false">@lang('menu.attendance')</button>
                                    <button class="btn btn-sm btn-primary" id="nav-documents-tab" data-bs-toggle="tab" data-bs-target="#nav-documents" type="button" role="tab" aria-controls="nav-documents" aria-selected="false">@lang('menu.documents')</button>
                                    <button class="btn btn-sm btn-primary" id="nav-timeline-tab" data-bs-toggle="tab" data-bs-target="#nav-timeline" type="button" role="tab" aria-controls="nav-timeline" aria-selected="false">@lang('menu.timeline')</button>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="display table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td>@lang('menu.name')</td>
                                                            <td>{{ $user->username }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.phone')</td>
                                                            <td>{{ $user->phone }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.email')</td>
                                                            <td>{{ $user->email}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.gender')</td>
                                                            <td>{{ $user->gender}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.date_of_birth')</td>
                                                            <td>{{ $user->date_of_birth }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.marital_status')</td>
                                                            <td>{{ $user->marital_status }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.blood_group')</td>
                                                            <td>{{ $user->blood_group }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card my-3">
                                        <div class="card-header">
                                            <h6 class="card-title m-0">@lang('menu.address') @lang('menu.details')</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="display table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td>@lang('menu.current') @lang('menu.address')</td>
                                                            <td>{{ $user->current_address }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.permanent') @lang('menu.address')</td>
                                                            <td>{{ $user->permanent_address }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card my-3">
                                        <div class="card-header">
                                            <h6 class="card-title m-0">@lang('menu.bank') @lang('menu.account_details')</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="display table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td>@lang('menu.account_title')</td>
                                                            <td>{{ $user->bank_ac_holder_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.bank_name')</td>
                                                            <td>{{ $user->bank_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.bank_branch_name')</td>
                                                            <td>{{ $user->bank_branch }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.bank_account_number')</td>
                                                            <td>{{ $user->bank_ac_no }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>IFSC Code</td>
                                                            <td>{{ $user->bank_identifier_code }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title m-0">@lang('menu.social_media_link')</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="display table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td>@lang('menu.facebook_link') :</td>
                                                            <td>{{ $user->facebook_link }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.twitter_link') :</td>
                                                            <td>{{ $user->twitter_link }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('menu.instagram_link') :</td>
                                                            <td>{{ $user->instagram_link }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-payroll" role="tabpanel" aria-labelledby="nav-payroll-tab" tabindex="0">2...</div>
                                <div class="tab-pane fade" id="nav-leaves" role="tabpanel" aria-labelledby="nav-leaves-tab" tabindex="0">3...</div>
                                <div class="tab-pane fade" id="nav-attendance" role="tabpanel" aria-labelledby="nav-attendance-tab" tabindex="0">4...</div>
                                <div class="tab-pane fade" id="nav-documents" role="tabpanel" aria-labelledby="nav-documents-tab" tabindex="0">5...</div>
                                <div class="tab-pane fade" id="nav-timeline" role="tabpanel" aria-labelledby="nav-timeline-tab" tabindex="0">6...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection
