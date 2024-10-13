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

    public function addBank(object $request): ?object
    {
        return Bank::create(['name' => $request->name]);
    }

    public function updateBank(int $id, object $request): object
    {
        $updateBank = $this->singleBank(id: $id);
        $updateBank->name = $request->name;
        $updateBank->save();

        return $updateBank;
    }

    public function deleteBank(int $id): array|object
    {
        $deleteBank = $this->singleBank(id: $id, with: ['accounts']);

        if (! is_null($deleteBank)) {

            if (count($deleteBank->accounts) > 0) {

                return ['pass' => false, 'msg' => __('Bank can not be deleted, This bank has already been attached with bank account')];
            }

            $deleteBank->delete();
        }

        return $deleteBank;
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
