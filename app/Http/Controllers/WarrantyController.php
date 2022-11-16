<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;

class WarrantyController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        
    }
    
    // Warranty main page/index page
    public function index()
    {
        if (!auth()->user()->can('warranties')) {

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
        if (!auth()->user()->can('warranties')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required',
            'duration' => 'required',
        ]);

        $addWarranty = Warranty::create([
            'name' => $request->name,
            'type' => $request->type,
            'duration' => $request->duration,
            'duration_type' => $request->duration_type,
            'description' => $request->description,
        ]);

        if ($addWarranty) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 25, data_obj: $addWarranty);
        }

        return response()->json('Warranty is created Successfully');
    }

    // Update warranty
    public function update(Request $request)
    {
        if (!auth()->user()->can('warranties')) {

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

        if ($updateWarranty) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 25, data_obj: $updateWarranty);
        }

        return response()->json('Warranty updated successfully');
    }

    // Delete warranty
    public function delete(Request $request, $warrantyId)
    {
        if (!auth()->user()->can('warranties')) {

            return response()->json('Access Denied');
        }
        
        $deleteWarranty = Warranty::find($warrantyId);

        if (!is_null($deleteWarranty)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 25, data_obj: $deleteWarranty);

            $deleteWarranty->delete();
        }
        return response()->json('Warranty deleted successfully');
    }
}
