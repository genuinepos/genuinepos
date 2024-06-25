<?php

namespace App\Http\Controllers\Products;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\QuickProductStoreRequest;
use App\Http\Requests\Products\QuickProductCreateRequest;
use App\Interfaces\Products\QuickProductAddControllerMethodContainersInterface;

class QuickProductAddController extends Controller
{
    public function create(QuickProductCreateRequest $request, QuickProductAddControllerMethodContainersInterface $quickProductAddControllerMethodContainersInterface)
    {
        $createMethodContainer = $quickProductAddControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('product.products.quick_add_product.create', compact('units', 'categories', 'brands', 'warranties', 'taxAccounts', 'branches', 'lastProductSerialCode', 'warehouses'));
    }

    public function store(QuickProductStoreRequest $request, QuickProductAddControllerMethodContainersInterface $quickProductAddControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $quickProductAddControllerMethodContainersInterface->storeMethodContainer(request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $storeMethodContainer;
    }
}
