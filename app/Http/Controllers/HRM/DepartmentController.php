<?php

namespace App\Http\Controllers\HRM;

use App\Enums\UserType;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Hrm\DepartmentService;
use App\Http\Requests\HRM\DepartmentStoreRequest;
use App\Http\Requests\HRM\DepartmentDeleteRequest;
use App\Http\Requests\HRM\DepartmentUpdateRequest;

class DepartmentController extends Controller
{
    public function __construct(private DepartmentService $departmentService, private UserService $userService)
    {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('departments_index') || config('generalSettings')['subscription']->features['hrm'] == BooleanType::False->value, 403);

        if ($request->ajax()) {

            return $this->departmentService->departmentsTable();
        }

        return view('hrm.departments.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('departments_create') || config('generalSettings')['subscription']->features['hrm'] == BooleanType::False->value, 403);

        return view('hrm.departments.ajax_view.create');
    }

    public function store(DepartmentStoreRequest $request)
    {
        return $this->departmentService->addDepartment(request: $request);
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('departments_edit') || config('generalSettings')['subscription']->features['hrm'] == BooleanType::False->value, 403);

        $department = $this->departmentService->singleDepartment(id: $id);

        return view('hrm.departments.ajax_view.edit', compact('department'));
    }

    public function update($id, DepartmentUpdateRequest $request)
    {
        $this->departmentService->updateDepartment(request: $request, id: $id);
        return response()->json(__('Department updated successfully'));
    }

    public function delete($id, DepartmentDeleteRequest $request)
    {
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

        $users = $query->whereIn('user_type', [UserType::Employee->value, UserType::Both->value])->select(['id', 'prefix', 'name', 'last_name', 'emp_id'])->get();
        return $users;
    }
}
