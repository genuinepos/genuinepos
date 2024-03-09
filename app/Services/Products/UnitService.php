<?php

namespace App\Services\Products;

use App\Models\Products\Unit;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UnitService
{
    public function unitsTable(): ?object
    {
        $units = DB::table('units')->leftJoin('units as baseUnit', 'units.base_unit_id', 'baseUnit.id')
            ->select(
                'units.id',
                'units.name',
                'units.code_name',
                'units.base_unit_multiplier',
                'baseUnit.name as base_unit_name',
                'baseUnit.code_name as base_unit_code_name',
            )->orderByRaw('COALESCE(units.base_unit_id, units.id), units.id')->orderBy('name', 'asc');

        return DataTables::of($units)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown table-dropdown">';

                $html .= '<a href="'.route('units.edit', $row->id).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                //
                $html .= '<a href="'.route('units.delete', $row->id).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';

                $html .= '</div>';

                return $html;
            })->editColumn('name', function ($row) {

                $baseUnit = '';
                if ($row->base_unit_name) {

                    $baseUnit .= '(<strong>'.$row->base_unit_multiplier.' '.$row->base_unit_name.'</strong>)';
                }

                return $row->name.$baseUnit;
            })->editColumn('base_unit_name', function ($row) {

                if ($row->base_unit_name) {

                    return $row->base_unit_name.'('.$row->base_unit_code_name.')';
                }
            })->editColumn('multiplierUnitDetails', function ($row) {

                $multipleUnitDetails = '';
                if ($row->base_unit_name) {

                    $multipleUnitDetails .= __('1').' '.$row->name.' = '.$row->base_unit_multiplier.' '.$row->base_unit_code_name;
                }

                return $multipleUnitDetails;
            })
            ->rawColumns(['action', 'name', 'base_unit_name', 'multipleUnitDetails'])
            ->smart(true)
            ->make(true);
    }

    public function addUnit($request): object
    {
        $addUnit = new Unit();
        $addUnit->name = $request->name;
        $addUnit->code_name = $request->short_name;
        $addUnit->base_unit_multiplier = $request->as_a_multiplier_of_other_unit == 1 ? $request->base_unit_multiplier : null;
        $addUnit->base_unit_id = $request->as_a_multiplier_of_other_unit == 1 ? $request->base_unit_id : null;
        $addUnit->created_by_id = auth()?->user()?->id;
        $addUnit->save();

        return $addUnit;
    }

    public function updateUnit(int $id, object $request): ?object
    {
        $updateUnit = $this->singleUnit($id);
        $updateUnit->name = $request->name;
        $updateUnit->code_name = $request->short_name;
        $updateUnit->base_unit_multiplier = $request->as_a_multiplier_of_other_unit == 1 ? $request->base_unit_multiplier : null;
        $updateUnit->base_unit_id = $request->as_a_multiplier_of_other_unit == 1 ? $request->base_unit_id : null;
        $updateUnit->save();

        return $updateUnit;
    }

    public function deleteUnit(int $id): ?array
    {
        $deleteUnit = $this->singleUnit(id: $id, with: ['childUnits']);

        if (count($deleteUnit->childUnits)) {

            return ['pass' => false, 'msg' => __('Unit can not be deleted. This unit is a base unit for one or many units.')];
        }

        if (! is_null($deleteUnit)) {

            $deleteUnit->delete();
        }

        return ['pass' => true, 'data' => $deleteUnit];
    }

    public function singleUnit(int $id, array $with = null)
    {
        $query = Unit::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function units(array $with = null)
    {
        $query = Unit::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
