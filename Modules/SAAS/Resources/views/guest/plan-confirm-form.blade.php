<x-saas::guest title="{{ __('Confirm Plan') }}">
    @push('css')
        <style>
            header.cart-header {
                border: 1px solid;
                border-radius: 7px;
                padding: 10px;
            }

            header.cart-header .header-link-area ul li {
                display: inline-block;
            }

            .header-link-area ul li {
                line-height: 43px;
                padding: 1px 8px;
                font-size: 16px;
                font-weight: 500;
            }
        </style>
    @endpush
    <header class="cart-header my-3">
        <div class="row">
            <div class="col-md-4">
                <img style="height: 50px;; width:150px;" src="http://gposs.com/wp-content/uploads/2023/05/cropped-GPOSs-logo-b.png" alt="">
            </div>
            <div class="col-md-8">
                <div class="header-link-area">
                    <ul class="list-unstyled">
                        <li>
                            <a href="https://gposs.com">{{ __('Home') }}</a>
                        </li>

                        <li>
                            <a href="https://gposs.com/pricing">{{ __('Pricing') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div class="row mt-4">
        <div class="col-12">
            <div class="tab-nav">
                <button class="single-nav active" data-tab="cartTab">
                    <span class="txt">{{ __('Step One') }}</span>
                    <span class="sl-no">{{ __('01') }}</span>
                </button>

                <button class="single-nav" data-tab="checkOutTab" disabled>
                    <span class="txt">{{ __('Step Two') }}</span>
                    <span class="sl-no">{{ __('02') }}</span>
                </button>

                <button class="single-nav" data-tab="orderCompletedTab" disabled>
                    <span class="txt">{{ __('Step Three') }}</span>
                    <span class="sl-no">{{ __('03') }}</span>
                </button>
            </div>

            <div class="tab-contents mt-1">

                <form id="tenantStoreForm" method="POST" action="{{ route('saas.guest.tenants.store') }}" method="POST">
                    @csrf
                    @include('saas::guest.partials.plan_confirm_partials.step_one')
                    @include('saas::guest.partials.plan_confirm_partials.step_two')
                </form>

                <div class="single-tab" id="orderCompletedTab">
                    <div class="check-icon">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </div>
                    <div class="order-complete-msg">
                        <h2>{{ __('Your Order Has Been Completed') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script src="{{ asset('backend/asset/js/plan_cart.js') }}"></script>
        <script>
            // Domain Check
            var typingTimer; //timer identifier
            var doneTypingInterval = 800; //time in ms, 5 seconds for example
            var $input = $('#domain');

            //on keyup, start the countdown
            $input.on('keyup', function() {

                if ($input.val() == '') {

                    $('#domainPreview').html('');
                    return;
                }

                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
            });

            //on keydown, clear the countdown
            $input.on('keydown', function() {
                clearTimeout(typingTimer);
            });

            //user is "finished typing," do something
            function doneTyping() {
                $('#domainPreview').html(`<span class="">🔍Checking availability...<span>`);
                var domain = $('#domain').val();

                if ($input.val() == '') {

                    $('#domainPreview').html('');
                    return;
                }

                $.ajax({
                    url: "{{ route('saas.domain.checkAvailability') }}",
                    type: 'GET',
                    data: {
                        domain: domain
                    },
                    success: function(res) {

                        if ($input.val() == '') {

                            $('#domainPreview').html('');
                            return;
                        }

                        if (res.isAvailable) {
                            isAvailable = true;
                            $('#domainPreview').html(`<span class="text-success">✔ Doamin is available<span>`);
                        }
                    }, error: function(err) {
                        isAvailable = false;
                        $('#domainPreview').html(`<span class="text-danger">❌ Doamin is not available<span>`);
                    }
                });
            }
        </script>
    @endpush
</x-saas::guest>
