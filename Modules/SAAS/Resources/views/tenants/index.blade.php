<x-saas::admin title="Create tenant">
    <div class="container">
        <div class="card  mt-3">
            <div class="card-header">
                <h2>{{ __('Create shop') }}</h2>
            </div>
            <div class="card-body">
                @foreach(auth()->user()->shops as $shop) 
                    <li>
                        {{ $shop->name }}
                        {{ $shop->domain }}
                    </li>
                @endforeach
            </div>
        </div>
    </div>
</x-saas::admin>