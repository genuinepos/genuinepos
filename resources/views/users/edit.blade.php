@extends('layout.master')
@push('stylesheets')

@endpush
@section('content')
<div class="body-wraper">
    <div class="sec-name">
        <h6>Edit User</h6>
        <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i><br> @lang('menu.back')</a>
    </div>
    <div class="container-fluid p-0">
        <form id="update_user_form" action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            <section class="p-15">
                <div class="row g-1">
                    <div class="col-12">
                        <div class="form_element rounded m-0">

                            <div class="element-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.prefix') :</b> </label>
                                            <div class="col-8">
                                                <input type="text" name="prefix" class="form-control" placeholder="Mr / Mrs / Miss" value="{{ $user->prefix }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>First Name :</b> <span class="text-danger">*</span></label>

                                            <div class="col-8">
                                                <input type="text" name="first_name" class="form-control" placeholder="First Name" id="first_name" value="{{ $user->name }}" required>
                                                <span class="error error_first_name"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>Last Name :</b> </label>
                                            <div class="col-8">
                                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ $user->last_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.email') :</b> <span class="text-danger">*</span> </label>
                                            <div class="col-8">
                                                <input required type="text" name="email" id="email" class="form-control" placeholder="exmple@email.com" value="{{ $user->email }}">
                                                <span class="error error_email"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.phone') :</b> <span class="text-danger">*</span> </label>
                                            <div class="col-8">
                                                <input required type="text" name="phone" class="form-control" placeholder="Phone Number" value="{{ $user->phone }}" autocomplete="off">
                                                <span class="error error_phone"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form_element rounded m-0">
                            <div class="heading_area">
                                <p class="p-1 text-primary"><b>Role Permission</b> </p>
                            </div>

                            <div class="element-body">
                                <div class="row g-2">
                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $user->allow_login == 1 ? 'CHECKED' : '' }} name="allow_login" id="allow_login">
                                            <b>Allow login</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="auth_field_area">
                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>Username :</b> <span class="text-danger">*</span> </label>
                                                <div class="col-8">
                                                    <input {{ $user->username ? 'readonly' : '' }} type="text" name="username" id="username" class="form-control " placeholder="Username" autocomplete="off" value="{{ $user->username }}">
                                                    <span class="error error_username"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"> <b>Role :</b> <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Admin has access to all business location." class="fas fa-info-circle tp"></i></label>
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

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>Password :</b> <span class="text-danger">*</span> </label>
                                                <div class="col-8">
                                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off">
                                                    <span class="error error_password"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-12"><b>Confirm Password : </b> <span class="text-danger">*</span> </label>
                                                <div class="col-sm-8 col-12">
                                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row g-2 mt-2">
                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                        <div class="col-md-6 access_branch">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-12"><b>Access Location :</b> </label>
                                                <div class="col-sm-8 col-12">
                                                    <select name="branch_id" id="branch_id" class="form-control">
                                                        <option value="">Select Business Location</option>
                                                        <option {{ $user->branch_id == NULL ? 'SELECTED' : '' }} value="head_office">{{ json_decode($generalSettings->business, true)['shop_name'] }} </option>
                                                        @foreach ($branches as $branch)
                                                            <option {{ $user->branch_id == $branch->id ? 'SELECTED' : '' }} value="{{ $branch->id }}">{{ $branch->name.' - '.$branch->branch_code }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_branch_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 belonging_branch d-hide">
                                            <div class="input-group">
                                                <label class="col-4"><b>Belonging Location :</b> <span class="text-danger">*</span> </label>
                                                <div class="col-8">
                                                    <select name="belonging_branch_id" id="belonging_branch_id" class="form-control">
                                                        <option value="head_office">{{ json_decode($generalSettings->business, true)['shop_name'] }} </option>
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
                    </div>

                    <div class="col-12">
                        <div class="form_element rounded m-0">
                            <div class="heading_area">
                                <p class="px-1 pt-1 pb-0 text-primary"><b>Sales</b> </p>
                            </div>

                            <div class="element-body">

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-12"> <b>Commission (%) :</b> </label>
                                            <div class="col-sm-8 col-12">
                                                <input type="text" name="sales_commission_percent" class="form-control" placeholder="Sales Commission Percentage (%)" autocomplete="off" value="{{ $user->sales_commission_percent }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-12"><b>Max Discount(%) : </b> </label>
                                            <div class="col-sm-8 col-12">
                                                <input type="text" name="max_sales_discount_percent" class="form-control" placeholder="Max sales discount percent" autocomplete="off" value="{{ $user->max_sales_discount_percent }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form_element rounded m-0">
                            <div class="heading_area">
                                <p class="px-1 pt-1 pb-0 text-primary"><b>More Information</b> </p>
                            </div>

                            <div class="element-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"> <b>@lang('menu.date_of_birth') :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="date_of_birth" class="form-control" autocomplete="off" placeholder="Date of birth" value="{{ $user->date_of_birth }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Gender :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <select name="gender" class="form-control">
                                                    <option value="">Select Gender</option>
                                                    <option {{ $user->gender == 'Male' ? 'SELECTED' : '' }} value="Male">Male</option>
                                                    <option {{ $user->gender == 'Female' ? 'SELECTED' : '' }} value="Female">Female</option>
                                                    <option {{ $user->gender == 'Others' ? 'SELECTED' : '' }} value="Others">@lang('menu.others')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Marital Status :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <select name="marital_status" class="form-control">
                                                    <option value="">Marital Status</option>
                                                    <option {{ $user->marital_status == 'Married' ? 'SELECTED' : '' }} value="Married">Married</option>
                                                    <option {{ $user->marital_status == 'Unmarried' ? 'SELECTED' : '' }} value="Unmarried">Unmarried</option>
                                                    <option {{ $user->marital_status == 'Divorced' ? 'SELECTED' : '' }} value="Divorced">Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Blood Group :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="blood_group" class="form-control" placeholder="Blood group" autocomplete="off" value="{{ $user->blood_group }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Twitter Link :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="twitter_link" class="form-control" autocomplete="off" placeholder="Twitter link" value="{{ $user->twitter_link }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Instagram Link :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="instagram_link" class="form-control" autocomplete="off" placeholder="Instagram link" value="{{ $user->instagram_link }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Guardian Name:</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="guardian_name" class="form-control" autocomplete="off" placeholder="Guardian name" value="{{ $user->guardian_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>ID Proof Name :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="id_proof_name" class="form-control" autocomplete="off" placeholder="ID proof name" value="{{ $user->id_proof_name }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Facebook Link :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="facebook_link" class="form-control" autocomplete="off" placeholder="Facebook link" value="{{$user->facebook_link }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>ID Proof Number :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="id_proof_number" class="form-control" autocomplete="off" placeholder="ID proof number" value="{{ $user->id_proof_number }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-2">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-12"><b>Permanent Address :</b> </label>
                                            <div class="col-sm-8 col-12">
                                                <input type="text" name="permanent_address" class="form-control form-control-sm" autocomplete="off" placeholder="Permanent address" value="{{ $user->permanent_address }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-2">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-12"><b>Current Address :</b> </label>
                                            <div class="col-sm-8 col-12">
                                                <input type="text" name="current_address" class="form-control form-control-sm" placeholder="Current address" value="{{ $user->current_address }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form_element rounded m-0">
                            <div class="heading_area">
                                <p class="px-1 pt-1 pb-0 text-primary"><b>Bank Details</b> </p>
                            </div>

                            <div class="element-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Account Name :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="bank_ac_holder_name" class="form-control " placeholder="Account holder's name" autocomplete="off" value="{{ $user->bank_ac_holder_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Account No :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="bank_ac_no" class="form-control" placeholder="@lang('menu.account_number')" autocomplete="off" value="{{ $user->bank_ac_no }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>@lang('menu.bank_name'):</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="bank_name" class="form-control" placeholder="@lang('menu.bank_name')" autocomplete="off" value="{{ $user->bank_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Identifier Code :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="bank_identifier_code" class="form-control" placeholder="Bank identifier code" autocomplete="off" value="{{ $user->bank_identifier_code }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Branch :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="bank_branch" class="form-control" placeholder="Branch" autocomplete="off" value="{{ $user->bank_branch }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-5"><b>Tax Payer ID :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="tax_payer_id" class="form-control" placeholder="Tax payer ID" autocomplete="off" value="{{ $user->tax_payer_id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if ($addons->hrm == 1)
                        <div class="col-md-8">
                            <div class="form_element m-0 mt-2">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>Human Resource Details</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>Employee ID :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" name="emp_id" placeholder="Employee ID" value="{{ $user->emp_id }}">
                                                    <span class="error error_emp_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>Shift :</b> </label>
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

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>Department :</b> </label>
                                                <div class="col-8">
                                                    <select name="department_id" class="form-control">
                                                        <option value="">Select Department</option>
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
                                                <label class="col-4"><b>Designation :</b> </label>
                                                <div class="col-8">
                                                    <select name="designation_id" class="form-control">
                                                        <option value="">Select Designation</option>
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
                                                <label class="col-4"><span class="text-danger">*</span> <b>Salary :</b> </label>
                                                <div class="col-8">
                                                    <input type="number" step="any" name="salary" id="salary" class="form-control" placeholder="Salary Amount" autocomplete="off" value="{{ $user->salary }}">
                                                    <span class="error error_salary"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><span class="text-danger">*</span> <b>Pay Type :</b> </label>
                                                <div class="col-8">
                                                    <select name="pay_type" class="form-control" id="pay_type">
                                                        <option value="">Select Pay type</option>
                                                        <option {{ $user->salary_type == 'Monthly' ? 'SELECTED' : '' }} value="Monthly">Monthly</option>
                                                        <option {{ $user->salary_type == 'Yearly' ? 'SELECTED' : '' }} value="Yearly">Yearly</option>
                                                        <option {{ $user->salary_type == 'Daliy' ? 'SELECTED' : '' }} value="Daliy">Daliy</option>
                                                        <option {{ $user->salary_type == 'Hourly' ? 'SELECTED' : '' }} value="Hourly">Hourly</option>
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
                        
                    <div class="col-12 d-flex justify-content-end">
                        <div class="btn-box">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                            <button class="btn w-auto btn-success submit_button float-end">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </section>
        </form>
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
        var request = $(this).serialize();
        $.ajax({
            url: url
            , type: 'post'
            , data: request
            , success: function(data) {
                toastr.success(data);
                $('.loading_button').hide();
                window.location = "{{ route('users.index') }}";
            }
            , error: function(err) {
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
