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

        .input-group-text {
            font-size: 12px !important;
        }

        .dropify-wrapper {
            height: 100px !important;
        }
    </style>
    <link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('title', 'Add User - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-6">
                    <h6>{{ __('Add User') }}
                        <span style="font-size: 12px;">({{ __('User Limit') }}
                            : <span class="text-danger" id="current_user_count"> --- </span>/{{ $generalSettings['subscription']->features['user_count'] }})
                        </span> |
                        <span style="font-size: 12px;">({{ __('Employee Limit') }}
                            : <span class="text-danger" id="current_employee_count"> --- </span>/{{ $generalSettings['subscription']->features['employee_count'] }})
                        </span>
                    </h6>
                </div>

                <div class="col-md-6">

                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                        <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="p-2">
            <form id="add_user_form" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section>
                    <div class="row g-2">
                        <div class="col-md-8">
                            <div class="form_element rounded mt-0 mb-1">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>{{ __('User Information') }}</b></p>
                                </div>
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Prefix') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="prefix" class="form-control" id="prefix" data-next="first_name" placeholder="{{ __('Mr / Mrs / Miss') }}" autofocus>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('First Name') }}</b> <span class="text-danger">*</span></label>

                                                <div class="col-8">
                                                    <input required type="text" name="first_name" class="form-control" id="first_name" data-next="last_name" placeholder="{{ __('First Name') }}">
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
                                                    <input type="text" name="last_name" class="form-control" id="last_name" data-next="email" placeholder="{{ __('Last Name') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Email') }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="text" name="email" id="email" class="form-control" data-next="phone" id="email" placeholder="exmple@email.com">
                                                    <span class="error error_email"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Phone') }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="text" name="phone" class="form-control" id="phone" data-next="branch_id" placeholder="{{ __('Phone Number') }}" value="" autocomplete="off">
                                                    <span class="error error_phone"></span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                        @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ location_label() }}</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <input type="hidden" name="branch_count" value="YES">
                                                        <select required name="branch_id" class="form-control" id="branch_id" data-next="user_type">
                                                            <option value="">{{ __('Select Store/Company') }}</option>
                                                            <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
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

                                    @if ($generalSettings['subscription']->features['hrm'] == 1)
                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Type') }}</b></label>
                                                    <div class="col-8">
                                                        <select name="user_type" class="form-control" id="user_type" data-next="allow_login">
                                                            @foreach (\App\Enums\UserType::cases() as $userType)
                                                                <option value="{{ $userType->value }}">{{ $userType->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1 role_permission_area">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 text-primary"><b>{{ __('Role Permission') }}</b></p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Allow Login') }}</b> </label>
                                                <div class="col-8">
                                                    <select name="allow_login" id="allow_login" class="form-control" data-next="username">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="auth_fields_area">
                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Username') }}</b> <span class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <input required type="text" name="username" id="username" class="form-control" data-next="role_id" placeholder="{{ __('Username') }}" autocomplete="off">
                                                        <span class="error error_username"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <small style="font-size: 9px;line-height:1.2;" class="float-end fw-bold" id="roleMsg"></small>
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Role') }}</b> <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" class="fas fa-info-circle tp"></i> </label>
                                                    <div class="col-8">
                                                        <select required name="role_id" id="role_id" class="form-control" data-next="password">
                                                            <option value="">{{ __('Select Role') }}</option>
                                                            @foreach ($roles as $role)
                                                                @if ($role->name != 'superadmin')
                                                                    <option data-has_accass_to_all_area="{{ $role->hasPermissionTo('has_access_to_all_area') }}" value="{{ $role->id }}">{{ $role->name }}</option>
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
                                                        <input required type="password" name="password" id="password" class="form-control" data-next="password_confirmation" placeholder="{{ __('Password') }}" autocomplete="off">
                                                        <span class="error error_password"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Confirm Password') }}</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <input required type="password" name="password_confirmation" class="form-control" id="password_confirmation" data-next="sales_commission_percent" placeholder="{{ __('Confirm Password') }}" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>{{ __('Sales') }}</b></p>
                                </div>

                                <div class="element-body">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Commission') }}</b></label>
                                                <div class="col-8">
                                                    <input type="number" name="sales_commission_percent" class="form-control" id="sales_commission_percent" data-next="max_sales_discount_percent" placeholder="{{ __('Commission (%)') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-6"><b>{{ __('Max Discount(%)') }}</b></label>
                                                <div class="col-6">
                                                    <input type="number" name="max_sales_discount_percent" class="form-control" id="max_sales_discount_percent" data-next="bank_ac_holder_name" placeholder="{{ __('Max Sales Discount Percent') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>{{ __('Bank Details') }}</b></p>
                                </div>
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Account Name') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_ac_holder_name" class="form-control" id="bank_ac_holder_name" data-next="bank_ac_no" placeholder="{{ __('Account Name') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Account No') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_ac_no" class="form-control" id="bank_ac_no" data-next="bank_name" placeholder="{{ __('Account Number') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Bank Name') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_name" class="form-control" id="bank_name" data-next="bank_identifier_code" placeholder="{{ __('Bank Name') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Bank Ddentifier Code') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_identifier_code" class="form-control" id="bank_identifier_code" data-next="bank_branch" placeholder="{{ __('Bank Identifier Code') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Branch') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_branch" class="form-control" id="bank_branch" data-next="tax_payer_id" placeholder="{{ __('Branch') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Tax Payer ID') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="tax_payer_id" class="form-control" id="tax_payer_id" data-next="date_of_birth" placeholder="{{ __('Tax Payer ID') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>{{ __('More Information') }}</b></p>
                                </div>
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"> <b>{{ __('Date Of Birth') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="date_of_birth" class="form-control" id="date_of_birth" data-next="facebook_link" autocomplete="off" placeholder="{{ __('Date Of Birth') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Facebook Link') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="facebook_link" class="form-control" id="facebook_link" data-next="gender" autocomplete="off" placeholder="{{ __('Facebook Link') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Gender') }}</b></label>
                                                <div class="col-8">
                                                    <select name="gender" class="form-control" id="gender" data-next="twitter_link">
                                                        <option value="">{{ __('Select Gender') }}</option>
                                                        <option value="Male">{{ __('Male') }}</option>
                                                        <option value="Female">{{ __('Female') }}</option>
                                                        <option value="Others">{{ __('Others') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('X Link') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="twitter_link" class="form-control" id="twitter_link" data-next="blood_group" autocomplete="off" placeholder="{{ __('X Link') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Blood Group') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="blood_group" class="form-control" id="blood_group" data-next="instagram_link" placeholder="{{ __('Blood Group') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Instagram Link') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="instagram_link" class="form-control" id="instagram_link" data-next="marital_status" autocomplete="off" placeholder="{{ __('Instagram Link') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Marital Status') }}</b></label>
                                                <div class="col-8">
                                                    <select name="marital_status" class="form-control" id="marital_status" data-next="guardian_name">
                                                        <option value="">{{ __('Marital Status') }}</option>
                                                        <option value="Married">{{ __('Married') }}</option>
                                                        <option value="Unmarried">{{ __('Unmarried') }}</option>
                                                        <option value="Divorced">{{ __('Divorced') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Guardian Name') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="guardian_name" class="form-control" id="guardian_name" data-next="id_proof_name" autocomplete="off" placeholder="{{ __('Guardian Name') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('ID Proof Name') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="id_proof_name" class="form-control" id="id_proof_name" data-next="id_proof_number" autocomplete="off" placeholder="{{ __('ID Proof Name') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('ID Proof Number') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="id_proof_number" class="form-control" id="id_proof_number" data-next="permanent_address" autocomplete="off" placeholder="{{ __('ID Proof Number') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group ">
                                                <label class="col-lg-3 col-4"><b>{{ __('Permanent Address') }}</b></label>
                                                <div class="col-lg-9 col-8">
                                                    <input type="text" name="permanent_address" class="form-control" id="permanent_address" data-next="current_address" autocomplete="off" placeholder="{{ __('Permanent Address') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-lg-3 col-4"><b>{{ __('Current Address') }}</b> </label>
                                                <div class="col-lg-9 col-8">
                                                    <input type="text" name="current_address" class="form-control" id="current_address" data-next="emp_id" placeholder="{{ __('Current Address') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-lg-3 col-4"><b>{{ __('Profile Photo') }}</b> </label>
                                                <div class="col-lg-9 col-8">
                                                    <input type="file" name="photo" class="form-control dropify" id="photo" data-allowed-file-extensions="png jpeg jpg gif">
                                                    <span class="error error_photo"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($generalSettings['subscription']->features['hrm'] == 1)
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="heading_area">
                                        <p class="px-1 pt-1 pb-0 text-primary"><strong>{{ __('Human Resource Details') }}</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Employee ID') }}</b></label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control" name="emp_id" id="emp_id" data-next="shift_id" placeholder="{{ __('Employee ID') }}">
                                                        <span class="error error_emp_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Shift') }}</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select name="shift_id" class="form-control" id="shift_id" data-next="department_id">
                                                                @foreach ($shifts as $shift)
                                                                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                                                @endforeach
                                                            </select>

                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text {{ !auth()->user()->can('shifts_create') ? 'disabled_element' : '' }} add_button" id="{{ auth()->user()->can('shifts_create') ? 'addShift' : '' }}"><i class="fas fa-plus-square text-dark"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-2 pt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Department') }}</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select name="department_id" class="form-control select2" id="department_id" data-next="designation_id">
                                                                <option value="">{{ __('Select Department') }}</option>
                                                                @foreach ($departments as $department)
                                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                                @endforeach
                                                            </select>

                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text {{ !auth()->user()->can('departments_create') ? 'disabled_element' : '' }} add_button" id="{{ auth()->user()->can('departments_create') ? 'addDepartment' : '' }}"><i class="fas fa-plus-square text-dark"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Designation') }}</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select name="designation_id" class="form-control select2" id="designation_id" data-next="salary">
                                                                <option value="">{{ __('Select Designation') }}</option>
                                                                @foreach ($designations as $designation)
                                                                    <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                                @endforeach
                                                            </select>

                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text {{ !auth()->user()->can('designations_create') ? 'disabled_element' : '' }} add_button" id="{{ auth()->user()->can('designations_create') ? 'addDesignation' : '' }}"><i class="fas fa-plus-square text-dark"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-2 pt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>{{ __('Salary') }}</b></label>
                                                    <div class="col-8">
                                                        <input type="number" step="any" name="salary" id="salary" class="form-control" data-next="pay_type" placeholder="{{ __('Salary Amount') }}" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>{{ __('Pay Type') }}</b></label>
                                                    <div class="col-8">
                                                        <select name="pay_type" class="form-control" id="pay_type" data-next="save_btn">
                                                            <option value="">{{ __('Select Pay type') }}</option>
                                                            <option value="Monthly">{{ __('Monthly') }}</option>
                                                            <option value="Yearly">{{ __('Yearly') }}</option>
                                                            <option value="Daliy">{{ __('Daily') }}</option>
                                                            <option value="Hourly">{{ __('Hourly') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="submit-area d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                                    <button type="button" id="save_btn" class="btn btn-success px-3 submit_button">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <div class="modal fade" id="shiftAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

    <div class="modal fade" id="departmentAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

    <div class="modal fade" id="designationAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>
@endsection
@push('scripts')
    @include('users.partials.js_partials.user_add_js')
@endpush
