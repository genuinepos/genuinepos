<?php

namespace App\Http\Controllers\Contacts;

use Exception;
use Illuminate\Http\Request;
use App\Imports\CustomerImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Accounts\AccountService;
use App\Services\Contacts\ContactService;
use App\Services\Accounts\AccountGroupService;
use App\Services\Accounts\AccountLedgerService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Contacts\CustomerImportRequest;
use App\Services\Contacts\ContactOpeningBalanceService;

class CustomerImportController extends Controller
{
    public function __construct(
        private ContactService $contactService,
        private ContactOpeningBalanceService $contactOpeningBalanceService,
        private AccountGroupService $accountGroupService,
        private AccountService $accountService,
        private AccountLedgerService $accountLedgerService,
        private CodeGenerationServiceInterface $codeGenerator
    ) {
    }

    public function create()
    {
        abort_if(!auth()->user()->can('customer_import'), 403);

        return view('contacts.import_customers.create');
    }

    public function store(CustomerImportRequest $request)
    {
        try {
            DB::beginTransaction();

            Excel::import(
                new CustomerImport(
                    accountGroupService: $this->accountGroupService,
                    codeGenerator: $this->codeGenerator,
                    contactService: $this->contactService,
                    contactOpeningBalanceService: $this->contactOpeningBalanceService,
                    accountLedgerService: $this->accountLedgerService,
                    accountService: $this->accountService,
                ),
                $request->import_file
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            return __('Something went wrong. Please check again the imported file.') . ' <a href="' . url()->previous() . '">' . __('Back') . '</a>';
        }

        session()->flash('successMsg', __('Customers imported successfully'));

        return redirect()->back();
    }
}
