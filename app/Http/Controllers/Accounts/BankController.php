<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Accounts\BankService;

class BankController extends Controller
{
    public function __construct(
        private BankService $bankService,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('ac_access')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->bankService->bankListTable();
        }

        return view('accounting.banks.index');
    }

    function create()
    {

        return view('accounting.banks.ajax_view.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('ac_access')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate(
            $request,
            ['name' => 'required|unique:banks,name',]
        );

        try {

            DB::beginTransaction();

            $addBank = $this->bankService->addBank($request);

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 16, data_obj: $addBank);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addBank;
    }

    public function edit($id)
    {
        if (!auth()->user()->can('ac_access')) {

            abort(403, 'Access Forbidden.');
        }

        $bank = $this->bankService->singleBank(id: $id);
        return view('accounting.banks.ajax_view.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('ac_access')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required|unique:banks,name,' . $id,
        ]);

        try {

            DB::beginTransaction();

            $updateBank = $this->bankService->updateBank(id: $id, request: $request);
            $this->userActivityLogUtil->addLog(action: 2, subject_type: 16, data_obj: $updateBank);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Bank updated successfully'));
    }

    public function delete(Request $request, $id)
    {
        if (!auth()->user()->can('ac_access')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $deleteBank = $this->bankService->deleteBank($id);

            if ($deleteBank['success'] == false) {

                return response()->json(['errorMsg' => $deleteBank['msg']]);
            }

            $this->userActivityLogUtil->addLog(action: 3,  subject_type: 16, data_obj: $deleteBank);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json($deleteBank['msg']);
    }
}
