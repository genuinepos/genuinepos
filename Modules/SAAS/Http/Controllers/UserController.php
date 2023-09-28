<?php

namespace Modules\SAAS\Http\Controllers;

use Modules\SAAS\Entities\User;
use App\Utils\FileUploader;
use Illuminate\Http\Request;
use Modules\SAAS\Entities\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Arr;
use Modules\SAAS\Http\Requests\UserStoreRequest;
use Modules\SAAS\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('users_index');

        $users = User::query();
        if ($request->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    if ($row->status == 1) {
                        $html .= '<a href="' . route('saas.users.edit', $row->id) .
                            '" class="px-2 edit-btn btn btn-primary btn-sm text-white" id="editUser" title="Edit"><span class="fas fa-edit pe-1"></span>Edit</a>';
                        $html .= '<a href="' . route('saas.users.trash', $row->id) .
                            '" class="px-2 trash-btn btn btn-danger btn-sm text-white ms-2" id="trashUser" title="Trash"><span class="fas fa-trash pe-1"></span>Trash</a>';
                    } else {
                        $html .= '<a href="' . route('saas.users.restore', $row->id) .
                            '" class="restore-btn btn btn-info btn-sm text-white" id="restoreUser" title="Restore"><span class="fas fa-recycle pe-1"></span>Restore</a>';
                        $html .= '<a href="' . route('saas.users.destroy', $row->id) .
                            '" class="px-2 delete-btn btn btn-warning btn-sm text-black ms-2" id="deleteUser" title="Delete"><span class="fas fa-trash pe-1"></span>Delete</a>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->make(true);
        }
        return view('saas::users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('users_create');
        return view('saas::users.create', [
            'roles' => Role::all(),
        ]);
    }


    public function store(UserStoreRequest $request, FileUploader $fileUploader)
    {
        $this->authorize('users_store');
        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = $fileUploader->upload($request->file('photo'), 'uploads/saas/users/');
        }
        $user = User::create([
            ...$request->except(['role_id', 'photo', 'password']),
            'photo' => $photo,
            'password' => bcrypt($request->password),
        ]);
        $role = Role::find($request->role_id);
        if ($user && $role) {
            $user->assignRole($role);
            return redirect(route('saas.users.index'))->with('success', 'User created successfully!');
        }
    }

    public function show($id)
    {
        $this->authorize('users_show');
        // return view('saas::show');
    }

    public function edit(User $user)
    {
        $this->authorize('users_edit');
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
