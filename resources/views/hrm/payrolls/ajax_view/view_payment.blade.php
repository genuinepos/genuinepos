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
                    <li><strong>{{ __('Employee') }} </strong>{{ $payroll->employee->prefix.' '.$payroll->employee->name.' '.$payroll->employee->last_name }}
                    </li>

                    <li><strong>@lang('menu.branch') </strong>
                        @if ($payroll->employee->branch)
                            {{ $payroll->employee->branch->name . '/' . $payroll->employee->branch->branch_code }}
                        @else
                            {{ $generalSettings['business__business_name'] }} (<b>@lang('menu.head_office')
                            </b>)
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong> @lang('menu.reference_no') </strong>{{ $payroll->reference_no }} </li>

                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>@lang('menu.total_due') : {{ $generalSettings['business__currency'] }}
                        </strong>{{ $payroll->due }} </li>
                    <li><strong>@lang('menu.date') </strong>{{ date($generalSettings['business__date_format'], strtotime($payroll->date))}} </li>
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
                    <th class="text-white">@lang('menu.date')</th>
                    <th class="text-white">@lang('menu.voucher_no')</th>
                    <th class="text-white">@lang('menu.amount')</th>
                    <th class="text-white">@lang('menu.method')</th>
                    <th class="text-white">@lang('menu.account')</th>
                    <th class="text-white">@lang('menu.action')</th>
                </tr>
            </thead>
            <tbody id="payment_list_body">
                @if (count($payroll->payments) > 0)
                    @foreach ($payroll->payments as $payment)
                        <tr>
                            <td>{{ date($generalSettings['business__date_format'], strtotime($payment->date)) }}</td>
                            <td>{{ $payment->reference_no }}</td>
                            <td>{{ $generalSettings['business__currency'] . ' ' . $payment->paid }}
                            </td>
                            <td>{{ $payment->paymentMethod ? $payment->paymentMethod->name : $payment->pay_mode }}</td>
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
                        <td colspan="6" class="text-center">@lang('menu.no_data_found')</td>
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