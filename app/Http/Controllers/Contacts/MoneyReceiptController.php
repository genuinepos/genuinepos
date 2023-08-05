<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Services\Contacts\ContactService;
use App\Services\Contacts\MoneyReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoneyReceiptController extends Controller
{
    public function __construct(
        private MoneyReceiptService $moneyReceiptService,
        private ContactService $contactService,
    ) {
    }

    public function index($contactId)
    {
        $contact = $this->contactService->singleContact(id: $contactId, with: ['moneyReceiptsOfOwnBranch', 'moneyReceiptsOfOwnBranch.branch']);

        return view('contacts.money_receipts.index', compact('contact'));
    }

    public function create($contactId)
    {
        $contact = $this->contactService->singleContact(id: $contactId);

        return view('contacts.money_receipts.create', compact('contact'));
    }

    public function store(Request $request, $contactId, CodeGenerationServiceInterface $codeGenerator)
    {
        $this->validate(
            $request,
            ['date' => 'required|date']
        );

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
        try {

            DB::beginTransaction();

            $moneyReceipt = $this->moneyReceiptService->singleMoneyReceipt(id: $receiptId, with: ['contact', 'branch']);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return view('contacts.money_receipts.edit', compact('moneyReceipt'));
    }

    public function update(Request $request, $receiptId)
    {
        $this->validate(
            $request,
            ['date' => 'required|date']
        );

        $updateMoneyReceipt = $this->moneyReceiptService->updateMoneyReceipt(moneyReceiptId: $receiptId, request: $request);
        $moneyReceipt = $this->moneyReceiptService->singleMoneyReceipt(id: $receiptId, with: ['contact', 'branch']);

        return view('contacts.money_receipts.print_receipt', compact('moneyReceipt'));
    }

    public function delete($receiptId)
    {
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
