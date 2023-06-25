<x-saas::guest title="Login">
    <div class="container">
        <div class="card mt-3">
            <div class="card-header">
                <h1 class="">Login to {{config('app.name')}}</h1>
            </div>
            <div class="card-body">
                <div class="">
                    <form method="POST" action="{{ route('saas.login') }}">
                        @csrf

                        <div class="form-group  mb-2 row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group  mb-2 row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group  mb-2 row mb-0">
                            <div class="">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                                <div class="col-md-6 offset-md-4 mt-2">
                                    <p>{{ __('Don\'t have an account? ') }} <a href="{{ route('saas.register') }}">{{ __('Register here') }}</a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-saas::guest>
