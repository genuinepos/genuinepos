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
                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ __('Plan Name') }}</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter Plan Name" required value="{{ $plan->name }}">
                                </div>
                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ __('URL Slug') }} ({{ __("Keep empty to get auto-generated slug") }})</label>
                                    <input type="text" class="form-control" name="slug"
                                        placeholder="{{ __("Enter URL Slug") }}" value="{{ $plan->slug }}">
                                </div>
                                <div class="mb-4">
                                    <label for="period_unit" class="form-label">{{ __('Plan Period Unit') }}</label>
                                    <select name="period_unit" id="period_unit" class="form-select" required>
                                        <option value="">{{ __('Select Plan Period Unit') }}</option>
                                        @foreach(\Modules\SAAS\Enums\PlanPeriod::cases() as $period)
                                        <option value="{{ $period->value }}" @selected($plan->period_unit == $period->value)>{{ $period->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="period_value" class="form-label">{{ __('Plan Period Value') }}</label>
                                    <input type="number" class="form-control" name="period_value"
                                        placeholder="Period Value" required value="{{ $plan->period_value }}" required>
                                </div>
                                <div class="mb-4">
                                    <label for="currency_code" class="form-label">{{ __('Select Currency') }}</label>
                                    <select name="currency_code" id="currency_code" class="form-select" required>
                                        @foreach(Modules\SAAS\Enums\Currencies::cases() as $currency)
                                        <option value="{{ $currency->value }}" @selected($currency->value == $plan->currency_code)>{{ $currency->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="price" class="form-label">{{ __('Period Price') }}</label>
                                    <input type="number" min="0" step="0.000001" class="form-control"
                                        name="price" placeholder="Enter Price" required value="{{ $plan->price }}">
                                </div>
                                <div class="mb-4">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control editor" name="description" placeholder="Enter Description" rows="4">{{ $plan->description }}</textarea>
                                </div>
                                <div class="">
                                    <h6>{{ __('Assign Features') }}</h6>
                                </div>
                                <div class="mb-4">
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
                                <div class="mb-4 p-3" style="border: 1px solid red;">
                                    <label for="status" class="form-label"><span class="text-danger">*</span>{{ __('Plan Status') }}</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="1" @selected($plan->status == 1)>Active</option>
                                        <option value="0" @selected($plan->status != 1)>In-Active</option>
                                    </select>
                                </div>

                                <input type="submit" class="btn btn-sm btn-primary" value="{{ __('Update Plan') }}" />
                                <a href="{{ route('saas.plans.index') }}" class="btn btn-sm btn-secondary">
                                    {{ __('Cancel Edit') }}
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
