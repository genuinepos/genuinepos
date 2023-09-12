<x-saas::guest title="Welcome">
    @push('css')
        <style>
            .bg {
                /* background: url("https://source.unsplash.com/random/1920x1080/?nature") no-repeat center center;  */
                background: url("{{ asset('modules/saas/images/main-bg-1.jpg') }}") no-repeat center center;
                width: 100%;
                height: calc(100vh - 62.8px);
            }

            .card {
                width: 80%;
                background: #efdfdf8f;
            }

            .container {
                height: 100%;
            }
        </style>
    @endpush
    <div class="bg">
        <div class="container d-flex justify-content-center align-items-center">
            <div class="card p-3 py-5">
                <div class="card-body">
                    <h1 class="text-center display-1">{{ __('Welcome to') }} {{ config('app.name') }}</h1>
                    <p class="text-center pt-3">
                        @guest
                            <a href="{{ route('saas.register') }}"
                                class="btn btn-primary btn-bg  pe-2">{{ __('Register') }}</a>
                            <a href="{{ route('saas.login') }}" class="btn btn-primary btn-bg ">{{ __('Login') }}</a>
                        @endguest
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-saas::guest>
