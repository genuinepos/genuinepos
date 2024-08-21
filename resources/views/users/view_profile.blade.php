@extends('layout.master')
@section('title', 'User Profile - ')
@push('stylesheets')
@endpush
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('View Profile') }}</h6>
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
                                            @php
                                                $photoSrc = null;
                                                if ($user->photo) {
                                                    $photoSrc = file_link('user', $user->photo);
                                                } else {
                                                    $photoSrc = asset('images/user_default.png');
                                                }
                                            @endphp
                                            <img src="{{ $photoSrc }}" alt="User Photo">
                                        </div>
                                        <div class="part-txt text-center">
                                            <h4>{!! $user->username ? $user->username : '<span class="badge bg-secondary">Not-Allowed-to-Login</span>' !!}</h4>
                                        </div>
                                    </div>
                                    <ul class="profile-short-info">
                                        <li>{{ __('Role') }}<span>{{ $user?->roles()?->first()?->name }}</span></li>
                                        <li>{{ __('Department') }} : <span>{{ $user?->department?->department_name ?? 'N/A' }}</span></li>
                                        <li>{{ __('Designation') }} : <span>{{ $user?->designation?->designation_name ?? 'N/A' }}</span></li>

                                        <li>{{ __('Basic Salary') }} : <span>{{ App\Utils\Converter::format_in_bdt($user->salary) }}</span></li>
                                        <li>{{ __('Pay Type') }} : <span>{{ $user->salary_type }}</span></li>
                                        <li>{{ __('Shift') }} : <span>{{ $user?->shift?->shift_name ?? 'N/A' }}</span></li>
                                        <li>{{ __('Joining Date') }}<span>...</span></li>
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
                                        <button class="btn btn-sm btn-primary active" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="true">{{ __('Profile') }}</button>
                                        <button class="btn btn-sm btn-primary" id="nav-payroll-tab" data-bs-toggle="tab" data-bs-target="#nav-payroll" type="button" role="tab" aria-controls="nav-payroll" aria-selected="false">{{ __('Payrolls') }}</button>
                                        <button class="btn btn-sm btn-primary" id="nav-leaves-tab" data-bs-toggle="tab" data-bs-target="#nav-leaves" type="button" role="tab" aria-controls="nav-leaves" aria-selected="false">{{ __('Leaves') }}</button>
                                        <button class="btn btn-sm btn-primary" id="nav-attendance-tab" data-bs-toggle="tab" data-bs-target="#nav-attendance" type="button" role="tab" aria-controls="nav-attendance" aria-selected="false">{{ __('Attendances') }}</button>
                                        <button class="btn btn-sm btn-primary" id="nav-documents-tab" data-bs-toggle="tab" data-bs-target="#nav-documents" type="button" role="tab" aria-controls="nav-documents" aria-selected="false">{{ __('Documents') }}</button>
                                        <button class="btn btn-sm btn-primary" id="nav-timeline-tab" data-bs-toggle="tab" data-bs-target="#nav-timeline" type="button" role="tab" aria-controls="nav-timeline" aria-selected="false">{{ __('Timeline') }}</button>
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
                                                                <td>{{ __('Name') }}</td>
                                                                <td>{{ $user->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Phone') }}</td>
                                                                <td>{{ $user->phone }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Email') }}</td>
                                                                <td>{{ $user->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Gender') }}</td>
                                                                <td>{{ $user->gender }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Date Of Birth') }}</td>
                                                                <td>{{ $user->date_of_birth }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Marital Status') }}</td>
                                                                <td>{{ $user->marital_status }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Blood Group') }}</td>
                                                                <td>{{ $user->blood_group }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card my-3">
                                            <div class="card-header">
                                                <h6 class="card-title m-0">{{ __('Address Details') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="display table-striped">
                                                        <tbody>
                                                            <tr>
                                                                <td>{{ __('Current Address') }}</td>
                                                                <td>{{ $user->current_address }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Permanent Address') }}</td>
                                                                <td>{{ $user->permanent_address }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card my-3">
                                            <div class="card-header">
                                                <h6 class="card-title m-0">{{ __('Bank Account Details') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="display table-striped">
                                                        <tbody>
                                                            <tr>
                                                                <td>{{ __('Account Title') }}</td>
                                                                <td>{{ $user->bank_ac_holder_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Bank Name') }}</td>
                                                                <td>{{ $user->bank_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Bank Branch') }}</td>
                                                                <td>{{ $user->bank_branch }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Account Number') }}</td>
                                                                <td>{{ $user->bank_ac_no }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('IFSC Code') }}</td>
                                                                <td>{{ $user->bank_identifier_code }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="card-title m-0">{{ __('Social Media Links') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="display table-striped">
                                                        <tbody>
                                                            <tr>
                                                                <td>{{ __('Facebook Link') }}</td>
                                                                <td>{{ $user->facebook_link }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('X Link') }}</td>
                                                                <td>{{ $user->twitter_link }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Instagram Link') }}</td>
                                                                <td>{{ $user->instagram_link }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade text-center" id="nav-payroll" role="tabpanel" aria-labelledby="nav-payroll-tab" tabindex="0"><img src="{{ asset('assets/images/no-data.png') }}" alt=""></div>
                                    <div class="tab-pane fade text-center" id="nav-leaves" role="tabpanel" aria-labelledby="nav-leaves-tab" tabindex="0"><img src="{{ asset('assets/images/no-data.png') }}" alt=""></div>
                                    <div class="tab-pane fade text-center" id="nav-attendance" role="tabpanel" aria-labelledby="nav-attendance-tab" tabindex="0"><img src="{{ asset('assets/images/no-data.png') }}" alt=""></div>
                                    <div class="tab-pane fade text-center" id="nav-documents" role="tabpanel" aria-labelledby="nav-documents-tab" tabindex="0"><img src="{{ asset('assets/images/no-data.png') }}" alt=""></div>
                                    <div class="tab-pane fade text-center" id="nav-timeline" role="tabpanel" aria-labelledby="nav-timeline-tab" tabindex="0"><img src="{{ asset('assets/images/no-data.png') }}" alt=""></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
