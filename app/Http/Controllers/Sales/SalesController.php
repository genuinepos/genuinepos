<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Utils\NameSearchUtil;
use App\Utils\SaleUtil;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function __construct(
        private NameSearchUtil $nameSearchUtil,
        private SaleUtil $saleUtil,
    ) {
    }

    public function create()
    {
        if (! auth()->user()->can('create_add_sale')) {

            abort(403, 'Access Forbidden.');
        }

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();
        $invoiceSchemas = DB::table('invoice_schemas')->get(['format', 'prefix', 'start_from']);

        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', $branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.warehouse_code as code',
            )->get();

        $priceGroups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

        return view('sales.create', compact(
            'customers',
            'methods',
            'accounts',
            'saleAccounts',
            'price_groups',
            'invoice_schemas',
            'warehouses'
        ));
    }
}
