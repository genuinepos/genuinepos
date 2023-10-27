<?php

namespace App\Http\Controllers\Products;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\Setups\BranchService;
use App\Services\Products\BrandService;
use App\Services\Accounts\AccountService;
use App\Services\Products\ProductService;
use App\Services\Products\CategoryService;
use App\Services\Products\WarrantyService;
use App\Services\Products\BulkVariantService;
use App\Services\Products\ProductVariantService;
use App\Services\Products\ProductAccessBranchService;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private UnitService $unitService,
        private CategoryService $categoryService,
        private BrandService $brandService,
        private BulkVariantService $bulkVariantService,
        private WarrantyService $warrantyService,
        private ProductVariantService $productVariantService,
        private ProductAccessBranchService $productAccessBranchService,
        private AccountService $accountService,
        private BranchService $branchService,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
    }

    public function index(Request $request, $isForCreatePage = 0)
    {
        if (!auth()->user()->can('product_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->productService->productListTable($request, $isForCreatePage);
        }

        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);
        $brands = $this->brandService->brands()->get(['id', 'name']);
        $units = $this->unitService->units()->get(['id', 'name', 'code_name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

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

        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);
        $brands = $this->brandService->brands()->get(['id', 'name']);
        $units = $this->unitService->units()->get(['id', 'name', 'code_name']);
        $warranties = $this->warrantyService->warranties()->orderBy('id', 'desc')->get(['id', 'name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        $bulkVariants = $this->bulkVariantService->bulkVariants(with: ['bulkVariantChild:id,bulk_variant_id,name'])->get();

        $lastProductSerialCode = $this->productService->getLastProductSerialCode();

        return view('product.products.create', compact('units', 'categories', 'brands', 'warranties', 'taxAccounts', 'branches', 'bulkVariants', 'lastProductSerialCode'));
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
                'unit_id.required' => __('Product unit field is required.'),
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

            if ($request->type == 1 && $request->is_variant == BooleanType::True->value) {

                $this->productVariantService->addProductVariants(request: $request, productId: $addProduct->id);
            }

            $this->productAccessBranchService->addProductAccessBranches(request: $request, productId: $addProduct->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addProduct;
    }

    public function formPart($type)
    {
        $type = $type;
        $taxAccounts = $this->accountService->accounts()->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $bulkVariants = $this->bulkVariantService->bulkVariants(with: ['bulkVariantChild:id,bulk_variant_id,name'])->get();

        return view('product.products.ajax_view.form_part', compact('type', 'taxAccounts', 'bulkVariants'));
    }

    public function getLastProductId() {

        return $this->productService->getLastProductSerialCode();
    }
}
