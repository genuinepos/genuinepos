<?php

namespace App\Http\Controllers;

use App\Utils\Util;
use App\Models\Purchase;
use App\Utils\AccountUtil;
use App\Utils\ProductUtil;
use App\Utils\PurchaseUtil;
use App\Utils\SupplierUtil;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Utils\PurchaseOrderUtil;
use App\Utils\SupplierPaymentUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\PurchaseOrderProductUtil;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private PurchaseUtil $purchaseUtil,
        private PurchaseOrderUtil $purchaseOrderUtil,
        private PurchaseOrderProductUtil $purchaseOrderProductUtil,
        private Util $util,
        private SupplierUtil $supplierUtil,
        private SupplierPaymentUtil $supplierPaymentUtil,
        private ProductUtil $productUtil,
        private AccountUtil $accountUtil,
        private InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
    }

    public function create()
    {
        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $purchaseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 3)
            ->get(['accounts.id', 'accounts.name']);

        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();
        $units = DB::table('units')->select('id', 'name')->get();

        $suppliers = DB::table('suppliers')->select('id', 'name', 'phone', 'pay_term', 'pay_term_number')->get();

        return view('purchases.orders.create', compact('methods', 'accounts', 'purchaseAccounts', 'taxes', 'units', 'suppliers'));
    }

    // add purchase method
    public function store(Request $request)
    {
        $this->validate($request, [
            'supplier_id' => 'required',
            'invoice_id' => 'sometimes|unique:purchases,invoice_id',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'purchase_account_id.required' => 'Purchase A/c is required.',
            'account_id.required' => 'Credit A/c is required.',
            'payment_method_id.required' => 'Payment method field is required.',
            'supplier_id.required' => 'Supplier is required.',
        ]);

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required']);
        }

        if (!isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Product table is empty.']);
        } elseif (count($request->product_ids) > 60) {

            return response()->json(['errorMsg' => 'Purchase invoice items must be less than 60 or equal.']);
        }

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $paymentInvoicePrefix = $generalSettings['prefix__purchase_payment'];
            $isEditProductPrice = $generalSettings['purchase__is_edit_pro_price'];

            $__purchaseOrderIdPrefix = 'PO';
            $addOrder = $this->purchaseOrderUtil->addPurchaseOrder(request: $request, invoiceVoucherRefIdUtil: $this->invoiceVoucherRefIdUtil, purchaseOrderIdPrefix: $__purchaseOrderIdPrefix);

            $this->purchaseOrderProductUtil->addPurchaseOrderProduct(request: $request, orderId: $addOrder->id, isEditProductPrice: $isEditProductPrice);

            if ($request->paying_amount > 0) {

                // Add payment
                $addPurchasePaymentGetId = $this->purchaseUtil->addPurchasePaymentGetId(
                    invoicePrefix: $paymentInvoicePrefix,
                    request: $request,
                    payingAmount: $request->paying_amount,
                    invoiceId: str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT),
                    purchase: $addOrder,
                    supplier_payment_id: NULL
                );

                // Add Bank/Cash-In-Hand A/C Ledger
                $this->accountUtil->addAccountLedger(
                    voucher_type_id: 11,
                    date: $request->date,
                    account_id: $request->account_id,
                    trans_id: $addPurchasePaymentGetId,
                    amount: $request->paying_amount,
                    balance_type: 'debit'
                );

                // Add supplier ledger for payment
                $this->supplierUtil->addSupplierLedger(
                    voucher_type_id: 3,
                    supplier_id: $request->supplier_id,
                    branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $addPurchasePaymentGetId,
                    amount: $request->paying_amount,
                );
            }

            $adjustedPurchase = $this->purchaseUtil->adjustPurchaseInvoiceAmounts($addOrder);

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 1, subject_type: 5, data_obj: $addOrder);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        $order = Purchase::with([
            'branch',
            'supplier',
            'admin:id,prefix,name,last_name',
            'purchase_order_products',
            'purchase_order_products.product',
            'purchase_order_products.product.warranty',
            'purchase_order_products.variant',
            'purchase_payments',
        ])->where('id', $addOrder->id)->first();

        if ($request->action == 2) {

            return response()->json(['successMsg' => 'Successfully purchase order is created.']);
        } else {

            return view('purchases.save_and_print_template.print_purchase_order', compact('order'));
        }
    }
}
