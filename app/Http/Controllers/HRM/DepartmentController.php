<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Hrm\DepartmentService;

class DepartmentController extends Controller
{
    public function __construct(
        private DepartmentService $departmentService,
        private UserService $userService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('departments_index') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        if ($request->ajax()) {

            return $this->departmentService->departmentsTable();
        }

        return view('hrm.departments.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('departments_create') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        return view('hrm.departments.ajax_view.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('departments_create') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $this->departmentService->storeValidation(request: $request);
        return $this->departmentService->addDepartment(request: $request);
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('departments_edit') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $department = $this->departmentService->singleDepartment(id: $id);

        return view('hrm.departments.ajax_view.edit', compact('department'));
    }

    public function update($id, Request $request)
    {
        abort_if(!auth()->user()->can('departments_edit') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $this->departmentService->updateValidation(request: $request, id: $id);
        $this->departmentService->updateDepartment(request: $request, id: $id);

        return response()->json(__('Department updated successfully'));
    }

    public function delete($id)
    {
        abort_if(!auth()->user()->can('departments_delete') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $this->departmentService->deleteDepartment(id: $id);

        return response()->json(__('Department deleted successfully'));
    }

    public function users($id)
    {
        $users = '';
        $query = $this->userService->users()->where('branch_id', auth()->user()->branch_id);
        if ($id != 'all') {

            $query->where('department_id', $id);
        }

        $users = $query->select(['id', 'prefix', 'name', 'last_name', 'emp_id'])->get();
        return $users;
    }
}
