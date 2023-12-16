<?php

namespace App\Services\Hrm;

use App\Models\Hrm\Shift;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ShiftService
{
    public function shiftsTable(): object
    {
        $shifts = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();

        return DataTables::of($shifts)
            ->addIndexColumn()
            ->editColumn('start_time', fn ($row) => date('h:i A', \strtotime($row->start_time)))
            ->editColumn('end_time', fn ($row) => date('h:i A', \strtotime($row->end_time)))
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';
                $html .= '<a href="' . route('hrm.shifts.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                $html .= '<a href="' . route('hrm.shifts.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                $html .= '</div>';

                return $html;
            })->rawColumns(['action'])->make(true);
    }

    public function addShift(object $request): object
    {
        $addShift = new Shift();

        $addShift->name = $request->name;
        $addShift->start_time = $request->start_time;
        $addShift->end_time = $request->end_time;
        $addShift->save();

        return $addShift;
    }

    public function updateShift(object $request, int $id): void
    {
        $updateShift = $this->singleShift(id: $id);

        $updateShift->name = $request->name;
        $updateShift->start_time = $request->start_time;
        $updateShift->end_time = $request->end_time;
        $updateShift->save();
    }


    public function deleteShift(int $id): void
    {
        $shift = $this->singleShift(id: $id);

        if (!is_null($shift)) {

            $shift->delete();
        }
    }

    public function singleShift(int $id, array $with = null): ?object
    {
        $query = Shift::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function shifts(array $with = null): ?object
    {
        $query = Shift::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function addValidation(object $request): ?array
    {
        return $request->validate([
            'name' => 'required|unique:hrm_shifts,name',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
    }

    public function updateValidation(object $request, int $id): ?array
    {
        return $request->validate([
            'name' => 'required|unique:hrm_shifts,name,' . $id,
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
    }
}
