<?php

namespace App\Services\Services;

use App\Models\Services\Status;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StatusService
{
    public function statusTable(): object
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $status = DB::table('service_status')
            ->leftJoin('users', 'service_status.created_by_id', 'users.id')
            ->where('service_status.branch_id', $ownBranchIdOrParentBranchId)
            ->select('service_status.*', 'users.prefix as user_prefix', 'users.name as user_name', 'users.last_name as user_last_name')
            ->orderByRaw('ISNULL(sort_order), sort_order ASC');

        return DataTables::of($status)
            // ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                // if (auth()->user()->can('product_brand_edit')) {

                $html .= '<a href="' . route('services.settings.status.edit', [$row->id]) . '" class="action-btn c-edit" id="editStatus" title="Edit"><span class="fas fa-edit"></span></a>';
                // }

                // if (auth()->user()->can('product_brand_delete')) {

                $html .= '<a href="' . route('services.settings.status.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteStatus" title="Delete"><span class="fas fa-trash "></span></a>';
                // }
                $html .= '</div>';

                return $html;
            })->editColumn('color', function ($row) {

                $color = '<span style="color:' . $row->color_code . '"><i class="fa-solid fa-circle"></i></span>';
                return $row->color_code . ' ' . $color;
            })->editColumn('created_by', function ($row) {

                return $row->user_prefix . ' ' . $row->user_name . ' ' . $row->user_last_name;
            })
            ->rawColumns(['color', 'created_by', 'action'])->make(true);
    }

    public function addStatus(object $request): object
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        $addStatus = new Status();
        $addStatus->branch_id = $ownBranchIdOrParentBranchId;
        $addStatus->name = $request->name;
        $addStatus->color_code = $request->color_code;
        $addStatus->sort_order = $request->sort_order;
        $addStatus->status_as_complete = $request->status_as_complete;
        $addStatus->sms_template = $request->sms_template;
        $addStatus->email_subject = $request->email_subject;
        $addStatus->email_body = $request->email_body;
        $addStatus->created_by_id = auth()->user()->id;
        $addStatus->save();

        return $addStatus;
    }

    public function updateStatus(int $id, object $request): void
    {
        $updateStatus = $this->singleStatus(id: $id);
        $updateStatus->name = $request->name;
        $updateStatus->color_code = $request->color_code;
        $updateStatus->sort_order = $request->sort_order;
        $updateStatus->status_as_complete = $request->status_as_complete;
        $updateStatus->sms_template = $request->sms_template;
        $updateStatus->email_subject = $request->email_subject;
        $updateStatus->email_body = $request->email_body;
        $updateStatus->save();
    }

    public function deleteStatus(int $id): void
    {
        $deleteStatus = $this->singleStatus(id: $id);

        if (isset($deleteStatus)) {

            $deleteStatus->delete();
        }
    }

    public function singleStatus(int $id, array $with = null): ?object
    {
        $query = Status::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function allStatus(array $with = null): ?object
    {
        $query = Status::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
