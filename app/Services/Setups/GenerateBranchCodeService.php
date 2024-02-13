<?php

namespace App\Services\Setups;

use App\Models\Setups\Branch;
use Illuminate\Support\Facades\DB;

class GenerateBranchCodeService
{
    public function branchCode(?int $parentBranchId = null)
    {

        $childBranchCount = 0;
        $parentBranchCode = null;
        if (isset($parentBranchId)) {

            $parentBranch = Branch::with('childBranches')->where('id', $parentBranchId)->first();

            $count = count($parentBranch->childBranches);
            $childBranchCount = $count > 0 ? ++$count : 1;
            $parentBranchCode = $parentBranch->branch_code;
        }

        $lastBranchCode = DB::table('branches')->whereNull('parent_branch_id')->orderBy('branch_code', 'desc')->first(['branch_code']);

        $differentBranchCode = isset($lastBranchCode) ? ++$lastBranchCode->branch_code : 1;

        $branchCode = str_pad($differentBranchCode, 2, '0', STR_PAD_LEFT);
        $childBranchCode = $childBranchCount > 0 ? $childBranchCount : null;
        $__childBranchCode = isset($childBranchCode) ? '/' . $childBranchCode : null;

        if (isset($parentBranchCode)) {

            return str_pad($parentBranchCode, 2, '0', STR_PAD_LEFT) . $__childBranchCode;
        } else {

            return $branchCode;
        }
    }
}
