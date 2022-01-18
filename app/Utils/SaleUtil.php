<?php

namespace App\Utils;

use App\Models\Sale;
use App\Models\CashFlow;
use App\Models\SalePayment;
use App\Utils\CustomerUtil;
use Illuminate\Support\Str;
use App\Models\CustomerLedger;
use App\Models\CustomerPayment;
use App\Models\PurchaseProduct;
use App\Utils\ProductStockUtil;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseSaleProductChain;
use Yajra\DataTables\Facades\DataTables;

class SaleUtil
{
    public $customerUtil;
    public $productStockUtil;
    public $accountUtil;
    public $converter;
    public $invoiceVoucherRefIdUtil;
    public $purchaseUtil;
    public function __construct(
        CustomerUtil $customerUtil,
        ProductStockUtil $productStockUtil,
        AccountUtil $accountUtil,
        Converter $converter,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        PurchaseUtil $purchaseUtil
    ) {
        $this->customerUtil = $customerUtil;
        $this->productStockUtil = $productStockUtil;
        $this->accountUtil = $accountUtil;
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->purchaseUtil = $purchaseUtil;
    }

    public function __getSalePaymentForAddSaleStore($request, $addSale, $paymentInvoicePrefix, $invoiceId)
    {
        if ($request->paying_amount > 0) {
            $changedAmount = $request->change_amount > 0 ? $request->change_amount : 0.00;
            $paidAmount = $request->paying_amount - $changedAmount;

            if ($request->previous_due > 0) {
                if ($paidAmount >= $request->total_invoice_payable) {
                    $this->addPayment($paymentInvoicePrefix, $request, $request->total_invoice_payable, $invoiceId, $addSale->id);
                    $payingPreviousDue = $paidAmount - $request->total_invoice_payable;
                    if ($payingPreviousDue > 0) {
                        $dueAmounts = $payingPreviousDue;
                        $dueInvoices = Sale::where('customer_id', $request->customer_id)
                            ->where('due', '>', 0)
                            ->get();
                        if (count($dueInvoices) > 0) {
                            $index = 0;
                            foreach ($dueInvoices as $dueInvoice) {
                                if ($dueInvoice->due > $dueAmounts) {
                                    if ($dueAmounts > 0) {
                                        $this->addPayment($paymentInvoicePrefix, $request, $dueAmounts, $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'), $dueInvoice->id);
                                        $dueAmounts -= $dueAmounts;
                                        $this->adjustSaleInvoiceAmounts($dueInvoice);
                                    }
                                } elseif ($dueInvoice->due == $dueAmounts) {
                                    if ($dueAmounts > 0) {
                                        $this->addPayment($paymentInvoicePrefix, $request, $dueAmounts, $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'), $dueInvoice->id);
                                        $dueAmounts -= $dueAmounts;
                                        $this->adjustSaleInvoiceAmounts($dueInvoice);
                                    }
                                } elseif ($dueInvoice->due < $dueAmounts) {
                                    if ($dueInvoice->due > 0) {
                                        $this->addPayment($paymentInvoicePrefix, $request, $dueInvoice->due, $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'), $dueInvoice->id);
                                        $dueAmounts = $dueAmounts - $dueInvoice->due;
                                        $this->adjustSaleInvoiceAmounts($dueInvoice);
                                    }
                                }
                                $index++;
                            }
                        }

                        if ($dueAmounts > 0) {
                            // Add Customer Payment Record
                            $customerPayment = new CustomerPayment();
                            $customerPayment->voucher_no = 'CPV' . $this->invoiceVoucherRefIdUtil->getLastId('customer_payments');
                            $customerPayment->branch_id = auth()->user()->branch_id;
                            $customerPayment->customer_id = $addSale->customer_id;
                            $customerPayment->account_id = $request->account_id;
                            $customerPayment->paid_amount = $dueAmounts;
                            $customerPayment->payment_method_id = $request->payment_method_id;
                            $customerPayment->date = $request->date;
                            $customerPayment->time = date('h:i:s a');
                            $customerPayment->month = date('F');
                            $customerPayment->year = date('Y');
                            $customerPayment->note = $request->note;
                            $customerPayment->save();

                            if ($request->account_id) {
                                // Add cash flow
                                $addCashFlow = new CashFlow();
                                $addCashFlow->account_id = $request->account_id;
                                $addCashFlow->credit = $dueAmounts;
                                $addCashFlow->customer_payment_id = $customerPayment->id;
                                $addCashFlow->transaction_type = 13;
                                $addCashFlow->cash_type = 2;
                                $addCashFlow->date = date('d-m-Y', strtotime($request->date));
                                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                                $addCashFlow->month = date('F');
                                $addCashFlow->year = date('Y');
                                $addCashFlow->admin_id = auth()->user()->id;
                                $addCashFlow->save();
                                $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
                                $addCashFlow->save();
                            }

                            // Add customer payment for direct payment
                            $addCustomerLedger = new CustomerLedger();
                            $addCustomerLedger->customer_id = $addSale->customer_id;
                            $addCustomerLedger->row_type = 5;
                            $addCustomerLedger->customer_payment_id = $customerPayment->id;
                            $addCustomerLedger->report_date = date('Y-m-d', strtotime($request->date));
                            $addCustomerLedger->save();
                        }
                    }
                } elseif ($paidAmount < $request->invoice_payable_amount) {
                    $this->addPayment($paymentInvoicePrefix, $request, $paidAmount, $invoiceId, $addSale->id);
                }
            } else {
                $this->addPayment($paymentInvoicePrefix, $request, $paidAmount, $invoiceId, $addSale->id);
            }
        }
    }

    // Add sale add payment util method
    public function addPayment($invoicePrefix, $request, $payingAmount, $invoiceId, $saleId)
    {
        $sale = DB::table('sales')->where('id', $saleId)->select('customer_id')->first();
        $addSalePayment = new SalePayment();
        $addSalePayment->invoice_id = ($invoicePrefix != null ? $invoicePrefix : 'SPV') . date('my') . $invoiceId;
        $addSalePayment->sale_id = $saleId;
        $addSalePayment->customer_id = $sale->customer_id ? $sale->customer_id : NULL;
        $addSalePayment->account_id = $request->account_id;
        $addSalePayment->payment_method_id = $request->payment_method_id;
        $addSalePayment->paid_amount = $payingAmount;
        $addSalePayment->date = $request->date;
        $addSalePayment->time = date('h:i:s a');
        $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addSalePayment->month = date('F');
        $addSalePayment->year = date('Y');
        $addSalePayment->note = $request->payment_note;
        $addSalePayment->admin_id = auth()->user()->id;
        $addSalePayment->save();

        if ($request->account_id) {
            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->credit = $payingAmount;
            $addCashFlow->sale_payment_id = $addSalePayment->id;
            $addCashFlow->transaction_type = 2;
            $addCashFlow->cash_type = 2;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
            $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
            $addCashFlow->save();
        }

        if ($sale->customer_id) {
            $addCustomerLedger = new CustomerLedger();
            $addCustomerLedger->customer_id = $sale->customer_id;
            $addCustomerLedger->sale_payment_id = $addSalePayment->id;
            $addCustomerLedger->row_type = 2;
            $addCustomerLedger->report_date = date('Y-m-d', strtotime($request->date));
            $addCustomerLedger->save();
        }
    }

    public function deleteSale($request, $saleId)
    {
        $deleteSale = Sale::with([
            'sale_payments',
            'sale_products',
            'sale_products.product',
            'sale_products.variant',
            'sale_products.product.comboProducts',
        ])->where('id', $saleId)->first();

        $storedCustomerId = $deleteSale->customer_id;
        $storedBranchId = $deleteSale->branch_id;
        $storedPayments = $deleteSale->sale_payments;
        $storedSaleProducts = $deleteSale->sale_products;
        $storeStatus = $deleteSale->status;
        $deleteSale->delete();

        if (count($storedPayments) > 0) {
            foreach ($storedPayments as $payment) {
                if ($payment->attachment) {
                    if (file_exists(public_path('uploads/payment_attachment/' . $payment->attachment))) {
                        unlink(public_path('uploads/payment_attachment/' . $payment->attachment));
                    }
                }

                if ($payment->account_id) {
                    $this->accountUtil->adjustAccountBalance($payment->account_id);
                }
            }
        }

        if ($storeStatus == 1) {
            foreach ($storedSaleProducts as $saleProduct) {
                $variant_id = $saleProduct->product_variant_id ? $saleProduct->product_variant_id : NULL;
                $this->productStockUtil->adjustMainProductAndVariantStock($saleProduct->product_id, $variant_id);
                $this->productStockUtil->adjustBranchStock($saleProduct->product_id, $variant_id, $storedBranchId);
            }
        }

        if ($storedCustomerId) {
            $this->customerUtil->adjustCustomerAmountForSalePaymentDue($storedCustomerId);
        }
    }

    public function addSaleTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $sales = '';
        $query = DB::table('sales')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id');

        $query->select(
            'sales.*',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $sales = $this->filteredQuery($request, $query)->where('sales.status', 1)
                ->where('sales.created_by', 1)
                ->orderBy('sales.report_date', 'desc');
        } else {
            if (auth()->user()->permission->sale['view_own_sale'] == '1') {
                $query->where('sales.admin_id', auth()->user()->id);
            }

            $sales = $this->filteredQuery($request, $query)->where('sales.branch_id', auth()->user()->branch_id)
                ->where('sales.status', 1)
                ->where('created_by', 1)
                ->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="' . route('sales.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';

                if (auth()->user()->permission->sale['shipment_access'] == '1') {
                    $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="fas fa-file-alt text-primary"></i> Packing Slip</a>';
                }

                if (auth()->user()->permission->sale['shipment_access'] == '1') {
                    $html .= '<a class="dropdown-item" id="edit_shipment" href="' . route('sales.shipment.edit', [$row->id]) . '"><i class="fas fa-truck text-primary"></i> Edit Shipping</a>';
                }

                if (auth()->user()->branch_id == $row->branch_id) {
                    if (auth()->user()->permission->sale['sale_payment'] == '1') {
                        if ($row->due > 0) {
                            $html .= '<a class="dropdown-item" id="add_payment" href="' . route('sales.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Payment</a>';
                        }
                    }

                    if (auth()->user()->permission->sale['sale_payment'] == '1') {
                        $html .= '<a class="dropdown-item" id="view_payment" data-toggle="modal"
                        data-target="#paymentListModal" href="' . route('sales.payment.view', [$row->id]) . '"><i
                            class="far fa-money-bill-alt text-primary"></i> View Payment</a>';
                    }

                    if ($row->sale_return_due > 0) {
                        if (auth()->user()->permission->sale['sale_payment'] == '1') {
                            $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('sales.return.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Pay Return Amount</a>';
                        }
                    }

                    if (auth()->user()->permission->sale['return_access'] == '1') {
                        $html .= '<a class="dropdown-item" href="' . route('sales.returns.create', [$row->id]) . '"><i class="fas fa-undo-alt text-primary"></i> Sale Return</a>';
                    }

                    if (auth()->user()->permission->sale['edit_add_sale'] == '1') {
                        $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->permission->sale['delete_add_sale'] == '1') {
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                }

                $html .= '<a class="dropdown-item" id="send_notification" href="' . route('sales.notification.form', [$row->id]) . '"><i class="fas fa-envelope text-primary"></i> New Sale Notification</a>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', fn ($row) => date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date)))
            ->editColumn('invoice_id', function ($row) {
                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo mr-1 text-white"></i></span>' : '';
                return $html;
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')
            ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="' . $row->total_payable_amount . '">' . $this->converter->format_in_bdt($row->total_payable_amount) . '</span>')
            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')
            ->editColumn('due', fn ($row) =>  '<span class="due text-danger" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')
            ->editColumn('sale_return_amount', fn ($row) => '<span class="sale_return_amount" data-value="' . $row->sale_return_amount . '">' . $this->converter->format_in_bdt($row->sale_return_amount) . '</span>')
            ->editColumn('sale_return_due', fn ($row) => '<span class="sale_return_due text-danger" data-value="' . $row->sale_return_due . '">' . $this->converter->format_in_bdt($row->sale_return_due) . '</span>')
            ->editColumn('paid_status', function ($row) {
                $payable = $row->total_payable_amount - $row->sale_return_amount;
                if ($row->due <= 0) {
                    return '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {
                    return '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {
                    return '<span class="text-danger"><b>Due</b></span>';
                }
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status'])
            ->make(true);
    }

    public function posSaleTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $sales = '';
        $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id');

        $query->select(
            'sales.*',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $sales = $this->filteredQuery($request, $query)->where('sales.status', 1)
                ->where('created_by', 2)
                ->orderBy('sales.report_date', 'desc');
        } else {
            if (auth()->user()->permission->sale['view_own_sale'] == '1') {
                $query->where('sales.admin_id', auth()->user()->id);
            }

            $sales = $this->filteredQuery($request, $query)
                ->where('sales.branch_id', auth()->user()->branch_id)
                ->where('created_by', 2)
                ->where('sales.status', 1)
                ->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="' . route('sales.pos.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';

                $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="fas fa-file-alt text-primary"></i> Packing Slip</a>';

                if (auth()->user()->permission->sale['shipment_access'] == '1') {
                    $html .= '<a class="dropdown-item" id="edit_shipment" href="' . route('sales.shipment.edit', [$row->id]) . '"><i class="fas fa-truck text-primary"></i> Edit Shipping</a>';
                }

                if (auth()->user()->branch_id == $row->branch_id) {
                    if (auth()->user()->permission->sale['sale_payment'] == '1') {
                        if ($row->due > 0) {
                            $html .= '<a class="dropdown-item" id="add_payment" href="' . route('sales.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Payment</a>';
                        }
                    }

                    if (auth()->user()->permission->sale['sale_payment'] == '1') {
                        $html .= '<a class="dropdown-item" id="view_payment" data-toggle="modal" data-target="#paymentListModal" href="' . route('sales.payment.view', [$row->id]) . '"><i
                            class="far fa-money-bill-alt text-primary"></i> View Payment</a>';
                    }

                    if ($row->sale_return_due > 0) {
                        if (auth()->user()->permission->sale['sale_payment'] == '1') {
                            $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('sales.return.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Pay Return Amount</a>';
                        }
                    }

                    if (auth()->user()->permission->sale['return_access'] == '1') {
                        $html .= '<a class="dropdown-item" href="' . route('sales.returns.create', [$row->id]) . '"><i
                                    class="fas fa-undo-alt text-primary"></i> Sale Return</a>';
                    }

                    if (auth()->user()->permission->sale['pos_edit'] == '1') {
                        $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->permission->sale['pos_delete'] == '1') {
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                }

                $html .= '<a class="dropdown-item" id="items_notification" href=""><i class="fas fa-envelope text-primary"></i> New Sale Notification</a>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', fn ($row) => date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date)))
            ->editColumn('invoice_id', function ($row) {
                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo mr-1 text-white"></i></span>' : '';
                return $html;
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')
            ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="' . $row->total_payable_amount . '">' . $this->converter->format_in_bdt($row->total_payable_amount) . '</span>')
            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')
            ->editColumn('due', fn ($row) => '<span class="due text-danger"  data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')
            ->editColumn('sale_return_amount', fn ($row) => '<span class="sale_return_amount text-danger"  data-value="' . $row->sale_return_amount . '">' . $this->converter->format_in_bdt($row->sale_return_amount) . '</span>')
            ->editColumn('sale_return_due', fn ($row) => '<span class="sale_return_due text-danger" data-value="' . $row->sale_return_due . '">' . $this->converter->format_in_bdt($row->sale_return_due) . '</span>')
            ->editColumn('paid_status', function ($row) {
                $payable = $row->total_payable_amount - $row->sale_return_amount;
                if ($row->due <= 0) {
                    return '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {
                    return '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {
                    return '<span class="text-danger"><b>Due</b></span>';
                }
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status'])
            ->make(true);
    }

    public function soldProductListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $saleProducts = '';
        $query = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.parent_category_id', 'sub_cate.id');

        if ($request->product_id) {
            $query->where('sale_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {
            $query->where('sale_products.product_variant_id', $request->variant_id);
        }

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('sales.branch_id', NULL);
            } else {
                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->customer_id) {
            if ($request->customer_id == 'NULL') {
                $query->where('sales.customer_id', NULL);
            } else {
                $query->where('sales.customer_id', $request->customer_id);
            }
        }

        if ($request->category_id) {
            $query->where('products.category_id', $request->category_id);
        }

        if ($request->sub_category_id) {
            $query->where('products.parent_category_id', $request->sub_category_id);
        }

        if ($request->sold_by) {
            $query->where('sales.created_by', $request->sold_by);
        }

        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $query->whereBetween('sales.report_date', $date_range); // Final
        }

        $query->select(
            'sale_products.sale_id',
            'sale_products.product_id',
            'sale_products.product_variant_id',
            'sale_products.unit_price_inc_tax',
            'sale_products.quantity',
            'units.code_name as unit_code',
            'sale_products.subtotal',
            'sales.id',
            'sales.date',
            'sales.invoice_id',
            'sales.created_by',
            'products.name',
            'products.product_code',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'customers.name as customer_name'
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 1) {
            $saleProducts = $query->orderBy('sales.report_date', 'desc');
        } else {
            if (auth()->user()->permission->sale['view_own_sale'] == '1') {
                $query->where('sales.admin_id', auth()->user()->id);
            }

            $saleProducts = $query->where('sales.branch_id', auth()->user()->branch_id)
                ->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($saleProducts)
            ->editColumn('product', function ($row) {
                $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                return Str::limit($row->name, 25, '') . $variant;
            })->editColumn('sold_by', fn ($row) => $row->created_by == 1 ? '<span class="text-info">ADD SALE</span>' : '<span class="text-success">POS</span>')
            ->editColumn('sku', function ($row) {
                return $row->variant_code ? $row->variant_code : $row->product_code;
            })->editColumn('date', function ($row) use ($generalSettings) {
                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })->editColumn('customer', function ($row) {
                return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
            })->editColumn('invoice_id', fn ($row) => '<a href="' . route('sales.show', [$row->sale_id]) . '" class="details_button text-danger text-hover" title="view" >' . $row->invoice_id . '</a>')
            ->editColumn('quantity', function ($row) {
                return $row->quantity . ' (<span class="qty" data-value="' . $row->quantity . '">' . $row->unit_code . '</span>)';
            })->editColumn('unit_price_inc_tax', fn ($row) => '<span class="unit_price_inc_tax" data-value="' . $row->unit_price_inc_tax . '">' . $this->converter->format_in_bdt($row->unit_price_inc_tax) . '</span>')
            ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->subtotal . '">' . $this->converter->format_in_bdt($row->subtotal) . '</span>')
            ->rawColumns(['product', 'customer', 'invoice_id', 'sku', 'date', 'sold_by', 'quantity', 'branch', 'unit_price_inc_tax', 'subtotal'])
            ->make(true);
    }

    public function saleDraftTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $drafts = '';
        $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

        $query->select(
            'sales.*',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer',
            'admin_and_users.prefix as u_prefix',
            'admin_and_users.name as u_name',
            'admin_and_users.last_name as u_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $drafts = $this->filteredQuery($request, $query)
                ->where('sales.status', 2)
                ->orderBy('sales.report_date', 'desc');
        } else {
            $drafts = $this->filteredQuery($request, $query)->where('branch_id', auth()->user()->branch_id)
                ->where('sales.status', 2)
                ->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($drafts)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="' . route('sales.quotations.details', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i>View</a>';

                if (auth()->user()->branch_id == $row->branch_id) {
                    if ($row->created_by == 1) {
                        $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                    } else {
                        $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                    }
                    $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt mr-1 text-primary"></i>Delete</a>';
                }

                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', function ($row) {
                return date('d/m/Y', strtotime($row->date));
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer',  function ($row) {
                return $row->customer ? $row->customer : 'Walk-In-Customer';
            })
            ->editColumn('total_payable_amount', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</b>';
            })
            ->editColumn('user', function ($row) {
                return $row->u_prefix . ' ' . $row->u_name . ' ' . $row->u_last_name;
            })
            ->setRowAttr([
                'data-href' => function ($row) {
                    return route('sales.quotations.details', [$row->id]);
                }
            ])
            ->setRowClass('clickable_row')
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'user'])
            ->make(true);
    }

    public function saleQuotationTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $quotations = '';
        $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

        $query->select(
            'sales.*',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer',
            'admin_and_users.prefix as u_prefix',
            'admin_and_users.name as u_name',
            'admin_and_users.last_name as u_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $quotations = $this->filteredQuery($request, $query)->where('sales.status', 4)->orderBy('sales.report_date', 'desc');
        } else {
            $quotations = $this->filteredQuery($request, $query)->where('sales.branch_id', auth()->user()->branch_id)->where('sales.status', 4)->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($quotations)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="' . route('sales.quotations.details', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';

                if (auth()->user()->branch_id == $row->branch_id) {
                    if ($row->created_by == 1) {
                        $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                    } else {
                        $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                    }
                    $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt mr-1 text-primary"></i> Delete</a>';
                }

                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', function ($row) {
                return date('d/m/Y', strtotime($row->date));
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer',  function ($row) {
                return $row->customer ? $row->customer : 'Walk-In-Customer';
            })
            ->editColumn('total_payable_amount', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</b>';
            })
            ->editColumn('user', function ($row) {
                return $row->u_prefix . ' ' . $row->u_name . ' ' . $row->u_last_name;
            })
            ->setRowAttr([
                'data-href' => function ($row) {
                    return route('sales.quotations.details', [$row->id]);
                }
            ])
            ->setRowClass('clickable_row')
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'user'])
            ->make(true);
    }

    public function saleShipmentListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $sales = '';
        $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

        $query->select(
            'sales.*',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer',
            'admin_and_users.prefix as cr_prefix',
            'admin_and_users.name as cr_name',
            'admin_and_users.last_name as cr_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $sales = $this->filteredQuery($request, $query)->where('sales.created_by', 1)
                ->where('sales.status', 1)
                ->where('shipment_status', '!=', 'NULL')
                ->orderBy('sales.report_date', 'desc');
        } else {
            $sales = $this->filteredQuery($request, $query)
                ->where('sales.created_by', 1)->where('branch_id', auth()->user()->branch_id)
                ->where('sales.status', 1)
                ->where('shipment_status', '!=', 'NULL')
                ->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="' . route('sales.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';
                $html .= '<a class="dropdown-item" id="edit_shipment" href="' . route('sales.shipment.edit', [$row->id]) . '"><i class="fas fa-truck mr-1 text-primary"></i> Edit shipment</a>';
                $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="fas fa-file-alt mr-1 text-primary"></i> Packing Slip </a>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', function ($row) {
                return date('d/m/Y', strtotime($row->date));
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer',  function ($row) {
                return $row->customer ? $row->customer : 'Walk-In-Customer';
            })
            ->editColumn('created_by',  function ($row) {
                return $row->cr_prefix . ' ' . $row->cr_name . ' ' . $row->cr_last_name;
            })
            ->editColumn('shipment_status',  function ($row) {
                $html = "";
                if ($row->shipment_status == 1) {
                    $html .= '<span class="text-primary"><b>Ordered</b></span>';
                } elseif ($row->shipment_status == 2) {
                    $html .= '<span class="text-secondary"><b>Packed</b></span>';
                } elseif ($row->shipment_status == 3) {
                    $html .= '<span class="text-warning"><b>Shipped</b></span>';
                } elseif ($row->shipment_status == 4) {
                    $html .= '<span class="text-success"><b>Delivered</b></span>';
                } elseif ($row->shipment_status == 5) {
                    $html .= '<span class="text-danger"><b>Cancelled</b></span>';
                }
                return $html;
            })
            ->editColumn('paid_status', function ($row) {
                $payable = $row->total_payable_amount - $row->sale_return_amount;
                $html = '';
                if ($row->due <= 0) {
                    $html .= '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {
                    $html .= '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {
                    $html .= '<span class="text-danger"><b>Due</b></span>';
                }
                return $html;
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'shipment_status', 'paid_status'])
            ->make(true);
    }

    private function filteredQuery($request, $query)
    {
        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('sales.branch_id', NULL);
            } else {
                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {
            $query->where('sales.admin_id', $request->user_id);
        }

        if ($request->customer_id) {
            if ($request->customer_id == 'NULL') {
                $query->where('sales.customer_id', NULL);
            } else {
                $query->where('sales.customer_id', $request->customer_id);
            }
        }

        if ($request->payment_status) {
            if ($request->payment_status == 1) {
                $query->where('sales.due', '=', 0);
            } else {
                $query->where('sales.due', '>', 0);
            }
        }

        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
             // $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range); // Final
        }
        return $query;
    }

    public function adjustSaleInvoiceAmounts($sale)
    {
        $totalSalePaid = DB::table('sale_payments')
            ->where('sale_payments.sale_id', $sale->id)->where('payment_type', 1)
            ->select(DB::raw('sum(paid_amount) as total_paid'))
            ->groupBy('sale_payments.sale_id')
            ->get();

        $totalReturnPaid = DB::table('sale_payments')
            ->where('sale_payments.sale_id', $sale->id)->where('payment_type', 2)
            ->select(DB::raw('sum(paid_amount) as total_paid'))
            ->groupBy('sale_payments.sale_id')
            ->get();

        $return = DB::table('sale_returns')->where('sale_id', $sale->id)->first();
        $returnAmount = $return ? $return->total_return_amount : 0;
        $due = $sale->total_payable_amount - $totalSalePaid->sum('total_paid') - $returnAmount + $totalReturnPaid->sum('total_paid');
        $returnDue = $returnAmount
            - ($sale->total_payable_amount - $totalSalePaid->sum('total_paid'))
            - $totalReturnPaid->sum('total_paid');

        $sale->paid = $totalSalePaid->sum('total_paid');
        $sale->due = $due;
        $sale->sale_return_amount = $returnAmount;
        $sale->sale_return_due = $returnDue > 0 ? $returnDue : 0;
        $sale->save();
    }

    public function addPurchaseSaleProductChain($sale, $stockAccountingMethod)
    {
        foreach ($sale->sale_products as $sale_product) {
            $variant_id = $sale_product->product_variant_id ? $sale_product->product_variant_id : NULL;

            $purchaseProducts = '';
            if ($stockAccountingMethod == 1) {
                $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                    ->where('product_id', $sale_product->product_id)
                    ->where('product_variant_id',  $variant_id)
                    ->orderBy('id', 'asc')->get();
            } else if ($stockAccountingMethod == 2) {
                $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                    ->where('product_id', $sale_product->product_id)
                    ->where('product_variant_id', $variant_id)
                    ->orderBy('id', 'desc')->get();
            }

            if (count($purchaseProducts) > 0) {
                $sold_qty = $sale_product->quantity;
                foreach ($purchaseProducts as $purchaseProduct) {
                    if ($sold_qty > $purchaseProduct->left_qty) {
                        if ($sold_qty > 0) {
                            $addPurchaseSaleChain = new PurchaseSaleProductChain();
                            $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                            $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                            $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                            $addPurchaseSaleChain->save();
                            $sold_qty -= $purchaseProduct->left_qty;
                            $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                        } else {
                            break;
                        }
                    } else if ($sold_qty == $purchaseProduct->left_qty) {
                        if ($sold_qty > 0) {
                            $addPurchaseSaleChain = new PurchaseSaleProductChain();
                            $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                            $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                            $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                            $addPurchaseSaleChain->save();
                            $sold_qty -= $purchaseProduct->left_qty;
                            $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                        } else {
                            break;
                        }
                    } else if ($sold_qty < $purchaseProduct->left_qty) {
                        if ($sold_qty > 0) {
                            $addPurchaseSaleChain = new PurchaseSaleProductChain();
                            $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                            $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                            $addPurchaseSaleChain->sold_qty = $sold_qty;
                            $addPurchaseSaleChain->save();
                            $sold_qty -= $sold_qty;
                            $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                        }else {
                            break;
                        }
                    }
                }
            }
        }
    }

    public function updatePurchaseSaleProductChain($sale, $stockAccountingMethod)
    {
        foreach ($sale->sale_products as $sale_product) {
            $variant_id = $sale_product->product_variant_id ? $sale_product->product_variant_id : NULL;
            
            $openingStock = DB::table('product_opening_stocks')->where('product_id', $sale_product->product_id)
                ->where('product_variant_id', $variant_id)->first();

            $purchaseProducts = '';
            if ($stockAccountingMethod == 1) {
                $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                    ->where('product_id', $sale_product->product_id)
                    ->where('product_variant_id', $variant_id)
                    ->orderBy('id', 'asc')->get();

            } else if ($stockAccountingMethod == 2) {
                $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                    ->where('product_id', $sale_product->product_id)
                    ->where('product_variant_id', $variant_id)
                    ->orderBy('id', 'desc')->get();
            }

            $sold_qty = $sale_product->quantity;
            if (count($purchaseProducts) > 0) {
                foreach ($purchaseProducts as $purchaseProduct) {
                    if ($sold_qty > $purchaseProduct->left_qty) {
                        if ($sold_qty > 0) {
                            $addPurchaseSaleChain = new PurchaseSaleProductChain();
                            $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                            $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                            $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                            $addPurchaseSaleChain->save();
                            $sold_qty -= $purchaseProduct->left_qty;
                            $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                        } else {
                            break;
                        }
                    } else if ($sold_qty == $purchaseProduct->left_qty) {
                        if ($sold_qty > 0) {
                            $addPurchaseSaleChain = new PurchaseSaleProductChain();
                            $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                            $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                            $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                            $addPurchaseSaleChain->save();
                            $sold_qty -= $purchaseProduct->left_qty;
                            $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                        } else {
                            break;
                        }
                    } else if ($sold_qty < $purchaseProduct->left_qty) {
                        if ($sold_qty > 0) {
                            $addPurchaseSaleChain = new PurchaseSaleProductChain();
                            $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                            $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                            $addPurchaseSaleChain->sold_qty = $sold_qty;
                            $addPurchaseSaleChain->save();
                            $sold_qty -= $sold_qty;
                            $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                        }else {
                            break;
                        }
                    }
                }
            }
        }
    }
}
