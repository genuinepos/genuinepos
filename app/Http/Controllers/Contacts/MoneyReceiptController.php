<?php

namespace App\Http\Controllers\Contacts;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Contacts\ContactService;
use App\Services\Contacts\MoneyReceiptService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Contacts\MoneyReceiptStoreRequest;
use App\Http\Requests\Contacts\MoneyReceiptUpdateRequest;

class MoneyReceiptController extends Controller
{
    public function __construct(
        private MoneyReceiptService $moneyReceiptService,
        private ContactService $contactService
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index($contactId)
    {
        abort_if(!auth()->user()->can('money_receipt_index') || config('generalSettings')['subscription']->features['contacts'] == BooleanType::False->value, 403);

        $contact = $this->contactService->singleContact(id: $contactId, with: ['account', 'account.branch', 'moneyReceiptsOfOwnBranch', 'moneyReceiptsOfOwnBranch.branch', 'moneyReceiptsOfOwnBranch.branch.parentBranch']);

        return view('contacts.money_receipts.index', compact('contact'));
    }

    public function create($contactId)
    {
        abort_if(!auth()->user()->can('money_receipt_add') || config('generalSettings')['subscription']->features['contacts'] == BooleanType::False->value, 403);

        $contact = $this->contactService->singleContact(id: $contactId, with: ['account', 'account.branch']);

        return view('contacts.money_receipts.create', compact('contact'));
    }

    public function store($contactId, MoneyReceiptStoreRequest $request, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $addMoneyReceipt = $this->moneyReceiptService->addMoneyReceipt(contactId: $contactId, request: $request, codeGenerator: $codeGenerator);
            $moneyReceipt = $this->moneyReceiptService->singleMoneyReceipt(id: $addMoneyReceipt->id, with: ['contact', 'branch']);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return view('contacts.money_receipts.print_receipt', compact('moneyReceipt'));
    }

    public function edit($receiptId)
    {
        abort_if(!auth()->user()->can('money_receipt_edit') || config('generalSettings')['subscription']->features['contacts'] == 0, 403);

        try {
            DB::beginTransaction();

            $moneyReceipt = $this->moneyReceiptService->singleMoneyReceipt(id: $receiptId, with: ['contact', 'contact.account', 'contact.account.branch']);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return view('contacts.money_receipts.edit', compact('moneyReceipt'));
    }

    public function update($receiptId, MoneyReceiptUpdateRequest $request)
    {
        $updateMoneyReceipt = $this->moneyReceiptService->updateMoneyReceipt(moneyReceiptId: $receiptId, request: $request);
        $moneyReceipt = $this->moneyReceiptService->singleMoneyReceipt(id: $receiptId, with: ['contact', 'branch']);

        return view('contacts.money_receipts.print_receipt', compact('moneyReceipt'));
    }

    public function delete($receiptId)
    {
        abort_if(!auth()->user()->can('money_receipt_delete') || config('generalSettings')['subscription']->features['contacts'] == 0, 403);

        try {
            DB::beginTransaction();

            $this->moneyReceiptService->deleteMoneyReceipt($receiptId);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Money receipt delete successfully.'));
    }

    public function print($receiptId)
    {
        $moneyReceipt = $this->moneyReceiptService->singleMoneyReceipt(id: $receiptId, with: ['contact', 'branch']);

        return view('contacts.money_receipts.print_receipt', compact('moneyReceipt'));
    }
}
