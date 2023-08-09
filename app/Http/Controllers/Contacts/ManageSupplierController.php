<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Services\Contacts\ManageSupplierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageSupplierController extends Controller
{
    public function __construct(
        private ManageSupplierService $manageSupplierService,
    ) {
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            return $this->manageSupplierService->supplierListTable($request);
        }

        $branches = '';
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        }

        return view('contacts.manage_suppliers.index', compact('branches'));
    }
}
