<x-saas::guest title="Welcome">
    <div class="container mt-3">
        <div class="card pb-5">
            <div class="card-header">
                <h5>{{ __('Subscribe to plan') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{  $plan->name }}
                </div>
            </div>
        </div>
    </div>
</x-saas::guest>
