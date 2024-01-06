<style>
    .payment_top_card {
        background: #d7dfe8;
    }

    .payment_top_card span {
        font-size: 12px;
        font-weight: 400;
    }

    .payment_top_card li {
        font-size: 12px;
    }

    .payment_top_card ul {
        padding: 6px;
    }

    .payment_list_table {
        position: relative;
    }

    .payment_details_contant {
        background: azure !important;
    }
</style>
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>@lang('menu.company')/@lang('menu.people') </strong>{{ $company->name }}</li>
                    <li><strong>@lang('menu.phone') </strong>{{ $company->phone }}</li>
                    <li><strong>@lang('menu.address') </strong>{{ $company->address }}</li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li>
                        <p>
                            <b>@lang('menu.total_loan_advance') </b> {{ $generalSettings['business_or_shop__currency_symbol'] }}
                            {{ App\Utils\Converter::format_in_bdt($company->pay_loan_amount) }}
                        </p>
                    </li>

                    <li>
                        <p>
                            <b class="text-success">@lang('menu.total_received') </b> {{ $generalSettings['business_or_shop__currency_symbol'] }}
                            {{ App\Utils\Converter::format_in_bdt($company->total_receive) }}
                        </p>
                    </li>

                    <li>
                        <p>
                            <b class="text-danger">@lang('menu.total_due') </b> {{ $generalSettings['business_or_shop__currency_symbol'] }}
                            {{ App\Utils\Converter::format_in_bdt($company->pay_loan_due) }}
                        </p>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li>
                        <p>
                            <b>@lang('menu.total_loan_liability') </b> {{ $generalSettings['business_or_shop__currency_symbol'] }}
                            {{ App\Utils\Converter::format_in_bdt($company->get_loan_amount) }}
                        </p>
                    </li>

                    <li>
                        <p>
                            <b class="text-success">@lang('menu.total_paid') </b> {{ $generalSettings['business_or_shop__currency_symbol'] }}
                            {{ App\Utils\Converter::format_in_bdt($company->total_pay) }}
                        </p>
                    </li>

                    <li>
                        <p>
                            <b class="text-danger">@lang('menu.total_due') </b> {{ $generalSettings['business_or_shop__currency_symbol'] }}
                            {{ App\Utils\Converter::format_in_bdt($company->get_loan_due) }}
                        </p>
                    </li>
                </ul>
            </div>
        </div>


    </div>
</div>

<div class="payment_list_table">
    <div class="data_preloader payment_list_preloader">
        <h6><i class="fas fa-spinner"></i> @lang('menu.processing')...</h6>
    </div>
    <div class="table-responsive">
        <table class="table modal-table table-sm table-striped">
            <thead>
                <tr class="bg-secondary">
                    <th class="text-white text-start">@lang('menu.date')</th>
                    <th class="text-white text-start">@lang('menu.voucher_no')</th>
                    <th class="text-white text-start">@lang('menu.payment_type')</th>
                    <th class="text-white text-start">@lang('menu.method')</th>
                    <th class="text-white text-start">@lang('menu.account')</th>
                    <th class="text-white text-end">@lang('menu.amount')({{ $generalSettings['business_or_shop__currency'] }})</th>
                    <th class="text-white text-start">@lang('menu.action')</th>
                </tr>
            </thead>
            <tbody id="payment_list_body">
                @php $total = 0; @endphp
                @if (count($loan_payments) > 0)
                    @foreach ($loan_payments as $payment)
                        <tr>
                            <td class="text-start">
                                {{ date($generalSettings['business_or_shop__date_format'], strtotime($payment->date)) }}
                            </td>
                            <td class="text-start">{{ $payment->voucher_no }}</td>
                            <td class="text-start">
                                @if ($payment->payment_type == 1)
                                    <span class="text-success"><b>{{ __('Loan & Advance Due Receive') }}</b></span>
                                @else
                                    <span class="text-danger"><b>@lang('menu.loan_liability_due_payment')</b></span>
                                @endif
                            </td>
                            <td class="text-start">{{ $payment->payment_method ? $payment->payment_method : $payment->pay_mode }}</td>
                            <td class="text-start">{{ $payment->ac_name ? $payment->ac_name . ' (A/C: ' . $payment->ac_no . ')' : 'N/A' }}</td>
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
                        <th colspan="7" class="text-center"> @lang('menu.no_data_found')</th>
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
