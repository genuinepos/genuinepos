<?php

namespace App\Services\Hrm;

use App\Models\Hrm\Allowance;
use Illuminate\Support\Facades\DB;
use App\Enums\AllowanceAndDeductionType;
use Yajra\DataTables\Facades\DataTables;

class AllowanceAndDeductionService
{
    public function allowancesAndDeductionsTable(): object
    {
        $allowancesAndDeductions = DB::table('hrm_allowances')->orderBy('id', 'desc')->get();

        return DataTables::of($allowancesAndDeductions)
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('allowances_edit') || auth()->user()->can('deductions_edit')) {

                    $html .= '<a href="' . route('hrm.allowances.deductions.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('allowances_edit') || auth()->user()->can('deductions_edit')) {

                    $html .= '<a href="' . route('hrm.allowances.deductions.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                }

                $html .= '</div>';

                return $html;
            })
            ->editColumn('type', function ($row) {

                return $row->type == AllowanceAndDeductionType::Allowance->value ? __('Allowance') : __('Deduction');
            })
            ->editColumn('amount', function ($row) {

                $amountType = $row->amount_type == 1 ?  '(' . __('Fixed') . ')' : '%';
                return \App\Utils\Converter::format_in_bdt($row->amount) . $amountType;
            })
            ->rawColumns(['action', 'type', 'amount'])->make(true);
    }

    public function addAllowanceOrDeduction(object $request): ?string
    {
        $addAllowance = new Allowance();
        $addAllowance->name = $request->name;
        $addAllowance->type = $request->type;
        $addAllowance->amount_type = $request->amount_type;
        $addAllowance->amount = $request->amount;
        $addAllowance->save();

        return $addAllowance->type ==  AllowanceAndDeductionType::Allowance->value ? __('Allowance') : __('Deduction');
    }

    public function updateAllowanceOrDeduction(object $request, int $id): ?string
    {
        $updateAllowance = $this->singleAllowanceOrDeduction(id: $id);
        $updateAllowance->name = $request->name;
        $updateAllowance->type = $request->type;
        $updateAllowance->amount_type = $request->amount_type;
        $updateAllowance->amount = $request->amount;
        $updateAllowance->save();

        return $updateAllowance->type ==  AllowanceAndDeductionType::Allowance->value ? __('Allowance') : __('Deduction');
    }

    public function deleteAllowanceOrDeduction(int $id): string
    {
        $deleteAllowanceOrDeduction = $this->singleAllowanceOrDeduction(id: $id);

        if (!is_null($deleteAllowanceOrDeduction)) {

            $deleteAllowanceOrDeduction->delete();
        }

        return $deleteAllowanceOrDeduction->type ==  AllowanceAndDeductionType::Allowance->value ? __('Allowance') : __('Deduction');
    }

    public function singleAllowanceOrDeduction(int $id, array $with = null): ?object
    {
        $query = Allowance::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }


    public function storeValidation(object $request): ?array
    {
        return $request->validate([
            'name' => 'required|unique:hrm_allowances,name',
            'amount' => 'required',
        ]);
    }

    public function updateValidation(object $request, int $id): ?array
    {
        return $request->validate([
            'name' => 'required|unique:hrm_allowances,name,' . $id,
            'amount' => 'required',
        ]);
    }
}
