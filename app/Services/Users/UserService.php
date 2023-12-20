<?php

namespace App\Services\Users;

use App\Models\User;
use App\Enums\RoleType;
use App\Enums\BooleanType;
use App\Utils\FileUploader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserService
{
    public function usersTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $users = '';
        $query = DB::table('users')
            ->leftJoin('branches', 'users.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('users.branch_id', null);
            } else {

                $query->where('users.branch_id', $request->branch_id);
            }
        }

        if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('users.branch_id', auth()->user()->branch_id);
        }

        $users = $query->select(
            'users.*',
            'branches.id as b_id',
            'branches.parent_branch_id',
            'branches.name as branch_name',
            'branches.area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
        )->orderBy('id', 'desc');

        return DataTables::of($users)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="' . route('users.show', [$row->id]) . '">' . __("View") . '</a>';
                $html .= '<a class="dropdown-item" id="edit" href="' . route('users.edit', [$row->id]) . '">' . __("Edit") . '</a>';
                $html .= '<a class="dropdown-item" id="delete" href="' . route('users.delete', [$row->id]) . '">' . __("Delete") . '</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->parent_branch_id) {

                    return $row->parent_branch_name . ' (' . $row->area_name . ')';
                } else {

                    if ($row->b_id) {

                        return $row->branch_name . ' (' . $row->area_name . ')';
                    } else {

                        return $generalSettings['business__business_name'];
                    }
                }
            })
            ->editColumn('role_name', function ($row) {

                $user = User::find($row->id);
                return $user?->roles->first()?->name ?? 'N/A';
            })
            ->editColumn('username', function ($row) {

                if ($row->username) {

                    return $row->username;
                } else {

                    return '...';
                }
            })
            ->editColumn('name', function ($row) {

                return $row->prefix . ' ' . $row->name . ' ' . $row->last_name;
            })
            ->editColumn('allow_login', function ($row) {

                if ($row->allow_login == 1) {

                    return '<span  class="badge badge-sm bg-success">' . __('Allowed') . '</span>';
                } else {

                    return '<span  class="badge badge-sm bg-danger">' . __('Not-Allowed') . '</span>';
                }
            })
            ->rawColumns(['action', 'branch', 'role_name', 'name', 'username', 'allow_login'])
            ->make(true);
    }

    function addUser(object $request, ?object $role): void
    {
        $addUser = new User();
        $addUser->prefix = $request->prefix;
        $addUser->name = $request->first_name;
        $addUser->last_name = $request->last_name;
        $addUser->email = $request->email;
        $addUser->status = BooleanType::True->value;

        $branchId = '';
        if ((auth()->user()->role_type == RoleType::SuperAdmin->value || auth()->user()->role_type == RoleType::Admin->value) || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $branchId = $request->branch_id == 'NULL' ? null : $request->branch_id;
        } else {

            $branchId = auth()->user()->branch_id;
        }

        if ($request->allow_login == BooleanType::True->value) {

            $addUser->allow_login = BooleanType::True->value;
            $addUser->username = $request->username;
            $addUser->password = Hash::make($request->password);

            if ($role->name == 'superadmin') {

                $addUser->role_type = RoleType::SuperAdmin->value;
                $addUser->is_belonging_an_area = BooleanType::False->value;
            } elseif ($role->name == 'admin') {

                $addUser->role_type = RoleType::Admin->value;
                $addUser->is_belonging_an_area = BooleanType::False->value;
            } else {

                $addUser->branch_id = $branchId;
                $addUser->role_type = RoleType::Other->value;
            }

            $addUser->assignRole($role->name);
        } else {

            $addUser->allow_login = BooleanType::False->value;
            $addUser->branch_id = $branchId;
        }

        $addUser->sales_commission_percent = $request->sales_commission_percent ? $request->sales_commission_percent : 0;
        $addUser->max_sales_discount_percent = $request->max_sales_discount_percent ? $request->max_sales_discount_percent : 0;
        $addUser->date_of_birth = $request->date_of_birth;
        $addUser->gender = $request->gender;
        $addUser->marital_status = $request->marital_status;
        $addUser->blood_group = $request->blood_group;
        $addUser->phone = $request->phone;
        $addUser->facebook_link = $request->facebook_link;
        $addUser->twitter_link = $request->twitter_link;
        $addUser->instagram_link = $request->instagram_link;
        $addUser->guardian_name = $request->guardian_name;
        $addUser->id_proof_name = $request->id_proof_name;
        $addUser->id_proof_number = $request->id_proof_number;
        $addUser->permanent_address = $request->permanent_address;
        $addUser->current_address = $request->current_address;
        $addUser->bank_ac_holder_name = $request->bank_ac_holder_name;
        $addUser->bank_ac_no = $request->bank_ac_no;
        $addUser->bank_name = $request->bank_name;
        $addUser->bank_identifier_code = $request->bank_identifier_code;
        $addUser->bank_branch = $request->bank_branch;
        $addUser->tax_payer_id = $request->tax_payer_id;
        $addUser->shift_id = $request->shift_id;
        $addUser->department_id = $request->department_id;
        $addUser->designation_id = $request->designation_id;
        $addUser->salary = $request->salary ? $request->salary : 0;
        $addUser->salary_type = $request->pay_type;

        if ($request->hasFile('photo')) {

            $addUser->photo = FileUploader::upload($request->file('photo'), 'uploads/user_photo');
        } else {

            $addUser->photo = 'default.png';
        }

        $addUser->save();
    }

    public function updateUser(object $request, int $id, ?object $role): void
    {
        $updateUser = $this->singleUser(id: $id);
        $updateUser->prefix = $request->prefix;
        $updateUser->name = $request->first_name;
        $updateUser->last_name = $request->last_name;
        $updateUser->status = isset($request->is_active) ? BooleanType::True->value : BooleanType::False->value;
        $updateUser->email = $request->email;

        $branchId = '';
        if ((auth()->user()->role_type == RoleType::SuperAdmin->value || auth()->user()->role_type == RoleType::Admin->value) || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $branchId = $request->branch_id == 'NULL' ? null : $request->branch_id;
        } else {

            $branchId = auth()->user()->branch_id;
        }

        $currentRole = $updateUser?->roles?->first();
        if($currentRole->name != 'superadmin'){

            if ($request->allow_login == BooleanType::True->value) {

                $updateUser->allow_login = BooleanType::True->value;
                $updateUser->username = $request->username;
                $updateUser->password = $request->password ? Hash::make($request->password) : $updateUser->password;
                $roleName = $role->name;

                switch ($roleName) {

                    case 'superadmin':
                        $updateUser->role_type = RoleType::SuperAdmin->value;
                        $updateUser->is_belonging_an_area = BooleanType::False->value;
                        break;
                    case 'admin':
                        $updateUser->role_type = RoleType::Admin->value;
                        $updateUser->is_belonging_an_area = BooleanType::False->value;
                        break;
                    default:
                        $updateUser->role_type = RoleType::Other->value;
                        $updateUser->branch_id = $branchId;
                        break;
                }

                $updateUser->syncRoles([$roleName]);
            } else {

                $updateUser->allow_login = BooleanType::False->value;
                $updateUser->branch_id = $branchId;
            }
        }else {

            $updateUser->allow_login = BooleanType::True->value;
            $updateUser->username = $request->username;
            $updateUser->password = $request->password ? Hash::make($request->password) : $updateUser->password;
        }

        $updateUser->sales_commission_percent = $request->sales_commission_percent ? $request->sales_commission_percent : 0;
        $updateUser->max_sales_discount_percent = $request->max_sales_discount_percent ? $request->max_sales_discount_percent : 0;
        $updateUser->date_of_birth = $request->date_of_birth;
        $updateUser->gender = $request->gender;
        $updateUser->marital_status = $request->marital_status;
        $updateUser->blood_group = $request->blood_group;
        $updateUser->phone = $request->phone;
        $updateUser->facebook_link = $request->facebook_link;
        $updateUser->twitter_link = $request->twitter_link;
        $updateUser->instagram_link = $request->instagram_link;
        $updateUser->guardian_name = $request->guardian_name;
        $updateUser->id_proof_name = $request->id_proof_name;
        $updateUser->id_proof_number = $request->id_proof_number;
        $updateUser->permanent_address = $request->permanent_address;
        $updateUser->current_address = $request->current_address;
        $updateUser->bank_ac_holder_name = $request->bank_ac_holder_name;
        $updateUser->bank_ac_no = $request->bank_ac_no;
        $updateUser->bank_name = $request->bank_name;
        $updateUser->bank_identifier_code = $request->bank_identifier_code;
        $updateUser->bank_branch = $request->bank_branch;
        $updateUser->tax_payer_id = $request->tax_payer_id;
        $updateUser->shift_id = $request->shift_id;
        $updateUser->department_id = $request->department_id;
        $updateUser->designation_id = $request->designation_id;
        $updateUser->salary = $request->salary ? $request->salary : 0;
        $updateUser->salary_type = $request->pay_type;

        if ($request->hasFile('photo')) {

            $newFile = FileUploader::upload($request->file('photo'), 'uploads/user_photo');
            if (
                isset($updateUser->photo) &&
                file_exists(public_path('uploads/user_photo/' . $updateUser->photo)) &&
                $updateUser->photo != 'default.png'
            ) {

                try {

                    unlink(public_path('uploads/user_photo/' . $updateUser->photo));
                } catch (Exception $e) {
                }
            }

            $updateUser->photo = $newFile;
        }

        $updateUser->save();
    }

    function deleteUser(int $id): array
    {
        $deleteUser = $this->singleUser(id: $id);

        if (!is_null($deleteUser)) {

            if ($deleteUser->role_type == RoleType::SuperAdmin->value) {

                return ['pass' => false, 'msg' => __('Superadmin can not be deleted')];
            }

            $deleteUser->delete();
        }

        return ['pass' => true];
    }

    public function changeBranch(object $request): void
    {
        $branchId = $request->branch_id == 'NULL' ? null : $request->branch_id;
        $user = $this->singleUser(id: auth()->user()->id);
        $user->branch_id = $branchId;
        $user->is_belonging_an_area = isset($branchId) ? BooleanType::True->value : BooleanType::False->value;
        $user->save();
    }

    public function singleUser(?int $id, array $with = null)
    {
        $query = User::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function users(array $with = null)
    {
        $query = User::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function addUserValidation(object $request, ?object $role)
    {
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|unique:users,email',
            'sales_commission_percent' => 'nullable|integer|min:1',
            'photo' => 'nullable|file|mimes:png,jpg,jpeg,gif,webp',
        ]);

        if ($role?->name != 'admin' && $role?->name != 'superadmin' && isset($request->branch_count)) {

            $request->validate([
                'branch_id' => 'required',
            ], ['branch_id.required' => __('Shop/Business is required.')]);
        }

        if ($request->allow_login == BooleanType::True->value) {

            $request->validate([
                'role_id' => 'required',
                'username' => 'required|unique:users,username',
                'password' => 'required|confirmed',
            ], ['role_id.required' => __('Role is required.')]);
        }
    }

    public function updateUserValidation(object $request, int $id, ?object $role)
    {
        $user = $this->singleUser(id: $id);

        $request->validate([
            'first_name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'photo' => 'nullable|file|mimes:png,jpg,jpeg,gif,webp',
        ]);

        if ($role?->name != 'admin' && $role?->name != 'superadmin' && isset($request->branch_count)) {

            $request->validate([
                'branch_id' => 'required',
            ], ['branch_id.required' => __('Shop/Business is required.')]);
        }

        if ($request->allow_login == BooleanType::True->value) {

            $request->validate([
                'role_id' => 'required',
                'username' => 'required|unique:users,username,' . $id,
            ]);

            if (!$user->password) {

                $request->validate([
                    'password' => 'required|confirmed',
                ]);
            } else {

                $request->validate([
                    'password' => 'sometimes|confirmed',
                ]);
            }
        }
    }
}
