<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Services\Setups\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\UserActivityLogActionType;
use App\Services\Sales\QuotationService;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Sales\SalesOrderService;
use App\Services\Sales\SaleProductService;
use App\Services\Products\PriceGroupService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Sales\QuotationProductService;
use App\Services\Products\ManagePriceGroupService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Sales\QuotationControllerMethodContainersInterface;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class QuotationControllerMethodContainersService implements QuotationControllerMethodContainersInterface
{
    public function __construct(
        private QuotationService $quotationService,
        private QuotationProductService $quotationProductService,
        private SaleService $saleService,
        private SalesOrderService $salesOrderService,
        private SaleProductService $saleProductService,
        private AccountService $accountService,
        private AccountLedgerService $accountLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private AccountFilterService $accountFilterService,
        private PaymentMethodService $paymentMethodService,
        private BranchService $branchService,
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function indexMethodContainer(object $request): object|array
    {
        $data = [];
        if ($request->ajax()) {

            return $this->quotationService->quotationListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['customerAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return $data;
    }

    public function showMethodContainer(int $id): array
    {
        $data = [];
        $quotation = $this->quotationService->singleQuotation(id: $id, with: [
            'customer:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'saleProducts.branch.parentBranch:id,name,branch_code,area_name',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        $data['customerCopySaleProducts'] = $this->saleProductService->customerCopySaleProducts(saleId: $quotation->id);
        $data['quotation'] = $quotation;

        return $data;
    }

    public function editMethodContainer(int $id): array
    {
        $data = [];
        $quotation = $this->quotationService->singleQuotation(id: $id, with: [
            'customer',
            'customer.group',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,name,code_name,base_unit_id',
        ]);

        $generalSettings = config('generalSettings');
        $ownBranchIdOrParentBranchId = $quotation?->branch?->parent_branch_id ? $quotation?->branch?->parent_branch_id : $quotation->branch_id;

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $quotation->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['saleAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', $quotation->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $quotation->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['priceGroups'] = $this->priceGroupService->priceGroups()->get(['id', 'name']);
        $data['priceGroupProducts'] = $this->managePriceGroupService->priceGroupProducts();
        $data['quotation'] = $quotation;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array
    {
        $quotation = $this->quotationService->singleQuotation(id: $id, with: ['saleProducts', 'references']);

        $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService, checkCustomerChangeRestriction: true, saleId: $id);

        $quotationRestrictions = $this->quotationService->restrictions(request: $request, quotation: $quotation);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        } elseif ($quotationRestrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $quotationRestrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';
        $salesOrderPrefix = $generalSettings['prefix__sales_order_prefix'] ? $generalSettings['prefix__sales_order_prefix'] : 'OR';

        $updateQuotation = $this->quotationService->updateQuotation(request: $request, updateQuotation: $quotation);

        $updateQuotationProducts = $this->quotationProductService->updateQuotationProducts(request: $request, quotation: $updateQuotation);

        $quotation = $this->quotationService->singleQuotation(id: $id, with: ['saleProducts']);

        $deletedUnusedQuotationProducts = $quotation->saleProducts()->where('is_delete_in_update', BooleanType::True->value)->get();

        if (count($deletedUnusedQuotationProducts) > 0) {

            foreach ($deletedUnusedQuotationProducts as $deletedUnusedQuotationProduct) {

                $deletedUnusedQuotationProduct->delete();
            }
        }

        $updateQuotationStatus = $this->quotationService->updateQuotationStatus(request: $request, id: $id, codeGenerator: $codeGenerator, salesOrderPrefix: $salesOrderPrefix);

        if ($updateQuotationStatus->order_status == BooleanType::True->value) {

            $this->salesOrderService->calculateDeliveryLeftQty(order: $updateQuotationStatus);

            if ($request->received_amount) {

                $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $updateQuotationStatus->id);

                // Add Debit Account Accounting voucher Description
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

                //Add Debit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

                // Add Accounting VoucherDescription References
                $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$updateQuotationStatus->id]);

                //Add Credit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
            }
        }

        $this->saleService->adjustSaleInvoiceAmounts(sale: $quotation);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Quotation->value, dataObj: $quotation);

        return null;
    }

    public function editStatusMethodContainer(int $id): ?array
    {
        $data = [];
        $data['quotation'] = $this->quotationService->singleQuotation(id: $id);
        return $data;
    }

    public function updateStatusMethodContainer(int $id, object $request, object $codeGenerator): ?array
    {
        $generalSettings = config('generalSettings');
        $salesOrderPrefix = $generalSettings['prefix__sales_order_prefix'] ? $generalSettings['prefix__sales_order_prefix'] : 'SO';

        $updateQuotationStatus = $this->quotationService->updateQuotationStatus(request: $request, id: $id, codeGenerator: $codeGenerator, salesOrderPrefix: $salesOrderPrefix);

        if (isset($updateQuotationStatus['pass']) && $updateQuotationStatus['pass'] == false) {

            return ['pass' => false, 'msg' => $updateQuotationStatus['msg']];
        }

        if ($updateQuotationStatus->status == SaleStatus::Order->value) {

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::ChangeQuotationStatus->value, dataObj: $updateQuotationStatus);
        }

        return null;
    }
}
