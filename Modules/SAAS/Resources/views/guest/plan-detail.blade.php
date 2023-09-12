<x-saas::guest title="{{ __('Welcome') }}">
    <div class="container mt-3 pb-5">
        <div class="card mb-3">
            <div class="card-header">
                <h5>{{ __('Plan Name') }}: {{ $plan->name }}</h5>
            </div>
            <div class="card-body">
                <p>{{ __('Plan Type') }}: {{ $plan->periodType }}</p>
                <p>{{ __('Price') }}: {{ $plan->price }}</p>
                <p>{{ __('Description') }}:</p>
                <div class="pb-3">
                    {!! $plan->description !!}
                </div>
                <p class="fw-bold">{{ __('Features') }}:</p>
                @foreach ($plan->features as $feature)
                    <li>{{ $feature->displayName }}</li>
                @endforeach
            </div>
        </div>
    </div>
</x-saas::guest>
