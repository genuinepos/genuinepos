<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\SupplierImport;
use Maatwebsite\Excel\Facades\Excel;

class SupplierImportController extends Controller
{
    public function create()
    {
        return view('contacts.import_supplier.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'import_file' => 'required|mimes:csv,xlx,xlsx,xls',
        ]);

        Excel::import(new SupplierImport, $request->import_file);
        return redirect()->back();
    }
}
