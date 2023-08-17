<x-saas::admin-layout title="Create Plan">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __("Create Plan") }}</h5>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('saas.plans.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                    <div class="mb-2">
                                        <input class="form-control" name="name" placeholder="Plan Name" >
                                    </div>
                                    <div class="mb-2">
                                        <label>Select Features:</label> <br>
                                        @foreach($features as $feature)
                                            <input type="checkbox" class="form-check-inline" name="feature_id[]" value="{{ $feature->id }}" /> {{ $feature->name }} <br>
                                        @endforeach
                                    </div>
                                    <input type="submit" class="btn btn-primary" value="{{ __('Create') }}"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>

        </script>
    @endpush
</x-saas::admin-layout>
