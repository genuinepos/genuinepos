@extends('layout.master')
@push('stylesheets')
    <style>
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-user-plus"></span>
                    <h5>Add User</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
            </div>
        </div>
        <div class="p-3">
            <form id="add_user_form" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-3">

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Prefix :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="prefix" class="form-control"
                                                placeholder="Mr / Mrs / Miss" autofocus>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><span class="text-danger">*
                                            </span><b>First Name :</b> </label>

                                        <div class="col-8">
                                            <input type="text" name="first_name" class="form-control"
                                                placeholder="First Name" id="first_name">
                                            <span class="error error_first_name"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Last Name :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="last_name" class="form-control"
                                                placeholder="Last Name">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><span
                                                class="text-danger">*</span> <b>Email :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="exmple@email.com">
                                            <span class="error error_email"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><b>Role Permission</b> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" checked name="allow_login" id="allow_login">
                                        <b>Allow login</b>
                                    </p>
                                </div>
                            </div>

                            <div class="auth_field_area">
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><span
                                                    class="text-danger">*</span> <b>Username :</b>  </label>
                                            <div class="col-8">
                                                <input type="text" name="username" id="username"
                                                    class="form-control " placeholder="Username" autocomplete="off">
                                                <span class="error error_username"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"> <span
                                                    class="text-danger">*</span> <b>Role :</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Admin has access to all branch." class="fas fa-info-circle tp"></i> </label>
                                            <div class="col-8">
                                                <select name="role_id" id="role_id" class="form-control">
                                                    <option value="">Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><span
                                                    class="text-danger">*</span> <b>Password :</b> </label>
                                            <div class="col-8">
                                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off">
                                                <span class="error error_password"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b><span
                                                class="text-danger">*</span> Confirm Pass : </b> </label>
                                            <div class="col-8">
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                    <div class="col-md-6 access_branch">
                                        <div class="input-group">
                                            <label class="col-4"><b>Access Location :</b> </label>
                                            <div class="col-8">
                                                <select name="branch_id" id="branch_id" class="form-control">
                                                    <option value="">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                    @foreach ($branches as $b)
                                                        <option value="{{ $b->id }}">{{$b->name.'/'.$b->branch_code}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_branch_id"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 belonging_branch d-none">
                                        <div class="input-group">
                                            <label class="col-4"><span
                                                    class="text-danger">*</span> <b>Belonging Location :</b> </label>
                                            <div class="col-8">
                                                <select name="belonging_branch_id" id="belonging_branch_id" class="form-control">
                                                    <option value="head_office">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                    @foreach ($branches as $b)
                                                    <option value="{{ $b->id }}">{{$b->name.'/'.$b->branch_code}}</option>
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
                            <p class="px-1 pt-1 pb-0 text-primary"><b>Sales</b> </p>
                        </div>

                        <div class="element-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"> <b>Commission (%) :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="sales_commission_percent"  class="form-control" placeholder="Sales Commission Percentage (%)" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Max Discount(%) : </b> </label>
                                        <div class="col-8">
                                            <input type="text" name="max_sales_discount_percent"  class="form-control" placeholder="Max sales discount percent" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="px-1 pt-1 pb-0 text-primary"><b>More Information</b> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"> <b>Date of birth :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="date_of_birth" class="form-control" autocomplete="off" placeholder="Date of birth">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Gender :</b> </label>
                                        <div class="col-8">
                                            <select name="gender" class="form-control">
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Marital Status :</b> </label>
                                        <div class="col-8">
                                            <select name="marital_status" class="form-control">
                                                <option value="">Marital Status</option>
                                                <option value="Married">Married</option>
                                                <option value="Unmarried">Unmarried</option>
                                                <option value="Divorced">Divorced</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Blood Group :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="blood_group"  class="form-control" placeholder="Blood group" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Phone :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="phone" class="form-control" autocomplete="off" placeholder="Phone number">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Facebook Link :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="facebook_link" class="form-control" autocomplete="off" placeholder="Facebook link">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Twitter Link :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="twitter_link" class="form-control" autocomplete="off" placeholder="Twitter link">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Instagram Link :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="instagram_link" class="form-control" autocomplete="off" placeholder="Instagram link">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Guardian Name:</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="guardian_name" class="form-control" autocomplete="off" placeholder="Guardian name">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>ID Proof Name :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="id_proof_name" class="form-control" autocomplete="off" placeholder="ID proof name">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>ID Proof Number :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="id_proof_number" class="form-control" autocomplete="off" placeholder="ID proof number">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label class="col-2"><b>Permanent Address :</b> </label>
                                        <div class="col-10">
                                            <input type="text" name="permanent_address" class="form-control form-control-sm" autocomplete="off" placeholder="Permanent address">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label class="col-2"><b>Current Address :</b> </label>
                                        <div class="col-10">
                                            <input type="text" name="current_address" class="form-control form-control-sm" placeholder="Current address">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="px-1 pt-1 pb-0 text-primary"><b>Bank Details</b> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Account Name :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="bank_ac_holder_name" class="form-control " placeholder="Account holder's name" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Account No :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="bank_ac_no" class="form-control" placeholder="Account number" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Bank Name :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="bank_name" class="form-control" placeholder="Bank name" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Identifier Code :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="bank_identifier_code" class="form-control" placeholder="Bank identifier code" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Branch :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="bank_branch" class="form-control" placeholder="Branch" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>Tax Payer ID :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="tax_payer_id" class="form-control" placeholder="Tax payer ID" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($addons->hrm == 1)
                        <div class="form_element rounded mt-0 mb-3">
                            <div class="heading_area">
                                <p class="px-1 pt-1 pb-0 text-primary"><strong>Human Resource Details</strong> </p>
                            </div>

                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>Employee ID :</b> </label>
                                            <div class="col-8">
                                                <input type="text" class="form-control" name="emp_id" placeholder="Employee ID">
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
                                                        <option value="{{ $shift->id }}">{{ $shift->shift_name }}</option>
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
                                                        <option value="{{ $department->id }}">{{ $department->department_name }}</option>
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
                                                        <option value="{{ $designation->id }}">{{ $designation->designation_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"> <b>Salary :</b> </label>
                                            <div class="col-8">
                                                <input type="number" step="any" name="salary" id="salary" class="form-control" placeholder="Salary Amount" autocomplete="off">
                                                <span class="error error_salary"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"> <b>Pay Type :</b> </label>
                                            <div class="col-8">
                                                <select name="pay_type" class="form-control" id="pay_type">
                                                    <option value="">Select Pay type</option>
                                                    <option value="Monthly">Monthly</option>
                                                    <option value="Yearly">Yearly</option>
                                                    <option value="Daliy">Daliy</option>
                                                    <option value="Hourly">Hourly</option>
                                                </select>
                                                <span class="error error_pay_type"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="submit-area d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner"></i></button>
                            <button class="btn btn-sm btn-success submit_button">Save</button>
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
    function setRoles() {
        $.ajax({
            url: "{{ route('users.all.roles') }}"
            , success: function(roles) {
                $.each(roles, function(key, val) {
                    $('#role_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                });
            }
        });
    }
    setRoles();

    // Add user by ajax
    $(document).on('submit', '#add_user_form', function(e) {
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

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

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
