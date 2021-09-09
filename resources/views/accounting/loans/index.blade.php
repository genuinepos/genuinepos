@extends('layout.master')
@push('stylesheets')
<link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css"/>
@endpush
@section('title', 'Loans - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-glass-whiskey"></span>
                                <h5>Loans</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="sec-name mt-1">
                            <div class="name-head">
                                <div class="tab_list_area">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a id="tab_btn" data-show="companies" class="tab_btn tab_active" href="#"><i class="fas fa-info-circle"></i> Companies</a>
                                        </li>

                                        <li>
                                            <a id="tab_btn" data-show="loans" class="tab_btn" href="#">
                                            <i class="fas fa-scroll"></i> Loans</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @include('accounting.loans.bodyPartials.companyBody')
                        @include('accounting.loans.bodyPartials.loanBody')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
    @include('accounting.loans.jsPartials.companyBodyJs')
    @include('accounting.loans.jsPartials.loanBodyJs')
    <script type="text/javascript">
        $(function() {
            var start = moment().startOf('year');
            var end = moment().endOf('year');
            $('.daterange').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',
                startDate: start,
                endDate: end,
                locale: {cancelLabel: 'Clear'},
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')],
                }
            });
            $('.daterange').val('');
        });

        $(document).on('click', '.cancelBtn ', function () {
           $('.daterange').val('');
        });
    </script>
@endpush
