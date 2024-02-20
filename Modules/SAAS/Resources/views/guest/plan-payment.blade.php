<x-saas::guest title="{{ __('Payment') }}">
    <div class="container ck-container mt-3 pb-5">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>{{ __('Payment') }}</h5>
                <div>
                    {{-- <x-back-button href="{{ route('saas.plan.all') }}"></x-back-button> --}}
                </div>
            </div>
            <div class="card-body">
                {{-- <form method="POST" action="{{ route('saas.subscriptions.store', $plan->id) }}" class="card-form mt-3 mb-3"> --}}
                <form method="POST" action="#" class="card-form mt-3 mb-3">
                    @csrf
                    <div class="col-lg-6 col-md-10 pe-2">
                        <input type="hidden" name="payment_method" class="payment-method">
                        <input class="StripeElement form-control mb-3" name="card_holder_name"
                            placeholder="Card holder name" required>
                    </div>
                    <div class="col-lg-6 col-md-10 pe-2">
                        <div id="card-element"></div>
                    </div>
                    <div id="card-errors" role="alert"></div>
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-sm btn-primary pay">
                            {{ __('Make Payment for Subscription') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            let stripe = Stripe("{{ env('STRIPE_KEY') }}")
            let elements = stripe.elements()
            let style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            }
            let card = elements.create('card', {
                style: style
            })
            card.mount('#card-element')
            let paymentMethod = null

            $('.card-form').on('submit', function(e) {
                $('button.pay').attr('disabled', true)
                if (paymentMethod) {
                    return true
                }
                stripe.confirmCardSetup(
                    "{{ $intent->client_secret }}", {
                        payment_method: {
                            card: card,
                            billing_details: {
                                name: $('.card_holder_name').val()
                            }
                        }
                    }
                ).then(function(result) {
                    if (result.error) {
                        $('#card-errors').text(result.error.message)
                        $('button.pay').removeAttr('disabled')
                    } else {
                        paymentMethod = result.setupIntent.payment_method
                        $('.payment-method').val(paymentMethod)
                        $('.card-form').submit()
                    }
                })
                return false
            })
        </script>
    @endpush
</x-saas::guest>
