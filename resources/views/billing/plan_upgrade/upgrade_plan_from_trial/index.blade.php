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

        <div class="p-1">

            <ul class="nav nav-tabs" id="planTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="plan-monthly-tab" data-bs-toggle="tab" data-bs-target="#plan-monthly" type="button" role="tab" aria-controls="plan-monthly" aria-selected="true">Monthly</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="plan-yearly-tab" data-bs-toggle="tab" data-bs-target="#plan-yearly" type="button" role="tab" aria-controls="plan-yearly" aria-selected="false" onclick="activeTab('yearly')">Yearly</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="plan-lifetime-tab" data-bs-toggle="tab" data-bs-target="#plan-lifetime" type="button" role="tab" aria-controls="plan-lifetime" aria-selected="false" onclick="activeTab('lifetime')">Lifetime</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="plan-monthly" role="tabpanel" aria-labelledby="plan-monthly-tab">
                    @include('billing.plan_upgrade.upgrade_plan_from_trial.partials.index_partials.plan_prices', ['plantype' => 'month'])
                </div>

                <div class="tab-pane fade" id="plan-yearly" role="tabpanel" aria-labelledby="plan-yearly-tab">
                    @include('billing.plan_upgrade.upgrade_plan_from_trial.partials.index_partials.plan_prices', ['plantype' => 'year'])
                </div>

                <div class="tab-pane fade" id="plan-lifetime" role="tabpanel" aria-labelledby="contact-tab">
                    @include('billing.plan_upgrade.upgrade_plan_from_trial.partials.index_partials.plan_prices', ['plantype' => 'lifetime'])
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

        // function activeTab(planType) {
        //     let elements = document.querySelectorAll('#link-plan');
        //     elements.forEach(el => {
        //         el.href = 'upgrade-plan/'+el.dataset.id + '?type=' + planType;
        //         console.log(el.href, 'active');
        //     });
        // }
    </script>
@endpush
