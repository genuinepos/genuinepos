<?php

namespace App\Services\Sales\Reports;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesReturnedProductReportService
{
    public function salesReturnedProductReportTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $salesReturnProducts = $this->query(request: $request);

        return DataTables::of($salesReturnProducts)
            ->editColumn('product', function ($row) {

                $variant = $row->variant_name ? ' - ' . $row->variant_name : '';

                return Str::limit($row->name, 35, '') . $variant;
            })
            ->editColumn('date', function ($row) {

                return date('d/m/Y', strtotime($row->date));
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->branch_area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('stored_location', function ($row) use ($generalSettings) {

                if ($row->warehouse_name) {

                    return $row->warehouse_name . '(' . $row->warehouse_code . ')';
                } else {

                    if ($row->branch_id) {

                        if ($row->parent_branch_name) {

                            return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                        } else {

                            return $row->branch_name . '(' . $row->branch_area_name . ')';
                        }
                    } else {

                        return $generalSettings['business_or_shop__business_name'];
                    }
                }
            })

            ->editColumn('voucher_no', fn ($row) => '<a href="' . route('sales.returns.show', [$row->sale_return_id]) . '" class="text-hover" id="details_btn" title="View">' . $row->sales_return_voucher . '</a>')

            ->editColumn('sales_invoice_id', function ($row) {

                if ($row->sale_id) {

                    return '<a href="' . route('sales.show', [$row->sale_id]) . '" id="details_btn">' . $row->sale_invoice_id . '</a>';
                }
            })

            ->editColumn('return_qty', fn ($row) => \App\Utils\Converter::format_in_bdt($row->return_qty) . '/<span class="return_qty" data-value="' . $row->return_qty . '">' . $row->unit_code . '</span>')
            ->editColumn('unit_price_exc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt(curr_cnv($row->unit_price_exc_tax, $row->c_rate, $row->branch_id)))
            ->editColumn('unit_discount_amount', fn ($row) => \App\Utils\Converter::format_in_bdt(curr_cnv($row->unit_discount_amount, $row->c_rate, $row->branch_id)))
            ->editColumn('unit_tax_amount', fn ($row) => '(' . \App\Utils\Converter::format_in_bdt($row->unit_tax_percent) . '%)=' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->unit_tax_amount, $row->c_rate, $row->branch_id)))
            ->editColumn('unit_price_inc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt(curr_cnv($row->unit_price_inc_tax, $row->c_rate, $row->branch_id)))
            ->editColumn('return_subtotal', fn ($row) => '<span class="return_subtotal" data-value="' . curr_cnv($row->return_subtotal, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->return_subtotal, $row->c_rate, $row->branch_id)) . '</span>')
            ->rawColumns(['product', 'date', 'branch', 'stored_location', 'return_qty', 'voucher_no', 'sales_invoice_id', 'unit_price_exc_tax', 'unit_discount_amount', 'unit_tax_amount', 'unit_price_inc_tax', 'return_subtotal'])
            ->make(true);
    }

    public function query(object $request): object
    {
        $query = DB::table('sale_return_products')
            ->leftJoin('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
            ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
            ->leftJoin('branches', 'sale_returns.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('currencies', 'branches.currency_id', 'currencies.id')
            ->leftJoin('warehouses', 'sale_returns.warehouse_id', 'warehouses.id')
            ->leftJoin('products', 'sale_return_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_return_products.variant_id', 'product_variants.id')
            ->leftJoin('accounts as customers', 'sale_returns.customer_account_id', 'customers.id')
            ->leftJoin('units', 'sale_return_products.unit_id', 'units.id');

        $this->filter(request: $request, query: $query);

        return $query->select(
            'sale_return_products.sale_return_id',
            'sale_return_products.product_id',
            'sale_return_products.variant_id',
            'sale_return_products.unit_price_exc_tax',
            'sale_return_products.unit_discount_amount',
            'sale_return_products.unit_tax_percent',
            'sale_return_products.unit_tax_amount',
            'sale_return_products.unit_price_inc_tax',
            'sale_return_products.return_qty',
            'sale_return_products.return_subtotal',
            'units.code_name as unit_code',
            'sale_returns.voucher_no as sales_return_voucher',
            'sale_returns.branch_id',
            'sale_returns.warehouse_id',
            'sale_returns.date',
            'sale_returns.date_ts',
            'sales.id as sale_id',
            'sales.invoice_id as sale_invoice_id',
            'products.name',
            'products.product_code',
            'product_variants.variant_code',
            'product_variants.variant_name',
            'customers.name as customer_name',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'currencies.currency_rate as c_rate'
        )->orderBy('sale_returns.date_ts', 'desc');
    }

    private function filter(object $request, object $query): object
    {
        if ($request->product_id) {

            $query->where('sale_return_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('sale_return_products.variant_id', $request->variant_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sale_returns.branch_id', null);
            } else {

                $query->where('sale_returns.branch_id', $request->branch_id);
            }
        }

        if ($request->customer_account_id) {

            $query->where('sale_returns.customer_account_id', $request->customer_account_id);
        }

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('sale_returns.date_ts', $date_range);
        }

        if (auth()->user()->can('view_only_won_transactions')) {

            $query->where('sale_returns.created_by_id', auth()->user()->id);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sale_returns.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
