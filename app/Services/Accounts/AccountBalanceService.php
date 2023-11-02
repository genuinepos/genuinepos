<?php

namespace App\Services\Accounts;

use Illuminate\Support\Facades\DB;

class AccountBalanceService
{
    public function accountBalance(object $request, int $accountId): array
    {
        $account = DB::table('accounts')->where('accounts.id', $accountId)
            ->leftJoin('contacts', 'accounts.contact_id', 'contacts.id')
            ->select('contacts.reward_point')
            ->first();

        return [
            'reward_point' => $account->reward_point ? $account->reward_point : 0,
        ];
    }
}
