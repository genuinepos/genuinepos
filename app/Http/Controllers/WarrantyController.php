<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use Illuminate\Http\Request;

class WarrantyController extends Controller
{
    // Warranty main page/index page
    public function index()
    {
        if (auth()->user()->permission->product['warranties'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        return view('product.warranties.index');
    }

    // Get all warranty by ajax
    public function allWarranty()
    {
        $warranties = Warranty::orderBy('id', 'DESC')->get();
        return view('product.warranties.ajax_view.warranty_list', compact('warranties'));
    }

    // Store warranty
    public function store(Request $request)
    {
        if (auth()->user()->permission->product['warranties'] == '0') {
            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required',
            'duration' => 'required',
        ]);

        Warranty::insert([
            'name' => $request->name,
            'type' => $request->type,
            'duration' => $request->duration,
            'duration_type' => $request->duration_type,
            'description' => $request->description,
        ]);

        return response()->json('Warranty is created Successfully');
    }

    // Update warranty
    public function update(Request $request)
    {
        if (auth()->user()->permission->product['warranties'] == '0') {
            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required',
            'duration' => 'required',
        ]);

        $updateWarranty = Warranty::where('id', $request->id)->first();
        $updateWarranty->update([
            'name' => $request->name,
            'type' => $request->type,
            'duration' => $request->duration,
            'duration_type' => $request->duration_type,
            'description' => $request->description,
        ]);
        return response()->json('Warranty updated successfully');
    }

    // Delete warranty
    public function delete(Request $request, $warrantyId)
    {
        if (auth()->user()->permission->product['warranties'] == '0') {
            return response()->json('Access Denied');
        }
        
        $deleteWarranty = Warranty::find($warrantyId);
        if (!is_null($deleteWarranty)) {
            $deleteWarranty->delete();
        }
        return response()->json('Warranty deleted successfully');
    }
}
