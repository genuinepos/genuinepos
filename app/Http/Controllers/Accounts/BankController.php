<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Accounts\BankService;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Users\UserActivityLogService;
use App\Http\Requests\Accounts\BankEditRequest;
use App\Http\Requests\Accounts\BankIndexRequest;
use App\Http\Requests\Accounts\BankStoreRequest;
use App\Http\Requests\Accounts\BankCreateRequest;
use App\Http\Requests\Accounts\BankDeleteRequest;
use App\Http\Requests\Accounts\BankUpdateRequest;

class BankController extends Controller
{
    public function __construct(
        private BankService $bankService,
        private UserActivityLogService $userActivityLogService
    ) {
    }

    public function index(BankIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->bankService->bankListTable();
        }

        return view('accounting.banks.index');
    }

    public function create(BankCreateRequest $request)
    {
        return view('accounting.banks.ajax_view.create');
    }

    public function store(BankStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $addBank = $this->bankService->addBank(request: $request);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Bank->value, dataObj: $addBank);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addBank;
    }

    public function edit($id, BankEditRequest $request)
    {
        $bank = $this->bankService->singleBank(id: $id);

        return view('accounting.banks.ajax_view.edit', compact('bank'));
    }

    public function update(BankUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $updateBank = $this->bankService->updateBank(id: $id, request: $request);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Bank->value, dataObj: $updateBank);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Bank updated successfully'));
    }

    public function delete(BankDeleteRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $deleteBank = $this->bankService->deleteBank($id);

            if (isset($deleteBank['pass']) && $deleteBank['pass'] == false) {

                return response()->json(['errorMsg' => $deleteBank['msg']]);
            }

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Bank->value, dataObj: $deleteBank);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Bank deleted successfully.'));
    }
}
