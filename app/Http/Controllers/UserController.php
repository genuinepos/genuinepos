<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Utils\FileUploader;
use Illuminate\Http\Request;
use App\Models\Setups\Branch;
use App\Models\AdminUserBranch;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct(
        private BranchService $branchService,
    ) {
    }

    // Users index view
    public function index(Request $request)
    {
        if (!auth()->user()->can('user_view')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

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

            if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

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
                    $html .= '<a class="dropdown-item details_button" href="' . route('users.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';
                    $html .= '<a class="dropdown-item" id="edit" href="' . route('users.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit </a>';
                    $html .= '<a class="dropdown-item" id="delete" href="' . route('users.delete', [$row->id]) . '"><i class="fas fa-trash-alt text-primary"></i> Delete </a>';
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

                            return $generalSettings['business__shop_name'];
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

                        return '<span  class="badge badge-sm bg-success">' . __("Allowed") . '</span>';
                    } else {

                        return '<span  class="badge badge-sm bg-danger">' . __("Not-Allowed") . '</span>';
                    }
                })
                ->rawColumns(['action', 'branch', 'role_name', 'name', 'username', 'allow_login'])
                ->make(true);
        }

        $branches = $this->branchService->branches(['parentBranch'])->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('users.index', compact('branches'));
    }

    // Create user view
    public function create()
    {
        if (!auth()->user()->can('user_add')) {

            abort(403, 'Access Forbidden.');
        }

        $roles = Role::all();
        $departments = DB::table('hrm_department')->orderBy('id', 'desc')->get();
        $designations = DB::table('hrm_designations')->orderBy('id', 'desc')->get();
        $shifts = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();

        $branches = Branch::select('id', 'name', 'branch_code')->orderBy('name', 'asc')->get();

        // if (auth()->user()->role_type == 1) {
        //     $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        // } else if (auth()->user()->role_type == 2) {
        //     $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
        //     $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        // } else {
        //     $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        // }

        return view('users.create', compact('departments', 'designations', 'shifts', 'branches', 'roles'));
    }

    // Add/Store user
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'email' => 'required|unique:users,email',
            'photo' => 'nullable|file|mimes:png,jpg,jpeg,gif,webp',
        ]);

        if (isset($request->allow_login)) {

            $this->validate($request, [
                'role_id' => 'required',
                'username' => 'required',
                'password' => 'required|confirmed',
                'sales_commission_percent' => 'nullable|integer|min:1',
            ]);
        }

        $addUser = new User();
        $addUser->prefix = $request->prefix;
        $addUser->name = $request->first_name;
        $addUser->last_name = $request->last_name;
        $addUser->email = $request->email;
        $addUser->status = 1;

        if (isset($request->allow_login)) {

            $addUser->allow_login = 1;
            $addUser->username = $request->username;
            $addUser->password = Hash::make($request->password);
            $roleId = $request->role_id ?? 3;
            $role = Role::find($roleId);

            if ($role->name == 'superadmin') {

                $addUser->role_type = 1;
                $addUser->assignRole($role->name);
            } elseif ($role->name == 'admin') {

                $addUser->role_type = 2;
                $addUser->assignRole($role->name);
                // $addUser->branch_id = $request->branch_id == 'head_office' ? NULL : $request->branch_id;
            } else {

                $addUser->branch_id = $request->branch_id == 'head_office' ? null : $request->branch_id;
                $addUser->role_type = 3;
                $addUser->assignRole($role->name);
            }
        } else {

            $addUser->allow_login = 0;
            $addUser->branch_id = $request->belonging_branch_id == 'head_office' ? null : $request->belonging_branch_id;
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

        return response()->json('User created successfully');
    }

    // User Edit view
    public function edit($userId)
    {
        if (!auth()->user()->can('user_edit')) {
            abort(403, 'Access Forbidden.');
        }
        $user = User::with(['roles'])->where('id', $userId)->first();

        if ($user->role_type == 1 && auth()->user()->role_type != 1) {
            abort(403, 'Access Forbidden.');
        }

        $roles = Role::all();
        $branches = Branch::select('id', 'name', 'branch_code')->orderBy('id', 'DESC')->get();
        // if (auth()->user()->role_type == 1) {
        //     $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        // } else if (auth()->user()->role_type == 2) {
        //     $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
        //     $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        // } else {
        //     $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        // }

        $departments = DB::table('hrm_department')->orderBy('id', 'desc')->get();
        $designations = DB::table('hrm_designations')->orderBy('id', 'desc')->get();
        $shifts = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();

        return view('users.edit', compact('user', 'roles', 'branches', 'departments', 'designations', 'shifts'));
    }

    // Update user
    public function update(Request $request, $userId)
    {
        if (!auth()->user()->can('user_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'first_name' => 'required',
            'email' => 'unique:users,email,' . $userId,
            'photo' => 'nullable|file|mimes:png,jpg,jpeg,gif,webp',
        ]);

        $updateUser = User::where('id', $userId)->first();

        if (isset($request->allow_login)) {

            $this->validate($request, [
                'role_id' => 'required',
                'username' => 'required',
            ]);

            if (!$updateUser->password) {

                $this->validate($request, [
                    'password' => 'required|confirmed',
                ]);
            } else {

                $this->validate($request, [
                    'password' => 'sometimes|confirmed',
                ]);
            }
        }

        $generalSettings = config('generalSettings');

        $updateUser->prefix = $request->prefix;
        $updateUser->name = $request->first_name;
        $updateUser->last_name = $request->last_name;
        $updateUser->status = isset($request->is_active) ? 1 : 0;
        $updateUser->allow_login = $request->allow_login;
        $updateUser->email = $request->email;

        if (isset($request->allow_login)) {

            $updateUser->allow_login = 1;
            $updateUser->username = $request->username;
            $updateUser->password = $request->password ? Hash::make($request->password) : $updateUser->password;
            $roleId = $request->role_id ?? 3;
            $role = Role::find($roleId);
            $roleName = $role->name;

            switch ($roleName) {

                case 'superadmin':
                    $updateUser->role_type = 1;
                    $updateUser->branch_id = null;
                    break;
                case 'admin':
                    $updateUser->role_type = 2;
                    $updateUser->branch_id = null;
                    break;
                default:
                    $updateUser->role_type = 3;
                    $updateUser->branch_id = $request->branch_id == 'head_office' ? null : $request->branch_id;
                    break;
            }

            $updateUser->syncRoles([$roleName]);
        } else {

            $updateUser->allow_login = 0;
            $updateUser->branch_id = $request->belonging_branch_id == 'head_office' ? null : $request->belonging_branch_id;
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

        session()->flash('successMsg', 'Successfully user updated');

        return response()->json('User updated successfully');
    }

    // Delete user
    public function delete($userId)
    {
        if (!auth()->user()->can('user_delete')) {

            abort(403, 'Access Forbidden.');
        }

        $deleteUser = User::find($userId);

        if ($deleteUser->role_type == 1) {

            return response()->json('Super-admin can not be deleted');
        }

        if (!is_null($deleteUser)) {

            $deleteUser->delete();
        }

        return response()->json('Successfully deleted');
    }

    public function show($userId)
    {
        if (!auth()->user()->can('user_view')) {
            abort(403, 'Access Forbidden.');
        }
        $user = User::with(['roles'])->where('id', $userId)->firstOrFail();

        return view('users.show', compact('user'));
    }

    // All Roles For user create form
    public function allRoles()
    {
        $roles = Role::all();

        return response()->json($roles);
    }
}
