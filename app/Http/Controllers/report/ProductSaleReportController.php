<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ProductSaleReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of supplier report
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $saleProducts = '';
            $query = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
                ->leftJoin('products', 'sale_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                ->leftJoin('units', 'products.unit_id', 'units.id');
            // ->where('purchases.branch_id', $request->branch_id)
            // ->where('purchases.supplier_id', $request->supplier_id)
            // ->where('purchases.warehouse_id', $request->warehouse_id)
            // ->select('purchases.*', 'products.name', 'product_variants.variant_name', 'suppliers.name as sup_name')
            // ->get();

            // if ($request->product_id) {
            //     $query->where('product_id', $request->product_id);
            // }

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

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                //$form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                $query->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            } else {
                $query->where('sales.year', date('Y'));
            }

            $saleProducts = $query
                ->select(
                    'sale_products.sale_id',
                    'sale_products.product_id',
                    'sale_products.product_variant_id',
                    'sale_products.unit_price_inc_tax',
                    'sale_products.quantity',
                    'units.code_name as unit_code',
                    'sale_products.subtotal',
                    'sales.*',
                    'products.name',
                    'products.product_code',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'customers.name as customer_name'
                )
                ->get();
            
                return DataTables::of($saleProducts)
                ->editColumn('product', function ($row) {
                    $variant = $row->variant_name ?' - '.$row->variant_name : '';
                    return $row->name .$variant;
                })
                ->editColumn('sku', function ($row) {
                    return $row->variant_code ? $row->variant_code : $row->product_code;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('customer', function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('qty', function ($row) {
                    return $row->quantity.' (<span class="qty" data-value="'.$row->quantity.'">'.$row->unit_code.'</span>)';
                })
                ->editColumn('unit_price_inc_tax',  function ($row) use ($generalSettings) {
                    return '<b><span class="unit_price_inc_tax" data-value="'.$row->unit_price_inc_tax.'">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->unit_price_inc_tax . '</span></b>';
                })
                ->editColumn('subtotal', function ($row) use ($generalSettings) {
                    return '<b><span class="subtotal" data-value="'.$row->subtotal.'">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->subtotal . '</span></b>';
                })
                ->rawColumns(['product', 'sku', 'date', 'qty', 'branch', 'unit_price_inc_tax', 'subtotal'])
                ->make(true);
        }
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.product_sale_report.index', compact('branches'));
    }

    // Search product 
    public function searchProduct($product_name)
    {
        $products = DB::table('products')
            ->where('name', 'like', "%{$product_name}%")
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->select(
                'products.id as product_id',
                'products.name',
                'products.product_code',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
            )
            ->get();

        if (count($products) > 0) {
            return view('reports.product_sale_report.ajax_view.search_result', compact('products'));
        } else {
            return response()->json(['noResult' => 'no result']);
        }
    }
}
