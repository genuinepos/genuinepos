<x-saas::admin-layout title="Edit Plan">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Edit Plan') }}</h5>
                    <div>
                        <a href="{{ route('saas.plans.index') }}" class="btn btn-primary">{{ __('Plan List') }}</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('saas.plans.update', $plan->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="name" class="form-label">{{ __('Plan Name') }}</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter Plan Name" required value="{{ $plan->name }}">
                                </div>
                                <div class="mb-2">
                                    <label for="period_month" class="form-label">{{ __('Period Month') }}</label>
                                    <input type="number" class="form-control" name="period_month"
                                        placeholder="Enter Period Month" required value="{{ $plan->period_month }}">
                                </div>
                                <div class="mb-2">
                                    <label for="price" class="form-label">{{ __('Price') }}</label>
                                    <input type="number" min="1" step="0.000001" class="form-control"
                                        name="price" placeholder="Enter Price" required value="{{ $plan->price }}">
                                </div>
                                <div class="mb-4">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control editor" name="description" placeholder="Enter Description" rows="4">{{ $plan->description }}</textarea>
                                </div>
                                <div class="">
                                    <h6>{{ __('Assign Features') }}</h6>
                                </div>
                                <div class="mb-2">
                                    <div class="py-2">
                                        <input type="checkbox" class="form-check-input" name="select_all"
                                            id="select_all">
                                        <label for="select_all" class="form-check-label">
                                            {{ __('Select All Features') }}
                                        </label>
                                    </div>
                                    @foreach ($features as $feature)
                                        @php
                                            $isEnabled = $plan->features->where('id', $feature->id)->first();
                                        @endphp
                                        <div>
                                            <input type="checkbox" class="form-check-input checkbox-child"
                                                name="feature_id[]" value="{{ $feature->id }}"
                                                id="{{ $feature->id }}"
                                                @if ($isEnabled) checked @endif />
                                            <label for="{{ $feature->id }}">
                                                {{ str($feature->name)->headline() }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="submit" class="btn btn-sm btn-primary" value="{{ __('Update') }}" />
                                <a href="{{ route('saas.plans.index') }}" class="btn btn-sm btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            const selectAll = document.getElementById('select_all');
            selectAll.addEventListener('click', function() {

                let allChild = document.querySelectorAll('.checkbox-child');
                for (let child of allChild) {
                    if (selectAll.checked) {
                        child.checked = true;
                    } else {
                        child.checked = false;
                    }
                }
            })
        </script>
    @endpush
</x-saas::admin-layout>
