<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Accounts\ExpenseStoreRequest;
use App\Http\Requests\Accounts\ExpenseDeleteRequest;
use App\Http\Requests\Accounts\ExpenseUpdateRequest;
use App\Interfaces\Accounts\ExpenseControllerMethodContainersInterface;

class ExpenseController extends Controller
{
    public function index(Request $request, ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('expenses_index'), 403);

        $indexMethodContainer = $expenseControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('accounting.accounting_vouchers.expenses.index', compact('branches'));
    }

    public function show(ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface, $id)
    {
        $showMethodContainer = $expenseControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('accounting.accounting_vouchers.expenses.ajax_view.show', compact('expense'));
    }

    public function print($id, Request $request, ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface)
    {
        $printMethodContainer = $expenseControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('accounting.accounting_vouchers.print_templates.print_expense', compact('expense', 'printPageSize'));
    }

    public function create(ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('expenses_create'), 403);

        $createMethodContainer = $expenseControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('accounting.accounting_vouchers.expenses.ajax_view.create', compact('accounts', 'methods', 'expenseAccounts'));
    }

    public function store(ExpenseStoreRequest $request, ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $expenseControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('accounting.accounting_vouchers.print_templates.print_expense', compact('expense', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Expense added successfully.')]);
        }
    }

    public function edit(ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface, $id)
    {
        abort_if(!auth()->user()->can('expenses_edit'), 403);

        $editMethodContainer = $expenseControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('accounting.accounting_vouchers.expenses.ajax_view.edit', compact('expense', 'accounts', 'methods', 'expenseAccounts'));
    }

    public function update(ExpenseUpdateRequest $request, ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface, $id)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $expenseControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Expense updated successfully.'));
    }

    public function delete($id, ExpenseDeleteRequest $request, ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $expenseControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Expense deleted successfully.'));
    }
}
