<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleReturn;
use App\Models\SaleProduct;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\SaleReturnProduct;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Utils\CustomerUtil;
use App\Utils\ProductStockUtil;
use App\Utils\SaleUtil;
use Yajra\DataTables\Facades\DataTables;

class SaleReturnController extends Controller
{
    protected $productStockUtil;
    protected $saleUtil;
    protected $customerUtil;
    public function __construct(ProductStockUtil $productStockUtil, SaleUtil $saleUtil, CustomerUtil $customerUtil)
    {
        $this->productStockUtil = $productStockUtil;
        $this->saleUtil = $saleUtil;
        $this->customerUtil = $customerUtil;
        $this->middleware('auth:admin_and_user');
    }

    // create Sale return view
    public function create($saleId)
    {
        if (auth()->user()->permission->sale['return_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        $saleId = $saleId;
        return view('sales.sale_return.create', compact('saleId'));
    }

    // Sale return index view
    public function index(Request $request)
    {
        if (auth()->user()->permission->sale['return_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $returns = '';
            $generalSettings = DB::table('general_settings')->first();
            $query = DB::table('sale_returns')
                ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
                ->leftJoin('branches', 'sale_returns.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'sale_returns.warehouse_id', 'warehouses.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id');

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $returns = $query->select(
                    'sale_returns.*',
                    'sales.invoice_id as parent_invoice_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'customers.name as cus_name',
                )->orderBy('id', 'desc');
            } else {
                $returns = $query->select(
                    'sale_returns.*',
                    'sales.invoice_id as parent_invoice_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'customers.name as cus_name',
                )->where('sale_returns.branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'desc');
            }

            return DataTables::of($returns)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.returns.show', $row->id) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';

                    if (auth()->user()->branch_id == $row->branch_id) {
                        $html .= '<a class="dropdown-item" href="' . route('sales.returns.create', $row->sale_id) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.returns.delete', $row->id) . '"><i class="far fa-trash-alt mr-1 text-primary"></i> Delete</a>';
                        $html .= '<a class="dropdown-item" id="view_payment" href="#"><i class="far fa-money-bill-alt mr-1 text-primary"></i> View Payment</a>';
                        if ($row->total_return_due > 0) {
                            $html .= '<a class="dropdown-item" id="add_purchase_supplier_return_payment" href="#"><i class="far fa-money-bill-alt mr-1 text-primary"></i> Add Payment</a>';
                        }
                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    return $row->branch_name != null ? ($row->branch_name . '/' . $row->branch_code) . '<b>(BL)</b>' : json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                })
                ->editColumn('total_return_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_return_amount . '</b>';
                })
                ->editColumn('total_return_due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-danger">' . json_decode($generalSettings->business, true)['currency'] . ($row->total_return_due >= 0 ? $row->total_return_due :   0.00) . '</span></b>';
                })
                ->editColumn('payment_status', function ($row) {
                    $html = '';
                    if ($row->total_return_due >= 0) {
                        $html .= '<span class="text-success"><b>Paid</b></span>';
                    } else {
                        $html .= '<<span class="text-danger"><b>Due</b></span>';
                    }
                    return $html;
                })
                ->editColumn('customer', function ($row) {
                    return $row->cus_name ? $row->cus_name : 'Walk-In-Customer';
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('sales.returns.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row text-start')
                ->rawColumns(['action', 'date', 'from', 'total_return_amount', 'total_return_due', 'payment_status'])
                ->make(true);
        }

        return view('sales.sale_return.index');
    }

    // Show Sale return details
    public function show($returnId)
    {
        $saleReturn = SaleReturn::with([
            'sale',
            'sale.customer',
            'warehouse',
            'branch',
            'sale_return_products',
            'sale_return_products.sale_product',
            'sale_return_products.sale_product.product',
            'sale_return_products.sale_product.variant',
        ])->where('id', $returnId)->first();

        return view('sales.sale_return.ajax_view.show', compact('saleReturn'));
    }

    // Get sale requested by ajax
    public function getSale($saleId)
    {
        $sale = Sale::with(['branch', 'customer', 'sale_products', 'sale_products.product', 'sale_products.variant', 'sale_return', 'sale_return.sale_return_products', 'sale_return.sale_return_products.sale_product', 'sale_return.sale_return_products.sale_product.product', 'sale_return.sale_return_products.sale_product.variant'])->where('id', $saleId)->first();
        return response()->json($sale);
    }

    public function store(Request $request, $saleId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['sale_return'];

        $sale_product_ids = $request->sale_product_ids;
        $return_quantities = $request->return_quantities;
        $return_subtotals = $request->return_subtotals;
        $units = $request->units;

        $qty = 0;
        foreach ($return_quantities as $return_quantity) {
            if ($return_quantity > 0) {
                $qty += 1;
            }
        }

        if ($qty == 0) {
            return response()->json(['errorMsg' => "All product`s quantity is 0."]);
        }

        // generate invoice ID
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $saleReturn = SaleReturn::where('sale_id', $saleId)->first();
        $sale = Sale::where('id', $saleId)->first();
        if ($saleReturn) {
            //Update purchase and supplier purchase return due
            $saleDue = $sale->total_payable_amount - ($sale->paid);
            $saleReturnDue = $request->total_return_amount - $saleDue;
            //Adjust Quantity 
            foreach ($saleReturn->sale_return_products as $sale_return_product) {
                // Addition sale product for adjustment
                $saleProduct = SaleProduct::where('id', $sale_return_product->sale_product_id)->first();
                if ($sale->branch_id) {
                    // Addition product branch qty for adjustment
                    $productBranch = ProductBranch::where('branch_id', $sale->branch_id)->where('product_id', $saleProduct->product_id)->first();
                    $productBranch->product_quantity -= $sale_return_product->return_qty;
                    $productBranch->save();

                    // Addition product branch variant qty for adjustment
                    if ($saleProduct->product_variant_id) {
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $saleProduct->product_id)->where('product_variant_id', $saleProduct->product_variant_id)->first();
                        $productBranchVariant->variant_quantity -= $sale_return_product->return_qty;
                        $productBranchVariant->save();
                    }
                } else {
                    $mb_product = Product::where('id', $saleProduct->product_id)->first();
                    $mb_product->mb_stock -= $sale_return_product->return_qty;
                    $mb_product->save();

                    if ($saleProduct->product_variant_id) {
                        $mb_variant = ProductVariant::where('id', $saleProduct->product_variant_id)->first();
                        $mb_variant->mb_stock -= $sale_return_product->return_qty;
                        $mb_variant->save();
                    }
                }
            }

            // Update Sale return
            $saleReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'SRI') . date('ymd') . $invoiceId;
            $saleReturn->return_discount_type = $request->return_discount_type;
            $saleReturn->return_discount = $request->return_discount;
            $saleReturn->return_discount_amount = $request->total_return_discount_amount;
            $saleReturn->net_total_amount = $request->net_total_amount;
            $saleReturn->total_return_amount = $request->total_return_amount;
            if ($saleReturnDue > 0) {
                $saleReturn->total_return_due = $saleReturnDue - $saleReturn->total_return_due_pay;
            }

            $saleReturn->date = $request->date;
            $saleReturn->report_date = date('Y-m-d', strtotime($request->date));
            $saleReturn->save();

            // update sale return products
            $index = 0;
            foreach ($sale_product_ids as $sale_product_id) {
                if ($sale->branch_id) {
                    // Update product branch quantity for adjustment
                    $productBranch = ProductBranch::where('branch_id', $sale->branch_id)->where('product_id', $saleProduct->product_id)->first();
                    $productBranch->product_quantity += $return_quantities[$index];
                    $productBranch->save();

                    if ($saleProduct->product_variant_id) {
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $saleProduct->product_id)->where('product_variant_id', $saleProduct->product_variant_id)->first();
                        $productBranchVariant->variant_quantity += $return_quantities[$index];
                        $productBranchVariant->save();
                    }
                } else {
                    $mb_product = Product::where('id', $saleProduct->product_id)->first();
                    $mb_product->mb_stock += $return_quantities[$index];
                    $mb_product->save();

                    if ($saleProduct->product_variant_id) {
                        $mb_variant = ProductVariant::where('id', $saleProduct->product_variant_id)->first();
                        $mb_variant->mb_stock += $return_quantities[$index];
                        $mb_variant->save();
                    }
                }

                $returnProduct = SaleReturnProduct::where('sale_return_id', $saleReturn->id)
                    ->where('sale_product_id', $sale_product_id)->first();

                $returnProduct->return_qty = $return_quantities[$index];
                $returnProduct->unit = $units[$index];
                $returnProduct->return_subtotal = $return_subtotals[$index];
                $returnProduct->save();
                $index++;
            }

            foreach ($sale->sale_products as $sale_product) {
                $this->productStockUtil->adjustMainProductAndVariantStock($sale_product->product_id, $sale_product->product_variant_id);
            }

            $this->saleUtil->adjustSaleInvoiceAmounts($sale);
            if ($sale->customer_id) {
                $this->customerUtil->adjustCustomerAmountForSalePaymentDue($sale->customer_id);
            }
            
        } else {
            //Update sale and customer return due
            $saleDue = $sale->total_payable_amount - $sale->paid;
            $saleReturnDue = $request->total_return_amount - $saleDue;
           
            $addSaleReturn = new SaleReturn();
            $addSaleReturn->sale_id = $sale->id;
            $addSaleReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'SRI') . date('ymd') . $invoiceId;

            $addSaleReturn->branch_id = $sale->branch_id;
            $addSaleReturn->admin_id = auth()->user()->id;
            $addSaleReturn->return_discount_type = $request->return_discount_type;
            $addSaleReturn->return_discount = $request->return_discount;
            $addSaleReturn->return_discount_amount = $request->total_return_discount_amount;
            $addSaleReturn->net_total_amount = $request->net_total_amount;
            $addSaleReturn->total_return_amount = $request->total_return_amount;
            if ($saleReturnDue > 0) {
                $addSaleReturn->total_return_due = $saleReturnDue;
            }

            $addSaleReturn->date = $request->date;
            $addSaleReturn->report_date = date('Y-m-d', strtotime($request->date));
            $addSaleReturn->month = date('F');
            $addSaleReturn->year = date('Y');
            $addSaleReturn->save();

            // Add sale return products
            $index = 0;
            foreach ($sale_product_ids as $sale_product_id) {
                // Update sale product quantity for adjustment
                $saleProduct = SaleProduct::where('id', $sale_product_id)->first();
                if ($sale->branch_id) {
                    // Update product branch quantity for adjustment
                    $productBranch = ProductBranch::where('branch_id', $sale->branch_id)
                        ->where('product_id', $saleProduct->product_id)
                        ->first();
                    $productBranch->product_quantity += $return_quantities[$index];
                    $productBranch->save();

                    if ($saleProduct->product_variant_id) {
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                            ->where('product_id', $saleProduct->product_id)
                            ->where('product_variant_id', $saleProduct->product_variant_id)
                            ->first();
                        $productBranchVariant->variant_quantity += $return_quantities[$index];
                        $productBranchVariant->save();
                    }
                } else {
                    $mb_product = Product::where('id', $saleProduct->product_id)->first();
                    $mb_product->mb_stock += $return_quantities[$index];
                    $mb_product->save();

                    if ($saleProduct->product_variant_id) {
                        $mb_variant = ProductVariant::where('id', $saleProduct->product_variant_id)->first();
                        $mb_variant->mb_stock += $return_quantities[$index];
                        $mb_variant->save();
                    }
                }

                $addReturnProduct = new SaleReturnProduct();
                $addReturnProduct->sale_return_id = $addSaleReturn->id;
                $addReturnProduct->sale_product_id = $sale_product_id;
                $addReturnProduct->product_id = $saleProduct->product_id;
                $addReturnProduct->product_variant_id = $saleProduct->product_variant_id;
                $addReturnProduct->return_qty = $return_quantities[$index];
                $addReturnProduct->unit = $units[$index];
                $addReturnProduct->return_subtotal = $return_subtotals[$index];
                $addReturnProduct->save();
                $index++;
            }

            foreach ($sale->sale_products as $sale_product) {
                $this->productStockUtil->adjustMainProductAndVariantStock($sale_product->product_id, $sale_product->product_variant_id);
            }

            $this->saleUtil->adjustSaleInvoiceAmounts($sale);
            if ($sale->customer_id) {
                $this->customerUtil->adjustCustomerAmountForSalePaymentDue($sale->customer_id);
            }
        }

