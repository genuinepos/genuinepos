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
        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li>
                        <strong>@lang('menu.supplier') </strong>
                        {{ $customer->name }}
                    </li>
                    <li>
                        <strong>@lang('menu.business') </strong>
                        {{ $customer->business_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li>
                        <h6>
                            @lang('menu.total_paid') : {{ $generalSettings['business_or_shop__currency_symbol'] }}
                            <b class="text-success">{{ App\Utils\Converter::format_in_bdt($customer->total_paid) }}</b>
                        </h6>
                    </li>

                    <li>
                        <h6>
                            @lang('menu.total_sale_due') : {{ $generalSettings['business_or_shop__currency_symbol'] }}
                            <b class="text-danger">{{ App\Utils\Converter::format_in_bdt($customer->total_sale_due) }}</b>
                        </h6>
                    </li>

                    <li>
                        <h6>
                            @lang('menu.total_return_due') : {{ $generalSettings['business_or_shop__currency_symbol'] }}
                            <b class="text-danger"> {{ App\Utils\Converter::format_in_bdt($customer->total_sale_return_due) }}</b>
                        </h6>
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
        <table class="display modal-table table-sm table-striped">
            <thead>
                <tr class="bg-primary">
                    <th class="text-white text-start">@lang('menu.date')</th>
                    <th class="text-white text-start">@lang('menu.voucher_no')</th>
                    <th class="text-white text-start">@lang('menu.type')</th>
                    <th class="text-white text-start">@lang('menu.method')</th>
                    <th class="text-white text-start">@lang('menu.account')</th>
                    <th class="text-white text-end">@lang('menu.amount')({{ $generalSettings['business_or_shop__currency'] }})</th>
                    <th class="text-white text-start">@lang('menu.action')</th>
                </tr>
            </thead>
            <tbody id="payment_list_body">
                @php $total = 0; @endphp
                @if (count($customer_payments) > 0)
                    @foreach ($customer_payments as $payment)
                        <tr>
                            <td class="text-start">
                                {{ date($generalSettings['business_or_shop__date_format'], strtotime($payment->date)) }}
                            </td>
                            <td class="text-start">{{ $payment->voucher_no }}</td>
                            <td class="text-start">{{ $payment->type == 1 ? 'Sale Due' : 'Return due' }}</td>
                            <td class="text-start">{{ $payment->payment_method ? $payment->payment_method : $payment->pay_mode }}</td>
                            <td class="text-start">{{ $payment->ac_name ? $payment->ac_name . ' (A/C: ' . $payment->ac_no . ')' : 'N/A' }}</td>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}
                                @php
                                    $total += $payment->paid_amount;
                                @endphp
                            </td>
                            <td class="text-start">
                                <a href="{{ route('customers.view.details', $payment->id) }}" id="payment_details" class="btn-sm"><i class="fas fa-eye text-primary"></i></a>
                                <a href="{{ route('customers.payment.delete', $payment->id) }}" id="delete_payment" class="btn-sm"><i class="far fa-trash-alt text-danger"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <th colspan="7" class="text-center"> @lang('menu.no_data_found')</th>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="bg-secondary">
                    <th colspan="5" class="text-white text-end"> <b>@lang('menu.total') : {{ $generalSettings['business_or_shop__currency'] }}</b> </th>
                    <th class="text-white text-end"><b>{{ App\Utils\Converter::format_in_bdt($total) }}</b></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <form id="deleted_payment_form" action="" method="post">
            @method('DELETE')
            @csrf
        </form>
    </div>
</div>
