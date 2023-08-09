<?php

namespace App\Http\Controllers;

use App\Imports\CustomerImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CustomerImportController extends Controller
{
    public function create()
    {
        return view('contacts.import_customer.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'import_file' => 'required|mimes:csv,xlx,xlsx,xls',
        ]);

        try {
            DB::beginTransaction();

            Excel::import(new CustomerImport, $request->import_file);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', 'Customers imported successfully');

        return redirect()->back();
    }
}
