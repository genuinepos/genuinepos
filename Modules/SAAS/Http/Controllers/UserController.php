<?php

namespace Modules\SAAS\Http\Controllers;

use App\Models\User;
use App\Utils\FileUploader;
use Illuminate\Http\Request;
use Modules\SAAS\Entities\Role;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Modules\SAAS\Http\Requests\UserStoreRequest;
use Modules\SAAS\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query();
        if ($request->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('saas.users.edit', $row->id) .
                        '" class="px-2 edit-btn btn btn-primary btn-sm text-white" id="editUser" title="Edit"><span class="fas fa-edit pe-1"></span>Edit</a>';
                    if ($row->status == 1) {
                        $html .= '<a href="' . route('saas.users.destroy', $row->id) .
                            '" class="px-2 delete-btn btn btn-danger btn-sm text-white ms-2" id="deleteUser" title="Delete"><span class="fas fa-trash pe-1"></span>Delete</a>';
                    } else {
                        $html .= '<a href="' . route('saas.users.restore', $row->id) .
                            '" class="px-2 restore-btn btn btn-info btn-sm text-white ms-2" id="restoreUser" title="Restore"><span class="fas fa-recycle pe-1"></span>Restore</a>';
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
        return view('saas::users.create', [
            'roles' => Role::all(),
        ]);
    }


    public function store(UserStoreRequest $request, FileUploader $fileUploader)
    {
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
        // return view('saas::show');
    }

    public function edit(User $user)
    {
        return view('saas::users.edit', [
            'user' => $user,
            'roles' => Role::all(),
        ]);
    }


    public function update(UserUpdateRequest $request, User $user, FileUploader $fileUploader)
    {
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

    public function destroy(User $user)
    {
        $user->update(['status' => false]);
        return redirect()->route('saas.users.index')->with('success', 'User deactivated successfully!');
    }
    public function restore(User $user)
    {
        $user->update(['status' => true]);
        return redirect()->route('saas.users.index')->with('success', 'User activated successfully!');
    }
}
