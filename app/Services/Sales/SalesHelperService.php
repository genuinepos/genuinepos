<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Models\Sales\Sale;
use App\Enums\PaymentStatus;
use App\Enums\SaleScreenType;
use App\Enums\ShipmentStatus;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesHelperService
{
    public function salesListTable(object $request, int|string $customerAccountId = null): object
    {
        $generalSettings = config('generalSettings');
        $sales = '';

        $query = DB::table('sales');
        $query->leftJoin('sales as salesOrder', 'sales.sales_order_id', 'salesOrder.id');
        $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id');
        $query->leftJoin('branches', 'sales.branch_id', 'branches.id');
        $query->leftJoin('currencies', 'branches.currency_id', 'currencies.id');

        $query->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');
        $query->leftJoin('users as created_by', 'sales.created_by_id', 'created_by.id');
        $query->where('sales.status', SaleStatus::Final->value);

        $this->filteredQuery(request: $request, query: $query, customerAccountId: $customerAccountId);

        $sales = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.invoice_id',
            'sales.date',
            'sales.total_item',
            'sales.total_qty',
            'sales.total_invoice_amount',
            'sales.sale_return_amount',
            'sales.sale_refund_amount',
            'sales.paid as received_amount',
            'sales.due',
            'sales.is_return_available',
            'sales.shipment_status',
            'sales.sale_screen',
            'salesOrder.id as sales_order_id',
            'salesOrder.order_id',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
            'currencies.currency_rate as c_rate'
        )->orderBy('sales.sale_date_ts', 'desc');

        $dataTables = DataTables::of($sales);

        $dataTables->addColumn('action', function ($row) {

            $html = '<div class="btn-group" role="group">';
            $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
            $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
            $html .= '<a href="' . route('sales.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

            if (auth()->user()->branch_id == $row->branch_id) {

                if ($row->sale_screen == SaleScreenType::AddSale->value) {

                    if (auth()->user()->can('sales_edit')) {

                        $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                    }
                } elseif ($row->sale_screen == SaleScreenType::PosSale->value || $row->sale_screen == SaleScreenType::ServicePosSale->value) {

                    if ($row->sale_screen == SaleScreenType::ServicePosSale->value) {

                        if (auth()->user()->can('service_invoices_edit')) {

                            $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id, $row->sale_screen]) . '">' . __('Edit') . '</a>';
                        }
                    } else {

                        if (auth()->user()->can('sales_edit')) {

                            $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id, $row->sale_screen]) . '">' . __('Edit') . '</a>';
                        }
                    }
                }

                if ($row->sale_screen == SaleScreenType::ServicePosSale->value) {

                    if (auth()->user()->can('service_invoices_delete')) {

                        $html .= '<a href="' . route('services.invoices.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                    }
                } else {

                    if (auth()->user()->can('sales_delete')) {

                        $html .= '<a href="' . route('sales.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                    }
                }
            }

            if (auth()->user()->can('shipment_access')) {

                $html .= '<a class="dropdown-item" id="editShipmentDetails" href="' . route('sale.shipments.edit', [$row->id]) . '">' . __('Edit Shipment Details') . '</a>';
            }

            $html .= '</div>';
            $html .= '</div>';

            return $html;
        });

        $dataTables->editColumn('date', function ($row) use ($generalSettings) {

            $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

            return date($__date_format, strtotime($row->date));
        });

        $dataTables->editColumn('invoice_id', function ($row) {

            $html = '';
            $html .= $row->invoice_id;
            $html .= $row->shipment_status != ShipmentStatus::NoStatus->value && $row->shipment_status != ShipmentStatus::Cancelled->value ? ' <i class="fas fa-shipping-fast text-dark"></i>' : '';
            $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';

            $link = '';
            $link .= '<a href="' . route('sales.show', [$row->id]) . '" id="details_btn" class="d-block" style="line-height:1.5!important;">' . $html . '</a>';

            if ($row->sales_order_id) {

                $link .= '<span class="p-0 m-0 d-block" style="line-height:1.5!important;font-size:11px;">' . __("S/O") . ':<a href="' . route('sale.orders.show', [$row->sales_order_id]) . '" id="details_btn">' . $row->order_id . '</a></span>';
            }

            return $link;
        });

        $dataTables->editColumn('branch', function ($row) use ($generalSettings) {

            if ($row->branch_id) {

                if ($row->parent_branch_name) {

                    return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                } else {

                    return $row->branch_name . '(' . $row->branch_area_name . ')';
                }
            } else {

                return $generalSettings['business_or_shop__business_name'];
            }
        });

        $dataTables->editColumn('customer', fn($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer');

        $dataTables->editColumn('total_item', fn($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>');

        $dataTables->editColumn('total_qty', fn($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>');

        $dataTables->editColumn('total_invoice_amount', fn($row) => '<span class="total_invoice_amount" data-value="' . curr_cnv($row->total_invoice_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->total_invoice_amount, $row->c_rate, $row->branch_id)) . '</span>');

        $dataTables->editColumn('received_amount', fn($row) => '<span class="paid received_amount text-success" data-value="' . curr_cnv($row->received_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->received_amount, $row->c_rate, $row->branch_id)) . '</span>');

        $dataTables->editColumn('sale_return_amount', function ($row) {

            $html = '';
            $html .= '<p class="sale_return_amount p-0 m-0" data-value="' . curr_cnv($row->sale_return_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->sale_return_amount, $row->c_rate, $row->branch_id)) . '</p>';

            if ($row->sale_refund_amount > 0) {
                $html .= '<p class="sale_return_amount p-0 m-0 text-danger" data-value="' . curr_cnv($row->sale_refund_amount, $row->c_rate, $row->branch_id) . '">' . __("R/F") . ':' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->sale_refund_amount, $row->c_rate, $row->branch_id)) . '</p>';
            }

            return $html;
        });

        $dataTables->editColumn('due', function ($row) {

            if ($row->due < 0) {

                return '(<span class="due text-danger" data-value="' . curr_cnv($row->due, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(abs(curr_cnv($row->due, $row->c_rate, $row->branch_id))) . '</span>)';
            } else {

                return '<span class="due text-danger" data-value="' . curr_cnv($row->due, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->due, $row->c_rate, $row->branch_id)) . '</span>';
            }
        });

        $dataTables->editColumn('payment_status', function ($row) {

            $receivable = $row->total_invoice_amount - $row->sale_return_amount;

            if ($row->due <= 0) {

                return '<span class="text-success"><b>' . __('Paid') . '</span>';
            } elseif ($row->due > 0 && $row->due < $receivable) {

                return '<span class="text-primary"><b>' . __('Partial') . '</b></span>';
            } elseif ($receivable == $row->due) {

                return '<span class="text-danger"><b>' . __('Due') . '</b></span>';
            }
        });

        $dataTables->editColumn('created_by', function ($row) {

            return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
        });

        $jobCardRawCols = ['delivery_date', 'job_no', 'status_name'];

        $dataTables->rawColumns(['action', 'date', 'total_item', 'total_qty', 'total_invoice_amount', 'received_amount', 'invoice_id', 'branch', 'customer', 'due', 'sale_return_amount', 'payment_status', 'created_by']);

        return $dataTables->make(true);
    }

    public function getPosSelectableProducts($request): ?object
    {
        $generalSettings = config('generalSettings');

        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        // $products = '';

        $query = DB::table('products')
            ->where('products.is_for_sale', BooleanType::True->value)
            ->where('product_access_branches.branch_id', $ownBranchIdOrParentBranchId)
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id')
            ->leftJoin('accounts as tax', 'products.tax_ac_id', 'tax.id')
            ->leftJoin('units', 'products.unit_id', 'units.id');
        // ->leftJoin('purchase_products as updateProductCost', function ($join) use ($generalSettings) {

        //     $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        //     if ($stockAccountingMethod == 1) {

        //         $ordering = 'asc';
        //     } else {

        //         $ordering = 'desc';
        //     }

        //     return $join->on('products.id', 'updateProductCost.product_id')
        //         ->where('updateProductCost.left_qty', '>', '0')
        //         ->where('updateProductCost.variant_id', null)
        //         ->where('updateProductCost.branch_id', auth()->user()->branch_id)
        //         ->orderBy('updateProductCost.created_at', $ordering)
        //         ->select('updateProductCost.product_id', 'updateProductCost.net_unit_cost')->take(1);
        //     // ->whereRaw('orders.id = (SELECT MAX(id) FROM orders WHERE user_id = users.id)');
        // })->leftJoin('purchase_products as updateVariantCost', function ($join) use ($generalSettings) {

        //     $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        //     if ($stockAccountingMethod == 1) {

        //         $ordering = 'asc';
        //     } else {

        //         $ordering = 'desc';
        //     }

        //     return $join->on('product_variants.id', 'updateVariantCost.variant_id')
        //         ->where('updateVariantCost.left_qty', '>', '0')
        //         ->where('updateVariantCost.branch_id', auth()->user()->branch_id)
        //         ->orderBy('updateVariantCost.created_at', $ordering)
        //         ->select('updateVariantCost.product_id', 'updateVariantCost.net_unit_cost')->take(1);
        //     // ->whereRaw('orders.id = (SELECT MAX(id) FROM orders WHERE user_id = users.id)');
        // });

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        $query->select(
            [
                'products.id as product_id',
                'products.name as product_name',
                'products.product_code',
                'products.status',
                'products.is_variant',
                'products.type',
                'products.tax_type',
                'products.product_cost_with_tax',
                'products.product_price',
                'products.is_manage_stock',
                'products.thumbnail_photo',
                'products.is_combo',
                'products.quantity',
                'products.is_show_emi_on_pos',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_variants.variant_image',
                'units.id as unit_id',
                'units.name as unit_name',
                'tax.id as tax_ac_id',
                'tax.tax_percent',
                // 'updateProductCost.net_unit_cost as update_product_cost',
                // 'updateVariantCost.net_unit_cost as update_variant_cost',
            ]
        )->distinct('product_access_branches.branch_id');

        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        if ($stockAccountingMethod == 1) {

            $ordering = 'asc';
        } else {

            $ordering = 'desc';
        }

        // $products = $query->addSelect([
        //     DB::raw('(SELECT net_unit_cost FROM purchase_products WHERE product_id = products.id AND left_qty > 0 AND variant_id IS NULL AND branch_id ' . (auth()->user()->branch_id ? '=' . auth()->user()->branch_id : ' IS NULL') . ' ORDER BY created_at ' . $ordering . ' LIMIT 1) as update_product_cost'),
        //     DB::raw('(SELECT net_unit_cost FROM purchase_products WHERE variant_id = product_variants.id AND left_qty > 0 AND branch_id ' . (auth()->user()->branch_id ? '=' . auth()->user()->branch_id : ' IS NULL') . ' ORDER BY created_at ' . $ordering . ' LIMIT 1) as update_variant_cost'),
        // ]);

        $main = null;
        if (!$request->category_id && !$request->brand_id) {

            $main = $query->orderBy('products.id', 'desc')->limit(100)->get();
        } else {

            $main = $query->orderBy('products.id', 'desc')->get();
        }

        return $main->each(function ($product) use ($ordering) {

            $product->update_product_cost = DB::table('purchase_products')
                ->where('product_id', $product->product_id)
                ->where('left_qty', '>', 0)
                ->where('variant_id', null)
                ->where('branch_id', auth()->user()->branch_id)
                ->orderBy('created_at', $ordering)
                ->value('net_unit_cost');

            if (isset($product->variant_id)) {

                $product->update_variant_cost = DB::table('purchase_products')
                    ->where('variant_id', $product->variant_id)
                    ->where('left_qty', '>', 0)
                    ->where('branch_id', auth()->user()->branch_id)
                    ->orderBy('created_at', $ordering)
                    ->value('net_unit_cost');
            } else {

                $product->update_variant_cost = 0;
            }
        });
    }

    public function recentSales(int $status, int $saleScreenType, int $limit = null): ?object
    {
        $sales = '';
        $query = DB::table('sales')
            ->leftJoin('accounts as customer', 'sales.customer_account_id', 'customer.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->where('sales.branch_id', auth()->user()->branch_id)
            ->where('sales.created_by_id', auth()->user()->id)
            ->where('sales.status', $status)
            ->where('sales.sale_screen', $saleScreenType);

        if (isset($limit)) {

            $query->limit($limit);
        }

        $sales = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.total_item',
            'sales.total_qty',
            'sales.invoice_id',
            'sales.draft_id',
            'sales.quotation_id',
            'sales.hold_invoice_id',
            'sales.suspend_id',
            'sales.status',
            'sales.sale_screen',
            'sales.total_invoice_amount',
            'sales.date',
            'customer.name as customer_name',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
        )->orderBy('sales.date_ts', 'desc')->get();

        return $sales;
    }

    public function sale(int $saleId): ?object
    {
        return Sale::where('id', $saleId)->with([
            'branch',
            'branch.parentBranch',
            'customer',
            'saleProducts',
            'saleProducts.product',
        ])->first();
    }

    public function productStocks(): array
    {
        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $productBranchStock = DB::table('products')
            // ->leftJoin('product_stocks', 'products.id', 'product_stocks.product_id')

            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id')
            ->where('product_access_branches.branch_id', $ownBranchIdOrParentBranchId)
            ->leftJoin('product_stocks', function ($query) {
                $query->on('products.id', 'product_stocks.product_id')
                    ->where('product_stocks.variant_id', null)
                    ->where('product_stocks.branch_id', auth()->user()->branch_id)
                    ->where('product_stocks.warehouse_id', null);
            })
            ->leftJoin('product_stocks as variant_stocks', function ($query) {
                $query->on('product_variants.id', 'variant_stocks.variant_id')
                    ->where('variant_stocks.branch_id', auth()->user()->branch_id)
                    ->where('variant_stocks.warehouse_id', null);
            })
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                'products.product_code',
                'units.name as unit_name',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                // 'product_stocks.variant_id',
                DB::raw('SUM(CASE WHEN product_stocks.branch_id is null AND product_stocks.warehouse_id is null THEN product_stocks.stock END) as product__stock'),
                DB::raw('SUM(CASE WHEN variant_stocks.branch_id is null AND variant_stocks.warehouse_id is null THEN variant_stocks.stock END) as variant__stock'),
            )

            ->groupBy(
                'products.id',
                'products.name',
                'products.product_code',
                'units.name',
                'product_variants.id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                // 'product_stocks.product_id',
                // 'product_stocks.variant_id',
                'product_stocks.branch_id',
            )
            ->distinct('product_access_branches.branch_id')
            ->orderBy('products.name', 'asc')
            ->get();

        return ['productBranchStock' => $productBranchStock];
    }

    private function filteredQuery(object $request, object $query, int $customerAccountId = null, ?int $saleScreen = null)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', null);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('sales.created_by_id', $request->created_by_id);
        }

        if ($request->customer_account_id) {

            if ($request->customer_id == 'NULL') {

                $query->where('sales.customer_account_id', null);
            } else {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }
        }

        if ($request->payment_status) {

            if ($request->payment_status == PaymentStatus::Paid->value) {

                $query->where('sales.due', '=', 0);
            } elseif ($request->payment_status == PaymentStatus::Partial->value) {

                $query->where('sales.paid', '>', 0)->where('sales.due', '>', 0);
            } elseif ($request->payment_status == PaymentStatus::Due->value) {

                $query->where('sales.paid', '=', 0);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.sale_date_ts', $date_range); // Final
        }

        if (isset($customerAccountId)) {

            $query->where('sales.customer_account_id', $customerAccountId);
        }

        if ($request->sale_screen) {

            $query->where('sales.sale_screen', $request->sale_screen);
        }

        if (auth()->user()->can('view_only_won_transactions')) {

            $query->where('sales.created_by_id', auth()->user()->id);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
