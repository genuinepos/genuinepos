@extends('layout.master')
@push('stylesheets')
    <style>
        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        label.col-2,
        label.col-3,
        label.col-4,
        label.col-5,
        label.col-6 {
            text-align: right;
            padding-right: 10px;
        }

        .checkbox_input_wrap {
            text-align: right;
        }
    </style>
@endpush
@section('title', 'Edit User - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-user-edit"></span>
                    <h6>{{ __('Edit User') }}</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
            <div class="p-1">
                <form id="update_user_form" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <section>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>{{ __('User Information') }}</b>
                                    </div>
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Prefix') }}</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="prefix" class="form-control" id="prefix" data-next="first_name" placeholder="Mr / Mrs / Miss" value="{{ $user->prefix }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('First Name') }}</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <input type="text" name="first_name" class="form-control" id="first_name" data-next="last_name" placeholder="{{ __('First Name') }}" id="first_name" value="{{ $user->name }}" required>
                                                        <span class="error error_first_name"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Last Name') }}</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="last_name" class="form-control" id="last_name" data-next="email" placeholder="{{ __('Last Name') }}" value="{{ $user->last_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Email') }}</b> <span class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <input required type="text" name="email" class="form-control" id="email" data-next="phone" placeholder="exmple@email.com" value="{{ $user->email }}">
                                                        <span class="error error_email"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Phone') }}</b> <span class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <input required type="text" name="phone" class="form-control" id="phone" data-next="branch_id" placeholder="{{ __('Phone Number') }}" value="{{ $user->phone }}" autocomplete="off">
                                                        <span class="error error_phone"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Shop/Business') }}</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="hidden" name="branch_count" value="YES">
                                                            <select required name="branch_id" class="form-control" id="branch_id" data-next="allow_login">
                                                                <option value="">{{ __('Select Shop/Business') }}</option>
                                                                <option {{ $user->branch_id == null ? 'SELECTED' : '' }} value="NULL">{{ $generalSettings['business__business_name'] }}({{ __('Business') }})</option>
                                                                @foreach ($branches as $branch)
                                                                    <option {{ $user->branch_id == $branch->id ? 'SELECTED' : '' }} value="{{ $branch->id }}">
                                                                        @php
                                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                            $branchCode = '-' . $branch->branch_code;
                                                                        @endphp
                                                                        {{ $branchName . $areaName . $branchCode }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="heading_area">
                                        <p class="px-1 pt-1 text-primary"><b>{{ __('Role Permission') }}</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Allow Login') }}</b> </label>
                                                    <div class="col-8">
                                                        <select name="allow_login" class="form-control" id="allow_login" data-next="username">
                                                            <option value="1">{{ __('Yes') }}</option>
                                                            <option {{ $user->allow_login == 0 ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="auth_fields_area" class="{{ $user->allow_login == 0 ? 'd-hide' : '' }}">
                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Username') }}</b> <span class="text-danger">*</span> </label>
                                                        <div class="col-8">
                                                            <input required {{ $user->username ? 'readonly' : '' }} type="text" name="username" id="username" class="form-control" data-next="role_id" value="{{ $user->username }}" placeholder="{{ __('Username') }}" autocomplete="off">
                                                            <span class="error error_username"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Role') }}</b> <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Superadmin And Admin has access to all Shop/Business.') }}" class="fas fa-info-circle tp"></i> </label>
                                                        <div class="col-8">
                                                            <select required name="role_id" id="role_id" class="form-control" data-next="password">
                                                                <option value="">{{ __('Select Role') }}</option>
                                                                @foreach ($roles as $role)
                                                                    @php
                                                                        $userRole = $user?->roles?->first();
                                                                        $userRoleId = $userRole?->id;
                                                                    @endphp

                                                                    @if ($role->name != 'superadmin')
                                                                        <option {{ $userRoleId == $role->id ? 'SELECTED' : '' }} data-role_name="{{ $role->name }}" value="{{ $role->id }}">{{ $role->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Password') }}</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="password" name="password" id="password" class="form-control" data-next="password_confirmation" placeholder="{{ __('Password') }}" autocomplete="off">
                                                            <span class="error error_password"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Confirm Password') }}</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" data-next="sales_commission_percent" placeholder="{{ __('Confirm Password') }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="heading_area">
                                        <p class="px-1 pt-1 pb-0 text-primary"><b>{{ __('Sales') }}</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-12"> <b>{{ __('Commission') }} (%)</b> </label>
                                                    <div class="col-sm-8 col-12">
                                                        <input type="number" name="sales_commission_percent" class="form-control" id="sales_commission_percent" data-next="max_sales_discount_percent" placeholder="{{ __('Commission (%)') }}" autocomplete="off" value="{{ $user->sales_commission_percent }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-12"><b>{{ __('Max Discount(%)') }}</b></label>
                                                    <div class="col-sm-8 col-12">
                                                        <input type="number" name="max_sales_discount_percent" class="form-control" id="max_sales_discount_percent" data-next="bank_ac_holder_name" placeholder="{{ __('Max Sales Discount Percent') }}" autocomplete="off" value="{{ $user->max_sales_discount_percent }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="heading_area">
                                        <p class="px-1 pt-1 pb-0 text-primary"><b>{{ __('Bank Details') }}</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Account Name') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="bank_ac_holder_name" class="form-control" id="bank_ac_holder_name" placeholder="{{ __('Account Name') }}" data-next="bank_ac_no" autocomplete="off" value="{{ $user->bank_ac_holder_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Account No') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="bank_ac_no" class="form-control" id="bank_ac_no" data-next="bank_name" placeholder="{{ __('Account Number') }}" autocomplete="off" value="{{ $user->bank_ac_no }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-2 pt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Bank Name') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="bank_name" class="form-control" id="bank_name" data-next="bank_identifier_code" placeholder="{{ __('Bank Name') }}" autocomplete="off" value="{{ $user->bank_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Bank Ddentifier Code') }}</b></label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="bank_identifier_code" class="form-control" id="bank_identifier_code" placeholder="{{ __('Bank Ddentifier Code') }}" data-next="bank_branch" autocomplete="off" value="{{ $user->bank_identifier_code }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-2 pt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Branch') }}</b></label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="bank_branch" class="form-control" id="bank_branch" placeholder="{{ __('Branch') }}" data-next="tax_payer_id" autocomplete="off" value="{{ $user->bank_branch }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Tax Payer ID') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="tax_payer_id" class="form-control" id="tax_payer_id" data-next="date_of_birth" placeholder="{{ __('Tax Payer ID') }}" autocomplete="off" value="{{ $user->tax_payer_id }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="heading_area">
                                        <p class="px-1 pt-1 pb-0 text-primary"><b>{{ __('More Information') }}</b> </p>
                                    </div>
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"> <b>{{ __('Profile image') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="file" name="photo" class="form-control" placeholder="{{ __('Profile image') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Date Of Birth') }}</b></label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="date_of_birth" class="form-control" id="date_of_birth" autocomplete="off" placeholder="{{ __('Date Of Birth') }}" data-next="gender" value="{{ $user->date_of_birth }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Gender') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <select name="gender" class="form-control" id="gender" data-next="marital_status">
                                                            <option value="">{{ __('Select Gender') }}</option>
                                                            <option {{ $user->gender == 'Male' ? 'SELECTED' : '' }} value="Male">{{ __('Male') }}</option>
                                                            <option {{ $user->gender == 'Female' ? 'SELECTED' : '' }} value="Female">{{ __('Female') }}</option>
                                                            <option {{ $user->gender == 'Others' ? 'SELECTED' : '' }} value="Others">{{ __('Others') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Marital Status') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <select name="marital_status" class="form-control" id="marital_status" data-next="blood_group">
                                                            <option value="">{{ __('Marital Status') }}</option>
                                                            <option {{ $user->marital_status == 'Married' ? 'SELECTED' : '' }} value="Married">{{ __('Married') }}</option>
                                                            <option {{ $user->marital_status == 'Unmarried' ? 'SELECTED' : '' }} value="Unmarried">{{ __('Unmarried') }}</option>
                                                            <option {{ $user->marital_status == 'Divorced' ? 'SELECTED' : '' }} value="Divorced">{{ __('Divorced') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Blood Group') }}</b></label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="blood_group" class="form-control" id="blood_group" placeholder="{{ __('Blood Group') }}" data-next="facebook_link" autocomplete="off" value="{{ $user->blood_group }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Facebook Link') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="facebook_link" class="form-control" id="facebook_link" autocomplete="off" placeholder="{{ __('Facebook Link') }}" data-next="twitter_link" value="{{ $user->facebook_link }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('X Link') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="twitter_link" class="form-control" id="twitter_link" autocomplete="off" placeholder="{{ __('X Link') }}" data-next="instagram_link" value="{{ $user->twitter_link }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('Instagram Link') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="instagram_link" class="form-control" id="instagram_link" autocomplete="off" placeholder="{{ __('Instagram Link') }}" data-next="id_proof_name" value="{{ $user->instagram_link }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('ID Proof Name') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="id_proof_name" class="form-control" id="id_proof_name" autocomplete="off" placeholder="{{ __('ID Proof Name') }}" data-next="id_proof_number" value="{{ $user->id_proof_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-5"><b>{{ __('ID Proof Number') }}</b> </label>
                                                    <div class="col-sm-8 col-7">
                                                        <input type="text" name="id_proof_number" class="form-control" id="id_proof_number" autocomplete="off" placeholder="{{ __('ID Proof Number') }}" data-next="guardian_name" value="{{ $user->id_proof_number }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-6 col-6"><b>{{ __('Guardian Name') }}</b> </label>
                                                    <div class="col-sm-6 col-6">
                                                        <input type="text" name="guardian_name" class="form-control" id="guardian_name" autocomplete="off" placeholder="{{ __('Guardian Name') }}" data-next="permanent_address" value="{{ $user->guardian_name }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-lg-3 col-4"><b>{{ __('Permanent Address') }}</b></label>
                                                    <div class="col-lg-9 col-8">
                                                        <input type="text" name="permanent_address" class="form-control" id="permanent_address" autocomplete="off" placeholder="{{ __('Permanent Address') }}" data-next="current_address" value="{{ $user->permanent_address }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-lg-3 col-4"><b>{{ __('Current Address') }}</b> </label>
                                                    <div class="col-lg-9 col-8">
                                                        <input type="text" name="current_address" class="form-control" id="current_address" placeholder="{{ __('Current Address') }}" data-next="emp_id" value="{{ $user->current_address }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($generalSettings['addons__hrm'] == 1)
                                    <div class="form_element rounded mt-0 mb-1">
                                        <div class="heading_area">
                                            <p class="px-1 pt-1 pb-0 text-primary"><b>{{ __('Human Resource Details') }}</b> </p>
                                        </div>

                                        <div class="element-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Employee ID') }}</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" class="form-control" name="emp_id" id="emp_id" placeholder="{{ __('Employee ID') }}" data-next="shift_id" value="{{ $user->emp_id }}">
                                                            <span class="error error_emp_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Shift') }}</b> </label>
                                                        <div class="col-8">
                                                            <select name="shift_id" class="form-control" id="shift_id" data-next="department_id">
                                                                @foreach ($shifts as $shift)
                                                                    <option {{ $user->shift_id == $shift->id ? 'SELECTED' : '' }} value="{{ $shift->id }}">{{ $shift->shift_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_shift_id"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Department') }}</b></label>
                                                        <div class="col-8">
                                                            <select name="department_id" class="form-control" id="department_id" data-next="designation_id">
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
                                                        <label class="col-4"><b>{{ __('Designation') }}</b> </label>
                                                        <div class="col-8">
                                                            <select name="designation_id" class="form-control" id="designation_id" data-next="salary">
                                                                <option value="">{{ __('Select Designation') }}</option>
                                                                @foreach ($designations as $designation)
                                                                    <option {{ $user->designation_id == $designation->id ? 'SELECTED' : '' }} value="{{ $designation->id }}">{{ $designation->designation_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Salary') }}</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="salary" class="form-control" id="salary" placeholder="{{ __('Salary Amount') }}" data-next="pay_type" autocomplete="off" value="{{ $user->salary }}">
                                                            <span class="error error_salary"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"> <b>{{ __('Pay Type') }}</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <select name="pay_type" class="form-control" id="pay_type" data-next="save_changes_btn">
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
                                @endif

                                <div class="col-12 d-flex justify-content-end">
                                    <div class="btn-loading">
                                        <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                                        <button type="button" id="save_changes_btn" class="btn btn-success submit_button">{{ __('Save Changes') }}</button>
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
    <script>
        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        var isAllowSubmit = true;
        $(document).on('click', '.submit_button', function() {

            var value = $(this).val();
            $('#action').val(value);

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

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
                    window.location = "{{ url()->previous() }}";
                    $('.loading_button').hide();
                    $('.error').html('');
                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }

                    toastr.error("{{ __('Please check again all form fields.') }}", "{{ __('Some thing went wrong.') }}");

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $(document).on('change', '#allow_login', function() {

            changeAllowLoginField();
        });

        function changeAllowLoginField() {

            $('#auth_fields_area').show();
            $('#role_id').prop('required', true);
            $('#username').prop('required', true);

            if ($('#allow_login').val() == 0) {

                $('#auth_fields_area').hide();
                $('#role_id').prop('required', false);
                $('#username').prop('required', false);
            }
        }

        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save_changes_btn').click();
                return false;
            }
        }

        $('select').on('select2:close', function(e) {

            var nextId = $(this).data('next');

            setTimeout(function() {

                $('#' + nextId).focus();
            }, 100);
        });

        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                if ($(this).attr('id') == 'allow_login' && $('#allow_login').val() == 0) {

                    $('#sales_commission_percent').focus().select();
                    return;
                }

                if (nextId == 'username' && $('#username').val()) {

                    $('#role_id').focus();
                    return;
                }

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                if (nextId == 'emp_id' && $('#emp_id').val() == undefined) {

                    $('#save_btn').focus();
                    return;
                }

                if (nextId == 'branch_id' && $('#branch_id').val() == undefined) {

                    $('#allow_login').focus();
                    return;
                }

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change', '#role_id', function(e) {
            var roleNeme = $(this).find(':selected').data('role_name');
            $('#branch_id').prop('required', true);
            if (roleName == 'admin') {

                $('#branch_id').prop('required', false);
            }
        });

        $('#prefix').focus().select();
    </script>
@endpush
