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
        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>Supplier : </strong>
                        {{ $customer->name  }}
                    </li>
                    <li><strong>Business : </strong>
                        {{ $customer->business_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>Total Sale Due : {{ json_decode($generalSettings->business, true)['currency'] }}
                        </strong>{{ $customer->total_sale_due > 0 ?  $customer->total_sale_due : bcadd(0, 0, 2) }}</li>
                    <li><strong>Total Return Due : {{ json_decode($generalSettings->business, true)['currency'] }}
                        </strong>{{ $customer->total_sale_return_due > 0 ? $customer->total_sale_return_due : bcadd(0, 0, 2) }}</li>
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
                    <th class="text-white">Date</th>
                    <th class="text-white">Voucher No</th>
                    <th class="text-white">Type</th>
                    <th class="text-white">Method</th>
                    <th class="text-white">Account</th>
                    <th class="text-white">Amount</th>
                    <th class="text-white">Action</th>
                </tr>
            </thead>
            <tbody id="payment_list_body">
                @php
                    $total = 0;
                @endphp
                @if (count($customer->customer_payments) > 0)
                    @foreach ($customer->customer_payments as $payment)
                        <tr>
                            <td>
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) }}
                            </td>
                            <td>{{ $payment->voucher_no }}</td>
                            <td>{{ $payment->type == 1 ? 'Sale Due' : 'Return due' }}</td>
                            <td>{{ $payment->pay_mode }}</td>
                            <td>{{ $payment->account ? $payment->account->name : 'N/A' }}</td>
                            <td>
                                {{ json_decode($generalSettings->business, true)['currency'] . ' ' . $payment->paid_amount }}
                                @php
                                    $total += $payment->paid_amount;
                                @endphp
                            </td>
                            <td>
                                <a href="{{ route('customers.view.details', $payment->id) }}" id="payment_details" class="btn-sm"><i class="fas fa-eye text-primary"></i></a>
                                <a href="{{ route('customers.payment.delete', $payment->id) }}" id="delete_payment" class="btn-sm"><i class="far fa-trash-alt text-danger"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">No Data Found</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="bg-secondary">
                    <th colspan="5" class="text-white text-end"> <b>Total :</b> </th>
                    <th class="text-white"><b>{{json_decode($generalSettings->business, true)['currency'] . ' ' . bcadd($total, 0, 2) }}</b></th>
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
