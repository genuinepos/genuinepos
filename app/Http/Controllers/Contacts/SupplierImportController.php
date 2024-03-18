<?php

namespace App\Http\Controllers\Contacts;

use Exception;
use Illuminate\Http\Request;
use App\Imports\SupplierImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Accounts\AccountService;
use App\Services\Contacts\ContactService;
use App\Services\Accounts\AccountGroupService;
use App\Services\Accounts\AccountLedgerService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Contacts\SupplierImportRequest;
use App\Services\Contacts\ContactOpeningBalanceService;
use Illuminate\Support\Facades\File;

class SupplierImportController extends Controller
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
        abort_if(!auth()->user()->can('supplier_import'), 403);

        return view('contacts.import_suppliers.create');
    }

    public function store(SupplierImportRequest $request)
    {
        try {
            DB::beginTransaction();

            Excel::import(
                new SupplierImport(
                    accountGroupService: $this->accountGroupService,
                    codeGenerator: $this->codeGenerator,
                    contactService: $this->contactService,
                    contactOpeningBalanceService: $this->contactOpeningBalanceService,
                    accountLedgerService: $this->accountLedgerService,
                    accountService: $this->accountService,
                ),
                $request->import_file
            );

            File::cleanDirectory(storage_path('framework/laravel-excel'));
            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            return __('Something went wrong. Please check again the imported file.') . ' <a href="' . url()->previous() . '">' . __('Back') . '</a>';
        }

        session()->flash('successMsg', __('Suppliers imported successfully'));

        return redirect()->back();
    }
}
