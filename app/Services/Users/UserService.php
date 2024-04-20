<?php

namespace App\Services\Users;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\RoleType;
use App\Enums\UserType;
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

        if ($request->user_type) {

            $query->where('users.user_type', $request->user_type);
        }

        // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

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

                        return $generalSettings['business_or_shop__business_name'];
                    }
                }
            })
            ->editColumn('type', function ($row) {

                return UserType::tryFrom($row->user_type)->name;
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
        $addUser->user_type = $request->user_type;
        $addUser->prefix = $request->prefix;
        $addUser->name = $request->first_name;
        $addUser->last_name = $request->last_name;
        $addUser->email = $request->email;
        $addUser->status = BooleanType::True->value;

        $branchId = '';
        if (
            auth()->user()->can('has_access_to_all_area') &&
            auth()->user()->is_belonging_an_area == BooleanType::False->value &&
            config('generalSettings')['subscription']->has_business == BooleanType::True->value &&
            $request->branch_count
        ) {

            $branchId = $request->branch_id == 'NULL' ? null : $request->branch_id;
        } else {

            $branchId = auth()->user()->branch_id;
        }

        if ($request->allow_login == BooleanType::True->value) {

            $addUser->allow_login = BooleanType::True->value;
            $addUser->username = $request->username;
            $addUser->password = Hash::make($request->password);

            if ($role?->hasPermissionTo('has_access_to_all_area')) {

                $addUser->is_belonging_an_area = BooleanType::False->value;
            } else {

                $addUser->branch_id = $branchId;
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
        $addUser->emp_id = $request->emp_id;
        $addUser->shift_id = $request->shift_id;
        $addUser->department_id = $request->department_id;
        $addUser->designation_id = $request->designation_id;
        $addUser->salary = $request->salary ? $request->salary : 0;
        $addUser->salary_type = $request->pay_type;

        if ($request->hasFile('photo')) {

            $dir = public_path('uploads/' . tenant('id') . '/' . 'user_photo/');

            $addUser->photo = FileUploader::upload($request->file('photo'), $dir);
        }

        $addUser->save();
    }

    public function updateUser(object $request, int $id, ?object $role): void
    {
        $updateUser = $this->singleUser(id: $id);
        $updateUser->user_type = $request->user_type;
        $updateUser->prefix = $request->prefix;
        $updateUser->name = $request->first_name;
        $updateUser->last_name = $request->last_name;
        $updateUser->status = isset($request->is_active) ? BooleanType::True->value : BooleanType::False->value;
        $updateUser->email = $request->email;

        $branchId = '';
        if (
            auth()->user()->can('has_access_to_all_area') &&
            auth()->user()->is_belonging_an_area == BooleanType::False->value &&
            config('generalSettings')['subscription']->has_business == BooleanType::True &&
            $request->branch_count
        ) {

            $branchId = $request->branch_id == 'NULL' ? null : $request->branch_id;
        } else {

            $branchId = auth()->user()->branch_id;
        }

        $currentRole = $updateUser?->roles?->first();
        if ($currentRole?->name != 'superadmin') {

            if ($request->allow_login == BooleanType::True->value) {

                $updateUser->allow_login = BooleanType::True->value;
                $updateUser->username = $request->username;
                $updateUser->password = $request->password ? Hash::make($request->password) : $updateUser->password;
                $roleName = $role->name;

                if ($role?->hasPermissionTo('has_access_to_all_area')) {

                    $updateUser->is_belonging_an_area = BooleanType::False->value;
                } else {

                    $updateUser->branch_id = $branchId;
                    $updateUser->is_belonging_an_area = BooleanType::True->value;
                }

                $updateUser->syncRoles([$roleName]);
            } else {

                $updateUser->allow_login = BooleanType::False->value;
                $updateUser->branch_id = $branchId;
            }
        } else {

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
        $updateUser->emp_id = $request->emp_id;
        $updateUser->shift_id = $request->shift_id;
        $updateUser->department_id = $request->department_id;
        $updateUser->designation_id = $request->designation_id;
        $updateUser->salary = $request->salary ? $request->salary : 0;
        $updateUser->salary_type = $request->pay_type;

        if ($request->hasFile('photo')) {

            $dir = public_path('uploads/' . tenant('id') . '/' . 'user_photo/');

            $newFile = FileUploader::upload($request->file('photo'), $dir);
            if (
                isset($updateUser->photo) &&
                file_exists($dir . $updateUser->photo)
            ) {

                try {
                    unlink($dir . $updateUser->photo);
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

            $dir = public_path('uploads/' . tenant('id') . '/' . 'user_photo/');

            if (
                isset($deleteUser->photo) &&
                file_exists($dir . $deleteUser->photo)
            ) {

                try {
                    unlink($dir . $deleteUser->photo);
                } catch (Exception $e) {
                }
            }

            $deleteUser->delete();
        }

        return ['pass' => true];
    }

    public function changeBranch(object $request): void
    {
        $currentBranch = null;
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch?->parentBranch) {

                $currentBranch = auth()->user()->branch->parentBranch->name . '(' . auth()->user()->branch->area_name . ')-' . auth()->user()->branch->branch_code;
            } else {

                $currentBranch = auth()->user()->branch->name . '(' . auth()->user()->branch->area_name . ')-' . auth()->user()->branch->branch_code;
            }
        } else {

            $currentBranch = config('generalSettings')['business_or_shop__business_name'] . '(' . __('Business') . ')';
        }

        $branchId = $request->branch_id == 'NULL' ? null : $request->branch_id;
        $__branchId = isset($request->select_type) && $request->select_type == 'business' ? null : $branchId;
        auth()->user()->branch_id = $__branchId;
        auth()->user()->is_belonging_an_area = isset($__branchId) ? BooleanType::True->value : BooleanType::False->value;
        auth()->user()->save();

        $user = $this->singleUser(id: auth()->user()->id, with: ['branch', 'branch.parentBranch']);

        $switchedBranch = null;
        if ($user?->branch) {

            if ($user?->branch?->parentBranch) {

                $switchedBranch = $user->branch->parentBranch->name . '(' . $user->branch->area_name . ')-' . $user->branch->branch_code;
            } else {

                $switchedBranch = $user->branch->name . '(' . $user->branch->area_name . ')-' . $user->branch->branch_code;
            }
        } else {

            $switchedBranch = config('generalSettings')['business_or_shop__business_name'] . '(' . __('Business') . ')';
        }

        $description = $currentBranch . ' ' . __('To') . ' ' . $switchedBranch;
        auth()->user()->location_switch_log_description = $description;
    }

    public function getBranchUsers(int $isOnlyAuthenticatedUser, int $allowAll, int|string $branchId = null): array|object
    {
        if ($allowAll == 0 && ($branchId == 'null' || $branchId == '')) {
            return [];
        }

        $branchId = $branchId == 'business' ? null : $branchId;
        $branchId = $branchId == 'null' ? null : $branchId;
        $query = $this->users();

        if (isset($branchId) && $branchId == 'NULL') {

            $query->where('branch_id', null);
        } else if (isset($branchId)) {

            $query->where('branch_id', $branchId);
        }

        if ($isOnlyAuthenticatedUser == BooleanType::True->value) {

            $query->where('allow_login', BooleanType::True->value);
        }

        $users = $query->get();
        return $users;
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

    public function storeRestrictions(object $request): ?array
    {
        $userLimit = (int) config('generalSettings')['subscription']->features['user_count'];
        $employeeLimit = (int) config('generalSettings')['subscription']->features['employee_count'];
        $branchId = isset($request->branch_id) && !empty($request->branch_id) ? $request->branch_id : auth()->user()->branch_id;
        $__branchId = $branchId == 'NULL' ? null : $branchId;

        $currentUserCount = $this->users()->whereIn('user_type', [UserType::User->value, UserType::Both->value])
            ->where('branch_id', $__branchId)->count();

        $currentEmployeeCount = $this->users()->whereIn('user_type', [UserType::Employee->value, UserType::Both->value])
            ->where('branch_id', $__branchId)->count();

        $additionalMsg = isset($request->branch_id) && !empty($request->branch_id) ? __('in the selected shop/business') : __('in your current shop/business.');

        if ($request->user_type == UserType::User->value && $currentUserCount >= $userLimit) {

            return ['pass' => false, 'msg' => __('Selected type is user. User Limit is ' . $userLimit . ' ' . $additionalMsg . ', The Limit has already full.')];
        }

        if ($request->user_type == UserType::Employee->value && $currentEmployeeCount >= $employeeLimit) {

            return ['pass' => false, 'msg' => __('Selected type is employee. Employee Limit is ' . $employeeLimit . ' ' . $additionalMsg . ', The Limit has already full.')];
        }

        if ($request->user_type == UserType::Both->value) {

            if ($currentUserCount >= $userLimit) {

                return ['pass' => false, 'msg' => __('Selected type is both (User And Employee). But User Limit is ' . $userLimit . ' ' . $additionalMsg . ', The Limit has already full.')];
            }

            if ($currentEmployeeCount >= $employeeLimit) {

                return ['pass' => false, 'msg' => __('Selected type is both (User And Employee). But Employee Limit is ' . $employeeLimit . ' ' . $additionalMsg . ', The Limit has already full.')];
            }
        }

        return ['pass' => true];
    }

    public function updateRestrictions(object $request, int $id): ?array
    {
        $user = $this->singleUser(id: $id);

        $userLimit = (int) config('generalSettings')['subscription']->features['user_count'];
        $employeeLimit = (int) config('generalSettings')['subscription']->features['employee_count'];
        $branchId = isset($request->branch_id) && !empty($request->branch_id) ? $request->branch_id : auth()->user()->branch_id;
        $__branchId = $branchId == 'NULL' ? null : $branchId;

        if ($user->user_type == UserType::Both->value) {

            return ['pass' => true];
        } else if ($user->user_type == $request->user_type) {

            return ['pass' => true];
        } else if ($user->user_type == UserType::User->value && $request->user_type == UserType::Both->value) {

            $userLimit += 1;
        } else if ($user->user_type == UserType::Employee->value && $request->user_type == UserType::Both->value) {

            $employeeLimit += 1;
        }

        $currentUserCount = $this->users()->whereIn('user_type', [UserType::User->value, UserType::Both->value])
            ->where('branch_id', $__branchId)->count();

        $currentEmployeeCount = $this->users()->whereIn('user_type', [UserType::Employee->value, UserType::Both->value])
            ->where('branch_id', $__branchId)->count();

        $additionalMsg = isset($request->branch_id) && !empty($request->branch_id) ? __('in the selected shop/business') : __('in your current shop/business.');

        if ($request->user_type == UserType::User->value && $currentUserCount >= $userLimit) {

            return ['pass' => false, 'msg' => __('Selected type is user. User Limit is ' . $userLimit . ' ' . $additionalMsg . ', The Limit has already full.')];
        }

        if ($request->user_type == UserType::Employee->value && $currentEmployeeCount >= $employeeLimit) {

            return ['pass' => false, 'msg' => __('Selected type is employee. Employee Limit is ' . $employeeLimit . ' ' . $additionalMsg . ', The Limit has already full.')];
        }

        if ($request->user_type == UserType::Both->value) {

            if ($currentUserCount >= $userLimit) {

                return ['pass' => false, 'msg' => __('Selected type is both (User And Employee). But User Limit is ' . $userLimit . ' ' . $additionalMsg . ', The Limit has already full.')];
            }

            if ($currentEmployeeCount >= $employeeLimit) {

                return ['pass' => false, 'msg' => __('Selected type is both (User And Employee). But Employee Limit is ' . $employeeLimit . ' ' . $additionalMsg . ', The Limit has already full.')];
            }
        }

        return ['pass' => true];
    }

    public function currentUserAndEmployeeCount(mixed $branchId = null)
    {
        $branchId = isset($branchId) && !empty($branchId) && $branchId != 'undefined' ? $branchId : auth()->user()->branch_id;
        $__branchId = $branchId == 'NULL' ? null : $branchId;

        $currentUserCount = $this->users()->whereIn('user_type', [UserType::User->value, UserType::Both->value])
            ->where('branch_id', $__branchId)->count();

        $currentEmployeeCount = $this->users()->whereIn('user_type', [UserType::Employee->value, UserType::Both->value])
            ->where('branch_id', $__branchId)->count();

        return [
            'current_user_count' => $currentUserCount,
            'current_employee_count' => $currentEmployeeCount,
        ];
    }

    public function addAppSuperAdminUser(object $request): void
    {
        $admin = [
            'name' => $request->fullname,
            'username' => isset($request->username) ? $request->username : explode('@', $request->email)[0],
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_type' => RoleType::SuperAdmin->value,
            'allow_login' => 1,
            'status' => 1,
            'phone' => $request->phone,
            'date_of_birth' => '0000-00-00',
            'language' => 'en',
            'is_belonging_an_area' => 0,
            'currency_id' => $request->currency_id,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'permanent_address' => $request->address,
            'current_address' => $request->address,
            'created_at' => Carbon::now(),
        ];

        $appUserAdmin = User::create($admin);
        $superAdminRole = (new \App\Services\Users\RoleService)->singleRole(id: 1);
        $appUserAdmin->assignRole($superAdminRole);
    }
}
