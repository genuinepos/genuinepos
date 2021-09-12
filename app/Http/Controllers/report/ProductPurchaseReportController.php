<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ProductPurchaseReportController extends Controller
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
            $purchaseProducts = '';
            $query = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
                ->leftJoin('products', 'purchase_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'purchase_products.product_variant_id', 'product_variants.id')
                ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
                ->leftJoin('units', 'products.unit_id', 'units.id');

            if ($request->product_id) {
                $query->where('purchase_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {
                $query->where('purchase_products.product_variant_id', $request->variant_id);
            }

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('purchases.branch_id', NULL);
                } else {
                    $query->where('purchases.branch_id', $request->branch_id);
                }
            }

            if ($request->supplier_id) {
                $query->where('purchases.supplier_id', $request->supplier_id);
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $query->whereBetween('purchases.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 1) {
                $purchaseProducts = $query->select(
                    'purchase_products.purchase_id',
                    'purchase_products.product_id',
                    'purchase_products.product_variant_id',
                    'purchase_products.net_unit_cost',
                    'purchase_products.quantity',
                    'units.code_name as unit_code',
                    'purchase_products.line_total',
                    'purchase_products.selling_price',
                    'purchases.*',
                    'products.name',
                    'products.product_code',
                    'products.product_price',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'product_variants.variant_price',
                    'suppliers.name as supplier_name'
                )->orderBy('purchase_products.id', 'desc');
            } else {
                $purchaseProducts = $query->select(
                    'purchase_products.purchase_id',
                    'purchase_products.product_id',
                    'purchase_products.product_variant_id',
                    'purchase_products.net_unit_cost',
                    'purchase_products.quantity',
                    'units.code_name as unit_code',
                    'purchase_products.line_total',
                    'purchase_products.selling_price',
                    'purchases.*',
                    'products.name',
                    'products.product_code',
                    'products.product_price',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'product_variants.variant_price',
                    'suppliers.name as supplier_name'
                )->where('purchases.branch_id', auth()->user()->branch_id)->orderBy('purchase_products.id', 'desc');
            }

            return DataTables::of($purchaseProducts)
                ->editColumn('product', function ($row) {
                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                    return $row->name . $variant;
                })
                ->editColumn('product_code', function ($row) {
                    return $row->variant_code ? $row->variant_code : $row->product_code;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('quantity', function ($row) {
                    return $row->quantity . ' (<span class="qty" data-value="' . $row->quantity . '">' . $row->unit_code . '</span>)';
                })
                ->editColumn('net_unit_cost',  function ($row) use ($generalSettings) {
                    return '<b><span class="net_unit_cost" data-value="' . $row->net_unit_cost . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->net_unit_cost . '</span></b>';
                })
                ->editColumn('price',  function ($row) use ($generalSettings) {
                    if ($row->selling_price > 0) {
                        return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->selling_price . '</b>';
                    }else {
                        if ($row->variant_name) {
                            return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->variant_price . '</b>';
                        }else {
                            return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->product_price . '</b>';
                        }
                    }
                    return '<b><span class="net_unit_cost" data-value="' . $row->net_unit_cost . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->net_unit_cost . '</span></b>';
                })
                ->editColumn('subtotal', function ($row) use ($generalSettings) {

                    return '<b><span class="subtotal" data-value="' . $row->line_total . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->line_total . '</span></b>';
                })
                ->rawColumns(['product', 'product_code', 'date', 'quantity', 'branch', 'net_unit_cost', 'price', 'subtotal'])
                ->make(true);
        }
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.product_purchase_report.index', compact('branches'));
    }

    public function print(Request $request)
    {
        $purchaseProducts = '';
        $fromDate = '';
        $toDate = '';
        $branch_id = $request->branch_id;
        $query = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->leftJoin('products', 'purchase_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_products.product_variant_id', 'product_variants.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('units', 'products.unit_id', 'units.id');

        if ($request->product_id) {
            $query->where('purchase_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {
            $query->where('purchase_products.product_variant_id', $request->variant_id);
        }

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('purchases.branch_id', NULL);
            } else {
                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_id) {
            $query->where('purchases.supplier_id', $request->supplier_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $fromDate = date('Y-m-d', strtotime($date_range[0]));
            $toDate = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('purchases.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 1) {
            $purchaseProducts = $query->select(
                'purchase_products.purchase_id',
                'purchase_products.product_id',
                'purchase_products.product_variant_id',
                'purchase_products.net_unit_cost',
                'purchase_products.quantity',
                'units.code_name as unit_code',
                'purchase_products.line_total',
                'purchases.*',
                'products.name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'suppliers.name as supplier_name'
            )->orderBy('purchase_products.id', 'desc')->get();
        } else {
            $purchaseProducts = $query->select(
                'purchase_products.purchase_id',
                'purchase_products.product_id',
                'purchase_products.product_variant_id',
                'purchase_products.net_unit_cost',
                'purchase_products.quantity',
                'units.code_name as unit_code',
                'purchase_products.line_total',
                'purchases.*',
                'products.name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'suppliers.name as supplier_name'
            )->where('purchases.branch_id', auth()->user()->branch_id)->orderBy('purchase_products.id', 'desc')->get();
        }

        return view('reports.product_purchase_report.ajax_view.print', compact('purchaseProducts', 'fromDate', 'toDate', 'branch_id'));
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
            )->get();

        if (count($products) > 0) {
            return view('reports.product_purchase_report.ajax_view.search_result', compact('products'));
        } else {
            return response()->json(['noResult' => 'no result']);
        }
    }
}
