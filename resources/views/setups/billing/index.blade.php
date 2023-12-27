@extends('layout.master')
@push('stylesheets')
    <style>
        .card-body {
            flex: 1 1 auto;
            padding: 0.4rem 0.4rem;
        }
    </style>
@endpush
@section('title', 'Billing - ')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush
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
                                        <h6>Basic Plan</h6>
                                    </div>

                                    <div class="card-body">
                                        <table class="display table table-sm">
                                            <tbody>
                                                <tr>
                                                    <th>{{ __("Plan Active Date") }}</th>
                                                    <th>:10-12-2023</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __("Current Status") }}</th>
                                                    <th>: Active</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <a href="{{ route('software.service.billing.upgrade.plan') }}" class="btn btn-danger p-2">{{ __("Upgrade Plan") }}</a>
                                <a href="{{ route('software.service.billing.cart.for.add.branch') }}" class="btn btn-success p-2">{{ __("Add Shop") }}</a>
                            </div>

                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="card p-1">
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <h6>{{ __("List Of Shops") }}</h6>
                                            </div>

                                            <div class="col-md-6 text-end">
                                                <a href="{{ route('software.service.billing.cart.for.renew.branch') }}" class="btn btn-sm btn-success">{{ __("Renew Shop") }}</a>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="display data_tbl data__table common-reloader w-100">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>{{ __('Serial') }}</th>
                                                        <th>{{ __('Shop Name') }}</th>
                                                        <th>{{ __('Registered On') }}</th>
                                                        <th>{{ __('Update On') }}</th>
                                                        <th>{{ __('Expiers On') }}</th>
                                                        <th>{{ __('Remaining Days') }}</th>
                                                        <th>{{ __('Status') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" name=""></td>
                                                        <td>1</td>
                                                        <td>Farea Super Market(Uttara Sector 4)</td>
                                                        <td>01-12-2023</td>
                                                        <td>01-12-2023</td>
                                                        <td>30-11-2024</td>
                                                        <td>365 Days</td>
                                                        <td>Active</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name=""></td>
                                                        <td>2</td>
                                                        <td>Farea Super Market(Uttara Sector 6)</td>
                                                        <td>01-12-2023</td>
                                                        <td>01-12-2023</td>
                                                        <td>30-11-2024</td>
                                                        <td>365 Days</td>
                                                        <td>Active</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name=""></td>
                                                        <td>3</td>
                                                        <td>SpeedDigit Computers</td>
                                                        <td>01-12-2023</td>
                                                        <td>01-12-2023</td>
                                                        <td>30-11-2025</td>
                                                        <td>730 Days</td>
                                                        <td>Active</td>
                                                    </tr>
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
                                        <table id="sales-table" class="display data_tbl data__table common-reloader w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Serial') }}</th>
                                                    <th>{{ __('Purchase Type') }}</th>
                                                    <th>{{ __('Payment Date') }}</th>
                                                    <th>{{ __('Transaction ID') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('Payment Gateway') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Buy Package</td>
                                                    <td>01-12-2023</td>
                                                    <td>M08NSBQSRMPRSOZOHX</td>
                                                    <td>120</td>
                                                    <td>Strpe</td>
                                                    <td><a href="#"><i class="fa-solid fa-download"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Shop Renew</td>
                                                    <td>01-3-2024</td>
                                                    <td>M08NSBQSRMPRSOZOHX</td>
                                                    <td>120</td>
                                                    <td>Strpe</td>
                                                    <td><a href="#"><i class="fa-solid fa-download"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Upgrade Package (Pro Plan)</td>
                                                    <td>01-3-2024</td>
                                                    <td>M08NSBQSRMPRSOZOHX</td>
                                                    <td>120</td>
                                                    <td>Strpe</td>
                                                    <td><a href="#"><i class="fa-solid fa-download"></i></a></td>
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

