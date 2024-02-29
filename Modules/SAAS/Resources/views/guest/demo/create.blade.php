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
                <button class="single-nav active" data-tab="stepOneTab" disabled>
                    <span class="txt">{{ __('Step One') }}</span>
                    <span class="sl-no">{{ __('01') }}</span>
                </button>

                <button class="single-nav" data-tab="stepTwoTab" disabled>
                    <span class="txt">{{ __('Step Three') }}</span>
                    <span class="sl-no">{{ __('02') }}</span>
                </button>
            </div>

            <div class="tab-contents mt-1">

                <form id="tenantStoreForm" method="POST" action="{{ route('saas.guest.tenants.store') }}" method="POST">
                    @csrf
                    <div class="single-tab active" id="stepOneTab">
                        @include('saas::guest.demo.partials.view_partials.js')
                    </div>
                </form>

                <div class="single-tab" id="stepThreeTab">
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
        @include('saas::guest.demo.partials.js_partials.js')
    @endpush
</x-saas::guest>
