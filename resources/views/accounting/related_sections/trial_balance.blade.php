@extends('layout.master')
@push('stylesheets') @endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">

            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-balance-scale-left"></span>
                    <h5>@lang('menu.trial_balance')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>

            <div class="p-3">
                <div class="card">
                    <div class="card-body">
                        <div class="data_preloader mt-5 pt-5"> <h6><i class="fas fa-spinner"></i> @lang('menu.processing')...</h6></div>
                        <div class="trial_balance_area">
                            <div class="print_header d-hide">
                                <div class="text-center pb-3">
                                    <h6>{{ $generalSettings['business__shop_name'] }}</h6>
                                    <h6><strong>@lang('menu.trial_balance')</h6>
                                </div>
                            </div>

                            <div id="data-list">
                                <table class="table modal-table table-sm table-bordered">
                                    <thead>
                                        <tr class="bg-secondary">
                                            <th class="trial_balance text-start text-white">@lang('menu.accounts')</th>
                                            <th class="debit text-white">@lang('menu.debit')</th>
                                            <th class="credit text-white">@lang('menu.credit')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-start"><strong>@lang('menu.supplier_balance') </strong> </td>

                                            <td>
                                                <em class="debit">0.00</em>
                                            </td>

                                            <td>
                                                <em class="credit">0.00</em>
                                            </td>
                                        </tr>

                                        <tr>
                                            @lang('menu.supplier_return_balance')     <td class="text-start"><strong> </strong> </td>

                                            <td>
                                                <em class="debit">0.00</em>
                                            </td>

                                            <td>
                                                <em class="credit">0.00</em>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-start"><strong>@lang('menu.customer_balance') </strong></td>

                                            <td>
                                                <em class="debit">0.00</em>
                                            </td>

                                            <td>
                                                <em class="credit">0.00</em>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-start"><strong>@lang('menu.customer_return_balance') </strong> </td>

                                            <td>
                                                <em class="debit">0.00</em>
                                            </td>

                                            <td>
                                                <em class="credit">0.00</em>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-start"><strong>@lang('menu.purchase_ac') </strong> </td>

                                            <td>
                                                <em class="debit">0.00</em>
                                            </td>

                                            <td>
                                                <em class="credit">0.00</em>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-start"><strong>@lang('menu.sale_ac') </strong> </td>

                                            <td>
                                                <em class="debit">0.00</em>
                                            </td>

                                            <td>
                                                <em class="credit">0.00</em>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-start"><strong>@lang('menu.opening_stock') </strong> </td>

                                            <td>
                                                <em class="debit">0.00</em>
                                            </td>

                                            <td>
                                                <em class="credit">0.00</em>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-start"><strong>@lang('menu.difference_in_opening_balance') </strong> </td>

                                            <td>
                                                <em class="debit">0.00</em>
                                            </td>

                                            <td>
                                                <em class="credit">0.00</em>
                                            </td>
                                        </tr>
                                    </tbody>

                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th class="text-white text-start">@lang('menu.total') </th>
                                            <th class="text-white">
                                                <span class="total_credit">0.00</span>
                                                {{ $generalSettings['business__currency'] }}
                                            </th>
                                            <th class="text-white">
                                                <span class="total_debit">0.00</span>
                                                {{ $generalSettings['business__currency'] }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="print_footer d-hide">
                                <div class="text-center">
                                    <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
                                </div>
                            </div>
                        </div>

                        <div class="print_btn_area">
                            <a id="print_btn" href="#" class="btn btn-sm btn-primary float-end"><i class="fas fa-print"></i>@lang('menu.print')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{ asset('assets/plugins/custom/print_this/printThis.js') }}"></script>
<script>
    // Set accounts in payment and payment edit form
    function getBalanceAmounts(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{route('accounting.trial.balance.amounts')}}",
            success:function(data){
              $('#data-list').html(data);
              $('.data_preloader').hide();
            }
        });
    }
    getBalanceAmounts();

    // Print single payment details
    $('#print_btn').on('click', function (e) {
        e.preventDefault();
        var body = $('.trial_balance_area').html();
        var header = $('.print_header').html();
        var footer = $('.print_footer').html();
        $(body).printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            loadCSS: "{{asset('assets/css/print/balance.sheet.print.css')}}",
            removeInline: false,
            printDelay: 600,
            header: header,
            footer: footer
        });
    });
</script>
@endpush
