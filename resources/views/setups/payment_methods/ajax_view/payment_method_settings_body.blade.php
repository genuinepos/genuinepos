@foreach ($paymentMethods as $method)
    <tr>
        <td class="text-start">
            <b>{{ $loop->index + 1 }}.</b>
        </td>
        <td class="text-start">
            {{ $method->name }}
            <input type="hidden" name="payment_method_ids[]" value="{{ $method->id }}">
        </td>

        <td class="text-start">
            <select name="account_ids[]" id="account_id" class="form-control" autofocus>
                <option value="">{{ __('None') }}</option>
                @foreach ($accounts as $ac)
                    @php
                        $methodSetting = $method?->paymentMethodSetting;
                    @endphp

                    @if ($ac->is_bank_account == 1 && $ac->has_bank_access_branch == 0)
                        @continue
                    @endif

                    <option {{ isset($methodSetting) && $methodSetting->account_id == $ac->id ? 'SELECTED' : '' }} value="{{ $ac->id }}">
                        @php
                            $acNo = $ac->account_number ? ', A/c No : ' . $ac->account_number : '';
                            $bank = $ac?->bank ? ', Bank : ' . $ac?->bank?->name : '';
                        @endphp
                        {{ $ac->name . $acNo . $bank }}
                    </option>
                @endforeach
            </select>
        </td>
    </tr>
@endforeach
