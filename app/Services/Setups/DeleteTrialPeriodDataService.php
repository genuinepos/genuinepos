<?php

namespace App\Services\Setups;

use App\Models\Sales\Sale;
use App\Models\Setups\Branch;
use App\Models\Products\Product;
use App\Models\Setups\Warehouse;
use App\Models\Sales\SaleProduct;
use App\Models\Purchases\Purchase;
use App\Models\Sales\CashRegister;
use Illuminate\Support\Facades\DB;
use App\Models\Manufacturing\Process;
use Illuminate\Support\Facades\Schema;
use App\Models\Manufacturing\Production;
use App\Models\Purchases\PurchaseProduct;
use App\Models\Accounts\AccountingVoucher;
use App\Models\Products\StockIssue;
use App\Models\TransferStocks\TransferStock;
use App\Models\StockAdjustments\StockAdjustment;
use App\Models\TransferStocks\TransferStockProduct;

class DeleteTrialPeriodDataService
{
    function cleanDataFromDB(): void
    {
        $sales = Sale::all();
        foreach ($sales as $sale) {

            $sale->delete();
        }

        if (Sale::count() == 0) {
            Schema::disableForeignKeyConstraints();
            Sale::truncate();
            SaleProduct::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $purchases = Purchase::all();
        foreach ($purchases as $purchase) {

            $purchase->delete();
        }

        if (Purchase::count() == 0) {
            Schema::disableForeignKeyConstraints();
            Purchase::truncate();
            PurchaseProduct::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $branches = Branch::all();
        foreach ($branches as $branch) {

            $branch->delete();
        }

        if (Branch::count() == 0) {

            Schema::disableForeignKeyConstraints();
            Branch::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $accountingVouchers = AccountingVoucher::all();
        foreach ($accountingVouchers as $accountingVoucher) {

            $accountingVoucher->delete();
        }

        if (AccountingVoucher::count() == 0) {
            Schema::disableForeignKeyConstraints();
            AccountingVoucher::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $transferStocks = TransferStock::all();
        foreach ($transferStocks as $transferStock) {

            $transferStock->delete();
        }

        if (TransferStock::count() == 0) {
            Schema::disableForeignKeyConstraints();
            TransferStock::truncate();
            TransferStockProduct::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $warehouses = Warehouse::all();
        foreach ($warehouses as $warehouse) {

            $warehouse->delete();
        }

        if (Warehouse::count() == 0) {
            Schema::disableForeignKeyConstraints();
            Warehouse::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $productions = Production::all();
        foreach ($productions as $production) {

            $production->delete();
        }

        if (Production::count() == 0) {
            Schema::disableForeignKeyConstraints();
            Production::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $processes = Process::all();
        foreach ($processes as $process) {

            $process->delete();
        }

        if (Process::count() == 0) {
            Schema::disableForeignKeyConstraints();
            Process::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $cashRegisters = CashRegister::all();
        foreach ($cashRegisters as $cashRegister) {

            $cashRegister->delete();
        }

        if (CashRegister::count() == 0) {
            Schema::disableForeignKeyConstraints();
            CashRegister::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $stockAdjustments = StockAdjustment::all();
        foreach ($stockAdjustments as $stockAdjustment) {

            $stockAdjustment->delete();
        }

        if (StockAdjustment::count() == 0) {
            Schema::disableForeignKeyConstraints();
            StockAdjustment::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $products = Product::all();
        foreach ($products as $product) {

            $product->delete();
        }

        if (Product::count() == 0) {
            Schema::disableForeignKeyConstraints();
            Product::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $stockIssues = StockIssue::all();
        foreach ($stockIssues as $stockIssue) {

            $stockIssue->delete();
        }

        if (StockIssue::count() == 0) {
            Schema::disableForeignKeyConstraints();
            StockIssue::truncate();
            Schema::enableForeignKeyConstraints();
        }
    }
}
