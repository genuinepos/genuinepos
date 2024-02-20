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
@section('title', 'Update Profile or Change Password')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __("Change Password") }}</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <section class="p-1">
            <div class="form_element rounded mt-0 mb-1">
                <form id="reset_password_form" action="{{ route('password.updateCurrent') }}" method="post">
                    @csrf
                    <div class="element-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label class="col-sm-4"> <b>{{ __('Current Password') }}</b> <span class="text-danger">*</span></label>
                                    <div class="col-sm-8 col-8">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock input_i"></i></span>
                                            </div>
                                            <input type="password" name="current_password" class="form-control" autocomplete="off" placeholder="{{ __('Current Password') }}">
                                        </div>
                                        <span class="error error_password"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input-group">
                                    <label class="col-sm-4"><b>{{ __('New Password') }}</b> <span class="text-danger">*</span></label>
                                    <div class="col-sm-8 col-8">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock input_i"></i></span>
                                            </div>
                                            <input type="password" name="password" class="form-control" autocomplete="off" placeholder="{{ __('New Password') }}">
                                        </div>
                                        <span class="error error_password"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input-group">
                                    <label class="col-sm-4"><b>{{ __('Confirm Password') }}</b> <span class="text-danger">*</span></label>
                                    <div class="col-sm-8 col-8">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock input_i"></i></span>
                                            </div>
                                            <input type="password" name="password_confirmation" class="form-control" autocomplete="off" placeholder="{{ __('Confirm new password') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="button-area d-flex justify-content-end mt-1">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button change_password_loading_btn d-hide"><i class="fas fa-spinner"></i></button>
                                    <button class="btn btn-sm btn-success submit_button">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <form id="update_profile_form" action="{{ route('users.profile.update') }}" method="POST">
                @csrf
                <div class="row g-1">
                    <div class="col-md-6">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="heading_area">
                                <p class="px-1 pt-1 pb-0 text-primary"><b>{{ __('Update Profile') }}</b> </p>
                            </div>

                            <div class="element-body">
                                <div class="row gx-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Prefix') }}</b> </label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="prefix" class="form-control" placeholder="Mr / Mrs / Miss" value="{{ auth()->user()->prefix }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('First Name') }}</b> <span class="text-danger">*</span></label>

                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="first_name" class="form-control" placeholder="{{ __('First Name') }}" id="first_name" value="{{ auth()->user()->name }}">
                                                <span class="error error_first_name"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Last Name') }}</b> </label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="last_name" class="form-control" placeholder="{{ __('Last Name') }}" value="{{ auth()->user()->last_name }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <div class="row gx-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Email') }}</b> <span class="text-danger">*</span></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="email" id="email" class="form-control" placeholder="exmple@email.com" value="{{ auth()->user()->email }}">
                                                <span class="error error_email"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-6 col-5"><b>{{ __('Default Language') }}</b>
                                            </label>
                                            <div class="col-lg-6 col-7">
                                                <select name="language" class="form-control">
                                                    <option {{ auth()->user()->language == 'en' ? 'SELECTED' : '' }} value="en">English</option>
                                                    <option {{ auth()->user()->language == 'bn' ? 'SELECTED' : '' }} value="bn">Bangla</option>
                                                </select>
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
                                <div class="row gx-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Account Name') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="bank_ac_holder_name" class="form-control " placeholder="{{ __('Account Name') }}" autocomplete="off" value="{{ auth()->user()->bank_ac_holder_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Account No.') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="bank_ac_no" class="form-control" placeholder="{{ __('Account Number') }}" autocomplete="off" value="{{ auth()->user()->bank_ac_no }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Bank Name') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="bank_name" class="form-control" placeholder="{{ __('Bank Name') }}" autocomplete="off" value="{{ auth()->user()->bank_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Identifier Code') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="bank_identifier_code" class="form-control" placeholder="{{ __('Bank Identifier Code') }}" autocomplete="off" value="{{ auth()->user()->bank_identifier_code }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Branch') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="bank_branch" class="form-control" placeholder="{{ __('Branch') }}" autocomplete="off" value="{{ auth()->user()->bank_branch }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Tax Payer ID') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="tax_payer_id" class="form-control" placeholder="{{ __('Tax Payer ID') }}" autocomplete="off" value="{{ auth()->user()->tax_payer_id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="heading_area">
                                <p class="px-1 pt-1 pb-0 text-primary"><b>{{ __('More Information') }}</b></p>
                            </div>

                            <div class="element-body">
                                <div class="row gx-2">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"> <b>{{ __('Profile image') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="file" name="photo" class="form-control" autocomplete="off" placeholder="{{ __('Profile image') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Date Of Birth') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="date_of_birth" class="form-control" autocomplete="off" placeholder="{{ __('Date Of Birth') }}" value="{{ auth()->user()->date_of_birth }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Gender') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <select name="gender" class="form-control">
                                                    <option value="">{{ __('Select Gender') }}</option>
                                                    <option {{ auth()->user()->gender == 'Male' ? 'SELECTED' : '' }} value="Male">{{ __('Male') }}
                                                    </option>
                                                    <option {{ auth()->user()->gender == 'Female' ? 'SELECTED' : '' }} value="Female">{{ __('Female') }}</option>
                                                    <option {{ auth()->user()->gender == 'Others' ? 'SELECTED' : '' }} value="Others">{{ __('Others') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Marital Status') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <select name="marital_status" class="form-control">
                                                    <option value="">{{ __('Marital Status') }}</option>
                                                    <option {{ auth()->user()->marital_status == 'Married' ? 'SELECTED' : '' }} value="Married">{{ __('Married') }}</option>
                                                    <option {{ auth()->user()->marital_status == 'Unmarried' ? 'SELECTED' : '' }} value="Unmarried">{{ __('Unmarried') }}</option>
                                                    <option {{ auth()->user()->marital_status == 'Divorced' ? 'SELECTED' : '' }} value="Divorced">{{ __('Divorced') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Blood Group') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="blood_group" class="form-control" placeholder="{{ __('Blood Group') }}" autocomplete="off" value="{{ auth()->user()->blood_group }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Phone') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="phone" class="form-control" autocomplete="off" placeholder="{{ __('Phone Number') }}" value="{{ auth()->user()->phone }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Facebook Link') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="facebook_link" class="form-control" autocomplete="off" placeholder="{{ __('Facebook Link') }}" value="{{ auth()->user()->facebook_link }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('X Link') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="twitter_link" class="form-control" autocomplete="off" placeholder="{{ __('X Link') }}" value="{{ auth()->user()->twitter_link }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Instagram Link') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="instagram_link" class="form-control" autocomplete="off" placeholder="{{ __('Instagram Link') }}" value="{{ auth()->user()->instagram_link }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-4 col-5"><b>{{ __('Id Proof Name') }}</b></label>
                                            <div class="col-lg-8 col-7">
                                                <input type="text" name="id_proof_name" class="form-control" autocomplete="off" placeholder="{{ __('Id Proof Name') }}" value="{{ auth()->user()->id_proof_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-lg-6 col-5"><b>{{ __('Guardian Name') }}</b></label>
                                            <div class="col-lg-6 col-7">
                                                <input type="text" name="guardian_name" class="form-control" autocomplete="off" placeholder="{{ __('Guardian Name') }}" value="{{ auth()->user()->guardian_name }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label class="col-lg-3 col-5"><b>{{ __('ID Proof Number') }}</b></label>
                                            <div class="col-lg-9 col-7">
                                                <input type="text" name="id_proof_number" class="form-control" autocomplete="off" placeholder="@lang('menu.id_proof_number')" value="{{ auth()->user()->id_proof_number }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label class="col-lg-3 col-5"><b>{{ __('Permanent Address') }}</b></label>
                                            <div class="col-lg-9 col-7">
                                                <input type="text" name="permanent_address" class="form-control" autocomplete="off" placeholder="{{ __('Permanent Address') }}" value="{{ auth()->user()->permanent_address }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label class="col-lg-3 col-5"><b>{{ __('Current Address') }}</b></label>
                                            <div class="col-lg-9 col-7">
                                                <input type="text" name="current_address" class="form-control" placeholder="{{ __('Current Address') }}" value="{{auth()->user()->current_address }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="submit-area d-flex justify-content-end">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button update_profile_loading_btn d-hide"><i class="fas fa-spinner"></i></button>
                        <button class="btn btn-sm btn-success submit_button">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        // Add user by ajax
        $(document).on('submit', '#update_profile_form', function(e) {
            e.preventDefault();

            $('.update_profile_loading_btn').show();
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
                    $('.update_profile_loading_btn').hide();
                    $('.error').html('');
                }, error: function(err) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.update_profile_loading_btn').hide();

                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if(err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        // Change password form submit by ajax
        $(document).on('submit', '#reset_password_form', function(e) {
            e.preventDefault();

            $('.change_password_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.change_password_loading_btn').hide();
                    $('.error').html('');

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;

                    }

                    toastr.success(data.successMsg);
                    window.location = "{{ route('login') }}";
                }, error: function(err) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.change_password_loading_btn').hide();

                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if(err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });
    </script>
@endpush
