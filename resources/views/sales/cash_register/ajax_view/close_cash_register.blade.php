@php
    use Carbon\Carbon;
@endphp

<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content" id="cash_register_details_content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Close Cash Register') }}
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
            <form id="close_cash_register_form" action="{{ route('cash.register.closed', $openedCashRegister->id) }}" method="post">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()?->user()?->id }}">
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
                                                <td class="text-end fw-bold" style="font-size: 11px!important;">{{ $receivedByAccount->name . $accountNumber }} : </td>
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
                                                <td class="text-end fw-bold" style="font-size: 11px!important;">{{ $receivedByPaymentMethod->name }} : </td>
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
                                                {{ __('Opening Cash') }} :
                                            </td>
                                            <td class="text-end" style="font-size: 11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($openedCashRegister->opening_cash) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-end fw-bold" style="font-size: 11px!important;">
                                                {{ __('Total Cash Received') }} :
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

                <div class="row">
                    <div class="col-md-4">
                        <label class="fw-bold">{{ __('Closing Cash') }}</label>
                        <input readonly type="text" step="any" name="closing_cash" class="form-control fw-bold" id="closing_cash" data-next="closing_note" value="{{ $totalCashBalance }}">
                        <span class="error error_colsing_cash_register_closing_cash"></span>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-md-12">
                        <label class="fw-bold">{{ __('Closing Note') }}</label>
                        <input type="text" name="closing_note" class="form-control" id="closing_note" data-next="close_btn" placeholder="{{ __('Closing Note') }}">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button close_cash_register_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="close_btn" class="btn btn-sm btn-success close_cash_register_submit_btn">{{ __('Close Cash Register') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('sales.cash_register.ajax_view.js_partial.close_cash_register_js')
