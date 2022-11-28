@extends('layout.master')
@push('stylesheets')

@endpush
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <span class="fas fa-user"></span>
                <h6>View Profile</h6>
            </div>
            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
        </div>
        <form id="add_user_form" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <section class="p-3">

                <div class="row g-3">
                    <div class="col-xl-3 col-lg-4 col-md-5">
                        <div class="card">
                            <div class="card-body p-2">
                                <div class="profile-sidebar">
                                    <div class="profile-top">
                                        <div class="part-img">
                                            <img src="{{ asset('assets/images/avatar.png')}}" alt="Image">
                                        </div>
                                        <div class="part-txt text-center">
                                            <h4>{!! $user->username ? $user->username : '<span class="badge bg-secondary">Not-Allowed-to-Login</span>' !!}</h4>
                                        </div>
                                    </div>
                                    <ul class="profile-short-info">
                                        <li>Staff ID<span>9002</span></li>
                                        <li>Role<span>{{ $user?->roles()?->first()?->name }}</span></li>
                                        <li>Designation<span>Faculty</span></li>
                                        <li>@lang('menu.departments')<span>Academic</span></li>
                                        <li>EPF No<span>954564154</span></li>
                                        <li>Basic Salary<span>21000</span></li>
                                        <li>Contract Type<span>Permanent</span></li>
                                        <li>Work Shift<span>Morning</span></li>
                                        <li>@lang('menu.location')<span>1st Floor</span></li>
                                        <li>Date Of Joining<span>03/10/2022</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-8 col-md-7">
                        <div class="card">
                            <div class="card-body p-2">
                                <nav>
                                    <div class="nav nav-tabs pb-2 mb-2" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="true">Profile</button>
                                        <button class="nav-link" id="nav-payroll-tab" data-bs-toggle="tab" data-bs-target="#nav-payroll" type="button" role="tab" aria-controls="nav-payroll" aria-selected="false">Payroll</button>
                                        <button class="nav-link" id="nav-leaves-tab" data-bs-toggle="tab" data-bs-target="#nav-leaves" type="button" role="tab" aria-controls="nav-leaves" aria-selected="false">Leaves</button>
                                        <button class="nav-link" id="nav-attendance-tab" data-bs-toggle="tab" data-bs-target="#nav-attendance" type="button" role="tab" aria-controls="nav-attendance" aria-selected="false">Attendance</button>
                                        <button class="nav-link" id="nav-documents-tab" data-bs-toggle="tab" data-bs-target="#nav-documents" type="button" role="tab" aria-controls="nav-documents" aria-selected="false">@lang('menu.documents')</button>
                                        <button class="nav-link" id="nav-timeline-tab" data-bs-toggle="tab" data-bs-target="#nav-timeline" type="button" role="tab" aria-controls="nav-timeline" aria-selected="false">Timeline</button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                                        <div class="card">
                                            <div class="card-body px-3">
                                                <div class="table-responsive">
                                                    <table class="table profile-table mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td>@lang('menu.phone')</td>
                                                                <td>{{ $user->phone }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Emergency Contact Number</td>
                                                                <td>01919585035</td>
                                                            </tr>
                                                            <tr>
                                                                <td>@lang('menu.email')</td>
                                                                <td>{{ $user->email}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Gender</td>
                                                                <td>Male</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date Of Birth</td>
                                                                <td>21/01/1999</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Marital Status</td>
                                                                <td>Married</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Father Name</td>
                                                                <td>Kalachan</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Mother Name</td>
                                                                <td>Shundori</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Qualification</td>
                                                                <td>Msc</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Work Experience</td>
                                                                <td>12 @lang('menu.years')</td>
                                                            </tr>
                                                            <tr>
                                                                <td>@lang('menu.note')</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>PAN Number</td>
                                                                <td>RLWEG5809L</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header px-3 bg-secondary text-white">
                                                <h5 class="card-title mt-0">@lang('menu.address') @lang('menu.details')</h5>
                                            </div>
                                            <div class="card-body py-0 px-3">
                                                <div class="table-responsive">
                                                    <table class="table profile-table mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td>@lang('menu.current') @lang('menu.address')</td>
                                                                <td>Sector-4, Uttara, Dhaka</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Permanent @lang('menu.address')</td>
                                                                <td>Sector-4, Uttara, Dhaka</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header px-3 bg-secondary text-white">
                                                <h5 class="card-title mt-0">@lang('menu.bank') @lang('menu.account_details')</h5>
                                            </div>
                                            <div class="card-body py-0 px-3">
                                                <div class="table-responsive">
                                                    <table class="table profile-table mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td>Account Title</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>@lang('menu.bank_name')</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>@lang('menu.bank_branch_name')</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Bank Account Number</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>IFSC Code</td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-0">
                                            <div class="card-header px-3 bg-secondary text-white">
                                                <h5 class="card-title mt-0">Social Media Link</h5>
                                            </div>
                                            <div class="card-body py-0 px-3">
                                                <div class="table-responsive">
                                                    <table class="table profile-table mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td>Facebook Link :</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Twitter Link :</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Instagram Link :</td>
                                                                <td></td>
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
        </form>
    </div>
</div>

@endsection
@push('scripts')

@endpush
