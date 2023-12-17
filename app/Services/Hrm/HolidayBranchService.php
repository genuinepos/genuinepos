<?php

namespace App\Services\Hrm;

use App\Enums\RoleType;
use App\Enums\BooleanType;
use App\Models\Hrm\Holiday;
use App\Enums\IsDeleteInUpdate;
use App\Models\HRM\HolidayBranch;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class HolidayBranchService
{
    public function addHolidayBranches(object $request, int $holidayId): void
    {
        if ($request->allowed_branch_count) {

            foreach ($request->allowed_branch_ids as $branchId) {

                $__branchId = $branchId == 'NULL' ? null : $branchId;
                $addHolidayBranch = new HolidayBranch();
                $addHolidayBranch->holiday_id = $holidayId;
                $addHolidayBranch->branch_id = $__branchId;
                $addHolidayBranch->save();
            }
        } else {

            $addHolidayBranch = new HolidayBranch();
            $addHolidayBranch->holiday_id = $holidayId;
            $addHolidayBranch->branch_id = auth()->user()->branch_id;
            $addHolidayBranch->save();
        }
    }

    public function updateHolidayBranches(object $request, object $holiday): void
    {
        if (
            isset($request->allowed_branch_count) &&
            auth()->user()->role_type != RoleType::Other->value &&
            auth()->user()->is_belonging_an_area == BooleanType::False->value
        ) {
            foreach ($holiday->allowedBranches as $allowedBranch) {

                $allowedBranch->is_delete_in_update = IsDeleteInUpdate::Yes->value;
                $allowedBranch->save();
            }

            if (isset($request->allowed_branch_ids)) {

                foreach ($request->allowed_branch_ids as $branchId) {

                    $__branchId = $branchId == 'NULL' ? null : $branchId;
                    $holidayAllowedBranch = $this->holidayAllowedBranch()->where('branch_id', $__branchId)
                        ->where('holiday_id', $holiday->id)->first();

                    if (! $holidayAllowedBranch) {

                        $addHolidayBranch = new HolidayBranch();
                        $addHolidayBranch->holiday_id = $holiday->id;
                        $addHolidayBranch->branch_id = $__branchId;
                        $addHolidayBranch->save();
                    } else {

                        $holidayAllowedBranch->is_delete_in_update = IsDeleteInUpdate::No->value;
                        $holidayAllowedBranch->save();
                    }
                }
            }


            $deleteUnusedHolidayAllowedBranches = $this->holidayAllowedBranch()->where('holiday_id', $holiday->id)
                ->where('is_delete_in_update', IsDeleteInUpdate::Yes->value)->get();

            foreach ($deleteUnusedHolidayAllowedBranches as $deleteUnusedHolidayAllowedBranch) {

                $deleteUnusedHolidayAllowedBranch->delete();
            }
        }
    }

    public function holidayAllowedBranch(array $with = null): ?object
    {
        $query = HolidayBranch::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
