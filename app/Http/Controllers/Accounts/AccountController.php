<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CodeGenerationService;
use App\Http\Requests\Accounts\AccountStoreRequest;
use App\Http\Requests\Accounts\AccountUpdateRequest;
use App\Interfaces\Accounts\AccountControllerMethodContainersInterface;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request, AccountControllerMethodContainersInterface $accountControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('accounts_index'), 403);

        $indexMethodContainer = $accountControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        extract($indexMethodContainer);

        return view('accounting.accounts.index', compact('branches', 'accountGroups'));
    }

    public function create($type, AccountControllerMethodContainersInterface $accountControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('accounts_create'), 403);

        $createMethodContainer = $accountControllerMethodContainersInterface->createMethodContainer(type: $type);

        extract($createMethodContainer);

        return view('accounting.accounts.ajax_view.create', compact('groups', 'banks', 'branches'));
    }

    public function store(AccountStoreRequest $request, CodeGenerationService $codeGenerator, AccountControllerMethodContainersInterface $accountControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $createMethodContainer = $accountControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $createMethodContainer;
    }

    public function edit($id, $type, AccountControllerMethodContainersInterface $accountControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('accounts_edit'), 403);

        $editMethodContainer = $accountControllerMethodContainersInterface->editMethodContainer(id: $id, type: $type);

        extract($editMethodContainer);

        return view('accounting.accounts.ajax_view.edit', compact('account', 'groups', 'banks', 'branches'));
    }

    public function update($id, AccountUpdateRequest $request, CodeGenerationService $codeGenerator, AccountControllerMethodContainersInterface $accountControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $accountControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Account updated successfully'));
    }

    public function delete($id, Request $request, AccountControllerMethodContainersInterface $accountControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('accounts_delete'), 403);

        try {
            DB::beginTransaction();

            $deleteMethodContainer = $accountControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Account deleted successfully'));
    }
}
