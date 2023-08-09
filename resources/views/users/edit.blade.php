@extends('layout.master')
@push('stylesheets')
<style>
    b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    label.col-2,label.col-3,label.col-4,label.col-5,label.col-6 { text-align: right; padding-right: 10px;}
    .checkbox_input_wrap {text-align: right;}
</style>
@endpush
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <span class="fas fa-user-edit"></span>
                <h6>@lang('menu.edit_user')</h6>
            </div>

            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
        </div>
        <div class="p-3">
            <form id="update_user_form" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <section>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>{{ __('User Information') }}</b>
                                </div>
                                <div class="element-body">
                                    <div class="row gx-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.prefix')</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="prefix" class="form-control" placeholder="Mr / Mrs / Miss" value="{{ $user->prefix }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.first_name')</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input type="text" name="first_name" class="form-control" placeholder="@lang('menu.first_name')" id="first_name" value="{{ $user->name }}" required>
                                                    <span class="error error_first_name"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 pt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.last_name')</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="last_name" class="form-control" placeholder="@lang('menu.last_name')" value="{{ $user->last_name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.email')</b> <span class="text-danger">*</span> </label>
                                                <div class="col-8">
                                                    <input required type="text" name="email" id="email" class="form-control" placeholder="exmple@email.com" value="{{ $user->email }}">
                                                    <span class="error error_email"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 pt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.phone')</b> <span class="text-danger">*</span> </label>
                                                <div class="col-8">
                                                    <input required type="text" name="phone" class="form-control" placeholder="@lang('menu.phone_number')" value="{{ $user->phone }}" autocomplete="off">
                                                    <span class="error error_phone"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>@lang('menu.sales')</b> </p>
                                </div>

                                <div class="element-body">

                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-12"> <b>@lang('menu.commission') (%)</b> </label>
                                                <div class="col-sm-8 col-12">
                                                    <input type="number" name="sales_commission_percent" class="form-control" placeholder="Sales Commission Percentage (%)" autocomplete="off" value="{{ $user->sales_commission_percent }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-12"><b>@lang('menu.max_discount')(%) </b> </label>
                                                <div class="col-sm-8 col-12">
                                                    <input type="number" name="max_sales_discount_percent" class="form-control" placeholder="Max sales discount percent" autocomplete="off" value="{{ $user->max_sales_discount_percent }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>@lang('menu.bank_details')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.account_name')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_ac_holder_name" class="form-control " placeholder="@lang('menu.account_holders_name')" autocomplete="off" value="{{ $user->bank_ac_holder_name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.account_no')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_ac_no" class="form-control" placeholder="@lang('menu.account_number')" autocomplete="off" value="{{ $user->bank_ac_no }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 pt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.bank_name')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_name" class="form-control" placeholder="@lang('menu.bank_name')" autocomplete="off" value="{{ $user->bank_name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.identifier_code')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_identifier_code" class="form-control" placeholder="{{ __('Bank Identifier Code') }}" autocomplete="off" value="{{ $user->bank_identifier_code }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 pt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.branch')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_branch" class="form-control" placeholder="@lang('menu.branch')" autocomplete="off" value="{{ $user->bank_branch }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.tax_payer_id')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="tax_payer_id" class="form-control" placeholder="@lang('menu.tax_payer_id')" autocomplete="off" value="{{ $user->tax_payer_id }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($generalSettings['addons__hrm'] == 1)
                                <div class="col-12">
                                    <div class="form_element rounded mt-0 mb-3">
                                        <div class="heading_area">
                                            <p class="px-1 pt-1 pb-0 text-primary"><b>{{ __('Human Resource Details') }}</b> </p>
                                        </div>

                                        <div class="element-body">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Employee ID') }}</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" class="form-control" name="emp_id" placeholder="{{ __('Employee ID') }}" value="{{ $user->emp_id }}">
                                                            <span class="error error_emp_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.shift')</b> </label>
                                                        <div class="col-8">
                                                            <select name="shift_id" class="form-control">
                                                                @foreach ($shifts as $shift)
                                                                <option {{ $user->shift_id == $shift->id ? 'SELECTED' : '' }} value="{{ $shift->id }}">{{ $shift->shift_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_shift_id"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-2 pt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.department')</b> </label>
                                                        <div class="col-8">
                                                            <select name="department_id" class="form-control">
                                                                <option value="">@lang('menu.select_department')</option>
                                                                @foreach ($departments as $department)
                                                                <option {{ $user->department_id == $department->id ? 'SELECTED' : '' }} value="{{ $department->id }}">{{ $department->department_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_department_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.designation')</b> </label>
                                                        <div class="col-8">
                                                            <select name="designation_id" class="form-control">
                                                                <option value="">{{ __('Select Designation') }}</option>
                                                                @foreach ($designations as $designation)
                                                                <option {{ $user->designation_id == $designation->id ? 'SELECTED' : '' }} value="{{ $designation->id }}">{{ $designation->designation_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-2 pt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Salary') }}</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="salary" id="salary" class="form-control" placeholder="Salary Amount" autocomplete="off" value="{{ $user->salary }}">
                                                            <span class="error error_salary"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"> <b>{{ __('Pay Type') }}</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <select name="pay_type" class="form-control" id="pay_type">
                                                                <option value="">{{ __('Select Pay type') }}</option>
                                                                <option {{ $user->salary_type == 'Monthly' ? 'SELECTED' : '' }} value="Monthly">{{ __('Monthly') }}</option>
                                                                <option {{ $user->salary_type == 'Yearly' ? 'SELECTED' : '' }} value="Yearly">{{ __('Yearly') }}</option>
                                                                <option {{ $user->salary_type == 'Daliy' ? 'SELECTED' : '' }} value="Daliy">{{ __('Daily') }}</option>
                                                                <option {{ $user->salary_type == 'Hourly' ? 'SELECTED' : '' }} value="Hourly">{{ __('Hourly') }}</option>
                                                            </select>
                                                            <span class="error error_pay_type"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form_element rounded mt-0">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>@lang('menu.role_permission')</b>
                                    <small class="float-end">
                                        <input type="checkbox" {{ $user->allow_login == 1 ? 'CHECKED' : '' }} name="allow_login" id="allow_login">
                                        <b>@lang('menu.allow_login')</b>
                                    </small>
                                </div>

                                <div class="element-body">
                                    <div class="row gx-2">
                                        <div class="col-md-12">
                                        </div>
                                    </div>

                                    <div class="auth_field_area">
                                        <div class="row gx-2 pt-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.username')</b> <span class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <input {{ $user->username ? 'readonly' : '' }} type="text" name="username" id="username" class="form-control " placeholder="@lang('menu.username')" autocomplete="off" value="{{ $user->username }}">
                                                        <span class="error error_username"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('menu.role')</b> <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Admin has access to all business location." class="fas fa-info-circle tp"></i></label>
                                                    <div class="col-8">
                                                        <select name="role_id" id="role_id" class="form-control">

                                                            @php
                                                                $userRole = $user?->roles?->first();
                                                                $userRoleId = $userRole?->id;
                                                            @endphp

                                                            @foreach ($roles as $role)
                                                                <option {{ $userRoleId == $role->id ? 'SELECTED' : '' }} value="{{ $role->id }}">{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row gx-2 pt-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.password')</b> <span class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off">
                                                        <span class="error error_password"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4">
                                                        <b>@lang('menu.confirm_password')</b>
                                                        <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <input type="password" name="password_confirmation" class="form-control" placeholder="@lang('menu.confirm_password')" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row g-2 pt-1">
                                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                            <div class="col-md-12 access_branch">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.access_location')</b> </label>
                                                    <div class="col-8">
                                                        <select name="branch_id" id="branch_id" class="form-control">
                                                            <option value="">@lang('menu.select_business_location')</option>
                                                            <option {{ $user->branch_id == NULL ? 'SELECTED' : '' }} value="head_office">{{ $generalSettings['business__shop_name'] }} </option>
                                                            @foreach ($branches as $branch)
                                                                <option {{ $user->branch_id == $branch->id ? 'SELECTED' : '' }} value="{{ $branch->id }}">{{ $branch->name.' - '.$branch->branch_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_branch_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 belonging_branch d-hide">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.belonging_location')</b> <span class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <select name="belonging_branch_id" id="belonging_branch_id" class="form-control">
                                                            <option value="head_office">{{ $generalSettings['business__shop_name'] }} </option>
                                                            @foreach ($branches as $branch)
                                                            <option {{ $user->branch_id == $branch->id ? 'SELECTED' : '' }} value="{{ $branch->id }}">{{ $branch->name.' - '.$branch->branch_code }}</option>
                                                            @endforeach
                                                        </select>

                                                        <span class="error error_belonging_branch_id"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                                            <input type="hidden" name="belonging_branch_id" value="{{ auth()->user()->branch_id }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>@lang('menu.more_information')</b> </p>
                                </div>
                                <div class="element-body">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"> <b>{{ __('Profile image') }}</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="file" name="photo" class="form-control form-control-sm" placeholder="{{ __('Profile image') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"> <b>@lang('menu.date_of_birth')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="date_of_birth" class="form-control" autocomplete="off" placeholder="@lang('menu.date_of_birth')" value="{{ $user->date_of_birth }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.gender')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <select name="gender" class="form-control">
                                                        <option value="">@lang('menu.select_gender')</option>
                                                        <option {{ $user->gender == 'Male' ? 'SELECTED' : '' }} value="Male">@lang('menu.male')</option>
                                                        <option {{ $user->gender == 'Female' ? 'SELECTED' : '' }} value="Female">@lang('menu.female')</option>
                                                        <option {{ $user->gender == 'Others' ? 'SELECTED' : '' }} value="Others">@lang('menu.others')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    {{-- </div>

                                    <div class="row g-2 pt-1"> --}}
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.marital_status')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <select name="marital_status" class="form-control">
                                                        <option value="">@lang('menu.marital_status')</option>
                                                        <option {{ $user->marital_status == 'Married' ? 'SELECTED' : '' }} value="Married">@lang('menu.married')</option>
                                                        <option {{ $user->marital_status == 'Unmarried' ? 'SELECTED' : '' }} value="Unmarried">{{ __('Unmarried') }}</option>
                                                        <option {{ $user->marital_status == 'Divorced' ? 'SELECTED' : '' }} value="Divorced">@lang('menu.divorced')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.blood_group')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="blood_group" class="form-control" placeholder="@lang('menu.blood_group')" autocomplete="off" value="{{ $user->blood_group }}">
                                                </div>
                                            </div>
                                        </div>
                                    {{-- </div>

                                    <div class="row g-2 pt-1"> --}}
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.twitter_link')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="twitter_link" class="form-control" autocomplete="off" placeholder="@lang('menu.twitter_link')" value="{{ $user->twitter_link }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.instagram_link')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="instagram_link" class="form-control" autocomplete="off" placeholder="@lang('menu.instagram_link')" value="{{ $user->instagram_link }}">
                                                </div>
                                            </div>
                                        </div>
                                    {{-- </div>

                                    <div class="row g-2 pt-1"> --}}

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.id_proof_name')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="id_proof_name" class="form-control" autocomplete="off" placeholder="@lang('menu.id_proof_name')" value="{{ $user->id_proof_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-6 col-6"><b>@lang('menu.guardian_name')</b> </label>
                                                <div class="col-sm-6 col-6">
                                                    <input type="text" name="guardian_name" class="form-control" autocomplete="off" placeholder="@lang('menu.guardian_name')" value="{{ $user->guardian_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.facebook_link')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="facebook_link" class="form-control" autocomplete="off" placeholder="@lang('menu.facebook_link')" value="{{$user->facebook_link }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2 pt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-lg-3 col-4"><b>@lang('menu.id_proof_number')</b> </label>
                                                <div class="col-lg-9 col-8">
                                                    <input type="text" name="id_proof_number" class="form-control" autocomplete="off" placeholder="@lang('menu.id_proof_number')" value="{{ $user->id_proof_number }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2 pt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-lg-3 col-4"><b>@lang('menu.permanent_address')</b> </label>
                                                <div class="col-lg-9 col-8">
                                                    <input type="text" name="permanent_address" class="form-control form-control-sm" autocomplete="off" placeholder="@lang('menu.permanent_address')" value="{{ $user->permanent_address }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2 pt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-lg-3 col-4"><b>@lang('menu.current_address')</b> </label>
                                                <div class="col-lg-9 col-8">
                                                    <input type="text" name="current_address" class="form-control form-control-sm" placeholder="@lang('menu.current_address')" value="{{ $user->current_address }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                                <button class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
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
<script>
    // Add user by ajax
    $(document).on('submit', '#update_user_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                toastr.success(data);
                $('.loading_button').hide();
                window.location = "{{ route('users.index') }}";
            }, error: function(err) {
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    //console.log(key);
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    if ($('#allow_login').is(':CHECKED', true)) {
        $('.auth_field_area').show();
        $('.access_branch').show();
        $('.belonging_branch').hide();
    } else {
        $('.auth_field_area').hide();
        $('.access_branch').hide();
        $('.belonging_branch').show();
    }

    $('#allow_login').on('click', function() {
        if ($(this).is(':CHECKED', true)) {
            $('.auth_field_area').show();
            $('.access_branch').show();
            $('.belonging_branch').hide();
        } else {
            $('.auth_field_area').hide();
            $('.access_branch').hide();
            $('.belonging_branch').show();
        }
    });

</script>
@endpush
