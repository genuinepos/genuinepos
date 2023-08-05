<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Hrm\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DesignationController extends Controller
{
    public function __construct()
    {

    }

    //show designation page only
    public function index()
    {
        if (! auth()->user()->can('designation')) {

            abort(403, 'Access Forbidden.');
        }

        return view('hrm.designation.index');
    }

    //ajax request for all designation
    public function allDesignation()
    {
        if (! auth()->user()->can('designation')) {

            abort(403, 'Access Forbidden.');
        }

        $designation = Designation::orderBy('id', 'DESC')->get();

        return view('hrm.designation.ajax.designation_list', compact('designation'));
    }

    //designations store
    public function storeDesignation(Request $request)
    {
        if (! auth()->user()->can('designation')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'designation_name' => 'required|unique:hrm_designations',
        ]);

        Designation::insert([
            'designation_name' => $request->designation_name,
            'description' => $request->description,
        ]);

        return response()->json('Successfully Designation Added!');
    }

    //designations update
    public function updateDesignation(Request $request)
    {
        if (! auth()->user()->can('designation')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'designation_name' => 'required',
        ]);
        $updateDesignation = Designation::where('id', $request->id)->first();
        $updateDesignation->update([
            'designation_name' => $request->designation_name,
            'description' => $request->description,
        ]);

        return response()->json('Successfully Designation Updated!');
    }

    //destroy designation
    public function deleteDesignation(Request $request, $designationId)
    {
        if (! auth()->user()->can('designation')) {

            abort(403, 'Access Forbidden.');
        }

        $deleteCategory = Designation::find($designationId);
        $deleteCategory->delete();
        Cache::forget('all-categories');
        Cache::forget('all-main_categories');
        Cache::forget('all-products');

        return response()->json('Successfully Designation Deleted');
    }
}
