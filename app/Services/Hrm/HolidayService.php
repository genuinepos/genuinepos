<?php

namespace App\Services\Hrm;

use App\Enums\RoleType;
use App\Enums\BooleanType;
use App\Models\Hrm\Holiday;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class HolidayService
{
    public function holidaysTable(object $request): object
    {
        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        $generalSettings = config('generalSettings');
        $holidays = '';
        $query = Holiday::query()->with([
            'allowedBranches',
            'allowedBranches.branch:id,name,area_name,branch_code,parent_branch_id',
            'allowedBranches.branch.parentBranch:id,name,area_name,branch_code',
        ])->leftJoin('hrm_holiday_branches', 'hrm_holidays.id', 'hrm_holiday_branches.holiday_id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('hrm_holiday_branches.branch_id', null);
            } else {

                $query->where('hrm_holiday_branches.branch_id', $request->branch_id);
            }
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('hrm_holiday_branches.branch_id', $ownBranchIdOrParentBranchId);
        }

        $holidays = $query->select(
            [
                'hrm_holidays.id',
                'hrm_holidays.name',
                'hrm_holidays.start_date',
                'hrm_holidays.end_date',
                'hrm_holidays.note',
            ]
        )->distinct('hrm_holiday_branches.branch_id')->orderBy('hrm_holidays.id', 'desc');

        return DataTables::of($holidays)
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('holidays_edit')) {

                    $html .= '<a href="' . route('hrm.holidays.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('holidays_delete')) {

                    $html .= '<a href="' . route('hrm.holidays.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                }
                $html .= '</div>';

                return $html;
            })

            ->editColumn('allowed_branches', function ($row) use ($generalSettings, $request, $ownBranchIdOrParentBranchId) {

                $allowedBranches = $row->allowedBranches;

                if ($request->branch_id) {

                    if ($request->branch_id == 'NULL') {

                        $allowedBranches->where('branch_id', null);
                    } else {

                        $allowedBranches->where('branch_id', $request->branch_id);
                    }
                }

                if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                    $allowedBranches->where('hrm_holiday_branches.branch_id', $ownBranchIdOrParentBranchId);
                }

                $text = '';
                foreach ($allowedBranches as $allowedBranch) {

                    $branchName = $allowedBranch?->branch?->parent_branch_id ? $allowedBranch?->branch?->name : $allowedBranch?->branch?->name;

                    $__branchName = isset($branchName) ? $branchName : $generalSettings['business__business_name'];

                    $areaName = $allowedBranch?->branch?->area_name ? '(' . $allowedBranch?->branch?->area_name . ')' : '';

                    $text .= '<p class="m-0 p-0" style="font-size: 9px; line-height: 11px; font-weight: 600; letter-spacing: 1px;"> - ' . $__branchName . ',</p>';
                }

                return $text;
            })
            ->addColumn('date', function ($row) use ($generalSettings) {

                $dateFormat = $generalSettings['business__date_format'];
                return date($dateFormat, strtotime($row->start_date)) . ' ' . __('To') . ' ' . date($dateFormat, strtotime($row->end_date));
            })
            ->rawColumns(['action', 'allowed_branches', 'date'])
            ->smart(true)->make(true);
    }

    public function addHoliday(object $request): object
    {
        $addHoliday = new Holiday();
        $addHoliday->name = $request->name;
        $addHoliday->start_date = $request->start_date;
        $addHoliday->end_date = $request->end_date;
        $addHoliday->note = $request->note;
        $addHoliday->save();

        return $addHoliday;
    }

    public function updateHoliday(object $request, int $id): object
    {
        $updateHoliday = $this->singleHoliday(id: $id, with: ['allowedBranches']);
        $updateHoliday->name = $request->name;
        $updateHoliday->start_date = $request->start_date;
        $updateHoliday->end_date = $request->end_date;
        $updateHoliday->note = $request->note;
        $updateHoliday->save();

        return $updateHoliday;
    }

    public function deleteHoliday(int $id): void
    {
        $deleteHoliday = $this->singleHoliday(id: $id);

        if (!is_null($deleteHoliday)) {

            $deleteHoliday->delete();
        }
    }

    public function singleHoliday(int $id, array $with = null): ?object
    {
        $query = Holiday::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function storeAndUpdateValidation(object $request)
    {
        $request->validate([
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        if (isset($request->allowed_branch_count)) {

            $request->validate([
                'allowed_branch_ids' => 'required|array',
                'allowed_branch_ids.*' => 'required',
            ], ['allowed_branch_ids.required' => __('Allowed shop/business is required')]);
        }
    }
}
