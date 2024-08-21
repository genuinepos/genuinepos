<?php

namespace App\Services\Hrm;

use Carbon\Carbon;
use App\Models\Hrm\Department;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DepartmentService
{
    public function departmentsTable(): object
    {
        $departments = DB::table('hrm_departments')->orderBy('id', 'desc')->get();

        return DataTables::of($departments)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('departments_edit')) {

                    $html .= '<a href="' . route('hrm.departments.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('departments_delete')) {

                    $html .= '<a href="' . route('hrm.departments.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                }

                $html .= '</div>';

                return $html;
            })->rawColumns(['action'])->make(true);
    }

    public function addDepartment(object $request): object
    {
        $addDepartment = new Department();
        $addDepartment->name = $request->name;
        $addDepartment->description = $request->description;
        $addDepartment->save();
        return $addDepartment;
    }

    public function updateDepartment(object $request, int $id): void
    {
        $updateDepartment = $this->singleDepartment(id: $id);
        $updateDepartment->name = $request->name;
        $updateDepartment->description = $request->description;
        $updateDepartment->save();
    }

    public function deleteDepartment(int $id): void
    {
        $deleteDepartment = $this->singleDepartment(id: $id);

        if (!is_null($deleteDepartment)) {

            $deleteDepartment->delete();
        }
    }

    public function singleDepartment(int $id, array $with = null)
    {
        $query = Department::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function departments(array $with = null)
    {
        $query = Department::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
