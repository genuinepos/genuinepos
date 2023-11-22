<x-saas::guest title="Welcome">
    <div class="container mt-3">
        <div class="card pb-5">
            <div class="card-header">
                <h5>{{ __('Select Your Plan') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($plans as $plan)
                        <div class="col-md-3">
                            <div class="card border-dark mb-3" style="max-width: 18rem;">
                                <div class="card-header">{{ $plan->name }}</div>
                                <div class="card-body text-dark">
                                    <h5 class="card-title">{{ $plan->periodType }} {{ __('Plan') }}</h5>
                                    <p class="card-text" style="height: 80px;">
                                        {!! strip_tags(Str::limit($plan->description, 110, '...')) !!}</p>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('saas.plan.detail', $plan->slug) }}"
                                        class="btn btn-primary btn-sm">{{ __('See Plan Details') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-saas::guest>
