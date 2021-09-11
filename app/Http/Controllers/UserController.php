<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Branch;
use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Users index view
    public function index(Request $request)
    {
        if (auth()->user()->permission->user['user_view'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $users = '';
            $query = DB::table('admin_and_users')
            ->leftJoin('branches', 'admin_and_users.branch_id', 'branches.id')
            ->leftJoin('roles', 'admin_and_users.role_id', 'roles.id')
            ->leftJoin('hrm_department', 'admin_and_users.department_id', 'hrm_department.id')
            ->leftJoin('hrm_designations', 'admin_and_users.designation_id', 'hrm_designations.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('admin_and_users.branch_id', NULL);
                } else {
                    $query->where('admin_and_users.branch_id', $request->branch_id);
                }
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $users = $query->select(
                    'admin_and_users.*',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'roles.name as role_name',
                    'hrm_department.department_name as dep_name',
                    'hrm_designations.designation_name as des_name',
                )->orderBy('id', 'desc');
            } else {
                $users = $query->select(
                    'admin_and_users.*',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'roles.name as role_name',
                    'hrm_department.department_name as dep_name',
                    'hrm_designations.designation_name as des_name',
                )->where('admin_and_users.branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'desc');
            }

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
                ->editColumn('branch', function ($row) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BRANCH</b>)';
                    } else {
                        return '(<b>Head Office</b>)';
                    }
                })
                ->editColumn('role_name',  function ($row) {
                    if ($row->role_type == 1){
                       return 'Super-Admin';
                    }elseif($row->role_type == 2)   {
                        return 'Admin';
                    }elseif($row->role_type == 3){
                        return  $row->role_name;
                    }else {
                       return '<span class="badge bg-warning text-white">No Role</span>';
                    }  
                })
                ->editColumn('username',  function ($row) {
                    if ($row->username){
                       return $row->username;
                    }else {
                        return '<span class="badge bg-secondary"><b>Login not allowed</b></span>';
                    }  
                })
                ->setRowClass('text-start')
                ->rawColumns(['action', 'branch', 'role_name', 'username'])
                ->make(true);
        }
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('users.index', compact('branches'));
    }

    // Create user view
    public function create()
    {
        if (auth()->user()->permission->user['user_add'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        $departments = DB::table('hrm_department')->orderBy('id', 'desc')->get();
        $designations = DB::table('hrm_designations')->orderBy('id', 'desc')->get();
        $shifts = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('users.create', compact('departments', 'designations', 'shifts', 'branches'));
    }

    // Add/Store user
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'email' => 'required|unique:admin_and_users,email',
            'department_id' => 'required',
            'shift_id' => 'required',
            'emp_id' => 'required',
            'salary' => 'required',
            'pay_type' => 'required',
        ],[
            'department_id.required' => 'Department field is required.',
            'shift_id.required' => 'Shift field is required.',
            'emp_id.required' => 'Employee ID field is required.',
        ]);

        if (isset($request->allow_login)) {
            $this->validate($request, [
                'username' => 'required|unique:admin_and_users,username',
                'password' => 'required|confirmed',
            ]);

            // if ($request->role_id) {
            //     $this->validate($request, [
            //         'branch_id' => 'required',
            //     ], [
            //         'branch_id.required' => 'Branch field must not be empty.'
            //     ]);
            // }
        }else {
            $this->validate($request, [
                'belonging_branch_id' => 'required',
            ], [
                'belonging_branch_id.required' => 'Belonging Branch field must not be empty.'
            ]);
        }

        //return $request->all();
        $addUser = new AdminAndUser();
        $addUser->prefix = $request->prefix;
        $addUser->name = $request->first_name;
        $addUser->last_name = $request->last_name;
        $addUser->email = $request->email;
        $addUser->status = isset($request->is_active) ? 1 : 0;

        if (isset($request->allow_login)) {
            $addUser->allow_login = 1;
            $addUser->username = $request->username;
            $addUser->password = Hash::make($request->password);
            if (!$request->role_id) {
                $superAdminPermission = RolePermission::where('is_super_admin_role', 1)->first();
                $addUser->role_type = 2;
                $addUser->role_permission_id = $superAdminPermission->id;
            } else {
                $userPermission = RolePermission::where('role_id', $request->role_id)->first();
                $addUser->role_type = 3;
                $addUser->role_id = $request->role_id;
                $addUser->role_permission_id = $userPermission->id;
                $addUser->branch_id = $request->branch_id == 'head_office' ? NULL : $request->branch_id;
            }
        }else {
            $addUser->branch_id = $request->belonging_branch_id == 'head_office' ? NULL : $request->belonging_branch_id;
        }

        $addUser->sales_commission_percent = $request->sales_commission_percent ? $request->sales_commission_percent : 0;
        $addUser->max_sales_discount_percent = $request->max_sales_discount_percent ? $request->max_sales_discount_percent : 0;;
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
        $addUser->salary = $request->salary;
        $addUser->salary_type = $request->pay_type;
        $addUser->save();
        session()->flash('successMsg', 'User created successfully');
        return response()->json('User created successfully');
    }

    // User Edit view 
    public function edit($userId)
    {
        if (auth()->user()->permission->user['user_edit'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $user = AdminAndUser::with(['role'])->where('id', $userId)->first();
        if ($user->role_type == 1) {
            abort(403, 'Access Forbidden.');
        }

        $roles = Role::all();
        $branches = Branch::select('id', 'name', 'branch_code')->orderBy('id', 'DESC')->get();
        $departments = DB::table('hrm_department')->orderBy('id', 'desc')->get();
        $designations = DB::table('hrm_designations')->orderBy('id', 'desc')->get();
        $shifts = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();
        return view('users.edit', compact('user', 'roles', 'branches', 'departments', 'designations', 'shifts'));
    }

    // Update user
    public function update(Request $request, $userId)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'email' => 'required|unique:admin_and_users,email,'.$userId,
            'department_id' => 'required',
            'shift_id' => 'required',
            'emp_id' => 'required',
            'salary' => 'required',
            'pay_type' => 'required',
        ],[
            'department_id.required' => 'Department field is required.',
            'shift_id.required' => 'Shift field is required.',
            'emp_id.required' => 'Employee ID field is required.',
        ]);

        $updateUser = AdminAndUser::where('id', $userId)->first();

        if (isset($request->allow_login)) {
            $this->validate($request, [
                'username' => 'required|unique:admin_and_users,username,'.$userId,
                'password' => 'sometimes|confirmed',
            ]);

            if ($updateUser->allow_login == 0) {
                $this->validate($request, [
                    'password' => 'required|confirmed',
                ]);
            }else {
                $this->validate($request, [
                    'password' => 'sometimes|confirmed',
                ]);
            }

            if ($request->role_id) {
                $this->validate($request, [
                    'branch_id' => 'required',
                ], [
                    'branch_id.required' => 'Branch field must not be empty.'
                ]);
            }
        }else {
            $this->validate($request, [
                'belonging_branch_id' => 'required',
            ], [
                'belonging_branch_id.required' => 'Beloging Branch field must not be empty.'
            ]);
        }

        //return $request->all();
        $updateUser->prefix = $request->prefix;
        $updateUser->name = $request->first_name;
        $updateUser->last_name = $request->last_name;
        $updateUser->email = $request->email;
        $updateUser->status = isset($request->is_active) ? 1 : 0;

        $updateUser->allow_login = 0;
        $updateUser->username = NULL;
        $updateUser->role_type = NULL;
        $updateUser->role_id = NULL;
        $updateUser->role_permission_id = NULL;

        if (isset($request->allow_login)) {
            $updateUser->allow_login = 1;
            $updateUser->username = $request->username;
            $updateUser->password = $request->password ? Hash::make($request->password) : $updateUser->password;
            if (!$request->role_id) {
                $superAdminPermission = RolePermission::where('is_super_admin_role', 1)->first();
                $updateUser->role_type = 2;
                $updateUser->role_permission_id = $superAdminPermission->id;
            } else {
                $userPermission = RolePermission::where('role_id', $request->role_id)->first();
                $updateUser->role_type = 3;
                $updateUser->role_id = $request->role_id;
                $updateUser->role_permission_id = $userPermission->id;
                $updateUser->branch_id = $request->branch_id == 'head_office' ? NULL : $request->branch_id;
            }
        }else {
            $updateUser->branch_id = $request->belonging_branch_id == 'head_office' ? NULL : $request->belonging_branch_id;
        }

        $updateUser->sales_commission_percent = $request->sales_commission_percent ? $request->sales_commission_percent : 0;
        $updateUser->max_sales_discount_percent = $request->max_sales_discount_percent ? $request->max_sales_discount_percent : 0;;
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
        $updateUser->salary = $request->salary;
        $updateUser->salary_type = $request->pay_type;
        $updateUser->save();
        session()->flash('successMsg', 'Successfully user updated');
        return response()->json('User updated successfully');
    }

    // Delete user
    public function delete($userId)
    {
        
        if (auth()->user()->permission->user['user_delete'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        
        $deleteUser = AdminAndUser::find($userId);

        if ($deleteUser->role_type == 1) {
            abort(403, 'Access Forbidden.');
        }

        if (!is_null($deleteUser)) {
            $deleteUser->delete();
        }
        return response()->json('Successfully user is deleted');
    }

    public function show($userId)
    {
        $user = AdminAndUser::with(['role', 'department', 'designation'])->where('id', $userId)->firstOrFail();
        // $firstName = str_split($user->name)[0];
        // $lastName = $user->last_name ? str_split($user->last_name)[0] : '';
        // $namePrefix = $firstName.' '.$lastName; 
        return view('users.show', compact('user'));
    }

    // All Roles For user create form 
    public function allRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }
}