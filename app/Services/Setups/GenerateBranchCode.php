<?php

namespace App\Services\Setups;

class GenerateBranchCode
{
    public function generate(?int $branchId = null) {

        $childBranchCount = 0;
        if(isset($branchId)){

            $count = DB::table('branches')->where('parent_branch_id', $branchId)->count();
            $childBranchCount = $count > 0 ? ++$count : $childBranchCount;
        }

        $lastBranchCode = DB::table('branches')->whereNull('parent_branch_id')->orderBy('branch_code', 'desc')->first(['branch_code']);

        $differentBranchCode = isset($lastBranchCode) ? ++$lastBranchCode : 1;

        $branchCode = str_pad($differentBranchCode, 2, '0', STR_PAD_LEFT);
        $childBranchCode = $childBranchCount > 0 ? ++$childBranchCount : null;
        $__childBranchCode = isset($childBranchCount) ? '/'. str_pad($childBranchCode, 2, '0', STR_PAD_LEFT) : null;

        return $branchCode.$__childBranchCode;
    }
}
