<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    public function create()
    {
        return view('product.import.create_v2');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'import_file' => 'required',
        ]);

        // dd($request->import_file);
        Excel::import(new ProductImport, $request->import_file);

        return redirect()->back()->with('successMsg', 'Product created Successfully');
    }
}
