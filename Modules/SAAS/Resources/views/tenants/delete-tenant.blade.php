{{-- This feature is dangerous to have, so skipped for now. --}}

<x-saas::admin-layout title="Create tenant">
    @push('css')
    <style>

    </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Manage Business') }}</h5>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="">
                        <label for="name" class="form-label text-bold"><b>{{ __("Confirm Deletion By Typing Business Name") }}</b></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid  @enderror" placeholder="{{ __('Enter Business Name') }}" required/>
                    </div>
                    <div class="">
                        <label for="password" class="form-label text-bold"><b>{{ __("Password") }}</b></label>
                        <input type="text" password="password" id="password" class="form-control @error('password') is-invalid  @enderror" placeholder="{{ __('Enter Business password') }}" required/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-saas::admin-layout>
