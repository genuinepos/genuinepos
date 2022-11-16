@extends('layout.master')
@push('stylesheets')

@endpush
@section('content')
<div class="body-wraper">
    <div class="sec-name">
        <h6>Change Password</h6>
        <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i><br> @lang('menu.back')</a>
    </div>
    <div class="container-fluid p-0">
        <section class="p-15">
            <div class="row g-1">
                <div class="col-12">
                    <div class="form_element rounded">

                        <form id="reset_password_form" action="{{ route('password.updateCurrent') }}" method="post">
                            @csrf
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-md-2 col-12"><b>Current Password :</b> <span class="text-danger">*</span> </label>
                                            <div class="col-md-10 col-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock input_i pb-1"></i></span>
                                                    </div>
                                                    <input type="password" name="current_password" class="form-control" autocomplete="off" placeholder="Current password">
                                                </div>
                                                <span class="error error_password"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-md-2 col-12"><b>New Password :</b> <span class="text-danger">*</span> </label>
                                            <div class="col-md-10 col-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock input_i pb-1"></i></span>
                                                    </div>
                                                    <input type="password" name="password" class="form-control" autocomplete="off" placeholder="New password">
                                                </div>
                                                <span class="error error_password"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-md-2 col-12"><b>Confirm password :</b> <span class="text-danger">*</span> </label>
                                            <div class="col-md-10 col-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock input_i pb-1"></i></span>
                                                    </div>
                                                    <input type="password" name="password_confirmation" class="form-control" autocomplete="off" placeholder="Confirm new password">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="button-area d-flex justify-content-end mt-2">
                                    <div class="btn-box">
                                        <button type="button" class="btn btn-sm loading_button d-none"><i class="fas fa-spinner"></i><b> @lang('menu.loading')</b></button>
                                        <button class="btn w-auto btn-sm btn-success submit_button float-end">@lang('menu.save')</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <form class="row g-1" id="update_profile_form" action="{{ route('users.profile.update') }}" method="POST">
                    @csrf
                    <div class="col-12">
                        <div class="form_element rounded">
                            <div class="heading_area">
                                <p class="px-1 pt-1 pb-0 text-primary"><b>Update Profile</b> </p>
                            </div>

                            <div class="element-body">
                                <div class="row g-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"><b>@lang('menu.prefix') :</b> </label>
                                            <div class="col-8">
                                                <input type="text" name="prefix" class="form-control" placeholder="Mr / Mrs / Miss" value="{{ auth()->user()->prefix }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"><b>First Name :</b> <span class="text-danger">*</span> </label>

                                            <div class="col-8">
                                                <input type="text" name="first_name" class="form-control" placeholder="First Name" id="first_name" value="{{ auth()->user()->name }}">
                                                <span class="error error_first_name"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-1 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"><b>Last Name :</b> </label>
                                            <div class="col-8">
                                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ auth()->user()->last_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"><b>@lang('menu.email') :</b> <span class="text-danger">*</span> </label>
                                            <div class="col-8">
                                                <input type="text" name="email" id="email" class="form-control" placeholder="exmple@email.com" value="{{ auth()->user()->email }}">
                                                <span class="error error_email"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-1 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-12"><b>Default Language :</b>
                                            </label>
                                            <div class="col-sm-8 col-12">
                                                <select name="language" class="form-control">
                                                    <option {{ auth()->user()->language == 'en' ? 'SELECTED' : '' }} value="en">
                                                        English</option>
                                                    <option {{ auth()->user()->language == 'bn' ? 'SELECTED' : '' }} value="bn">
                                                        Bangla</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form_element rounded">
                            <div class="heading_area">
                                <p class="px-1 pt-1 pb-0 text-primary"><b>More Information</b> </p>
                            </div>

                            <div class="element-body">
                                <div class="row g-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"> <b>@lang('menu.date_of_birth') :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="date_of_birth" class="form-control" autocomplete="off" placeholder="Date of birth" value="{{ auth()->user()->date_of_birth }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Gender :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <select name="gender" class="form-control">
                                                    <option value="">Select Gender</option>
                                                    <option {{ auth()->user()->gender == 'Male' ? 'SELECTED' : '' }} value="Male">Male
                                                    </option>
                                                    <option {{ auth()->user()->gender == 'Female' ? 'SELECTED' : '' }} value="Female">
                                                        Female</option>
                                                    <option {{ auth()->user()->gender == 'Others' ? 'SELECTED' : '' }} value="Others">
                                                        Others</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-1 mt-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Marital Status :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <select name="marital_status" class="form-control">
                                                    <option value="">Marital Status</option>
                                                    <option {{ auth()->user()->marital_status == 'Married' ? 'SELECTED' : '' }} value="Married">Married</option>
                                                    <option {{ auth()->user()->marital_status == 'Unmarried' ? 'SELECTED' : '' }} value="Unmarried">Unmarried</option>
                                                    <option {{ auth()->user()->marital_status == 'Divorced' ? 'SELECTED' : '' }} value="Divorced">Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Blood Group :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="blood_group" class="form-control" placeholder="Blood group" autocomplete="off" value="{{ auth()->user()->blood_group }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-1 mt-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>@lang('menu.phone') :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="phone" class="form-control" autocomplete="off" placeholder="Phone number" value="{{ auth()->user()->phone }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Facebook Link :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="facebook_link" class="form-control" autocomplete="off" placeholder="Facebook link" value="{{ auth()->user()->facebook_link }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-1 mt-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Twitter Link :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="twitter_link" class="form-control" autocomplete="off" placeholder="Twitter link" value="{{ auth()->user()->twitter_link }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Instagram Link :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="instagram_link" class="form-control" autocomplete="off" placeholder="Instagram link" value="{{ auth()->user()->instagram_link }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-1 mt-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Guardian Name:</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="guardian_name" class="form-control" autocomplete="off" placeholder="Guardian name" value="{{ auth()->user()->guardian_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>ID Proof Name :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="id_proof_name" class="form-control" autocomplete="off" placeholder="ID proof name" value="{{ auth()->user()->id_proof_name }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-1 mt-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>ID Proof Number :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="id_proof_number" class="form-control" autocomplete="off" placeholder="ID proof number" value="{{ auth()->user()->id_proof_number }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-1 mt-2">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-2 col-12"><b>Permanent Address :</b>
                                            </label>
                                            <div class="col-sm-10 col-12">
                                                <input type="text" name="permanent_address" class="form-control form-control-sm" autocomplete="off" placeholder="Permanent address" {{ auth()->user()->permanent_address }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-1 mt-2">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-2 col-12"><b>Current Address :</b> </label>
                                            <div class="col-sm-10 col-12">
                                                <input type="text" name="current_address" class="form-control form-control-sm" placeholder="Current address" {{ auth()->user()->current_address }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form_element rounded">
                            <div class="heading_area">
                                <p class="px-1 pt-1 pb-0 text-primary"><b>Bank Details</b> </p>
                            </div>

                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Account Name :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="bank_ac_holder_name" class="form-control " placeholder="Account holder's name" autocomplete="off" value="{{ auth()->user()->bank_ac_holder_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Account No :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="bank_ac_no" class="form-control" placeholder="@lang('menu.account_number')" autocomplete="off" value="{{ auth()->user()->bank_ac_no }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>@lang('menu.bank_name') :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="bank_name" class="form-control" placeholder="@lang('menu.bank_name')" autocomplete="off" value="{{ auth()->user()->bank_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Identifier Code :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="bank_identifier_code" class="form-control" placeholder="Bank identifier code" autocomplete="off" value="{{ auth()->user()->bank_identifier_code }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Branch :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="bank_branch" class="form-control" placeholder="Branch" autocomplete="off" value="{{ auth()->user()->bank_branch }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-sm-4 col-5"><b>Tax Payer ID :</b> </label>
                                            <div class="col-sm-8 col-7">
                                                <input type="text" name="tax_payer_id" class="form-control" placeholder="Tax payer ID" autocomplete="off" value="{{ auth()->user()->tax_payer_id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="submit-area d-flex justify-content-end pt-3">
                            <div class="btn-box">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner"></i></button>
                                <button class="btn w-auto btn-success submit_button float-end">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Add user by ajax
    $(document).on('submit', '#update_profile_form', function(e) {
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
                window.location = "{{ route('dashboard.dashboard') }}";
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

    // Change password form submit by ajax
    $(document).on('submit', '#reset_password_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url
            , type: 'post'
            , data: request
            , success: function(data) {
                $('.loading_button').hide();
                if ($.isEmptyObject(data.errorMsg)) {
                    toastr.success(data.successMsg);
                    // window.location = "{{ route('login') }}";
                } else {
                    toastr.error(data.errorMsg);
                    $('.error').html('');
                    $('.form-control').removeClass('is-invalid');
                }
            }
            , error: function(err) {
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.'
                    , 'Some thing went wrong.');
                $('.error').html('');
                $('.form-control').removeClass('is-invalid');
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                    $('#' + key).addClass('is-invalid');
                });
            }
        });
    });

</script>
@endpush
