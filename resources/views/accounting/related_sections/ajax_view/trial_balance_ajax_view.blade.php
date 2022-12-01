@php
    $totalDebit = 0;
    $totalCredit = 0;
@endphp
<style>
    .debit {font-weight: 450;}
    .credit {font-weight: 450;}
</style>
<table class="table modal-table table-sm table-bordered">
    <thead>
        <tr class="bg-secondary">
            <th class="trial_balance text-start text-white">Accounts</th>
            <th class="text-white text-end">@lang('menu.debit')</th>
            <th class="text-white text-end">@lang('menu.credit')</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-start"><em>Supplier Balance :</em> </td>

            <td class="text-end">
                <em class="debit">0.00</em> 
            </td>

            <td class="text-end">
                <em class="credit">{{ App\Utils\Converter::format_in_bdt($suppliers->sum('balance')) }}</em> 
                @php
                    $totalCredit += $suppliers->sum('balance');
                @endphp
            </td>
        </tr>

        <tr>
            <td class="text-start"><em>Supplier Return Balance :</em> </td>

            <td class="text-end">
                <em class="debit">{{ App\Utils\Converter::format_in_bdt($suppliers->sum('return_balance')) }}</em> 
                @php
                    $totalDebit += $suppliers->sum('return_balance');
                @endphp
            </td>

            <td class="text-end">
                <em class="credit">0.00</em>
            </td>
        </tr>

        <tr>
            <td class="text-start"><em>Customer Balance :</em></td>

            <td class="text-end">
                <em class="debit">{{ App\Utils\Converter::format_in_bdt($customers->sum('balance')) }}</em>
                @php
                    $totalDebit += $customers->sum('balance');
                @endphp
            </td>

            <td class="text-end">
                <em class="credit">0.00</em>
            </td>
        </tr>

        <tr>
            <td class="text-start"><em>Customer Return Balance :</em> </td>

            <td class="text-end">
                <em class="debit">0.00</em>
            </td>

            <td class="text-end">
                <em class="credit">{{ App\Utils\Converter::format_in_bdt($customers->sum('return_balance')) }}</em>
                @php
                    $totalCredit += $customers->sum('return_balance');
                @endphp
            </td>
        </tr>

        @foreach ($accounts as $account)
            <tr>
                <td class="text-start"><em>{{ App\Utils\Util::accountType($account->account_type) }}</em> </td>

                <td class="text-end">
                    <em class="debit">
                        @if ($accountUtil->accountBalanceType($account->account_type) == 'debit')
                            {{ App\Utils\Converter::format_in_bdt($account->total_balance)  }}
                            @php
                                $totalDebit += $account->total_balance;
                            @endphp
                        @else 
                            0.00
                        @endif
                    </em>
                </td>

                <td class="text-end">
                    <em class="credit">
                        @if ($accountUtil->accountBalanceType($account->account_type) == 'credit')
                            {{ App\Utils\Converter::format_in_bdt($account->total_balance) }}
                            @php
                                $totalCredit += $account->total_balance;
                            @endphp
                        @else 
                            0.00
                        @endif
                    </em>
                </td>
            </tr>
        @endforeach
        
        <tr>
            <td class="text-start"><em>Opening Stock :</em> </td>

            <td class="text-end">
                <em class="debit">{{ App\Utils\Converter::format_in_bdt($openingStock->sum('total_value')) }}</em>
                @php
                    $totalDebit += $openingStock->sum('total_value');
                @endphp
            </td>

            <td class="text-end">
                <em class="credit">0.00</em>
            </td>
        </tr>

        <tr>
            <td class="text-start"><em>Difference In Opening Balance :</em> </td>

            <td class="text-end">
                <em class="debit">0.00</em>
            </td>

            <td class="text-end">
                @php
                    $diff = $totalDebit - $totalCredit;
                    $totalCredit += $diff;
                @endphp
                <em class="credit">{{ App\Utils\Converter::format_in_bdt($diff) }}</em>
            </td>
        </tr>
    </tbody>

    <tfoot>
        <tr class="bg-secondary">
            <th class="text-white text-end"><em>Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</em></th>
            
            <th class="text-white text-end">
                <em class="total_debit">{{ App\Utils\Converter::format_in_bdt($totalDebit) }}</em> 
            </th>

            <th class="text-white text-end">
                <em class="total_credit">{{ App\Utils\Converter::format_in_bdt($totalCredit) }}</em> 
             </th>
        </tr>
    </tfoot>
</table>