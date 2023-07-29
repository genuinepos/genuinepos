<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('ac_access')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $accounts = '';
            $query = DB::table('account_branches')
                ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
                ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
                ->leftJoin('branches', 'account_branches.branch_id', 'branches.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('account_branches.branch_id', NULL);
                } else {

                    $query->where('account_branches.branch_id', $request->branch_id);
                }
            }

            if ($request->account_type) {

                $query = $query->where('accounts.account_type', $request->account_type);
            }

            $query->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.opening_balance',
                'accounts.balance',
                'accounts.account_type',
                'banks.name as b_name',
                'banks.branch_name as b_branch',
                'branches.name as branch_name',
                'branches.branch_code',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $accounts = $query->orderBy('accounts.account_type', 'asc');
            } else {

                $accounts = $query->where('account_branches.branch_id', auth()->user()->branch_id)
                    ->orderBy('accounts.account_type', 'asc');
            }

            return DataTables::of($accounts)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a id="editAccount" class="dropdown-item" href="' . route('accounts.edit', [$row->id]) . '" > '. __("Edit") .'</a>';
                    $html .= '<a class="dropdown-item" href="' . route('accounts.ledgers', [$row->id]) . '">'.__('Ledger').'</a>';
                    $html .= '<a class="dropdown-item" href="' . route('accounts.delete', [$row->id]) . '" id="delete">' . __("Delete") . '</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })

                ->editColumn('ac_number', fn ($row) => $row->account_number ? $row->account_number : 'Not Applicable')

                ->editColumn('bank', fn ($row) => $row->b_name ? $row->b_name . ' (' . $row->b_branch . ')' : 'Not Applicable')

                ->editColumn('account_type', fn ($row) => '<b>' . $this->util->accountType($row->account_type) . '</b>')

                ->editColumn('branch', fn ($row) => '<b>' . ($row->branch_name ? $row->branch_name . '/' . $row->branch_code : $generalSettings['business__shop_name']) . '</b>')

                ->editColumn('opening_balance', fn ($row) => $this->converter->format_in_bdt($row->opening_balance))

                ->editColumn('balance', fn ($row) => $this->converter->format_in_bdt($row->balance))

                ->rawColumns(['action', 'ac_number', 'bank', 'account_type', 'branch', 'opening_balance', 'balance'])

                ->make(true);
        }

        $banks = DB::table('banks')->get();
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('accounting.accounts.index', compact('banks', 'branches'));
    }
}
