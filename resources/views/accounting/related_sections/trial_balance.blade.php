@extends('layout.master')
@push('stylesheets') @endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">

                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-balance-scale-left"></span>
                                <h5>Trial Balance</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="p-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="data_preloader mt-5 pt-5"> <h6><i class="fas fa-spinner"></i> Processing...</h6></div>
                                    <div class="trial_balance_area">
                                        <div class="print_header d-none">
                                            <div class="text-center pb-3">
                                                <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
                                                <h6><strong>TRIAL BALANCE</h6>
                                            </div>
                                        </div>

                                        <div id="data-list">
                                            <table class="table modal-table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-primary">
                                                        <th class="trial_balance text-start text-white">Accounts</th>
                                                        <th class="debit text-white">Debit</th>
                                                        <th class="credit text-white">Credit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-start"><strong>Supplier Balance :</strong> </td>

                                                        <td>
                                                            <em class="debit">0.00</em>
                                                        </td>

                                                        <td>
                                                            <em class="credit">0.00</em>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start"><strong>Supplier Return Balance :</strong> </td>

                                                        <td>
                                                            <em class="debit">0.00</em>
                                                        </td>

                                                        <td>
                                                            <em class="credit">0.00</em>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start"><strong>Customer Balance :</strong></td>

                                                        <td>
                                                            <em class="debit">0.00</em>
                                                        </td>

                                                        <td>
                                                            <em class="credit">0.00</em>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start"><strong>Customer Return Balance :</strong> </td>

                                                        <td>
                                                            <em class="debit">0.00</em>
                                                        </td>

                                                        <td>
                                                            <em class="credit">0.00</em>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start"><strong>Purchase A/C :</strong> </td>

                                                        <td>
                                                            <em class="debit">0.00</em>
                                                        </td>

                                                        <td>
                                                            <em class="credit">0.00</em>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start"><strong>Sale A/C :</strong> </td>

                                                        <td>
                                                            <em class="debit">0.00</em>
                                                        </td>

                                                        <td>
                                                            <em class="credit">0.00</em>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start"><strong>Opening Stock :</strong> </td>

                                                        <td>
                                                            <em class="debit">0.00</em>
                                                        </td>

                                                        <td>
                                                            <em class="credit">0.00</em>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start"><strong>Difference In Opening Balance :</strong> </td>

                                                        <td>
                                                            <em class="debit">0.00</em>
                                                        </td>

                                                        <td>
                                                            <em class="credit">0.00</em>
                                                        </td>
                                                    </tr>
                                                </tbody>

                                                <tfoot>
                                                    <tr class="bg-primary">
                                                        <th class="text-white text-start">Total :</th>
                                                        <th class="text-white">
                                                            <span class="total_credit">0.00</span>
                                                            {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        </th>
                                                        <th class="text-white">
                                                            <span class="total_debit">0.00</span>
                                                            {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <div class="print_footer d-none">
                                            <div class="text-center">
                                                <small>Software by <b>SpeedDigit Pvt. Ltd.</b></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="print_btn_area">
                                        <a id="print_btn" href="#" class="btn btn-sm btn-primary float-end"><i class="fas fa-print"></i> Print</a>
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
