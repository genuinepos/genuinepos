<x-saas::admin>
    <div class="container">
        <div class="card mt-3">
            <div class="card-header">
                <h2>Edit Profile</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('saas.profile.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="form-group mb-2">
                        <label for="name" class="form-label text-bold"><strong>{{ __('Name') }}</strong></label>
                        <input type="text"  name="name" class="form-control" value="{{ $user->name }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="email" class="form-label text-bold"><strong>{{ __('Email') }}</strong></label>
                        <input type="text" name="email" class="form-control" value="{{ $user->email }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="phone" class="form-label text-bold"><strong>{{ __('Phone Number') }}</strong></label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" placeholder="{{ __('Enter Your Phone  Number') }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="addresss" class="form-label text-bold"><strong>{{ __('Addresss') }}</strong></label>
                        <input type="text" name="address" class="form-control" value="{{ $user->addresss }}" placeholder="{{ __('Enter Your Addresss') }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="language" class="form-label text-bold"><strong>{{ __('Language') }}</strong></label>
                        <select name="language" id="language" class="form-control">
                            <option value="english">{{ __('English') }}</option>
                            <option value="bangla">{{ __('Bangla') }}</option>
                            <option value="arabic">{{ __('Arabic') }}</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <input type="submit" value="Update" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-saas::admin>
