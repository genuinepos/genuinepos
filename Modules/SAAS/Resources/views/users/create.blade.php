<x-saas::admin-layout title="Create User">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Create User') }}</h5>
                    <div class="btn-box">
                        <a href="{{ route('saas.users.index') }}" class="btn btn-sm btn-primary">{{ __('All Users') }}</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('saas.users.store') }}" id="userStoreForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="name" class="form-label"><strong>{{ __('Name') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="{{ __('Enter fullname') }}" required>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="email" class="form-label"><strong>{{ __('Email') }}</strong><span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="{{ __('Enter email-address') }}" required>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="password" class="form-label"><strong>{{ __('Password') }}</strong><span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" value="{{ old('password') }}" id="password" placeholder="{{ __('Enter password') }}" required>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="phone" class="form-label"><strong>{{ __('Phone Number') }}</strong></label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="{{ __('Enter phone number') }}" id="phone">
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="photo" class="form-label"><strong>{{ __('Profile Image') }}</strong></label>
                                <input type="file" name="photo" class="form-control">
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="addresss" class="form-label"><strong>{{ __('Address') }}</strong></label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="{{ __('Enter address') }}">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="language" class="form-label"><strong>{{ __('Language') }}</strong><span class="text-danger">*</span></label>
                                <select name="language" id="languageDropdown" class="form-control form-select lang-select" required>
                                    <option value="en" @selected(old('language') === 'en')>{{ __('English') }}</option>
                                    <option value="bn" @selected(old('language') === 'bn')>{{ __('Bangla') }}</option>
                                    <option value="ar" @selected(old('language') === 'ar')>{{ __('Arabic') }}</option>
                                </select>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="role" class="form-label"><strong>{{ __('Role') }}</strong><span class="text-danger">*</span></label>
                                <select name="role_id" id="roleDropdown" class="form-control form-select" required>
                                    <option value="">{{ __('Select Role') }}</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected(old('role') === $role->id)>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-3 text-end">
                                <input type="submit" class="btn btn-sm btn-primary" value="{{ __('Create') }}" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script></script>
    @endpush
</x-saas::admin-layout>
