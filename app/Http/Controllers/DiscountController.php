<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Models\DiscountProduct;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $discounts = DB::table('discounts')->where('branch_id', auth()->user()->branch_id)->get();

            return DataTables::of($discounts)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="javascript:;" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('product.categories.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
                    return date($__date_format, strtotime($row->date));
                })
                ->editColumn('bank', fn ($row) => $row->b_name ? $row->b_name . ' (' . $row->b_branch . ')' : 'Not Applicable')
                ->editColumn('account_type', fn ($row) => '<b>' . $this->util->accountType($row->account_type) . '</b>')
                ->editColumn('branch', fn ($row) => '<b>' . ($row->branch_name ? $row->branch_name . '/' . $row->branch_code : json_decode($generalSettings->business, true)['shop_name']) . '</b>')
                ->editColumn('opening_balance', fn ($row) => $this->converter->format_in_bdt($row->opening_balance))
                ->editColumn('balance', fn ($row) => $this->converter->format_in_bdt($row->balance))
                ->rawColumns(['action', 'ac_number', 'bank', 'account_type', 'branch', 'opening_balance', 'balance'])
                ->make(true);
        }

        $brands = DB::table('brands')->select('id', 'name')->get();
        $categories = DB::table('categories')->where('parent_category_id', NULL)->select('id', 'name')->get();

        $products = DB::table('product_branches')
            ->where('product_branches.branch_id', auth()->user()->branch_id)
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->select('products.id', 'products.name', 'products.product_code')->get();

        $price_groups = DB::table('price_groups')
            ->select('id', 'name')->get();

        return view('sales.discounts.index', compact('brands', 'categories', 'products', 'price_groups'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'priority' => 'required',
            "start_at"    => "required",
            "end_at"  => "required",
            "discount_type"  => "required",
            "discount_amount"  => "required",
        ]);

        $addDiscount = new Discount();
        $addDiscount->branch_id = auth()->user()->branch_id;
        $addDiscount->name = $request->name;
        $addDiscount->priority = $request->priority;
        $addDiscount->start_at = date('Y-m-d', strtotime($request->start_at));
        $addDiscount->end_at = date('Y-m-d', strtotime($request->end_at));
        $addDiscount->discount_type = $request->discount_type;
        $addDiscount->discount_amount = $request->discount_amount;
        $addDiscount->price_group_id = $request->price_group_id;
        $addDiscount->save();

        if (count($request->product_ids) > 0) {

            foreach ($request->product_ids as $product_id) {

                $addDiscountProduct = new DiscountProduct();
                $addDiscountProduct->discount_id = $addDiscount->id;
                $addDiscountProduct->product_id = $product_id;
                $addDiscountProduct->save();
            }
        } else {

            $addDiscount->brand_id = $request->brand_id;
            $addDiscount->category_id = $request->category_id;
            $addDiscount->save();
        }

        return response()->json('Discount created successfully');
    }
}
