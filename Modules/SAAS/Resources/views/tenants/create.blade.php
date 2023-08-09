<x-saas::admin-layout title="Create Business">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __("Create Business") }}</h5>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('saas.tenants.store') }}">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="name">{{ __("Business Name") }}</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid  @enderror" placeholder="{{ __('Enter Business Name') }}" />
                        </div>
                        <div class="form-group mb-2">
                            <label for="name">{{ __("Domain Name") }}</label>
                            <input type="text" name="domain" id="domain" class="form-control @error('name') is-invalid  @enderror" placeholder="{{ __('Enter Domain Name') }}" oninput="domainPreview()" />
                            {{-- <p class="mt-2">* {{ __('Selected domain') }}:  <strong><span id="domainPreview" class="monospace"></span></strong></p> --}}
                        </div>
                        <div class="form-group mb-2">
                            <input type="submit" class="btn btn-primary" value="{{ __('Create') }}"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            function domainPreview() {
                let domainInput = document.getElementById('domain');
                let domainText = domainInput.value;
                let selectedDomain = domainText;
                let appDomain = "{{ config('app.domain') }}";
                let isAvailable = true;

                if (selectedDomain.lastIndexOf(appDomain) > -1) {
                    selectedDomain = domainText.substring(0, domainText.lastIndexOf(appDomain));
                }

                if (domainText.length > 0) {
                    selectedDomain = `${domainText}.${appDomain}`;
                    if (!isAvailable) {
                        selectedDomain = `<span class="text-danger">${selectedDomain} is already booked.</span>`;
                    }
                    if (isAvailable) {
                        selectedDomain = `<span class="text-success">${selectedDomain} ✅</span>`;
                    }
                }
                let domainPreview = document.getElementById('domainPreview');
                domainPreview.innerHTML = selectedDomain;
            }
        </script>
    @endpush
</x-saas::admin-layout>
