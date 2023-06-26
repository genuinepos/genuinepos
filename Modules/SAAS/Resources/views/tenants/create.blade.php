<x-saas::admin title="Create tenant">
    <div class="container">
        <div class="card  mt-3">
            <div class="card-header">
                <h2>{{ __('Create shop') }}</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('saas.tenants.store') }}">
                    @csrf
                    <div class="form-group mb-2">
                        <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Enter shop name') }}">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" name="domain" id="domain" class="form-control" placeholder="{{ __('Enter domain') }}">
                    </div>
                    <div class="form-group mb-2">
                        <input type="submit" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-saas::admin>