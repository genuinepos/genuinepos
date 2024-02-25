<?php

namespace Modules\SAAS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SAAS\Entities\Coupon;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    public function index(Request $request)
    {
       // $this->authorize('users_index');

        $coupons = Coupon::query();

        if ($request->ajax()) {
            return DataTables::of($coupons)
                ->addIndexColumn()

                ->addColumn('is_minimum_purchase', function ($row) {
                    if($row->is_minimum_purchase==1){
                        return "Yes";
                     }else{
                         return "No";
                     }
                })
                ->addColumn('is_maximum_usage', function ($row) {
                    if($row->is_maximum_usage==1){
                        return "Yes";
                     }else{
                         return "No";
                     }
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';

                        $html .= '<a href="' . route('saas.users.edit', $row->id) . '" class="px-2 edit-btn btn btn-primary btn-sm text-white" id="editUser" title="Edit"><span class="fas fa-edit pe-1"></span>Edit</a>';
                        $html .= '<a href="' . route('saas.users.trash', $row->id) . '" class="px-2 trash-btn btn btn-danger btn-sm text-white ms-2" id="trashUser" title="Trash"><span class="fas fa-trash pe-1"></span>Trash</a>';
                    $html .= '</div>';

                    return $html;
                })
                ->make(true);
        }

        return view('saas::coupons.index', compact('coupons'));
    }

    public function create()
    {
        //$this->authorize('users_create');

        return view('saas::coupons.create');
    }

    public function store(Request $request)
    {
        
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
