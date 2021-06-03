<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use Illuminate\Http\Request;

class WarrantyController extends Controller
{
    // Warranty main page/index page
    public function index()
    {
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
        $deleteWarranty = Warranty::find($warrantyId);
        if (!is_null($deleteWarranty)) {
            $deleteWarranty->delete();
        }
        return response()->json('Warranty deleted successfully');
    }
}
