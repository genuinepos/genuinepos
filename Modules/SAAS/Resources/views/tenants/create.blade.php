<x-admin::admin-layout title="Create tenant">
    <div class="container">
        <div class="card  mt-3">
            <div class="card-header">
                <h2>{{ __('Create Business') }}</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('saas.tenants.store') }}">
                    @csrf
                    <div class="form-group mb-2">
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid  @enderror" placeholder="{{ __('Enter Business Name') }}" />
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" name="domain" id="domain" class="form-control @error('name') is-invalid  @enderror" placeholder="{{ __('Enter Domain Name') }}" oninput="domainPreview()" />
                        <p class="mt-2">* {{ __('Selected domain') }}:  <strong><span id="domainPreview" class="monospace"></span></strong></p>
                    </div>
                    <div class="form-group mb-2">
                        <input type="submit" class="btn btn-primary" value="{{ __('Create') }}"/>
                    </div>
                </form>
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

                if(selectedDomain.lastIndexOf(appDomain) > -1) {
                   selectedDomain = domainText.substring(0, domainText.lastIndexOf(appDomain));
                }

                if(domainText.length > 0) {
                    selectedDomain = `${domainText}.${appDomain}`;
                    if(! isAvailable) {
                        selectedDomain = `<span class="text-danger">${selectedDomain} is already booked.</span>`;
                    }
                    if(isAvailable) {
                        selectedDomain = `<span class="text-success">${selectedDomain} ✅</span>`;
                    }
                }
                let domainPreview = document.getElementById('domainPreview');
                domainPreview.innerHTML = selectedDomain;
            }
        </script>
    @endpush
</x-admin::admin-layout>
