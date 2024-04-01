<?php

namespace Modules\SAAS\Http\Controllers;

use App\Models\User;
use App\Utils\FileUploader;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Modules\SAAS\Entities\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Modules\SAAS\Http\Requests\UserStoreRequest;
use Modules\SAAS\Http\Requests\UserUpdateRequest;
use Modules\SAAS\Interfaces\UserServiceInterface;

class UserController extends Controller
{
    public function __construct(
        private UserServiceInterface $userServiceInterface,
    ) {
    }

    public function index(Request $request)
    {
        $this->authorize('users_index');

        if ($request->ajax()) {

            return $this->userServiceInterface->usersTable();
        }

        return view('saas::users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('users_create');

        return view('saas::users.create', ['roles' => Role::all()]);
    }

    public function store(UserStoreRequest $request, FileUploader $fileUploader)
    {
        $this->userServiceInterface->addUser(request: $request);

        return redirect(route('saas.users.index'))->with('success', 'User created successfully!');
    }

    public function show($id)
    {
        $this->authorize('users_show');
        // return view('saas::show');
    }

    public function edit(User $user)
    {
        $this->authorize('users_update');

        return view('saas::users.edit', [
            'user' => $user,
            'roles' => Role::all(),
        ]);
    }

    public function update(UserUpdateRequest $request, User $user, FileUploader $fileUploader)
    {
        $this->authorize('users_update');
        $userUpdateAttributes = $request->validated();

        if ($request->hasFile('photo')) {

            if (isset($user->photo)) {

                File::delete(public_path('uploads/saas/users/' . $user->photo));
            }

            $userUpdateAttributes['photo'] = $fileUploader->upload($request->file('photo'), 'uploads/saas/users/');
        } else {

            Arr::forget($userUpdateAttributes, 'photo');
        }

        if (isset($userUpdateAttributes['password']) && !empty($userUpdateAttributes['password'])) {

            $userUpdateAttributes['password'] = bcrypt($userUpdateAttributes['password']);
        } else {

            Arr::forget($userUpdateAttributes, 'password');
        }

        $role = Role::find($userUpdateAttributes['role_id']);
        Arr::forget($userUpdateAttributes, 'role_id');

        $user->update($userUpdateAttributes);

        if ($user && $role) {

            $user->syncRoles($role);

            return redirect(route('saas.users.index'))->with('success', 'User updated successfully!');
        }

        return back()->with('success', 'User update failed!');
    }

    public function trash(User $user)
    {
        $this->authorize('users_trash');
        $user->update(['status' => false]);

        return redirect()->route('saas.users.index')->with('success', 'User Deactivated!');
    }

    public function restore(User $user)
    {
        $this->authorize('users_restore');
        $user->update(['status' => true]);

        return redirect()->route('saas.users.index')->with('success', 'User Successfully Activated!');
    }

    public function destroy(User $user)
    {
        $this->authorize('users_destroy');
        $user->delete();

        return redirect()->route('saas.users.index')->with('success', 'User Deleted Permanently!');
    }
}
