<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomerImport;

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

        Excel::import(new CustomerImport, $request->import_file);
        return redirect()->back();
    }
}
