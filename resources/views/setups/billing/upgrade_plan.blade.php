@extends('layout.master')
@push('stylesheets')
    <style>
        .card-body {
            flex: 1 1 auto;
            padding: 0.4rem 0.4rem;
        }
    </style>
@endpush
@section('title', 'Upgrade Plan - ')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-4">
                    <h6>{{ __('Upgrade Plan') }}</h6>
                </div>

                <div class="col-md-4">
                    @if (Session::has('trialExpireDate'))
                        <p class="text-danger fw-bold">{{ session('trialExpireDate') }}</p>
                    @endif
                </div>
     
                <div class="col-md-4">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                </div>

            </div>
        </div>

        <div class="main-content p-0 mt-2">
            <div class="panel pricing-panel">
                <div class="panel-body d-flex flex-column align-items-center justify-content-center">
                    <div class="pricing">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="plan-type d-flex justify-content-center gap-2 mb-20 mb-2">
                                        <button id="Yearly" class="btn btn-primary">Yearly</button>
                                    </div>

                                    <div class="table-responsive">
                                        <div class="table-wrap">
                                            <table class="table table-light table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="table-title">
                                                                <h4>Choose Your Plan</h4>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="table-top">
                                                                <h3>Lean</h3>
                                                                <h2 class="price">$<span class="amount">120 </span> <span class="type">Monthly</span>
                                                                </h2>
                                                                <p>For your essential business needs.</p>
                                                                <a href="{{ route('software.service.billing.cart.for.upgrade.plan') }}" class="btn btn-primary">Select</a>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="table-top">
                                                                <h3>Standard</h3>
                                                                <h2 class="price">$<span class="amount">450</span> <span class="type">Monthly</span>
                                                                </h2>
                                                                <p>For your essential business needs.</p>
                                                                <a href="{{ route('software.service.billing.cart.for.upgrade.plan') }}" class="btn btn-primary">Select</a>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="table-top">
                                                                <h3>Advanced</h3>
                                                                <h2 class="price">$<span class="amount">780</span> <span class="type">Monthly</span>
                                                                </h2>
                                                                <p>For your essential business needs.</p>
                                                                <a href="{{ route('software.service.billing.cart.for.upgrade.plan') }}" class="btn btn-primary">Select</a>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="table-top">
                                                                <h3>Enterprise</h3>
                                                                <h2 class="price">$<span class="amount">150</span> <span class="type">Monthly</span>
                                                                </h2>
                                                                <p>For your essential business needs.</p>
                                                                <a href="{{ route('software.service.billing.cart.for.upgrade.plan') }}" class="btn btn-primary">Select</a>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="table-secondary">
                                                        <td colspan="3"><span>Products</span></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Retail POS</td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Registers</td>
                                                        <td>1 Included</td>
                                                        <td>1 Included</td>
                                                        <td>1 Included</td>
                                                        <td>1 Included</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Integrated Payments</td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Accounting</td>
                                                        <td><span class="icon minus"><i class="fas fa-minus"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>eCommerce</td>
                                                        <td><span class="icon minus"><i class="fas fa-minus"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Omnichannel Loyalty</td>
                                                        <td><span class="icon minus"><i class="fas fa-minus"></i></span></td>
                                                        <td><span class="icon minus"><i class="fas fa-minus"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Advanced Reporting</td>
                                                        <td><span class="icon minus"><i class="fas fa-minus"></i></span></td>
                                                        <td><span class="icon minus"><i class="fas fa-minus"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                    </tr>
                                                    <tr class="table-secondary">
                                                        <td colspan="3"><span>Services</span></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Digiboard Payments Card-present rate</td>
                                                        <td>2.6%+ 10c</td>
                                                        <td>2.6%+ 10c</td>
                                                        <td>2.6%+ 10c</td>
                                                        <td>2.6%+ 10c</td>
                                                    </tr>
                                                    <tr>
                                                        <td>24/7 customer support</td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>One on one onboarding</td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Additional free training</td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dedicated account manager</td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                        <td><span class="icon check"><i class="far fa-check-circle text-success"></i></span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();

            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').hide();
            var show_content = $(this).data('show');
            $('.' + show_content).show();
            $(this).addClass('tab_active');
        });
    </script>
@endpush

