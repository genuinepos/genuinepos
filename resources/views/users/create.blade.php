@extends('layout.master')
@push('stylesheets')

@endpush
@section('content')
<div class="body-wraper">
    <div class="container-fluid">
        <div class="mt-5 pt-5">
            <form id="add_user_form" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
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
                                                    <input type="text" name="prefix" class="form-control" placeholder="Mr / Mrs / Miss" autofocus>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>First Name :</b><span class="text-danger">*</span> </label>

                                                <div class="col-8">
                                                    <input type="text" name="first_name" class="form-control" placeholder="First Name" id="first_name" required>
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
                                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.email') :</b> <span class="text-danger">*</span> </label>
                                                <div class="col-8">
                                                    <input type="text" name="email" id="email" class="form-control" placeholder="exmple@email.com" required>
                                                    <span class="error error_email"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"> <b>@lang('menu.phone') :</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input type="text" name="phone" class="form-control" autocomplete="off" placeholder="Phone number" required>
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
                                                <input type="checkbox" checked name="allow_login" id="allow_login">
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
                                                        <input type="text" name="username" id="username" class="form-control " placeholder="Username" autocomplete="off">
                                                        <span class="error error_username"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>Role :</b>  <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Admin has access to all branch." class="fas fa-info-circle tp"></i> </label>
                                                    <div class="col-8">
                                                        <select name="role_id" id="role_id" class="form-control"></select>
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
                                                            <option value="">{{ json_decode($generalSettings->business, true)['shop_name'] }} </option>
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
                                                    <label class="col-4"><span class="text-danger">*</span> <b>Belonging Location :</b> </label>
                                                    <div class="col-8">
                                                        <select name="belonging_branch_id" id="belonging_branch_id" class="form-control">
                                                            <option value="head_office">{{ json_decode($generalSettings->business, true)['shop_name'] }} </option>
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
                                                    <input type="number" name="sales_commission_percent" class="form-control" placeholder="Sales Commission Percentage (%)" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-12"><b>Max Discount(%) : </b> </label>
                                                <div class="col-sm-8 col-12">
                                                    <input type="number" name="max_sales_discount_percent" class="form-control" placeholder="Max sales discount percent" autocomplete="off">
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
                                                    <input type="text" name="date_of_birth" class="form-control" autocomplete="off" placeholder="Date of birth">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>Gender :</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <select name="gender" class="form-control">
                                                        <option value="">Select Gender</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                        <option value="Others">@lang('menu.others')</option>
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
                                                        <option value="Married">Married</option>
                                                        <option value="Unmarried">Unmarried</option>
                                                        <option value="Divorced">Divorced</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>Blood Group :</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="blood_group" class="form-control" placeholder="Blood group" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>Facebook Link :</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="facebook_link" class="form-control" autocomplete="off" placeholder="Facebook link">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>Twitter Link :</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="twitter_link" class="form-control" autocomplete="off" placeholder="Twitter link">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>Instagram Link :</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="instagram_link" class="form-control" autocomplete="off" placeholder="Instagram link">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>Guardian Name:</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="guardian_name" class="form-control" autocomplete="off" placeholder="Guardian name">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>ID Proof Name :</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="id_proof_name" class="form-control" autocomplete="off" placeholder="ID proof name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>ID Proof Number :</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="id_proof_number" class="form-control" autocomplete="off" placeholder="ID proof number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-12"><b>Permanent Address :</b> </label>
                                                <div class="col-sm-8 col-12">
                                                    <input type="text" name="permanent_address" class="form-control form-control-sm" autocomplete="off" placeholder="Permanent address">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-12"><b>Current Address :</b> </label>
                                                <div class="col-sm-8 col-12">
                                                    <input type="text" name="current_address" class="form-control form-control-sm" placeholder="Current address">
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
                                                    <input type="text" name="bank_ac_holder_name" class="form-control " placeholder="Account holder's name" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>Account No :</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_ac_no" class="form-control" placeholder="@lang('menu.account_number')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.bank_name'):</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_name" class="form-control" placeholder="@lang('menu.bank_name')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>Identifier Code :</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_identifier_code" class="form-control" placeholder="Bank identifier code" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>Branch :</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_branch" class="form-control" placeholder="Branch" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>Tax Payer ID :</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="tax_payer_id" class="form-control" placeholder="Tax payer ID" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <div class="btn-box">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-white"></i></button>
                                <button class="btn w-auto btn-success submit_button">@lang('menu.save')</button>
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
