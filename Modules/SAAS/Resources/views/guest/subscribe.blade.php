<x-saas::guest title="Welcome">
    @push('css')
        <style>
            .StripeElement {
                box-sizing: border-box;
                height: 40px;
                padding: 10px 12px;
                border: 1px solid transparent;
                border-radius: 4px;
                background-color: white;
                box-shadow: 0 1px 3px 0 #e6ebf1;
                -webkit-transition: box-shadow 150ms ease;
                transition: box-shadow 150ms ease;
            }
            .StripeElement--focus {
                box-shadow: 0 1px 3px 0 #cfd7df;
            }
            .StripeElement--invalid {
                border-color: #fa755a;
            }
            .StripeElement--webkit-autofill {
                background-color: #fefde5 !important;
            }
        </style>
    @endpush

    <div class="container mt-3">
        <div class="card pb-5">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>{{ __('Payment for subscription') }}</h5>
                <div>
                    <x-back-button href="{{ route('saas.plan.all') }}"></x-back-button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="row">
                        <div class="col-lg-6 col-md-10">
                            <div class="list-group">
                                <div class="list-group-item">
                                    Plan Name: {{  $plan->name }}
                                </div>
                                <div class="list-group-item">
                                    Plan Price: ${{  $plan->price }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <form method="POST" action="{{ route('saas.subscriptions.store', $plan->id) }}" class="card-form mt-3 mb-3">
                            @csrf
                            <div class="col-lg-6 col-md-10 pe-2">
                                <input type="hidden" name="payment_method" class="payment-method">
                                <input class="StripeElement form-control mb-3" name="card_holder_name" placeholder="Card holder name" required>
                            </div>
                            <div class="col-lg-6 col-md-10 pe-2">
                                <div id="card-element"></div>
                            </div>
                            <div id="card-errors" role="alert"></div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-sm btn-primary pay">
                                    Make Payment for Subscription
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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
            let card = elements.create('card', {style: style})
            card.mount('#card-element')
            let paymentMethod = null

            $('.card-form').on('submit', function (e) {
                $('button.pay').attr('disabled', true)
                if (paymentMethod) {
                    return true
                }
                stripe.confirmCardSetup(
                    "{{ $intent->client_secret }}",
                    {
                        payment_method: {
                            card: card,
                            billing_details: {name: $('.card_holder_name').val()}
                        }
                    }
                ).then(function (result) {
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
