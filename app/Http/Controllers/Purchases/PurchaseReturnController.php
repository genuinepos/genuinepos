<?php

namespace App\Http\Controllers\Purchases;

use Carbon\Carbon;


use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Mail\PurchaseReturnCreated;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Purchases\PurchaseService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Purchases\PurchaseReturnService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Purchases\PurchaseReturnProductService;
use Modules\Communication\Interface\EmailServiceInterface;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Services\CodeGenerationService;

class PurchaseReturnController extends Controller
{
    public function __construct(
        private PurchaseReturnService $purchaseReturnService,
        private PurchaseReturnProductService $purchaseReturnProductService,
        private PurchaseService $purchaseService,
        private EmailServiceInterface $emailService,
        private UserActivityLogUtil $userActivityLogUtil,
        private PaymentMethodService $paymentMethodService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private ProductLedgerService $productLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
    ) {
    }

    // Sale return index view
    public function index(Request $request)
    {
        if (!auth()->user()->can('purchase_return')) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $returns = '';
            $generalSettings = config('generalSettings');
            $query = DB::table('purchase_returns')
                ->leftJoin('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                ->leftJoin('branches', 'purchase_returns.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'purchase_returns.warehouse_id', 'warehouses.id')
                ->leftJoin('suppliers', 'purchase_returns.supplier_id', 'suppliers.id')
                ->leftJoin('suppliers as p_supplier', 'purchases.supplier_id', 'p_supplier.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('purchase_returns.branch_id', null);
                } else {

                    $query->where('purchase_returns.branch_id', $request->branch_id);
                }
            }

            if ($request->supplier_id) {

                $query->where('purchase_returns.supplier_id', $request->supplier_id);
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('purchase_returns.report_date', $date_range); // Final
            }

            $query->select(
                'purchase_returns.*',
                'purchases.invoice_id as parent_invoice_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'suppliers.name as sup_name',
                'p_supplier.name as ps_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $returns = $query->orderBy('purchase_returns.report_date', 'desc');
            } else {

                $returns = $query->where('purchase_returns.branch_id', auth()->user()->branch_id)
                    ->orderBy('purchase_returns.report_date', 'desc');
            }

            return DataTables::of($returns)

                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('purchases.returns.show', $row->id) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';

                    if (auth()->user()->branch_id == $row->branch_id) {

                        if ($row->return_type == 1) {

                            $html .= '<a class="dropdown-item" href="' . route('purchases.returns.create', $row->purchase_id) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                        } else {

                            $html .= '<a class="dropdown-item" href="' . route('purchases.returns.supplier.return.edit', $row->id) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                        }

                        $html .= '<a class="dropdown-item" id="delete" href="' . route('purchases.returns.delete', $row->id) . '"><i class="far fa-trash-alt mr-1 text-primary"></i> Delete</a>';
                        // $html .= '<a class="dropdown-item" id="view_payment" href="#"><i class="far fa-money-bill-alt mr-1 text-primary"></i> View Payment</a>';
                        if ($row->total_return_due > 0) {

                            if ($row->purchase_id) {

                                $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('purchases.return.payment.modal', [$row->purchase_id]) . '"><i class="far fa-money-bill-alt mr-1 text-primary"></i> Add Payment</a>';
                            } else {

                                // $html .= '<a class="dropdown-item" id="add_supplier_return_payment" href="#"><i class="far fa-money-bill-alt mr-1 text-primary"></i> Receive Return Amt.</a>';
                            }
                        }
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('supplier', function ($row) {

                    if ($row->sup_name == null) {

                        return $row->ps_name;
                    }

                    return $row->sup_name;
                })
                ->editColumn('location', function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '<b>(BL)</b>';
                    } else {

                        return $generalSettings['business__shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('return_from', function ($row) use ($generalSettings) {

                    if ($row->warehouse_name) {

                        return ($row->warehouse_name . '/' . $row->warehouse_code) . '<b>(WH)</b>';
                    } elseif ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '<b>(BL)</b>';
                    } else {

                        return $generalSettings['business__shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('total_return_amount', fn ($row) => $this->converter->format_in_bdt($row->total_return_amount))

                ->editColumn('total_return_due_received', fn ($row) => $this->converter->format_in_bdt($row->total_return_due_received))

                ->editColumn('total_return_due', function ($row) {

                    if ($row->parent_invoice_id) {

                        return '<span class="text-danger"> ' . ($row->total_return_due >= 0 ? $this->converter->format_in_bdt($row->total_return_due) : $this->converter->format_in_bdt(0)) . '</span></b>';
                    } else {

                        return '<span class="text-dark"><b>CHECK SUPPLIER DUE</b></span>';
                    }
                })

                ->editColumn('payment_status', function ($row) {

                    if ($row->parent_invoice_id) {
                        if ($row->total_return_due > 0) {

                            return '<span class="text-danger"><b>Due</b></span>';
                        } else {

                            return '<span class="text-success"><b>Paid</b></span>';
                        }
                    } else {

                        return '<span class="text-dark"><b>CHECK SUPPLIER DUE</b></span>';
                    }
                })
                ->rawColumns(['action', 'date', 'supplier', 'return_from', 'location', 'total_return_amount', 'total_return_due_received', 'total_return_due', 'payment_status'])
                ->make(true);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->where('status', 1)->get(['id', 'name', 'phone']);

        return view('purchases.purchase_return.index', compact('branches', 'suppliers'));
    }

    // create purchase return view
    public function create()
    {
        if (!auth()->user()->can('purchase_return')) {

            abort(403, 'Access Forbidden.');
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $generalSettings = config('generalSettings');
        $branchName = $generalSettings['business__shop_name'];
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch?->name . '(' . auth()->user()?->branch->parentBranch?->area_name . ')';
            } else {

                $branchName = auth()->user()?->branch?->name . '(' . auth()->user()?->branch?->area_name . ')';
            }
        }

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $purchaseAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.purchase_return.create', compact('accounts', 'methods', 'purchaseAccounts', 'warehouses', 'taxAccounts', 'supplierAccounts', 'branchName'));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'supplier_account_id' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'purchase_account_id.required' => __("Purchase A/c is required."),
            'account_id.required' => __("Credit field must not be is empty."),
            'payment_method_id.required' => __("Payment method field is required."),
            'supplier_account_id.required' => __("Supplier is required."),
        ]);

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required']);
        }

