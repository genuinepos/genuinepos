<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        return view('settings.taxes.index');
    }

    public function getAllVat()
    {
        $taxes = Tax::all();
        return view('settings.taxes.ajax_view.tax_list', compact('taxes'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tax_name' => 'required',
            'tax_percent' => 'required'
        ]);

        $addTax = new Tax();
        $addTax->tax_name = $request->tax_name;
        $addTax->tax_percent = $request->tax_percent;
        $addTax->save();
        Cache::forget('all-taxes');
        return response()->json('Successfully Tax is added');
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'tax_name' => 'required',
            'tax_percent' => 'required'
        ]);

        $updateTax = Tax::where('id', $request->id)->first();
        $updateTax->tax_name = $request->tax_name;
        $updateTax->tax_percent = $request->tax_percent;
        $updateTax->save();
        Cache::forget('all-taxes');
        return response()->json('Successfully Tax is updated');
    }

    public function delete(Request $request, $taxId)
    {
        $deleteVat = Tax::where('id', $taxId)->first();
        if (!is_null($deleteVat)) {
            $deleteVat->delete();
        }
        Cache::forget('all-taxes');
        return response()->json('Successfully Tax is deleted'); 
    }
}
