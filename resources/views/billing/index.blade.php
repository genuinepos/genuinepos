@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .card-body {
            flex: 1 1 auto;
            padding: 0.4rem 0.4rem;
        }
    </style>
@endpush
@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
@endphp

@section('title', 'Billing - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Billing') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>

                    <div class="tab_list_area">
                        <div class="btn-group">
                            <a id="tab_btn" data-show="plan_details" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                <i class="fas fa-scroll"></i> {{ __('Plan Details') }}
                            </a>

                            <a id="tab_btn" data-show="purchase_history" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-info-circle"></i> {{ __('Purchase History') }}
                            </a>
                        </div>
                    </div>

                    <div class="tab_contant plan_details">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        @if ($generalSettings['subscription']->plan_type == 1)
                                            <h6>{{ $generalSettings['subscription']->plan_name }}</h6>
                                        @else
                                            <h6>{{ __('Custom Plan') }}</h6>
                                        @endif
                                    </div>

                                    <div class="card-body">
                                        <table class="display table table-sm">
                                            <tbody>
                                                <tr>
                                                    <th>{{ __('Plan Active Date') }}</th>
                                                    <td>: {{ date($dateFormat, strtotime($generalSettings['subscription']->initial_plan_start_date)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Multi Store Management System') }} / {{ __('Company') }}</th>
                                                    <td>:
                                                        @if ($generalSettings['subscription']->has_business == 1)
                                                            <span class="text-success fw-bold ">
                                                                {{ __('Yes') }}
                                                            </span>
                                                        @else
                                                            <span class="text-danger fw-bold ">
                                                                {{ __('No') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Store Limit') }}</th>
                                                    <td>:
                                                        <span class="text-danger fw-bold">{{ count($branches) }}</span> / {{ $generalSettings['subscription']->current_shop_count }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Current Status') }}</th>
                                                    <td>: {{ __('Active') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                @if ($generalSettings['subscription']->plan_type == 1 && $generalSettings['subscription']->has_due_amount == 0)
                                    <a href="{{ route('software.service.billing.upgrade.plan.index') }}" class="btn btn-danger p-2">{{ __('Upgrade Plan') }}</a>
                                @endif

                                @if ($generalSettings['subscription']->is_trial_plan == 0 && $generalSettings['subscription']->has_business == 0 && auth()->user()->can('billing_business_add') && $generalSettings['subscription']->has_due_amount == 0)
                                    <a href="{{ route('software.service.billing.add.business.cart') }}" class="btn btn-success p-2">{{ __('Add Multi Store Management System') }}</a>
                                @endif

                                @if ($generalSettings['subscription']->is_trial_plan == 0 && auth()->user()->can('billing_branch_add') && $generalSettings['subscription']->has_due_amount == 0)
                                    <a href="{{ route('software.service.billing.add.shop.cart') }}" class="btn btn-success p-2">{{ __('Add Store') }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="card p-1">
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <h6>{{ __('List of Stores') }}</h6>
                                        </div>

                                        <div class="col-md-6 text-end">
                                            @if ($generalSettings['subscription']->is_trial_plan == 0 && auth()->user()->can('billing_renew_branch') && $generalSettings['subscription']->has_due_amount == 0)
                                                <a href="{{ route('software.service.billing.shop.renew.cart') }}" class="btn btn-sm btn-success">{{ __('Renew Store') }}</a>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="display data_tbl data__table common-reloader w-100">
                                            <thead>
                                                <tr>
                                                    {{-- <th></th> --}}
                                                    <th>{{ __('Serial') }}</th>
                                                    <th>{{ __('Store Name') }}</th>
                                                    <th>{{ __('Registered On') }}</th>
                                                    <th>{{ __('Expiers On') }}</th>
                                                    <th>{{ __('Remaining Days') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($branches as $branch)
                                                    <tr>
                                                        {{-- <td><input type="checkbox" name="" value="{{ $branch->id }}"></td> --}}
                                                        <td>{{ $branch->id }}</td>
                                                        <td>
                                                            @if ($branch?->parentBranch)
                                                                {{ $branch?->parentBranch?->name . '(' . $branch->area_name . ')' . $branch->branch_code }}
                                                            @else
                                                                {{ $branch?->name . '(' . $branch->area_name . ')-' . $branch->branch_code }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $branch?->shopExpireDateHistory ? date($dateFormat, strtotime($branch?->shopExpireDateHistory?->start_date)) : date($dateFormat, strtotime($branch?->created_at)) }}
                                                        </td>
                                                        <td>
                                                            @if ($generalSettings['subscription']->is_trial_plan == 0)
                                                                {{ date($dateFormat, strtotime($branch->expire_date)) }}
                                                            @else
                                                                @php
                                                                    $planStartDate = $generalSettings['subscription']->trial_start_date;
                                                                    $trialDays = $generalSettings['subscription']->trial_days;
                                                                    $startDate = new DateTime($planStartDate);
                                                                    $lastDate = $startDate->modify('+ ' . $trialDays . ' days');
                                                                    $expireDate = $lastDate->format('Y-m-d');
                                                                    $dateFormat = $generalSettings['business_or_shop__date_format'];
                                                                @endphp
                                                                {{ date($dateFormat, strtotime($expireDate)) }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($generalSettings['subscription']->is_trial_plan == 0)
                                                                @if (date('Y-m-d') < date('Y-m-d', strtotime($branch->expire_date)))
                                                                    <span class="text-success fw-bold">{{ (new \DateTime(date('Y-m-d')))->diff(new \DateTime($branch->expire_date))->days + 1 }}</span> / Days
                                                                @else
                                                                    <span class="text-danger fw-bold">0</span> / Days
                                                                @endif
                                                            @else
                                                                @php
                                                                    $planStartDate = $generalSettings['subscription']->trial_start_date;
                                                                    $trialDays = $generalSettings['subscription']->trial_days;
                                                                    $startDate = new DateTime($planStartDate);
                                                                    $lastDate = $startDate->modify('+ ' . $trialDays . ' days');
                                                                    $expireDate = $lastDate->format('Y-m-d');
                                                                    $dateFormat = $generalSettings['business_or_shop__date_format'];
                                                                @endphp

                                                                @if (date('Y-m-d') < date('Y-m-d', strtotime($expireDate)))
                                                                    <span class="text-success fw-bold">{{ (new \DateTime(date('Y-m-d')))->diff(new \DateTime($expireDate))->days }}</span> / {{ __('Days') }}
                                                                @else
                                                                    <span class="text-danger fw-bold">0</span> / {{ __('Days') }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>{{ date('Y-m-d') > date('Y-m-d', strtotime($branch->expire_date)) ? __('Expired') : __('Active') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant purchase_history d-hide">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table_area">
                                <div class="table-responsive">
                                    <table id="sales-table" class="display data_tbl data__table table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Serial') }}</th>
                                                <th>{{ __('Purchase Type') }}</th>
                                                <th>{{ __('Payment Date') }}</th>
                                                <th>{{ __('Payment Gateway') }}</th>
                                                <th>{{ __('Transaction ID') }}</th>
                                                <th>{{ __('Total Payable') }}</th>
                                                <th>{{ __('Paid') }}</th>
                                                <th>{{ __('Due') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($transactions as $transaction)
                                                <tr>
                                                    <td>{{ $transaction->id }}</td>
                                                    <td class="fw-bold">{{ str(\App\Enums\SubscriptionTransactionType::tryFrom($transaction->transaction_type)->name)->headline() }}</td>
                                                    <td>{{ $transaction->payment_date }}</td>
                                                    <td>{{ $transaction->payment_trans_id }}</td>
                                                    <td>{{ $transaction->payment_method_name }}</td>
                                                    <td class="fw-bold">{{ App\Utils\Converter::format_in_bdt($transaction->total_payable_amount) }}</td>
                                                    <td class="text-success fw-bold">{{ App\Utils\Converter::format_in_bdt($transaction->paid) }}</td>
                                                    <td class="text-danger fw-bold">{{ App\Utils\Converter::format_in_bdt($transaction->due) }}</td>

                                                    <td>
                                                        {{-- <a href="{{ route('software.service.billing.invoice.view', $transaction->id) }}">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a> --}}

                                                        <a href="{{ route('software.service.billing.invoice.pdf', $transaction->id) }}" target="_blank">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>

                                                        {{-- <a href="{{ route('software.service.billing.invoice.download', $transaction->id) }}" target="_blank">
                                                            <i class="fa-solid fa-download"></i>
                                                        </a> --}}
                                                    </td>
                                                </tr>
                                            @empty
                                                <td colspan="7" class="text-center">{{ __('No data found!') }}</td>
                                            @endforelse
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
