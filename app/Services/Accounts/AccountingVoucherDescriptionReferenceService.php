<?php

namespace App\Services\Accounts;

use App\Enums\PurchaseStatus;
use App\Enums\SaleStatus;
use App\Models\Accounts\AccountingVoucherDescriptionReference;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\PurchaseReturn;
use App\Models\Sales\Sale;
use App\Models\Sales\SaleReturn;
use Illuminate\Support\Facades\DB;

class AccountingVoucherDescriptionReferenceService
{
    public function addAccountingVoucherDescriptionReferences(
        int $accountingVoucherDescriptionId,
        ?int $accountId,
        float $amount,
        string $refIdColName,
        array $refIds = null,
        int $branchId = null,
    ) {
        if ($refIdColName == 'stock_adjustment_id' && count($refIds) > 0) {

            $addPaymentDescriptionRef = new AccountingVoucherDescriptionReference();
            $addPaymentDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
            $addPaymentDescriptionRef->{$refIdColName} = $refIds[0];
            $addPaymentDescriptionRef->amount = $amount;
            $addPaymentDescriptionRef->save();

            return;
        }

        if ($refIdColName == 'payroll_id' && count($refIds) > 0) {

            $addPaymentDescriptionRef = new AccountingVoucherDescriptionReference();
            $addPaymentDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
            $addPaymentDescriptionRef->{$refIdColName} = $refIds[0];
            $addPaymentDescriptionRef->amount = $amount;
            $addPaymentDescriptionRef->save();

            return;
        }

        if (isset($refIds)) {

            $this->specificAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $accountingVoucherDescriptionId, accountId: $accountId, amount: $amount, refIdColName: $refIdColName, refIds: $refIds, branchId: $branchId);
        } else {

            $this->randomAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $accountingVoucherDescriptionId, accountId: $accountId, amount: $amount, refIdColName: $refIdColName, branchId: $branchId);
        }
    }

    private function specificAccountingVoucherDescriptionReferences(
        int $accountingVoucherDescriptionId,
        int $accountId,
        float $amount,
        string $refIdColName,
        array $refIds,
        int $branchId = null
    ) {

        $saleService = new \App\Services\Sales\SaleService();
        $purchaseService = new \App\Services\Purchases\PurchaseService();
        $purchaseReturnService = new \App\Services\Purchases\PurchaseReturnService();
        $salesReturnService = new \App\Services\Sales\SalesReturnService();

        $receivedOrPaidAmount = $amount;
        $dueSpecificInvoices = $this->dueSpecificInvoices(accountId: $accountId, refIdColName: $refIdColName, refIds: $refIds, branchId: $branchId);

        // $dueInvoices = Sale::where('customer_id', $customerId)
        //     ->whereIn('id', $saleIds)
        //     ->orderBy('report_date', 'asc')
        //     ->get();

        if (count($dueSpecificInvoices) > 0) {

            $index = 0;
            foreach ($dueSpecificInvoices as $dueInvoice) {

                $isOrderInvoice = 0;
                if ($refIdColName == 'sale_id') {

                    $isOrderInvoice = $dueInvoice->status != SaleStatus::Final->value ? 1 : 0;
                } elseif ($refIdColName == 'purchase_id') {

                    $isOrderInvoice = $dueInvoice->purchase_status != PurchaseStatus::Purchase->value ? 1 : 0;
                }

                if ($dueInvoice->due > $receivedOrPaidAmount) {

                    if ($receivedOrPaidAmount > 0) {

                        $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                        $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                        $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                        $addAccountingVoucherDescriptionRef->amount = $receivedOrPaidAmount;
                        $addAccountingVoucherDescriptionRef->save();

                        if ($isOrderInvoice == 0) {

                            $receivedOrPaidAmount -= $receivedOrPaidAmount;
                        }

                        if ($refIdColName == 'sale_id') {

                            $saleService->adjustSaleInvoiceAmounts($dueInvoice);
                        } elseif ($refIdColName == 'purchase_id') {

                            $purchaseService->adjustPurchaseInvoiceAmounts($dueInvoice);
                        } elseif ($refIdColName == 'purchase_return_id') {

                            $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                        } elseif ($refIdColName == 'sale_return_id') {

                            $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                        }
                    } else {

                        break;
                    }
                } elseif ($dueInvoice->due == $receivedOrPaidAmount) {

                    if ($receivedOrPaidAmount > 0) {

                        $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                        $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                        $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                        $addAccountingVoucherDescriptionRef->amount = $receivedOrPaidAmount;
                        $addAccountingVoucherDescriptionRef->save();

                        if ($isOrderInvoice == 0) {

                            $receivedOrPaidAmount -= $receivedOrPaidAmount;
                        }

                        if ($refIdColName == 'sale_id') {

                            $saleService->adjustSaleInvoiceAmounts($dueInvoice);
                        } elseif ($refIdColName == 'purchase_id') {

                            $purchaseService->adjustPurchaseInvoiceAmounts($dueInvoice);
                        } elseif ($refIdColName == 'purchase_return_id') {

                            $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                        } elseif ($refIdColName == 'sale_return_id') {

                            $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                        }
                    } else {

                        break;
                    }
                } elseif ($dueInvoice->due < $receivedOrPaidAmount) {

                    if ($receivedOrPaidAmount > 0) {

                        $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                        $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                        $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                        $addAccountingVoucherDescriptionRef->amount = $dueInvoice->due;
                        $addAccountingVoucherDescriptionRef->save();

                        if ($isOrderInvoice == 0) {

                            $receivedOrPaidAmount -= $dueInvoice->due;
                        }

                        if ($refIdColName == 'sale_id') {

                            $saleService->adjustSaleInvoiceAmounts($dueInvoice);
                        } elseif ($refIdColName == 'purchase_id') {

                            $purchaseService->adjustPurchaseInvoiceAmounts($dueInvoice);
                        } elseif ($refIdColName == 'purchase_return_id') {

                            $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                        } elseif ($refIdColName == 'sale_return_id') {

                            $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                        }
                    } else {

                        break;
                    }
                }

                $index++;
            }
        }

        if ($receivedOrPaidAmount > 0) {

            $dueRandomInvoices = $this->dueRandomInvoices(accountId: $accountId, refIdColName: $refIdColName, branchId: $branchId);

            if (count($dueRandomInvoices) > 0) {

                $index = 0;
                foreach ($dueRandomInvoices as $dueInvoice) {

                    if ($dueInvoice->due > $receivedOrPaidAmount) {

                        if ($receivedOrPaidAmount > 0) {

                            $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                            $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                            $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                            $addAccountingVoucherDescriptionRef->amount = $receivedOrPaidAmount;
                            $addAccountingVoucherDescriptionRef->save();

                            $receivedOrPaidAmount -= $receivedOrPaidAmount;

                            if ($refIdColName == 'sale_id') {

                                $saleService->adjustSaleInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_id') {

                                $purchaseService->adjustPurchaseInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_return_id') {

                                $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                            } elseif ($refIdColName == 'sale_return_id') {

                                $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                            }
                        } else {

                            break;
                        }
                    } elseif ($dueInvoice->due == $receivedOrPaidAmount) {

                        if ($receivedOrPaidAmount > 0) {

                            $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                            $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                            $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                            $addAccountingVoucherDescriptionRef->amount = $receivedOrPaidAmount;
                            $addAccountingVoucherDescriptionRef->save();

                            $receivedOrPaidAmount -= $receivedOrPaidAmount;

                            if ($refIdColName == 'sale_id') {

                                $saleService->adjustSaleInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_id') {

                                $purchaseService->adjustPurchaseInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_return_id') {

                                $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                            } elseif ($refIdColName == 'sale_return_id') {

                                $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                            }
                        } else {

                            break;
                        }
                    } elseif ($dueInvoice->due < $receivedOrPaidAmount) {

                        if ($receivedOrPaidAmount > 0) {

                            $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                            $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                            $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                            $addAccountingVoucherDescriptionRef->amount = $dueInvoice->due;
                            $addAccountingVoucherDescriptionRef->save();

                            $receivedOrPaidAmount -= $dueInvoice->due;

                            if ($refIdColName == 'sale_id') {

                                $saleService->adjustSaleInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_id') {

                                $purchaseService->adjustPurchaseInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_return_id') {

                                $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                            } elseif ($refIdColName == 'sale_return_id') {

                                $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                            }
                        } else {

                            break;
                        }
                    }

                    $index++;
                }
            } else {

                if ($refIdColName = 'sale_id') {

                    $refIdColName = 'purchase_return_id';
                } elseif ($refIdColName = 'purchase_id') {

                    $refIdColName = 'sale_return_id';
                }

                $dueRandomInvoices = $this->dueRandomInvoices(accountId: $accountId, refIdColName: $refIdColName, branchId: $branchId);

                if (count($dueRandomInvoices) > 0) {

                    foreach ($dueRandomInvoices as $dueInvoice) {

                        if ($dueInvoice->due > $receivedOrPaidAmount) {

                            if ($receivedOrPaidAmount > 0) {

                                $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                                $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                                $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                                $addAccountingVoucherDescriptionRef->amount = $receivedOrPaidAmount;
                                $addAccountingVoucherDescriptionRef->save();

                                $receivedOrPaidAmount -= $receivedOrPaidAmount;

                                if ($refIdColName == 'purchase_return_id') {

                                    $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                                } elseif ($refIdColName == 'sale_return_id') {

                                    $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                                }
                            } else {

                                break;
                            }
                        } elseif ($dueInvoice->due == $receivedOrPaidAmount) {

                            if ($receivedOrPaidAmount > 0) {

                                $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                                $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                                $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                                $addAccountingVoucherDescriptionRef->amount = $receivedOrPaidAmount;
                                $addAccountingVoucherDescriptionRef->save();

                                $receivedOrPaidAmount -= $receivedOrPaidAmount;

                                if ($refIdColName == 'purchase_return_id') {

                                    $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                                } elseif ($refIdColName == 'sale_return_id') {

                                    $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                                }
                            } else {

                                break;
                            }
                        } elseif ($dueInvoice->due < $receivedOrPaidAmount) {

                            if ($receivedOrPaidAmount > 0) {

                                $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                                $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                                $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                                $addAccountingVoucherDescriptionRef->amount = $dueInvoice->due;
                                $addAccountingVoucherDescriptionRef->save();

                                $receivedOrPaidAmount -= $dueInvoice->due;

                                if ($refIdColName == 'purchase_return_id') {

                                    $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                                } elseif ($refIdColName == 'sale_return_id') {

                                    $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                                }
                            } else {

                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    private function randomAccountingVoucherDescriptionReferences(
        int $accountingVoucherDescriptionId,
        int $accountId,
        float $amount,
        string $refIdColName,
        int $branchId = null
    ) {

        $saleService = new \App\Services\Sales\SaleService();
        $purchaseService = new \App\Services\Purchases\PurchaseService();
        $purchaseReturnService = new \App\Services\Purchases\PurchaseReturnService();
        $salesReturnService = new \App\Services\Sales\SalesReturnService();

        $receivedOrPaidAmount = $amount;

        if ($receivedOrPaidAmount > 0) {

            $dueRandomInvoices = $this->dueRandomInvoices(accountId: $accountId, refIdColName: $refIdColName, branchId: $branchId);

            if (count($dueRandomInvoices) > 0) {

                $index = 0;
                foreach ($dueRandomInvoices as $dueInvoice) {

                    if ($dueInvoice->due > $receivedOrPaidAmount) {

                        if ($receivedOrPaidAmount > 0) {

                            $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                            $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                            $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                            $addAccountingVoucherDescriptionRef->amount = $receivedOrPaidAmount;
                            $addAccountingVoucherDescriptionRef->save();

                            $receivedOrPaidAmount -= $receivedOrPaidAmount;

                            if ($refIdColName == 'sale_id') {

                                $saleService->adjustSaleInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_id') {

                                $purchaseService->adjustPurchaseInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_return_id') {

                                $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                            } elseif ($refIdColName == 'sale_return_id') {

                                $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                            }
                        } else {

                            break;
                        }
                    } elseif ($dueInvoice->due == $receivedOrPaidAmount) {

                        if ($receivedOrPaidAmount > 0) {

                            $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                            $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                            $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                            $addAccountingVoucherDescriptionRef->amount = $receivedOrPaidAmount;
                            $addAccountingVoucherDescriptionRef->save();

                            $receivedOrPaidAmount -= $receivedOrPaidAmount;

                            if ($refIdColName == 'sale_id') {

                                $saleService->adjustSaleInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_id') {

                                $purchaseService->adjustPurchaseInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_return_id') {

                                $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                            } elseif ($refIdColName == 'sale_return_id') {

                                $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                            }
                        } else {

                            break;
                        }
                    } elseif ($dueInvoice->due < $receivedOrPaidAmount) {

                        if ($receivedOrPaidAmount > 0) {

                            $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                            $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                            $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                            $addAccountingVoucherDescriptionRef->amount = $dueInvoice->due;
                            $addAccountingVoucherDescriptionRef->save();

                            $receivedOrPaidAmount -= $dueInvoice->due;

                            if ($refIdColName == 'sale_id') {

                                $saleService->adjustSaleInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_id') {

                                $purchaseService->adjustPurchaseInvoiceAmounts($dueInvoice);
                            } elseif ($refIdColName == 'purchase_return_id') {

                                $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                            } elseif ($refIdColName == 'sale_return_id') {

                                $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                            }
                        } else {

                            break;
                        }
                    }

                    $index++;
                }
            }
        }

        if ($receivedOrPaidAmount > 0) {

            if ($refIdColName = 'sale_id') {

                $refIdColName = 'purchase_return_id';
            } elseif ($refIdColName = 'purchase_id') {

                $refIdColName = 'sale_return_id';
            }

            $dueRandomInvoices = $this->dueRandomInvoices(accountId: $accountId, refIdColName: $refIdColName, branchId: $branchId);

            if (count($dueRandomInvoices) > 0) {

                foreach ($dueRandomInvoices as $index => $dueInvoice) {

                    if ($dueInvoice->due > $receivedOrPaidAmount) {

                        if ($receivedOrPaidAmount > 0) {

                            $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                            $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                            $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                            $addAccountingVoucherDescriptionRef->amount = $receivedOrPaidAmount;
                            $addAccountingVoucherDescriptionRef->save();

                            $receivedOrPaidAmount -= $receivedOrPaidAmount;

                            if ($refIdColName == 'purchase_return_id') {

                                $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                            } elseif ($refIdColName == 'sale_return_id') {

                                $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                            }
                        } else {

                            break;
                        }
                    } elseif ($dueInvoice->due == $receivedOrPaidAmount) {

                        if ($receivedOrPaidAmount > 0) {

                            $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                            $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                            $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                            $addAccountingVoucherDescriptionRef->amount = $receivedOrPaidAmount;
                            $addAccountingVoucherDescriptionRef->save();

                            $receivedOrPaidAmount -= $receivedOrPaidAmount;

                            if ($refIdColName == 'purchase_return_id') {

                                $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                            } elseif ($refIdColName == 'sale_return_id') {

                                $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                            }
                        } else {

                            break;
                        }
                    } elseif ($dueInvoice->due < $receivedOrPaidAmount) {

                        if ($receivedOrPaidAmount > 0) {

                            $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                            $addAccountingVoucherDescriptionRef->voucher_description_id = $accountingVoucherDescriptionId;
                            $addAccountingVoucherDescriptionRef->{$refIdColName} = $dueInvoice->id;
                            $addAccountingVoucherDescriptionRef->amount = $dueInvoice->due;
                            $addAccountingVoucherDescriptionRef->save();

                            $receivedOrPaidAmount -= $dueInvoice->due;

                            if ($refIdColName == 'purchase_return_id') {

                                $purchaseReturnService->adjustPurchaseReturnVoucherAmounts($dueInvoice);
                            } elseif ($refIdColName == 'sale_return_id') {

                                $salesReturnService->adjustSalesReturnVoucherAmounts($dueInvoice);
                            }
                        } else {

                            break;
                        }
                    }
                }
            }
        }
    }

    public function invoiceOrVoucherDueAmountAutoDistribution(
        int $accountId,
        int $accountingVoucherType,
        string $refIdColName,
        object $purchase = null,
        object $sale = null,
    ): void {

        $saleService = new \App\Services\Sales\SaleService();
        $purchaseService = new \App\Services\Purchases\PurchaseService();

        $dueAmount = $refIdColName == 'purchase_id' ? $purchase->due : $sale->due;
        $branchId = $refIdColName == 'purchase_id' ? $purchase->branch_id : $sale->branch_id;

        $voucherDescriptions = DB::table('accounting_voucher_descriptions')
            ->leftJoin('voucher_description_references', 'accounting_voucher_descriptions.id', 'voucher_description_references.voucher_description_id')
            ->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id')
            ->leftJoin('sales', 'voucher_description_references.sale_id', 'sales.id')
            ->leftJoin('purchases', 'voucher_description_references.purchase_id', 'purchases.id')
            ->where('accounting_voucher_descriptions.account_id', $accountId)
            ->where('accounting_vouchers.voucher_type', $accountingVoucherType)
            ->where('accounting_vouchers.branch_id', $branchId)
            ->select(
                'accounting_vouchers.id as accounting_voucher_id',
                'accounting_voucher_descriptions.account_id',
                'accounting_voucher_descriptions.id as voucher_description_id',
                // 'supplier_payments.account_id',
                // 'supplier_payments.date',
                // 'supplier_payments.voucher_no',
                'accounting_voucher_descriptions.amount as received_or_paid_amount',
                // DB::raw('SUM(supplier_payment_invoices.paid_amount) as total_invoice_paid_amount'),
                // DB::raw('SUM(- IFNULL(supplier_payment_invoices.paid_amount, 0)) + supplier_payments.paid_amount as left_amount')
                // DB::raw('SUM(- IFNULL(voucher_description_references.amount, 0)) + accounting_voucher_descriptions.amount as left_amount')
                DB::raw('SUM(- CASE WHEN COALESCE(sales.status, 0) != 3 AND COALESCE(purchases.purchase_status, 0) != 2 THEN voucher_description_references.amount ELSE 0 END) + accounting_voucher_descriptions.amount as left_amount')
            )
            ->having('left_amount', '>', 0)
            ->groupBy('accounting_vouchers.id')
            ->groupBy('accounting_voucher_descriptions.account_id')
            ->groupBy('accounting_voucher_descriptions.id')
            ->groupBy('accounting_voucher_descriptions.amount')
            ->get();

        foreach ($voucherDescriptions as $voucherDescription) {

            if ($dueAmount > $voucherDescription->left_amount) {

                if ($dueAmount > 0) {

                    $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                    $addAccountingVoucherDescriptionRef->voucher_description_id = $voucherDescription->voucher_description_id;
                    $addAccountingVoucherDescriptionRef->{$refIdColName} = $refIdColName == 'purchase_id' ? $purchase->id : $sale->id;
                    $addAccountingVoucherDescriptionRef->amount = $voucherDescription->left_amount;
                    $addAccountingVoucherDescriptionRef->save();

                    $dueAmount -= $voucherDescription->left_amount;

                    if ($refIdColName == 'sale_id') {

                        $saleService->adjustSaleInvoiceAmounts($sale);
                    } elseif ($refIdColName == 'purchase_id') {

                        $purchaseService->adjustPurchaseInvoiceAmounts($purchase);
                    }
                } else {

                    break;
                }
            } elseif ($dueAmount == $voucherDescription->left_amount) {

                if ($dueAmount > 0) {

                    $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                    $addAccountingVoucherDescriptionRef->voucher_description_id = $voucherDescription->voucher_description_id;
                    $addAccountingVoucherDescriptionRef->{$refIdColName} = $refIdColName == 'purchase_id' ? $purchase->id : $sale->id;
                    $addAccountingVoucherDescriptionRef->amount = $voucherDescription->left_amount;
                    $addAccountingVoucherDescriptionRef->save();

                    $dueAmount -= $voucherDescription->left_amount;

                    if ($refIdColName == 'sale_id') {

                        $saleService->adjustSaleInvoiceAmounts($sale);
                    } elseif ($refIdColName == 'purchase_id') {

                        $purchaseService->adjustPurchaseInvoiceAmounts($purchase);
                    }
                } else {

                    break;
                }
            } elseif ($dueAmount < $voucherDescription->left_amount) {

                if ($dueAmount > 0) {

                    $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                    $addAccountingVoucherDescriptionRef->voucher_description_id = $voucherDescription->voucher_description_id;
                    $addAccountingVoucherDescriptionRef->{$refIdColName} = $refIdColName == 'purchase_id' ? $purchase->id : $sale->id;
                    $addAccountingVoucherDescriptionRef->amount = $dueAmount;
                    $addAccountingVoucherDescriptionRef->save();

                    $dueAmount -= $dueAmount;

                    if ($refIdColName == 'sale_id') {

                        $saleService->adjustSaleInvoiceAmounts($sale);
                    } elseif ($refIdColName == 'purchase_id') {

                        $purchaseService->adjustPurchaseInvoiceAmounts($purchase);
                    }
                } else {

                    break;
                }
            }
        }
    }

    private function dueSpecificInvoices(int $accountId, string $refIdColName, array $refIds, int $branchId = null)
    {
        $__branchId = $branchId ? $branchId : auth()->user()->branch_id;

        if ($refIdColName == 'purchase_id') {

            return Purchase::where('supplier_account_id', $accountId)
                ->whereIn('id', $refIds)
                ->get()
                ->sortBy(function ($purchase) use ($refIds) {
                    return array_search($purchase->id, $refIds);
                });
        } elseif ($refIdColName == 'purchase_return_id') {

            return PurchaseReturn::where('supplier_account_id', $accountId)
                ->whereIn('id', $refIds)
                ->get()
                ->sortBy(function ($purchaseReturn) use ($refIds) {
                    return array_search($purchaseReturn->id, $refIds);
                });
        } elseif ($refIdColName == 'sale_id') {

            return Sale::where('customer_account_id', $accountId)
                ->whereIn('id', $refIds)
                ->get()
                ->sortBy(function ($sale) use ($refIds) {
                    return array_search($sale->id, $refIds);
                });
        } elseif ($refIdColName == 'sale_return_id') {

            return SaleReturn::where('customer_account_id', $accountId)
                ->whereIn('id', $refIds)
                ->get()
                ->sortBy(function ($salesReturn) use ($refIds) {
                    return array_search($salesReturn->id, $refIds);
                });
        }
    }

    private function dueRandomInvoices(int $accountId, string $refIdColName, int $branchId = null)
    {
        $__branchId = $branchId ? $branchId : auth()->user()->branch_id;

        $account = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->select('account_groups.sub_sub_group_number')->first();

        if ($refIdColName == 'purchase_id') {

            $purchases = '';
            $query = Purchase::where('supplier_account_id', $accountId);

            if ($account->sub_sub_group_number != 6) {

                $query->where('branch_id', $__branchId);
            }

            $purchases = $query->where('due', '>', 0)
                ->where('purchase_status', PurchaseStatus::Purchase->value)
                ->orderBy('report_date', 'asc')
                ->get();

            return $purchases;
        } elseif ($refIdColName == 'purchase_return_id') {

            $purchaseReturns = '';
            $query = PurchaseReturn::where('supplier_account_id', $accountId);

            if ($account->sub_sub_group_number != 6) {

                $query->where('branch_id', $__branchId);
            }

            $purchaseReturns = $query->where('due', '>', 0)->orderBy('date_ts', 'asc')->get();

            return $purchaseReturns;
        } elseif ($refIdColName == 'sale_id') {

            $sales = '';
            $query = Sale::where('customer_account_id', $accountId);

            if ($account->sub_sub_group_number != 6) {

                $query->where('branch_id', $__branchId);
            }

            $sales = $query->where('due', '>', 0)
                ->where('status', SaleStatus::Final->value)
                ->orderBy('date_ts', 'asc')
                ->get();

            return $sales;
        } elseif ($refIdColName == 'sale_return_id') {

            $salesReturns = '';
            $query = SaleReturn::where('customer_account_id', $accountId);

            if ($account->sub_sub_group_number != 6) {

                $query->where('branch_id', $__branchId);
            }

            $salesReturns = $query->where('due', '>', 0)
                ->orderBy('date_ts', 'asc')
                ->get();

            return $salesReturns;
        }
    }
}
