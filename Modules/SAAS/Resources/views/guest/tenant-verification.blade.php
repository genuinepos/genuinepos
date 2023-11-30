<x-saas::guest title="{{ __('Confirm Plan') }}">
    <div class="container ck-container mt-3 pb-5">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>{{ __("Verify your Business Account") }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('saas.business-verification.send') }}" method="POST">
                    @csrf
                    <div class="input-group mb-2">
                        <input type="email" name="email" class="form-control" placeholder="{{ __('Registration-Time Email Address') }}" required>
                    </div>
                    <div class="input-group mb-3" id="recaptcha-div">
                        {!! NoCaptcha::display() !!}
                    </div>
                    <div class="my-1 pb-1">
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="text-danger">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="input-group mb-2">
                        <input type="submit" class="btn btn-primary" placeholder="{{ __('Send Verification Email') }}"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-saas::guest>