        $saleReturn = SaleReturn::with(['sale', 'branch', 'sale.customer', 'sale_return_products', 'sale_return_products.sale_product'])->where('sale_id', $saleId)->first();
        if ($saleReturn) {
            return view('sales.sale_return.save_and_print_template.sale_return_print_view', compact('saleReturn'));
        }
    }

    //Deleted sale return 
    public function delete($saleReturnId)
    {
        $saleReturn = SaleReturn::with(['sale', 'sale.customer', 'sale_return_products'])->where('id', $saleReturnId)->first();
        $storedReturnedProducts = $saleReturn->sale_return_products;
        if ($saleReturn->total_return_due_pay > 0) {
            return response()->json(['errorMsg' => "You can not delete this, casuse your have paid some or full amount on this return."]);
        }
        $saleReturn->sale->is_return_available = 0;

        foreach ($saleReturn->sale_return_products as $sale_return_product) {
            // Get sale product
            $saleProduct = SaleProduct::where('id', $sale_return_product->sale_product_id)->first();
            if ($saleReturn->branch_id) {
                // Addition product branch qty for adjustment
                $productBranch = ProductBranch::where('branch_id', $saleReturn->branch_id)->where('product_id', $saleProduct->product_id)->first();
                $productBranch->product_quantity -= $sale_return_product->return_qty;
                $productBranch->save();

                // Addition product branch variant qty for adjustment
                if ($saleProduct->product_variant_id) {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $saleProduct->product_id)->where('product_variant_id', $saleProduct->product_variant_id)->first();
                    $productBranchVariant->variant_quantity -= $sale_return_product->return_qty;
                    $productBranchVariant->save();
                }
            } else {
                $mb_product = Product::where('id', $saleProduct->product_id)->first();
                $mb_product->mb_stock -= $sale_return_product->return_qty;
                $mb_product->save();

                if ($saleProduct->product_variant_id) {
                    $mb_variant = ProductVariant::where('id', $saleProduct->product_variant_id)->first();
                    $mb_variant->mb_stock -= $sale_return_product->return_qty;
                    $mb_variant->save();
                }
            }
        }

        $saleReturn->delete();

        foreach ($storedReturnedProducts as $return_product) {
            $this->productStockUtil->adjustMainProductAndVariantStock($return_product->product_id, $return_product->product_variant_id);
        }

        $this->saleUtil->adjustSaleInvoiceAmounts($saleReturn->sale);
        if ($saleReturn->sale->customer_id) {
            $this->customerUtil->adjustCustomerAmountForSalePaymentDue($saleReturn->sale->customer_id);
        }
        
        return response()->json('Sale return deleted successfully');
    }
}
