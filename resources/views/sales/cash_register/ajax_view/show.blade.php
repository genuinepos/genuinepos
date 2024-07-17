@php
    use Carbon\Carbon;
@endphp

<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content" id="cash_register_details_content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Cash Register Details') }}
                (
                {{ Carbon::createFromFormat('Y-m-d H:i:s', $openedCashRegister->created_at)->format('jS M, Y h:i A') }}
                @if ($openedCashRegister->closed_at)
                    - {{ Carbon::createFromFormat('Y-m-d H:i:s', $openedCashRegister->closed_at)->format('jS M, Y h:i A') }}
                @else
                    - {{ Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('jS M, Y h:i A') }}
                @endif
                )
            </h6>

            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                <span class="fas fa-times"></span>
            </a>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6" style="border-right: 1px solid black;">
                    <div class="row">
                        <div class="col-md-12">
                            <p style="font-size: 11px!important;"><strong>{{ __('Received By Accounts') }}</strong></p>
                            <table class="cash_register_table table modal-table table-sm">
                                <tbody>
                                    @php
                                        $totalReceivedByAccount = 0;
                                    @endphp
                                    @foreach ($cashRegisterData['receivedByAccounts'] as $receivedByAccount)
                                        @php
                                            $accountNumber = $receivedByAccount->account_number ? ' / ' . $receivedByAccount->account_number : '';
                                            $totalReceivedByAccount += $receivedByAccount->total_received;
                                        @endphp
                                        <tr>
                                            <td class="text-end fw-bold" style="font-size: 11px!important;">{{ $receivedByAccount->name . $accountNumber }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                            <td class="text-end" style="font-size: 11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($receivedByAccount->total_received) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-end fw-bold" style="font-size: 11px!important;">{{ __('Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                        <td class="text-end fw-bold" style="font-size: 11px!important;">{{ App\Utils\Converter::format_in_bdt($totalReceivedByAccount) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <p style="font-size: 11px!important;"><strong>{{ __('Received By Payment Methods') }}</strong></p>
                            <table class="cash_register_table table modal-table table-sm">
                                <tbody>
                                    @php
                                        $totalReceivedByPaymentMethod = 0;
                                    @endphp
                                    @foreach ($cashRegisterData['receivedByPaymentMethods'] as $receivedByPaymentMethod)
                                        @php
                                            $totalReceivedByPaymentMethod += $receivedByPaymentMethod->total_received;
                                        @endphp
                                        <tr>
                                            <td class="text-end fw-bold" style="font-size: 11px!important;">{{ $receivedByPaymentMethod->name }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                            <td class="text-end" style="font-size: 11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($receivedByPaymentMethod->total_received) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-end fw-bold" style="font-size: 11px!important;">{{ __('Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                        <td class="text-end fw-bold" style="font-size: 11px!important;">{{ App\Utils\Converter::format_in_bdt($totalReceivedByPaymentMethod) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <p style="font-size: 11px!important;"><strong>{{ __('Sale Details') }}</strong></p>
                            <table class="cash_register_table table modal-table table-sm">
                                <tbody>
                                    <tr>
                                        <td class="text-end fw-bold" style="font-size: 11px!important;">
                                            {{ __('Total Sale') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                        </td>
                                        <td class="text-end" style="font-size: 11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($cashRegisterData['totalSaleAndDue']->sum('total_sale')) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-end fw-bold" style="font-size: 11px!important;">
                                            {{ __('Total Credit') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                        </td>
                                        <td class="text-end fw-bold" style="color: #dc3545!important; font-size: 11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($cashRegisterData['totalSaleAndDue']->sum('total_due')) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <p style="font-size: 11px!important;"><strong>{{ __('Total Cash Balance') }}</strong></p>
                            <table class="cash_register_table table modal-table table-sm">
                                <tbody>
                                    <tr>
                                        <td class="text-end fw-bold" style="font-size: 11px!important;">
                                            {{ __('Opening Cash') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                        </td>
                                        <td class="text-end" style="font-size: 11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($openedCashRegister->opening_cash) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-end fw-bold" style="font-size: 11px!important;">
                                            {{ __('Total Cash Received') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                        </td>
                                        <td class="text-end" style="font-size: 11px!important;">
                                            @php
                                                $totalCashBalance = $openedCashRegister->opening_cash + $cashRegisterData['totalCashReceived']->sum('total_cash_received');
                                            @endphp
                                            {{ App\Utils\Converter::format_in_bdt($cashRegisterData['totalCashReceived']->sum('total_cash_received')) }}
                                        </td>
                                    </tr>
                                <tfoot>
                                    <tr>
                                        <td class="text-end fw-bold" style="font-size: 11px!important;">
                                            {{ __('Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                        </td>
                                        <td class="text-end fw-bold" style="font-size: 11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($totalCashBalance) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="cash_register_info">
                <ul class="list-unstyled">
                    <li style="font-size: 11px!important;">
                        <b>{{ __('User') }} : </b> {{ $openedCashRegister?->user?->prefix . ' ' . $openedCashRegister?->user?->name . ' ' . $openedCashRegister?->user?->last_name }}
                    </li>

                    <li style="font-size: 11px!important;">
                        <b>{{ location_label() }} : </b>
                        @if ($openedCashRegister?->branch)
                            @if ($openedCashRegister?->branch?->parent_branch_id)
                                {{ $openedCashRegister?->branch?->parentBranch?->name }}
                            @else
                                {{ $openedCashRegister?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </li>

                    <li style="font-size: 11px!important;">
                        <b>{{ __('Cash Counter') }} : </b> {!! $openedCashRegister?->cashCounter?->counter_name . ' (<b>' . $openedCashRegister?->cashCounter?->short_name . '</b>)' !!}
                    </li>
                </ul>
            </div>

            <div class="form-group text-end mt-3">
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
