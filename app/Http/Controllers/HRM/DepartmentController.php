<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use App\Models\Hrm\Department;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Hrm\DepartmentService;

class DepartmentController extends Controller
{
    public function __construct(
        private DepartmentService $departmentService,
        private UserService $userService,
    ) {
    }

    //department showing page method
    public function index()
    {
        if (!auth()->user()->can('department')) {

            abort(403, 'Access Forbidden.');
        }

        return view('hrm.department.index');
    }

    //department ajax data show method
    public function allDepartment()
    {
        if (!auth()->user()->can('department')) {

            abort(403, 'Access Forbidden.');
        }

        $department = Department::orderBy('id', 'DESC')->get();

        return view('hrm.department.ajax.department_list', compact('department'));
    }

    //store department method
    public function storeDepartment(Request $request)
    {
        if (!auth()->user()->can('department')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'department_id' => 'required|unique:hrm_departments',
        ]);

        Department::insert([
            'name' => $request->department_name,
            'department_id' => $request->department_id,
            'description' => $request->description,
        ]);

        return response()->json('Successfully Department Added!');
    }

    //update departments method
    public function updateDepartments(Request $request)
    {
        if (!auth()->user()->can('department')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'department_name' => 'required',
            'department_id' => 'required',
        ]);

        $updateDepartment = Department::where('id', $request->id)->first();
        $updateDepartment->update([
            'department_name' => $request->department_name,
            'department_id' => $request->department_id,
            'description' => $request->description,
        ]);

        return response()->json('Successfully Department Updated!');
    }

    //destroy single department
    public function deleteDepartment($departmentId)
    {
        if (!auth()->user()->can('department')) {

            abort(403, 'Access Forbidden.');
        }

        $deleteDepartment = Department::find($departmentId);
        $deleteDepartment->delete();

        return response()->json('Successfully Department Deleted');
    }

    private function users($id)
    {
        $users = '';
        $query = $this->userService->user()->where('branch_id', auth()->user()->branch_id);
        if ($id != 'all') {

            $query->where('department_id', $request->department_id);
        }

        $users = $query->select(['id', 'prefix', 'name', 'last_name', 'emp_id'])->get();
        return $users;
    }
}
