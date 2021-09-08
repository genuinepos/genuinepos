<?php

namespace App\Utils;

use App\Models\Sale;
use App\Models\Account;
use App\Models\Product;
use App\Models\CashFlow;
use App\Models\Customer;
use App\Models\SalePayment;
use App\Models\ProductBranch;
use App\Models\CustomerLedger;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use Yajra\DataTables\Facades\DataTables;

class SaleUtil
{
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
                                    $dueInvoice->paid = $dueInvoice->paid + $dueAmounts;
                                    $dueInvoice->due = $dueInvoice->due - $dueAmounts;
                                    $dueInvoice->save();
                                    $this->addPayment($paymentInvoicePrefix, $request, $dueAmounts, $invoiceId, $dueInvoice->id);
                                    //$dueAmounts -= $dueAmounts; 
                                    if ($index == 1) {
                                        break;
                                    }
                                } elseif ($dueInvoice->due == $dueAmounts) {
                                    $dueInvoice->paid = $dueInvoice->paid + $dueAmounts;
                                    $dueInvoice->due = $dueInvoice->due - $dueAmounts;
                                    $dueInvoice->save();
                                    $this->addPayment($paymentInvoicePrefix, $request, $dueAmounts, $invoiceId, $dueInvoice->id);
                                    if ($index == 1) {
                                        break;
                                    }
                                } elseif ($dueInvoice->due < $dueAmounts) {
                                    $this->addPayment($paymentInvoicePrefix, $request, $dueInvoice->due, $invoiceId, $dueInvoice->id);
                                    $dueAmounts = $dueAmounts - $dueInvoice->due;
                                    $dueInvoice->paid = $dueInvoice->paid + $dueInvoice->due;
                                    $dueInvoice->due = $dueInvoice->due - $dueInvoice->due;
                                    $dueInvoice->save();
                                }
                                $index++;
                            }
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

    public function updateProductBranchStock($request, $branch_id)
    {
        // update product quantity
        $quantities = $request->quantities;
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;

        $index = 0;
        foreach ($product_ids as $product_id) {
            // Update Branch product stock
            if ($branch_id) {
                $updateProductQty = Product::where('id', $product_id)->first();
                if ($updateProductQty->type == 1) {
                    $updateProductQty->quantity -= (float)$quantities[$index];
                    $updateProductQty->number_of_sale -= (float)$quantities[$index];
                    $updateProductQty->save();

                    $updateBranchProductQty = ProductBranch::where('branch_id', $branch_id)
                        ->where('product_id', $product_id)->first();
                    $updateBranchProductQty->product_quantity -= (float)$quantities[$index];
                    $updateBranchProductQty->save();

                    if ($variant_ids[$index] != 'noid') {
                        $updateProductVariant = ProductVariant::where('id', $variant_ids[$index])
                            ->where('product_id', $product_id)->first();
                        $updateProductVariant->variant_quantity -= (float)$quantities[$index];
                        $updateProductVariant->number_of_sale += (float)$quantities[$index];
                        $updateProductVariant->save();

                        $updateProductBranchVariant = ProductBranchVariant::where('product_branch_id', $updateBranchProductQty->id)
                            ->where('product_id', $product_id)
                            ->where('product_variant_id', $variant_ids[$index])
                            ->first();
                        $updateProductBranchVariant->variant_quantity -= (float)$quantities[$index];
                        $updateProductBranchVariant->save();
                    }
                }
            } else {
                $updateProductQty = Product::where('id', $product_id)->first();
                if ($updateProductQty->type == 1) {
                    $updateProductQty->quantity -= (float)$quantities[$index];
                    $updateProductQty->number_of_sale += (float)$quantities[$index];
                    $updateProductQty->mb_stock -= (float)$quantities[$index];
                    $updateProductQty->save();

                    if ($variant_ids[$index] != 'noid') {
                        $updateProductVariant = ProductVariant::where('id', $variant_ids[$index])
                            ->where('product_id', $product_id)
                            ->first();

                        $updateProductVariant->variant_quantity -= (float)$quantities[$index];
                        $updateProductVariant->number_of_sale += (float)$quantities[$index];

                        $updateProductVariant->mb_stock -= (float)$quantities[$index];
                        $updateProductVariant->save();
                    }
                }
            }
        }
    }

    // Add sale add payment util method
    public function addPayment($invoicePrefix, $request, $payingAmount, $invoiceId, $saleId)
    {
        $addSalePayment = new SalePayment();
        $addSalePayment->invoice_id = ($invoicePrefix != null ? $invoicePrefix : 'SPI') . date('ymd') . $invoiceId;
        $addSalePayment->sale_id = $saleId;
        $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
        $addSalePayment->account_id = $request->account_id;
        $addSalePayment->pay_mode = $request->payment_method;
        $addSalePayment->paid_amount = $payingAmount;
        $addSalePayment->date = $request->date;
        $addSalePayment->time = date('h:i:s a');
        $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addSalePayment->month = date('F');
        $addSalePayment->year = date('Y');
        $addSalePayment->note = $request->payment_note;

        if ($request->payment_method == 'Card') {
            $addSalePayment->card_no = $request->card_no;
            $addSalePayment->card_holder = $request->card_holder_name;
            $addSalePayment->card_transaction_no = $request->card_transaction_no;
            $addSalePayment->card_type = $request->card_type;
            $addSalePayment->card_month = $request->month;
            $addSalePayment->card_year = $request->year;
            $addSalePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $addSalePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $addSalePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $addSalePayment->transaction_no = $request->transaction_no;
        }

        $addSalePayment->admin_id = auth()->user()->id;
        $addSalePayment->save();

        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->credit = $account->credit + $payingAmount;
            $account->balance = $account->balance + $payingAmount;
            $account->save();

            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->credit = $payingAmount;
            $addCashFlow->balance = $account->balance;
            $addCashFlow->sale_payment_id = $addSalePayment->id;
            $addCashFlow->transaction_type = 2;
            $addCashFlow->cash_type = 2;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
        }

        if ($request->customer_id) {
            $addCustomerLedger = new CustomerLedger();
            $addCustomerLedger->customer_id = $request->customer_id;
            $addCustomerLedger->sale_payment_id = $addSalePayment->id;
            $addCustomerLedger->row_type = 2;
            $addCustomerLedger->save();
        }
    }

    public function deleteSale($request, $saleId)
    {
        $deleteSale = Sale::with([
            'sale_products',
            'sale_products.product',
            'sale_products.variant',
            'sale_products.product.comboProducts',
            'sale_products.sale_payments'
        ])->where('id', $saleId)->first();

        if ($deleteSale->status == 1) {
            if ($deleteSale->customer_id) {
                $customer = Customer::where('id', $deleteSale->customer_id)->first();
                $customer->total_sale_due -= ($deleteSale->due > 0 ? $deleteSale->due : 0);
                $customer->total_sale_return_due -= ($deleteSale->sale_return_due > 0 ? $deleteSale->sale_return_due : 0);
                $customer->total_sale -= $deleteSale->total_payable_amount;
                $customer->save();
            }
        }

        if (count($deleteSale->sale_payments) > 0) {
            foreach ($deleteSale->sale_payments as $payment) {
                if ($$payment->attachment) {
                    if (file_exists(public_path('uploads/payment_attachment/' . $payment->attachment))) {
                        unlink(public_path('uploads/payment_attachment/' . $payment->attachment));
                    }
                }
                
                if ($payment->account_id) {
                    $account = Account::where('id', $payment->account)->first();
                    if ($account) {
                        $account->credit -= $payment->paid_amount;
                        $account->balance -= $payment->paid_amount;
                        $account->save();
                    }
                }
            }
        }

        // Add product quantity for adjustment
        if ($deleteSale->status == 1) {
            foreach ($deleteSale->sale_products as $sale_product) {
                if ($sale_product->product->type == 1) {
                    $product = Product::where('id', $sale_product->product_id)->first();
                    $product->quantity += $sale_product->quantity;
                    $product->number_of_sale -= $sale_product->quantity;
                    $product->save();
                    if ($sale_product->product_variant_id) {
                        $variant = ProductVariant::where('id', $sale_product->product_variant_id)->first();
                        $variant->variant_quantity += $sale_product->quantity;
                        $variant->number_of_sale -= $sale_product->quantity;
                        $variant->save();
                    }

                    if ($deleteSale->branch_id) {
                        $productBranch = ProductBranch::where('branch_id', $deleteSale->branch_id)
                            ->where('product_id', $sale_product->product_id)
                            ->first();
                        $productBranch->product_quantity += $sale_product->quantity;
                        $productBranch->save();
                        if ($sale_product->product_variant_id) {
                            $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                                ->where('product_id', $sale_product->product_id)
                                ->where('product_variant_id', $sale_product->product_variant_id)
                                ->first();

                            $productBranchVariant->variant_quantity += $sale_product->quantity;
                            $productBranchVariant->save();
                        }
                    } else {
                        $product = Product::where('id', $sale_product->product_id)
                            ->first();
                        $product->mb_stock += $sale_product->quantity;
                        $product->save();

                        if ($sale_product->product_variant_id) {
                            $productVariant = ProductVariant::where('id', $sale_product->product_variant_id)
                                ->where('id', $sale_product->product_id)
                                ->first();
                            $productVariant->mb_stock += $sale_product->quantity;
                            $productVariant->save();
                        }
                    }
                }
            }
        }

        $deleteSale->delete();
        return response()->json('Sale deleted successfully');
    }

    public function deleteSaleOrReturnPayment($request, $paymentId)
    {
        $deleteSalePayment = SalePayment::with('account', 'customer', 'sale', 'sale.sale_return', 'cashFlow')
            ->where('id', $paymentId)->first();

        if (!is_null($deleteSalePayment)) {
            //Update customer due 
            if ($deleteSalePayment->payment_type == 1) {
                if ($deleteSalePayment->customer_id) {
                    $customer = Customer::where('id', $deleteSalePayment->customer_id)->first();
                    $customer->total_sale_due += $deleteSalePayment->paid_amount;
                    $customer->save();
                }

                // Update sale 
                $deleteSalePayment->sale->paid = $deleteSalePayment->sale->paid - $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->due = $deleteSalePayment->sale->due + $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->save();

                // Update previous account and delete previous cashflow.
                if ($deleteSalePayment->account) {
                    $deleteSalePayment->account->credit = $deleteSalePayment->account->credit - $deleteSalePayment->paid_amount;
                    $deleteSalePayment->account->balance = $deleteSalePayment->account->balance - $deleteSalePayment->paid_amount;
                    $deleteSalePayment->account->save();
                    $deleteSalePayment->cashFlow->delete();
                }

                if ($deleteSalePayment->attachment != null) {
                    if (file_exists(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment))) {
                        unlink(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment));
                    }
                }

                $deleteSalePayment->delete();
            } elseif ($deleteSalePayment->payment_type == 2) {
                if ($deleteSalePayment->customer) {
                    $deleteSalePayment->customer->total_sale_return_due = $deleteSalePayment->customer->total_sale_return_due + $deleteSalePayment->paid_amount;
                    $deleteSalePayment->customer->save();
                }

                // Update sale 
                $deleteSalePayment->sale->sale_return_due = $deleteSalePayment->sale->sale_return_due + $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->save();

                // Update sale return
                $deleteSalePayment->sale->sale_return->total_return_due = $deleteSalePayment->sale->sale_return->total_return_due + $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->sale_return->total_return_due_pay = $deleteSalePayment->sale->sale_return->total_return_due_pay - $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->sale_return->save();

                // Update previous account and delete previous cashflow.
                if ($deleteSalePayment->account) {
                    $deleteSalePayment->account->debit = $deleteSalePayment->account->debit - $deleteSalePayment->paid_amount;
                    $deleteSalePayment->account->balance = $deleteSalePayment->account->balance + $deleteSalePayment->paid_amount;
                    $deleteSalePayment->account->save();
                    $deleteSalePayment->cashFlow->delete();
                }

                if ($deleteSalePayment->attachment != null) {
                    if (file_exists(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment))) {
                        unlink(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment));
                    }
                }

                $deleteSalePayment->delete();
            }
        }
        return response()->json('Payment deleted successfully.');
    }

    public function addSaleTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $sales = '';
        $query = DB::table('sales')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id');

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $sales = $this->filteredQuery($request, $query)->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
            )->where('sales.status', 1)->where('created_by', 1)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $sales = $this->filteredQuery($request, $query)->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
            )->where('branch_id', auth()->user()->branch_id)
                ->where('sales.status', 1)->where('created_by', 1)
                ->orderBy('id', 'desc')
                ->get();
        }

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="' . route('sales.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';
                $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="fas fa-file-alt text-primary"></i> Packing Slip</a>';

                if (auth()->user()->permission->sale['shipment_access'] == '1') {
                    $html .= '<a class="dropdown-item" id="edit_shipment" href="' . route('sales.shipment.edit', [$row->id]) . '"><i class="fas fa-truck text-primary"></i> Edit Shipping</a>';
                }

                if (auth()->user()->branch_id == $row->branch_id) {
                    if ($row->due > 0) {
                        if (auth()->user()->permission->sale['sale_payment'] == '1') {
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

                    $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                }

                $html .= '<a class="dropdown-item" id="send_notification" href="' . route('sales.notification.form', [$row->id]) . '"><i class="fas fa-envelope text-primary"></i> New Sale Notification</a>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {
                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })
            ->editColumn('invoice_id', function ($row) {
                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo mr-1 text-white"></i></span>' : '';
                return $html;
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer',  function ($row) {
                return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
            })
            ->editColumn('total_payable_amount', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</b>';
            })
            ->editColumn('paid', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</b>';
            })
            ->editColumn('due', function ($row) use ($generalSettings) {
                return '<b><span class="text-success">' . json_decode($generalSettings->business, true)['currency'] . ($row->due >= 0 ? $row->due :   0.00) . '</span></b>';
            })
            ->editColumn('sale_return_amount', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->sale_return_amount . '</b>';
            })
            ->editColumn('sale_return_due', function ($row) use ($generalSettings) {
                return '<b><span class="text-danger">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->sale_return_due . '</span></b>';
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
            ->setRowAttr([
                'data-href' => function ($row) {
                    return route('sales.show', [$row->id]);
                }
            ])
            ->setRowClass('clickable_row')
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status'])
            ->make(true);
    }

    public function posSaleTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $sales = '';
        $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id');

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $sales = $this->filteredQuery($request, $query)->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
            )->where('sales.status', 1)->where('created_by', 2)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $sales = $this->filteredQuery($request, $query)->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
            )->where('sales.branch_id', auth()->user()->branch_id)->where('created_by', 2)
                ->where('sales.status', 1)
                ->orderBy('id', 'desc')
                ->get();
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
                    if ($row->due > 0) {
                        if (auth()->user()->permission->sale['sale_payment'] == '1') {
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

                    $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                }

                $html .= '<a class="dropdown-item" id="items_notification" href=""><i class="fas fa-envelope text-primary"></i> New Sale Notification</a>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {
                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })
            ->editColumn('invoice_id', function ($row) {
                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo mr-1 text-white"></i></span>' : '';
                return $html;
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer',  function ($row) {
                return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
            })
            ->editColumn('total_payable_amount', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</b>';
            })
            ->editColumn('paid', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</b>';
            })
            ->editColumn('due', function ($row) use ($generalSettings) {
                return '<b><span class="text-success">' . json_decode($generalSettings->business, true)['currency'] . ($row->due >= 0 ? $row->due :   0.00) . '</span></b>';
            })
            ->editColumn('sale_return_amount', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->sale_return_amount . '</b>';
            })
            ->editColumn('sale_return_due', function ($row) use ($generalSettings) {
                return '<b><span class="text-danger">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->sale_return_due . '</span></b>';
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
            ->setRowAttr([
                'data-href' => function ($row) {
                    return route('sales.pos.show', [$row->id]);
                }
            ])
            ->setRowClass('clickable_row')
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status'])
            ->make(true);
    }

    public function saleDraftTable($request)
    {

        $generalSettings = DB::table('general_settings')->first();

        $drafts = '';
        $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $drafts = $this->filteredQuery($request, $query)->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
                'admin_and_users.prefix as u_prefix',
                'admin_and_users.name as u_name',
                'admin_and_users.last_name as u_last_name',
            )->where('sales.status', 2)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $drafts = $this->filteredQuery($request, $query)->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
                'admin_and_users.prefix as u_prefix',
                'admin_and_users.name as u_name',
                'admin_and_users.last_name as u_last_name',
            )->where('branch_id', auth()->user()->branch_id)
                ->where('sales.status', 2)
                ->orderBy('id', 'desc')
                ->get();
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
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>BR</b>)';
                }
            })
            ->editColumn('customer',  function ($row) {
                return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
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

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $quotations = $this->filteredQuery($request, $query)->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
                'admin_and_users.prefix as u_prefix',
                'admin_and_users.name as u_name',
                'admin_and_users.last_name as u_last_name',
            )->where('sales.status', 4)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $quotations = $this->filteredQuery($request, $query)->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
                'admin_and_users.prefix as u_prefix',
                'admin_and_users.name as u_name',
                'admin_and_users.last_name as u_last_name',
            )->where('sales.branch_id', auth()->user()->branch_id)
                ->where('sales.status', 4)
                ->orderBy('id', 'desc')
                ->get();
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
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>BR</b>)';
                }
            })
            ->editColumn('customer',  function ($row) {
                return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
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

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $sales = $this->filteredQuery($request, $query)->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
                'admin_and_users.prefix as cr_prefix',
                'admin_and_users.name as cr_name',
                'admin_and_users.last_name as cr_last_name',
            )->where('sales.created_by', 1)->orderBy('id', 'desc')->where('sales.status', 1)
                ->where('shipment_status', '!=', 'NULL')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $sales = $this->filteredQuery($request, $query)->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
                'admin_and_users.prefix as cr_prefix',
                'admin_and_users.name as cr_name',
                'admin_and_users.last_name as cr_last_name',
            )->where('sales.created_by', 1)->where('branch_id', auth()->user()->branch_id)
                ->where('sales.status', 1)
                ->where('shipment_status', '!=', 'NULL')
                ->orderBy('id', 'desc')
                ->get();
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
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer',  function ($row) {
                return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
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
            ->setRowAttr([
                'data-href' => function ($row) {
                    return route('sales.show', [$row->id]);
                }
            ])
            ->setRowClass('clickable_row text-start')
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'shipment_status', 'paid_status'])
            ->make(true);
    }

    private function filteredQuery($request, $query) {
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

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
        }
        return $query;
    }
}
