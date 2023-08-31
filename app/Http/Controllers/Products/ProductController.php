<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Products\ProductService;
use App\Services\Products\ProductVariantService;
use App\Services\Products\ProductAccessBranchService;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private ProductVariantService $productVariantService,
        private ProductAccessBranchService $productAccessBranchService,
        private AccountService $accountService,
        private BranchService $branchService,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('product_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->productService->productListTable($request);
        }

        $categories = DB::table('categories')->where('parent_category_id', null)->get(['id', 'name']);
        $brands = DB::table('brands')->get(['id', 'name']);
        $units = DB::table('units')->get(['id', 'name', 'code_name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);

        return view('product.products.index', compact('categories', 'brands', 'units', 'taxAccounts', 'branches'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('product_add')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->productService->createProductListOfProducts();
        }

        $units = DB::table('units')->get(['id', 'name', 'code_name']);
        $categories = DB::table('categories')->where('parent_category_id', null)->orderBy('id', 'desc')->get(['id', 'name']);
        $brands = DB::table('brands')->orderBy('id', 'desc')->get(['id', 'name']);
        $warranties = DB::table('warranties')->orderBy('id', 'desc')->get(['id', 'name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('product.products.create', compact('units', 'categories', 'brands', 'warranties', 'taxAccounts', 'branches'));
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'code' => 'sometimes|unique:products,product_code',
                'unit_id' => 'required',
                'photo' => 'sometimes|image|max:2048',
                'image.*' => 'sometimes|image|max:2048',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
            ]
        );

        if ($request->is_variant == 1) {

            $this->validate(
                $request,
                ['variant_image.*' => 'sometimes|image|max:2048'],
            );
        }

        try {

            DB::beginTransaction();

            $restrictions = $this->productService->restrictions($request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $addProduct = $this->productService->addProduct($request);

            $variantIds = '';
            if ($request->type == 1 && $request->is_variant == 1) {

                $variantIds = $this->productVariantService->addProductVariants($request);
            }

            $this->productAccessBranchService->addProductAccessBranches($request, $addProduct->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addProduct;
    }

    public function formPart($type)
    {
        $type = $type;
        $taxAccounts = $this->accountService->accounts()->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        return view('product.products.ajax_view.form_part', compact('type', 'taxAccounts'));
    }
}
