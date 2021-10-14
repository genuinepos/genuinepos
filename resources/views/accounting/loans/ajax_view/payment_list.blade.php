<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
</style>
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>Company/People : </strong>{{ $company->name  }}</li>
                    <li><strong>Phone : </strong>{{ $company->phone }}</li>
                    <li><strong>Address : </strong>{{ $company->address }}</li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li> 
                        <h6>
                            Total Get Loan : {{ json_decode($generalSettings->business, true)['currency'] }} 
                            <b class="text-success">{{ App\Utils\Converter::format_in_bdt($company->get_loan_amount) }}</b> 
                        </h6>
                    </li>

                    <li>
                        <h6>
                            Total Due Paid : {{ json_decode($generalSettings->business, true)['currency'] }}
                            <b class="text-danger">{{ App\Utils\Converter::format_in_bdt($company->total_pay) }}</b> 
                        </h6>
                    </li>

                    <li>
                        <h6>
                            Total Payment Due : {{ json_decode($generalSettings->business, true)['currency'] }}
                            <b class="text-danger"> {{ App\Utils\Converter::format_in_bdt($company->get_loan_due) }}</b> 
                        </h6>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li> 
                        <h6>
                            Total Pay Loan : {{ json_decode($generalSettings->business, true)['currency'] }} 
                            <b class="text-success">{{ App\Utils\Converter::format_in_bdt($company->pay_loan_amount) }}</b> 
                        </h6>
                    </li>

                    <li>
                        <h6>
                            Total Due Paid : {{ json_decode($generalSettings->business, true)['currency'] }}
                            <b class="text-danger">{{ App\Utils\Converter::format_in_bdt($company->total_receive) }}</b> 
                        </h6>
                    </li>

                    <li>
                        <h6>
                            Total Payment Due : {{ json_decode($generalSettings->business, true)['currency'] }}
                            <b class="text-danger"> {{ App\Utils\Converter::format_in_bdt($company->pay_loan_due) }}</b> 
                        </h6>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="payment_list_table">
    <div class="data_preloader payment_list_preloader">
        <h6><i class="fas fa-spinner"></i> Processing...</h6>
    </div>
    <div class="table-responsive">
        <table class="table modal-table table-sm table-striped">
            <thead>
                <tr class="bg-primary">
                    <th class="text-white text-start">Date</th>
                    <th class="text-white text-start">Voucher No</th>
                    <th class="text-white text-start">Payment Type</th>
                    <th class="text-white text-start">Method</th>
                    <th class="text-white text-start">Account</th>
                    <th class="text-white text-end">Amount({{ json_decode($generalSettings->business, true)['currency']}})</th>
                    <th class="text-white text-start">Action</th>
                </tr>
            </thead>
            <tbody id="payment_list_body">
                @php $total = 0; @endphp
                @if (count($loan_payments) > 0)
                    @foreach ($loan_payments as $payment)
                        <tr>
                            <td class="text-start">
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) }}
                            </td>
                            <td class="text-start">{{ $payment->voucher_no }}</td>
                            <td class="text-start">{{ $payment->payment_type == 1 ? 'Pay Loan Due Receive' : 'Get Loan Due Paid' }}</td>
                            <td class="text-start">{{ $payment->pay_mode }}</td>
                            <td class="text-start">{{ $payment->ac_name ? $payment->ac_name.' (A/C: '.$payment->ac_no.')' : 'N/A' }}</td>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}
                            </td>
                            <td class="text-start">
                                <a href="" id="payment_details" class="btn-sm"><i class="fas fa-eye text-primary"></i></a>
                                <a href="{{ route('accounting.loan.payment.delete', $payment->id) }}" id="delete_payment" class="btn-sm"><i class="far fa-trash-alt text-danger"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <th colspan="7" class="text-center"> No Data Found</th>
                    </tr>
                @endif
            </tbody>
        </table>

        <form id="deleted_payment_form" action="" method="post">
            @method('DELETE')
            @csrf
        </form>
    </div>
</div>
