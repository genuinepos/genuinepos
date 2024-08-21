<?php

namespace Modules\SAAS\Http\Controllers;

use App\Utils\FileUploader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\SAAS\Http\Requests\UserStoreRequest;
use Modules\SAAS\Http\Requests\UserUpdateRequest;
use Modules\SAAS\Interfaces\RoleServiceInterface;
use Modules\SAAS\Interfaces\UserServiceInterface;

class UserController extends Controller
{
    public function __construct(
        private UserServiceInterface $userServiceInterface,
        private RoleServiceInterface $roleServiceInterface,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('users_index'), 403);

        if ($request->ajax()) {

            return $this->userServiceInterface->usersTable();
        }

        return view('saas::users.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('users_create'), 403);

        $roles = $this->roleServiceInterface->roles()->get(['id', 'name']);

        return view('saas::users.create', ['roles' => $roles]);
    }

    public function store(UserStoreRequest $request, FileUploader $fileUploader)
    {
        $role = $this->roleServiceInterface->singleRole(id: $request->role_id);
        $this->userServiceInterface->addUser(request: $request, role: $role, fileUploader: $fileUploader);
        return redirect(route('saas.users.index'))->with('success', 'User created successfully!');
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('users_update'), 403);

        $roles = $this->roleServiceInterface->roles()->get(['id', 'name']);
        $user = $this->userServiceInterface->singleUser(id: $id);

        return view('saas::users.edit', ['user' => $user, 'roles' => $roles]);
    }

    public function update($id, UserUpdateRequest $request, FileUploader $fileUploader)
    {
        $role = $this->roleServiceInterface->singleRole(id: $request->role_id);
        $this->userServiceInterface->updateUser(id: $id, request: $request, role: $role, fileUploader: $fileUploader);

        return redirect()->route('saas.users.index')->with('success', 'User update successfully!');
    }

    public function delete($id)
    {
        abort_if(!auth()->user()->can('users_destroy'), 403);

        $deleteUser = $this->userServiceInterface->deleteUser(id: $id);

        if ($deleteUser['pass'] == false) {

            return redirect()->back()->with('error', $deleteUser['msg']);
        }

        return redirect()->route('saas.users.index')->with('success', 'User Deleted successfully!');
    }
}
