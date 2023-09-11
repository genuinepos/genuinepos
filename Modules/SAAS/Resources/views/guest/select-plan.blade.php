<x-saas::guest title="Welcome">
    <div class="container mt-3">
        <div class="card mb-5">
            <div class="card-header">
                <h5>{{ __('Plans') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($plans as $plan)
                        <div class="col-md-3">
                            <div class="card border-dark mb-3" style="max-width: 18rem;">
                                <div class="card-header">{{ $plan->name }}</div>
                                <div class="card-body text-dark">
                                    <h5 class="card-title">Type: {{ $plan->periodType }}</h5>
                                    <p class="card-text" style="min-height: 80px;">{!! Str::limit($plan->description, 100, '...') !!}</p>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('saas.select-plan.show', $plan->id) }}"
                                        class="btn btn-primary btn-sm">{{ __('Details') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-saas::guest>
