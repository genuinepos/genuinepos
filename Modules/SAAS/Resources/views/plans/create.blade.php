<x-saas::admin-layout title="Create Plan">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Create Plan') }}</h5>
                    <div>
                        <a href="{{ route('saas.plans.index') }}" class="btn btn-primary">{{ __('Plan List') }}</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('saas.plans.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <input type="text" class="form-control" name="name" placeholder="Plan Name" required>
                                </div>
                                <div class="mb-2">
                                    <input type="number" class="form-control" name="period_month" placeholder="Period Month" required>
                                </div>
                                <div class="mb-2">
                                    <input type="number" min="1" step="0.000001" class="form-control" name="price" placeholder="Price" required>
                                </div>  
                                <div class="mb-2">
                                    <textarea class="form-control" name="description" placeholder="Description" rows="4"></textarea>
                                </div>
                                <div class="">
                                    <h6>{{ __("Assign Features") }}</h6>
                                </div>
                                <div class="mb-2">
                                    <div class="py-2">
                                        <input type="checkbox" class="form-check-input" name="select_all" id="select_all"> <label for="select_all" class="form-check-label">Select All</label>
                                    </div>
                                    @foreach ($features as $feature)
                                        <div>
                                            <input type="checkbox" class="form-check-input checkbox-child" name="feature_id[]" value="{{ $feature->id }}" id="{{ $feature->id }}" />
                                            <label for="{{ $feature->id }}">{{ str($feature->name)->headline() }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="submit" class="btn btn-primary" value="{{ __('Create') }}" />
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
