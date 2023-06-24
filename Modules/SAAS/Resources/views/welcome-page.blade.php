<x-saas::guest title="Welcomee">
    @push('css')
    <style>
        .bg {
            /* background: lightblue; */
            background: url("https://source.unsplash.com/random/1920x1080/?nature") no-repeat center center;
            width: 100%;
            height: 100vh;
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
                    <h1 class="text-center display-1">Welcome to {{config('app.name')}}</h1>
                   <p class="text-center pt-3">
                    <a href="{{ route('saas.register') }}" class="btn btn-primary btn-bg  pe-2">{{ _('Register') }}</a>
                    <a href="{{ route('saas.login') }}" class="btn btn-primary btn-bg ">{{ _('Login') }}</a>
                   </p>
                </div>
            </div>
       </div>
    </div>
</x-saas::guest>
