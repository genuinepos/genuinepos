<?php

namespace Modules\SAAS\Services;

use App\Models\User;
use Illuminate\Support\Facades\File;
use App\Enums\BillingPanelUserType;
use Yajra\DataTables\Facades\DataTables;
use Modules\SAAS\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function usersTable(): object
    {
        $users = $this->users()->where('user_type', BillingPanelUserType::AuthorizeUser->value);

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

    public function addUser(object $request, ?object $role, object $fileUploader): void
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

        if ($user && isset($role)) {

            $user->assignRole($role);
        }
    }

    public function addSubscriberUser(object $request, string $tenantId): object
    {
        return User::create([
            'name' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'tenant_id' => $tenantId,
            'ip_address' => request()->ip(),
            'user_type' => BillingPanelUserType::Subscriber->value,
        ]);
    }

    public function updateUser(int $id, object $request, ?object $role, object $fileUploader): void
    {
        $updateUser = $this->singleUser(id: $id);

        $updateUser->email = $request->email;
        $updateUser->phone = $request->phone;
        $updateUser->address = $request->address;
        $updateUser->language = $request->language;
        $updateUser->password = isset($request->password) ? bcrypt($request->password) : $updateUser->password;

        if ($request->hasFile('photo')) {

            if (isset($updateUser->photo) && file_exists(public_path('uploads/saas/users/' . $updateUser->photo))) {

                File::delete(public_path('uploads/saas/users/' . $user->photo));
            }

            $updateUser->photo = $fileUploader->upload($request->file('photo'), 'uploads/saas/users/');
        }

        $updateUser->save();

        if ($updateUser && isset($role)) {

            $updateUser->assignRole($role);
        }
    }

    function deleteUser(int $id): array
    {
        $deleteUser = $this->singleUser(id: $id);

        if (!is_null($deleteUser)) {

            if ($deleteUser->id == 1) {

                return ['pass' => false, 'msg' => __('Admin can not be deleted')];
            }

            if (
                isset($deleteUser->photo) &&
                file_exists(public_path('uploads/saas/users/' . $updateUser->photo))
            ) {

                try {

                    File::delete(public_path('uploads/saas/users/' . $user->photo));
                } catch (Exception $e) {
                }
            }

            $deleteUser->delete();
        }

        return ['pass' => true];
    }

    public function singleUser(?int $id, ?array $with = null): ?object
    {
        $query = User::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function users(?array $with = null): object
    {
        $query = User::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
