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
                    <li><strong>Employee : </strong>{{ $payroll->employee->prefix.' '.$payroll->employee->name.' '.$payroll->employee->last_name }}
                    </li>

                    <li><strong>Branch: </strong>
                        @if ($payroll->employee->branch)
                            {{ $payroll->employee->branch->name . '/' . $payroll->employee->branch->branch_code }}
                        @else
                            {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head
                            Office</b>)
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong> Referance No : </strong>{{ $payroll->reference_no }} </li>
                    
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>Total Due : {{ json_decode($generalSettings->business, true)['currency'] }}
                        </strong>{{ $payroll->due }} </li>
                    <li><strong>Date : </strong>{{ $payroll->date }} </li>
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
                    <th class="text-white">Amount</th>
                    <th class="text-white">Method</th>
                    <th class="text-white">Account</th>
                    <th class="text-white">Action</th>
                </tr>
            </thead>
            <tbody id="payment_list_body">
                @if (count($payroll->payments) > 0)
                    @foreach ($payroll->payments as $payment)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($payment->date)) }}</td>
                            <td>{{ $payment->reference_no }}</td>
                            <td>{{ json_decode($generalSettings->business, true)['currency'] . ' ' . $payment->paid }}
                            </td>
                            <td>{{ $payment->pay_mode }}</td>
                            <td>{{ $payment->account ? $payment->account->name : 'N/A' }}</td>
                            <td>
                                @if (auth()->user()->branch_id == $payroll->employee->branch_id)
                                    <a href="{{ route('hrm.payrolls.payment.edit', $payment->id) }}"
                                        id="edit_payment" class="btn-sm"><i
                                            class="fas fa-edit text-info"></i></a>
                                    <a href="{{ route('hrm.payrolls.payment.details', $payment->id) }}" id="payment_details"
                                        class="btn-sm"><i class="fas fa-eye text-primary"></i></a>
                                    <a href="{{ route('hrm.payrolls.payment.delete', $payment->id) }}" id="delete_payment"
                                        class="btn-sm"><i class="far fa-trash-alt text-danger"></i></a>
                                @else
                                    .....
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No Data Found</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <form id="payment_deleted_form" action="" method="post">
            @method('DELETE')
            @csrf
        </form>
    </div>
</div>