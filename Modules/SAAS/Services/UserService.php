<?php

namespace Modules\SAAS\Services;

use Modules\SAAS\Entities\User;
use App\Enums\BillingPanelUserType;
use Yajra\DataTables\Facades\DataTables;
use Modules\SAAS\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function userTable(): object
    {
        $users = User::query()->where('user_type', BillingPanelUserType::AuthorizeUser->value);

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('users_update')) {

                    $html .= '<a href="' . route('saas.users.edit', $row->id) . '" class="px-2 edit-btn btn btn-primary btn-sm text-white" id="editUser" title="Edit"><span class="fas fa-edit pe-1"></span>Edit</a>';
                }

                if (auth()->user()->can('users_destroy')) {

                    $html .= '<a href="' . route('saas.users.delete', $row->id) . '" class="px-2 trash-btn btn btn-danger btn-sm text-white ms-2" id="trashUser" title="Trash"><span class="fas fa-trash pe-1"></span>Delete</a>';
                }

                $html .= '</div>';

                return $html;
            })->make(true);
    }

    public function addUser(object $request): void
    {
        $photo = null;

        if ($request->hasFile('photo')) {

            $photo = $fileUploader->upload($request->file('photo'), 'uploads/saas/users/');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'language' => $request->language,
            'photo' => $request->photo,
            'password' => bcrypt($request->password),
        ]);

        $role = Role::find($request->role_id);

        if ($user && $role) {

            $user->assignRole($role);
        }
    }
}
