<?php

namespace App\Services\Accounts;

use App\Models\Accounts\Bank;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BankService
{
    public function bankListTable(): ?object
    {
        $bank = DB::table('banks')->orderBy('name', 'asc')->get();

        return DataTables::of($bank)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown table-dropdown">';
                $html .= '<a href="'.route('banks.edit', [$row->id]).'" class="action-btn c-edit" id="editBank" title="Edit"><span class="fas fa-edit"></span></a>';
                $html .= '<a href="'.route('banks.delete', [$row->id]).'" class="action-btn c-delete" id="deleteBank" title="Delete"><span class="fas fa-trash"></span></a>';
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function addBank(object $request): ?Bank
    {
        return Bank::create(['name' => $request->name]);
    }

    public function updateBank(int $id, object $request): int
    {
        return Bank::where('id', $id)->update(['name' => $request->name]);
    }

    public function deleteBank(int $id): array
    {
        $deleteBank = Bank::with('accounts')->where('id', $id)->first();

        if (! is_null($deleteBank)) {

            if (count($deleteBank->accounts) > 0) {

                return ['success' => false, 'msg' => __('Bank can not be deleted')];
            }

            $deleteBank->delete();
        }

        return ['success' => true, 'msg' => __('Bank deleted successfully.')];
    }

    public function singleBank(int $id, array $with = null): ?Bank
    {
        $query = Bank::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function banks(array $with = null): object
    {
        $query = Bank::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