        $restrictions = $this->purchaseReturnService->restrictions($request);
        if ($restrictions['pass'] == false) {

            return response()->json(['errorMsg' => $restrictions['msg']]);
        }

        $generalSettings = config('generalSettings');
        $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
        $purchaseReturnVoucherPrefix = isset($branchSetting) && $branchSetting?->purchase_return_prefix ? $branchSetting?->purchase_return_prefix : $generalSettings['prefix__purchase_return'];
        $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];

        $addReturn = $this->purchaseReturnService->addPurchaseReturn(request: $request, voucherPrefix: $purchaseReturnVoucherPrefix, codeGenerator: $codeGenerator);

        $this->dayBookService->addDayBook(voucherTypeId: 6, date: $request->date, accountId: $request->supplier_account_id, transId: $addReturn->id, amount: $request->total_return_amount, amountType: 'debit');

        // Add Purchase A/c Ledger Entry
        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 4, date: $request->date, account_id: $request->purchase_account_id, trans_id: $addReturn->id, amount: $request->purchase_ledger_amount, amount_type: 'credit');

        // Add supplier A/c ledger Entry For Purchase
        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 4, account_id: $request->supplier_account_id, date: $request->date, trans_id: $addReturn->id, amount: $request->total_purchase_amount, amount_type: 'debit');

        if ($request->return_tax_ac_id) {

            // Add Tax A/c ledger Entry For Purchase
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 4, account_id: $request->return_tax_ac_id, date: $request->date, trans_id: $addReturn->id, amount: $request->return_tax_amount, amount_type: 'credit');
        }

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addPurchaseReturnProduct = $this->purchaseProductService->addPurchaseReturnProduct(request: $request, purchaseReturnId: $addReturn->id, index: $index);

            // Add Product Ledger Entry
            $this->productLedgerService->addProductLedgerEntry(voucherTypeId: 4, date: $request->date, productId: $productId, transId: $addPurchaseReturnProduct->id, rate: $addPurchaseReturnProduct->net_unit_cost, quantityType: 'out', quantity: $addPurchaseReturnProduct->return_qty, subtotal: $addPurchaseReturnProduct->line_total, variantId: $addPurchaseReturnProduct->variant_id, warehouseId: (isset($request->warehouse_count) ? $request->warehouse_id : null));

            // purchase product tax will be go here
            if ($addPurchaseProduct->tax_ac_id) {

                // Add Tax A/c ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 19, date: $request->date, account_id: $addPurchaseProduct->tax_ac_id, trans_id: $addPurchaseProduct->id, amount: ($addPurchaseReturnProduct->unit_tax_amount * $addPurchaseReturnProduct->return_qty), amount_type: 'credit');
            }

            $index++;
        }

        if ($request->received_amount > 0) {

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->receipt_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, purchaseReturnRefId: $addReturn->id);

            // Add Payment Description Credit Entry
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: null, amountType: 'dr', amount: $request->received_amount, note: null);

            //Add debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 8, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Credit Account Accounting voucher Description
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->supplier_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->supplier_account_id, amount: $request->received_amount, refIdColName: 'purchase_return_id', refIds: [$addReturn->id]);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 8, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->supplier_account_id);
        }

        $__index = 0;
        foreach ($request->product_ids as $productId) {

            $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
            $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

            if (isset($request->warehouse_count)) {

                $this->productStockService->addWarehouseProduct(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_id);
                $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_id);
            } else {

                $this->productStockService->addBranchProduct(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);
                $this->productStockService->adjustBranchStock($productId, $variantId, branchId: auth()->user()->branch_id);
            }

            $__index++;
        }

        if ($request->action == 'save_and_print') {

            $return = $this->purchaseReturnService->singlePurchaseReturn(id: $addReturn->id, with:[
                'purchase',
                'branch',
                'branch.parentBranch',
                'supplier',
                'purchaseReturnProducts',
                'purchaseReturnProducts.product',
                'purchaseReturnProducts.variant',
                'purchaseReturnProducts.branch',
                'purchaseReturnProducts.warehouse',
            ])->first();

            if ($purchaseReturn) {

                return view('purchase.save_and_print_template.print_purchase_return', compact('return'));
            }
        } else {

            return response()->json(['successMsg' => 'Purchase Return Added Successfully.']);
        }
    }

    // Show purchase return details
    // public function show($returnId)
    // {
    //     $return = PurchaseReturn::with([
    //         'purchase',
    //         'warehouse',
    //         'branch',
    //         'supplier',
    //         'purchase_return_products',
    //         'purchase_return_products.product',
    //         'purchase_return_products.variant',
    //         'purchase_return_products.purchase_product',
    //     ])->where('id', $returnId)->first();

    //     return view('purchases.purchase_return.ajax_view.show', compact('return'));
    // }

    //Deleted purchase return
    // public function delete($purchaseReturnId)
    // {
    //     $purchaseReturn = PurchaseReturn::with(['purchase', 'purchase.supplier', 'supplier', 'purchase_return_products'])->where('id', $purchaseReturnId)->first();
    //     $storeReturnProducts = $purchaseReturn->purchase_return_products;
    //     $storePurchase = $purchaseReturn->purchase;
    //     $storedReturnType = $purchaseReturn->return_type;
    //     $storedBranchId = $purchaseReturn->branch_id;
    //     $storedWarehouseId = $purchaseReturn->warehouse_id;
    //     $storePurchaseReturnAccountId = $purchaseReturn->purchase_return_account_id;
    //     $storeSupplierId = $purchaseReturn->purchase ? $purchaseReturn->purchase->supplier_id : $purchaseReturn->supplier_id;

    //     if ($purchaseReturn->return_type == 1) {

    //         $purchaseReturn->purchase->is_return_available = 0;

    //         if ($purchaseReturn->total_return_due_received > 0) {

    //             return response()->json(['errorMsg' => 'You can not delete this, cause your have received some or full amount on this return.']);
    //         }
    //     } else {

    //         if ($purchaseReturn->total_return_due_received > 0) {

    //             return response()->json(['errorMsg' => 'You can not delete this, cause your have received some or full amount on this return.']);
    //         }
    //     }
    //     $purchaseReturn->delete();

    //     foreach ($storeReturnProducts as $return_product) {

    //         $this->productStockUtil->adjustMainProductAndVariantStock($return_product->product_id, $return_product->product_variant_id);

    //         if ($storedReturnType == 1) {

    //             if ($storePurchase->warehouse_id) {

    //                 $this->productStockUtil->adjustWarehouseStock($return_product->product_id, $return_product->product_variant_id, $storePurchase->warehouse_id);
    //             } else {

    //                 $this->productStockUtil->adjustBranchStock($return_product->product_id, $return_product->product_variant_id, $storePurchase->branch_id);
    //             }
    //         } else {

    //             if ($storedWarehouseId) {

    //                 $this->productStockUtil->adjustWarehouseStock($return_product->product_id, $return_product->product_variant_id, $storedWarehouseId);
    //             } else {

    //                 $this->productStockUtil->adjustBranchStock($return_product->product_id, $return_product->product_variant_id, $storedBranchId);
    //             }
    //         }
    //     }

    //     if ($storePurchase) {

    //         $this->purchaseUtil->adjustPurchaseInvoiceAmounts($storePurchase);
    //     }

    //     if ($storePurchaseReturnAccountId) {

    //         $this->accountUtil->adjustAccountBalance('credit', $storePurchaseReturnAccountId);
    //     }

    //     $this->supplierUtil->adjustSupplierForPurchasePaymentDue($storeSupplierId);

    //     return response()->json('Successfully purchase return is deleted');
    // }
}
