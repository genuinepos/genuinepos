<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Services\Contacts\ManageCustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageCustomerController extends Controller
{
    public function __construct(
        private ManageCustomerService $manageCustomerService,
    ) {
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            return $this->manageCustomerService->customerListTable($request);
        }

        $branches = '';
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        }

        return view('contacts.manage_customers.index', compact('branches'));
    }
}
