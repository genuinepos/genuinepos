<?php

namespace App\Services\Accounts;

use App\Models\Sale;
use App\Models\Purchases\Purchase;
use Illuminate\Support\Facades\DB;
use App\Models\Accounts\AccountingVoucherDescriptionReference;

class AccountingVoucherDescriptionReferenceService
{
    public function addAccountingVoucherDescriptionReferences(
        int $accountingVoucherDescriptionId,
        int $accountId,
        float $amount,
        string $refIdColName,
        ?array $refIds = null,
    ) {
        // $index = 0;
        // foreach ($refIds as $refId) {

        //     $addPaymentDescriptionRef = new AccountingVoucherDescriptionReference();
        //     $addPaymentDescriptionRef->payment_description_id = $accountingVoucherDescriptionId;
        //     $addPaymentDescriptionRef->{$refIdColName} = $refId;
        //     $addPaymentDescriptionRef->amount = $amounts[$index];
        //     $addPaymentDescriptionRef->save();

        //     $index++;
        // }

        if (isset($refIds)) {

            $this->specificAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $accountingVoucherDescriptionId, accountId: $accountId, amount: $amount, refIdColName: $refIdColName, refIds: $refIds);
        }else {

            $this->randomAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $accountingVoucherDescriptionId, accountId: $accountId, amount: $amount, refIdColName: $refIdColName);
        }
    }

    private function specificAccountingVoucherDescriptionReferences(
        int $accountingVoucherDescriptionId,
        int $accountId,
        int $amount,
        string $refIdColName,
        array $refIds
    ) {

        $saleService = new \App\Services\Sales\SaleService();
        $purchaseService = new \App\Services\Purchases\PurchaseService();

        $receivedOrPaidAmount = $amount;
        $dueSpecificInvoices = $this->dueSpecificInvoices(accountId: $accountId, refIdColName: $refIdColName, refIds: $refIds);

        // $dueInvoices = Sale::where('customer_id', $customerId)
        //     ->whereIn('id', $saleIds)
        //     ->orderBy('report_date', 'asc')
        //     ->get();

        if (count($dueSpecificInvoices) > 0) {

            $index = 0;
            foreach ($dueSpecificInvoices as $dueInvoice) {

                $isOrderInvoice = 0;
                if ($refIdColName == 'sale_id') {

                    $isOrderInvoice = $dueInvoice->status != 1 ? 1 : 0;
                } elseif ($refIdColName == 'purchase_id') {

                    $isOrderInvoice = $dueInvoice->purchase_status != 1 ? 1 : 0;
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
                        }
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
                        }
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
                        }
                    }
                }

                $index++;
            }
        }

        if ($receivedOrPaidAmount > 0) {

            $dueRandomInvoices = $this->dueRandomInvoices(accountId: $accountId, refIdColName: $refIdColName);

            if (count($dueRandomInvoices) > 0) {

                $index = 0;
                foreach ($dueRandomInvoices as $dueInvoice) {

                    $isOrderInvoice = 0;
                    if ($refIdColName == 'sale_id') {

                        $isOrderInvoice = $dueInvoice->status != 1 ? 1 : 0;
                    } elseif ($refIdColName == 'purchase_id') {

                        $isOrderInvoice = $dueInvoice->purchase_status != 1 ? 1 : 0;
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
                            }
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
                            }
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
                            }
                        }
                    }

                    $index++;
                }
            }
        }
    }

    private function randomAccountingVoucherDescriptionReferences(
        int $accountingVoucherDescriptionId,
        int $accountId,
        int $amount,
        string $refIdColName,
    ) {

        $saleService = new \App\Services\Sales\SaleService();
        $purchaseService = new \App\Services\Purchases\PurchaseService();

        $receivedOrPaidAmount = $amount;

        if ($receivedOrPaidAmount > 0) {

            $dueRandomInvoices = $this->dueRandomInvoices(accountId: $accountId, refIdColName: $refIdColName);

            if (count($dueRandomInvoices) > 0) {

                $index = 0;
                foreach ($dueRandomInvoices as $dueInvoice) {

                    $isOrderInvoice = 0;
                    if ($refIdColName == 'sale_id') {

                        $isOrderInvoice = $dueInvoice->status != 1 ? 1 : 0;
                    } elseif ($refIdColName == 'purchase_id') {

                        $isOrderInvoice = $dueInvoice->purchase_status != 1 ? 1 : 0;
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
                            }
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
                            }
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
                            }
                        }
                    }

                    $index++;
                }
            }
        }
    }

    public function invoiceOrVoucherDueAmountAutoDistribution(
        int $accountId,
        int $accountingVoucherType,
        string $refIdColName,
        ?object $purchase = null,
        ?object $sale = null,
    ): void {

        $saleService = new \App\Services\Sales\SaleService();
        $purchaseService = new \App\Services\Purchases\PurchaseService();

        $dueAmount = $refIdColName == 'purchase_id' ? $purchase->due : $sale->due;

        $voucherDescriptions = DB::table('accounting_voucher_descriptions')
            ->leftJoin('voucher_description_references', 'accounting_voucher_descriptions.id', 'voucher_description_references.voucher_description_id')
            ->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id')
            ->where('accounting_voucher_descriptions.account_id', $accountId)
            ->where('accounting_vouchers.voucher_type', $accountingVoucherType)
            ->select(
                'accounting_vouchers.id as accounting_voucher_id',
                'accounting_voucher_descriptions.id as voucher_description_id',
                // 'supplier_payments.account_id',
                // 'supplier_payments.date',
                // 'supplier_payments.voucher_no',
                'accounting_voucher_descriptions.amount as received_or_paid_amount',
                // DB::raw('SUM(supplier_payment_invoices.paid_amount) as total_invoice_paid_amount'),
                // DB::raw('SUM(- IFNULL(supplier_payment_invoices.paid_amount, 0)) + supplier_payments.paid_amount as left_amount')
                DB::raw('SUM(- IFNULL(voucher_description_references.amount, 0)) + accounting_voucher_descriptions.amount as left_amount')
            )
            ->having('left_amount', '!=', 0)
            ->groupBy('accounting_vouchers.id')
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

    private function dueSpecificInvoices(int $accountId, string $refIdColName, array $refIds)
    {
        if ($refIdColName == 'purchase_id') {

            return Purchase::where('branch_id', auth()->user()->branch_id)
                ->where('supplier_account_id', $accountId)
                ->whereIn('id', $refIds)
                ->orderBy('report_date', 'asc')
                ->get();
        } elseif ($refIdColName == 'purchase_return_id') {

            // return PurchaseReturn::where('supplier_account_id', $accountId)
            // ->whereIn('id', $refIds)
            // ->orderBy('report_date', 'asc')
            // ->get();
            return;
        } elseif ($refIdColName == 'sale_id') {

            return Sale::where('branch_id', auth()->user()->branch_id)
                ->where('customer_account_id', $accountId)
                ->whereIn('id', $refIds)
                ->orderBy('report_date', 'asc')
                ->get();
        } elseif ($refIdColName == 'sale_return_id') {

            // return SaleReturn::where('customer_account_id', $accountId)
            // ->whereIn('id', $refIds)
            // ->orderBy('report_date', 'asc')
            // ->get();
            return;
        }
    }

    private function dueRandomInvoices(int $accountId, string $refIdColName)
    {
        if ($refIdColName == 'purchase_id') {

            return Purchase::where('branch_id', auth()->user()->branch_id)
                ->where('supplier_account_id', $accountId)
                ->where('due', '>', 0)
                ->orderBy('report_date', 'asc')
                ->get();
        } elseif ($refIdColName == 'purchase_return_id') {

            // return PurchaseReturn::where('supplier_account_id', $accountId)
            // ->whereIn('id', $refIds)
            // ->orderBy('report_date', 'asc')
            // ->get();
            return;
        } elseif ($refIdColName == 'sale_id') {

            return Sale::where('branch_id', auth()->user()->branch_id)
                ->where('customer_account_id', $accountId)
                ->where('due', '>', 0)
                ->orderBy('report_date', 'asc')
                ->get();
        } elseif ($refIdColName == 'sale_return_id') {

            // return SaleReturn::where('customer_account_id', $accountId)
            // ->whereIn('id', $refIds)
            // ->orderBy('report_date', 'asc')
            // ->get();
            return;
        }
    }
}
