<?php

namespace App\Services\Hrm;

use App\Models\Hrm\Designation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DesignationService
{
    public function designationsTable(): object
    {
        $designations = DB::table('hrm_designations')->orderBy('id', 'desc')->get();

        return DataTables::of($designations)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('designations_edit')) {

                    $html .= '<a href="' . route('hrm.designations.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('designations_delete')) {

                    $html .= '<a href="' . route('hrm.designations.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                }
                
                $html .= '</div>';

                return $html;
            })->rawColumns(['action'])->make(true);
    }

    public function addDesignation(object $request): object
    {
        $addDesignation = new Designation();
        $addDesignation->name = $request->name;
        $addDesignation->description = $request->description;
        $addDesignation->save();
        return $addDesignation;
    }

    public function updateDesignation(object $request, int $id): void
    {
        $updateDesignation = $this->singleDesignation(id: $id);
        $updateDesignation->name = $request->name;
        $updateDesignation->description = $request->description;
        $updateDesignation->save();
    }

    public function deleteDesignation(int $id): void
    {
        $deleteDesignation = $this->singleDesignation(id: $id);

        if (!is_null($deleteDesignation)) {

            $deleteDesignation->delete();
        }
    }

    public function singleDesignation(int $id, array $with = null)
    {
        $query = Designation::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    function storeValidation(object $request): ?array
    {
        return $request->validate([
            'name' => 'required|unique:hrm_designations,name',
        ]);
    }

    function updateValidation(object $request, int $id): ?array
    {
        return $request->validate([
            'name' => 'required|unique:hrm_designations,name,' . $id,
        ]);
    }
}
