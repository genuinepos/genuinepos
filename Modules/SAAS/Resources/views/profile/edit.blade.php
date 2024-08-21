<x-saas::admin-layout>
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Edit Profile') }}</h5>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('saas.profile.update', $user->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="name" class="form-label text-bold"><strong>{{ __('Name') }}</strong></label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                    </div>
                    <div class="col-sm-6">
                        <label for="email" class="form-label text-bold"><strong>{{ __('Email') }}</strong></label>
                        <input type="text" name="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label for="phone" class="form-label text-bold"><strong>{{ __('Phone Number') }}</strong></label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" placeholder="{{ __('Enter Your Phone  Number') }}">
                    </div>
                    <div class="col-sm-6">
                        <label for="photo" class="form-label text-bold"><strong>{{ __('Profile Image') }}</strong></label>
                        <input type="file" name="photo" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label for="addresss" class="form-label text-bold"><strong>{{ __('Addresss') }}</strong></label>
                        <input type="text" name="address" class="form-control" value="{{ $user?->address }}" placeholder="{{ __('Enter Your Addresss') }}" >
                    </div>
                    <div class="col-sm-6">
                        <label for="language" class="form-label text-bold"><strong>{{ __('Language') }}</strong></label>
                        <select name="language" id="languageDropdown" class="form-control">
                            <option value="en" @selected($user?->language === 'en')>{{ __('English') }}</option>
                            <option value="bn" @selected($user?->language === 'bn')>{{ __('Bangla') }}</option>
                            {{-- <option value="ar" @selected($user?->language === 'ar')>{{ __('Arabic') }}</option> --}}
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-sm-6">
                        <input type="submit" value="{{ __('Submit') }}" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-saas::admin-layout>
