<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\RolePermission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Role index view
    public function index()
    {
        if (auth()->user()->permission->roles['role_view'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('users.roles.index');
    }

    // Role index view
    public function allRoles()
    {
        $roles = Role::all();
        return view('users.roles.ajax_view.role_list', compact('roles'));
    }

    // Create cash role
    public function create()
    {
        if (auth()->user()->permission->roles['role_add'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('users.roles.create');
    }

    // Add role and permission
    public function store(Request $request)
    {
        $this->validate($request, [
            'role_name' => 'required|unique:roles,name',
        ]);

        $addRole = new Role();
        $addRole->name = $request->role_name;
        $addRole->save();

        $addRolePermission = new RolePermission();
        $addRolePermission->role_id = $addRole->id;
        $addRolePermission->user = $this->userPermissons($request);
        $addRolePermission->roles = $this->rolePermissons($request);
        $addRolePermission->supplier = $this->supplierPermissons($request);
        $addRolePermission->customers = $this->customerPermissons($request);
        $addRolePermission->product = $this->productPermissons($request);
        $addRolePermission->purchase = $this->purchasePermissons($request);
        $addRolePermission->s_adjust = $this->s_adjustPermissons($request);
        $addRolePermission->sale = $this->salePermissons($request);
        $addRolePermission->register = $this->cashRegisterPermissons($request);
        $addRolePermission->brand = $this->brandPermissons($request);
        $addRolePermission->category = $this->categoryPermissons($request);
        $addRolePermission->unit = $this->unitPermissons($request);
        $addRolePermission->report = $this->reportPermissons($request);
        $addRolePermission->setup = $this->setupPermissons($request);
        $addRolePermission->dashboard = $this->dashboardPermissons($request);
        $addRolePermission->accounting = $this->accountingPermissons($request);
        $addRolePermission->hrms = $this->hrmsPermissons($request);
        $addRolePermission->essential = $this->essentialPermissons($request);
        $addRolePermission->manufacturing = $this->manufacturingPermissons($request);
        $addRolePermission->project = $this->projectPermissons($request);
        $addRolePermission->repair = $this->repairPermissons($request);
        $addRolePermission->superadmin = $this->superadminPermissons($request);
        $addRolePermission->e_commerce = $this->eCommercePermissons($request);
        $addRolePermission->save();

        session()->flash('successMsg', 'Successfully Role is added.');
        return redirect()->route('users.role.index');
    }

    // Add role and permission
    public function update(Request $request, $roleId)
    {
        $this->validate($request, [
            'role_name' => 'required|unique:roles,name,' . $roleId,
        ]);

        $updateRole =  Role::where('id', $roleId)->first();
        $updateRole->name = $request->role_name;
        $updateRole->save();

        $updateRolePermission = RolePermission::where('role_id', $roleId)->first();
        $updateRolePermission->role_id = $updateRole->id;
        $updateRolePermission->user = $this->userPermissons($request);
        $updateRolePermission->roles = $this->rolePermissons($request);
        $updateRolePermission->supplier = $this->supplierPermissons($request);
        $updateRolePermission->customers = $this->customerPermissons($request);
        $updateRolePermission->product = $this->productPermissons($request);
        $updateRolePermission->purchase = $this->purchasePermissons($request);
        $updateRolePermission->s_adjust = $this->s_adjustPermissons($request);
        $updateRolePermission->sale = $this->salePermissons($request);
        $updateRolePermission->register = $this->cashRegisterPermissons($request);
        $updateRolePermission->brand = $this->brandPermissons($request);
        $updateRolePermission->unit = $this->unitPermissons($request);
        $updateRolePermission->report = $this->reportPermissons($request);
        $updateRolePermission->setup = $this->setupPermissons($request);
        $updateRolePermission->dashboard = $this->dashboardPermissons($request);
        $updateRolePermission->accounting = $this->accountingPermissons($request);
        $updateRolePermission->hrms = $this->hrmsPermissons($request);
        $updateRolePermission->essential = $this->essentialPermissons($request);
        $updateRolePermission->manufacturing = $this->manufacturingPermissons($request);
        $updateRolePermission->project = $this->projectPermissons($request);
        $updateRolePermission->repair = $this->repairPermissons($request);
        $updateRolePermission->superadmin = $this->superadminPermissons($request);
        $updateRolePermission->e_commerce = $this->eCommercePermissons($request);
        $updateRolePermission->save();

        session()->flash('successMsg', 'Successfully Role is updated.');
        return redirect()->route('users.role.index');
    }

    // Edit view of role 
    public function edit($roleId)
    {
        if (auth()->user()->permission->roles['role_edit'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $role = Role::with('permission')->where('id', $roleId)->firstOrFail();
        return view('users.roles.edit', compact('role'));
    }

    // Delete Role 
    public function delete(Request $request, $roleId)
    {
        if (auth()->user()->permission->roles['role_delete'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $deleteRole = Role::find($roleId);
        if (!is_null($deleteRole)) {
            $deleteRole->delete();
        }
        return response()->json('Successfully role is deleted');
    }

    // User permissions
    private function userPermissons($request)
    {
        $permissons = [
            'user_view' => isset($request->user_view) ? 1 : 0,
            'user_add' => isset($request->user_add) ? 1 : 0,
            'user_edit' => isset($request->user_edit) ? 1 : 0,
            'user_delete' => isset($request->user_delete) ? 1 : 0,
        ];

        return $permissons;
    }

    // Role permissions
    private function rolePermissons($request)
    {
        $permissons = [
            'role_view' => isset($request->role_view) ? 1 : 0,
            'role_add' => isset($request->role_add) ? 1 : 0,
            'role_edit' => isset($request->role_edit) ? 1 : 0,
            'role_delete' => isset($request->role_delete) ? 1 : 0,
        ];

        return $permissons;
    }

    // Supplier permissions
    private function supplierPermissons($request)
    {
        $permissons = [
            'supplier_all' => isset($request->supplier_all) ? 1 : 0,
            'supplier_add' => isset($request->supplier_add) ? 1 : 0,
            'supplier_edit' => isset($request->supplier_edit) ? 1 : 0,
            'supplier_delete' => isset($request->supplier_delete) ? 1 : 0,
        ];

        return $permissons;
    }

    // Customer permissions
    private function customerPermissons($request)
    {
        $permissons = [
            'customer_all' => isset($request->customer_all) ? 1 : 0,
            'customer_add' => isset($request->customer_add) ? 1 : 0,
            'customer_edit' => isset($request->customer_edit) ? 1 : 0,
            'customer_delete' => isset($request->customer_delete) ? 1 : 0,
        ];

        return $permissons;
    }

    // Product permissions
    private function productPermissons($request)
    {
        $permissons = [
            'product_all' => isset($request->product_all) ? 1 : 0,
            'product_add' => isset($request->product_add) ? 1 : 0,
            'product_edit' => isset($request->product_edit) ? 1 : 0,
            'openingStock_add' => isset($request->openingStock_add) ? 1 : 0,
            'product_delete' => isset($request->product_delete) ? 1 : 0,
            'pro_unit_cost' => isset($request->pro_unit_cost) ? 1 : 0,
        ];

        return $permissons;
    }

    // Purchase permissions
    private function purchasePermissons($request)
    {
        $permissons = [
            'purchase_all' => isset($request->purchase_all) ? 1 : 0,
            'purchase_add' => isset($request->purchase_add) ? 1 : 0,
            'purchase_edit' => isset($request->purchase_edit) ? 1 : 0,
            'purchase_delete' => isset($request->purchase_delete) ? 1 : 0,
            'purchase_payment' => isset($request->purchase_payment) ? 1 : 0,
            'purchase_return' => isset($request->purchase_return) ? 1 : 0,
            'status_update' => isset($request->status_update) ? 1 : 0,
        ];

        return $permissons;
    }

    // Stock Adjustment permissions
    private function s_adjustPermissons($request)
    {
        $permissons = [
            'adjustment_all' => isset($request->adjustment_all) ? 1 : 0,
            'adjustment_add' => isset($request->adjustment_add) ? 1 : 0,
            'adjustment_delete' => isset($request->adjustment_delete) ? 1 : 0,
        ];

        return $permissons;
    }

    // Sale permissions
    private function salePermissons($request)
    {
        $permissons = [
            'pos_all' => isset($request->pos_all) ? 1 : 0,
            'pos_add' => isset($request->pos_add) ? 1 : 0,
            'pos_edit' => isset($request->pos_edit) ? 1 : 0,
            'pos_delete' => isset($request->pos_delete) ? 1 : 0,
            'sale_access' => isset($request->sale_access) ? 1 : 0,
            'sale_draft' => isset($request->sale_draft) ? 1 : 0,
            'sale_quotation' => isset($request->sale_quotation) ? 1 : 0,
            'sale_all_own' => isset($request->sale_all_own) ? 1 : 0,
            'sale_payment' => isset($request->sale_payment) ? 1 : 0,
            'edit_price_sale_screen' => isset($request->edit_price_sale_screen) ? 1 : 0,
            'edit_price_pos_screen' => isset($request->edit_price_pos_screen) ? 1 : 0,
            'edit_discount_pos_screen' => isset($request->edit_discount_sale_screen) ? 1 : 0,
            'edit_discount_sale_screen' => isset($request->edit_discount_sale_screen) ? 1 : 0,
            'shipment_access' => isset($request->shipment_access) ? 1 : 0,
            'return_access' => isset($request->return_access) ? 1 : 0,
        ];

        return $permissons;
    }

    // Cash Register  permissions
    private function cashRegisterPermissons($request)
    {
        $permissons = [
            'register_view' => isset($request->register_view) ? 1 : 0,
            'register_close' => isset($request->register_close) ? 1 : 0,
        ];

        return $permissons;
    }

    // Brand permissions
    private function brandPermissons($request)
    {
        $permissons = [
            'brand_all' => isset($request->brand_all) ? 1 : 0,
            'brand_add' => isset($request->brand_add) ? 1 : 0,
            'brand_edit' => isset($request->brand_edit) ? 1 : 0,
            'brand_delete' => isset($request->brand_delete) ? 1 : 0,
        ];

        return $permissons;
    }

    // Category permissions
    private function categoryPermissons($request)
    {
        $permissons = [
            'category_all' => isset($request->category_all) ? 1 : 0,
            'category_add' => isset($request->category_add) ? 1 : 0,
            'category_edit' => isset($request->category_edit) ? 1 : 0,
            'category_delete' => isset($request->category_delete) ? 1 : 0,
        ];

        return $permissons;
    }

    // Unit permissions
    private function unitPermissons($request)
    {
        $permissons = [
            'unit_all' => isset($request->unit_all) ? 1 : 0,
            'unit_add' => isset($request->unit_add) ? 1 : 0,
            'unit_edit' => isset($request->unit_edit) ? 1 : 0,
            'unit_delete' => isset($request->unit_delete) ? 1 : 0,
        ];

        return $permissons;
    }

    // Unit permissions
    private function reportPermissons($request)
    {
        $permissons = [
            'loss_profit_report' => isset($request->loss_profit_report) ? 1 : 0,
            'purchase_sale_report' => isset($request->purchase_sale_report) ? 1 : 0,
            'tax_report' => isset($request->tax_report) ? 1 : 0,
            'cus_sup_report' => isset($request->cus_sup_report) ? 1 : 0,
            'stock_report' => isset($request->stock_report) ? 1 : 0,
            'stock_adjustment_report' => isset($request->stock_adjustment_report) ? 1 : 0,
            'tranding_report' => isset($request->tranding_report) ? 1 : 0,
            'item_report' => isset($request->item_report) ? 1 : 0,
            'pro_purchase_report' => isset($request->pro_purchase_report) ? 1 : 0,
            'pro_sale_report' => isset($request->pro_sale_report) ? 1 : 0,
            'purchase_payment_report' => isset($request->purchase_payment_report) ? 1 : 0,
            'sale_payment_report' => isset($request->sale_payment_report) ? 1 : 0,
            'expanse_report' => isset($request->expanse_report) ? 1 : 0,
            'register_report' => isset($request->register_report) ? 1 : 0,
            'representative_report' => isset($request->representative_report) ? 1 : 0,
        ];

        return $permissons;
    }

    // Setup permissions
    private function setupPermissons($request)
    {
        $permissons = [
            'tax' => isset($request->tax) ? 1 : 0,
            'branch' => isset($request->branch) ? 1 : 0,
            'warehouse' => isset($request->warehouse) ? 1 : 0,
            'g_settings' => isset($request->g_settings) ? 1 : 0,
            'p_settings' => isset($request->p_settings) ? 1 : 0,
            'inv_sc' => isset($request->inv_sc) ? 1 : 0,
            'inv_lay' => isset($request->inv_lay) ? 1 : 0,
            'barcode_settings' => isset($request->barcode_settings) ? 1 : 0,
            'cash_counters' => isset($request->cash_counters) ? 1 : 0,
        ];

        return $permissons;
    }

    // Dashboard permissions
    private function dashboardPermissons($request)
    {
        $permissons = [
            'dash_data' => isset($request->dash_data) ? 1 : 0,
        ];
        return $permissons;
    }

    // Accounting permissions
    private function accountingPermissons($request)
    {
        $permissons = [
            'ac_access' => isset($request->ac_access) ? 1 : 0,
        ];
        return $permissons;
    }

    // Human Resource mangagement system (HRMS) permissions
    private function hrmsPermissons($request)
    {
        $permissons = [
            'leave_type' => isset($request->leave_type) ? 1 : 0,
            'view_own_leave' => isset($request->view_own_leave) ? 1 : 0,
            'leave_approve' => isset($request->leave_approve) ? 1 : 0,
            'attendance_all' => isset($request->attendance_all) ? 1 : 0,
            'view_own_attendance' => isset($request->view_own_attendance) ? 1 : 0,
            'view_a_d' => isset($request->view_a_d) ? 1 : 0,
            'department' => isset($request->department) ? 1 : 0,
            'designation' => isset($request->designation) ? 1 : 0,
            'department' => isset($request->department) ? 1 : 0,
        ];

        return $permissons;
    }

    // Essentials permissions
    private function essentialPermissons($request)
    {
        $permissons = [
            'assign_todo' => isset($request->assign_todo) ? 1 : 0,
            'create_msg' => isset($request->create_msg) ? 1 : 0,
            'view_msg' => isset($request->view_msg) ? 1 : 0,
        ];
        return $permissons;
    }

    // Manufacturing permissions
    private function manufacturingPermissons($request)
    {
        $permissons = [
            'menuf_view' => isset($request->menuf_view) ? 1 : 0,
            'menuf_add' => isset($request->menuf_add) ? 1 : 0,
            'menuf_edit' => isset($request->menuf_edit) ? 1 : 0,
            'menuf_delete' => isset($request->menuf_delete) ? 1 : 0,
        ];
        return $permissons;
    }

    // Project permissions
    private function projectPermissons($request)
    {
        $permissons = [
            'proj_view' => isset($request->proj_view) ? 1 : 0,
            'proj_create' => isset($request->proj_create) ? 1 : 0,
            'proj_edit' => isset($request->proj_edit) ? 1 : 0,
            'proj_delete' => isset($request->proj_delete) ? 1 : 0,
        ];
        return $permissons;
    }

    // Project permissions
    private function repairPermissons($request)
    {
        $permissons = [
            'ripe_add_invo' => isset($request->ripe_add_invo) ? 1 : 0,
            'ripe_edit_invo' => isset($request->ripe_edit_invo) ? 1 : 0,
            'ripe_view_invo' => isset($request->ripe_view_invo) ? 1 : 0,
            'ripe_delete_invo' => isset($request->ripe_delete_invo) ? 1 : 0,
            'change_invo_status' => isset($request->ripe_delete_invo) ? 1 : 0,
            'ripe_jop_sheet_status' => isset($request->ripe_jop_sheet_status) ? 1 : 0,
            'ripe_jop_sheet_add' => isset($request->ripe_jop_sheet_add) ? 1 : 0,
            'ripe_jop_sheet_edit' => isset($request->ripe_jop_sheet_edit) ? 1 : 0,
            'ripe_jop_sheet_delete' => isset($request->ripe_jop_sheet_delete) ? 1 : 0,
            'ripe_only_assinged_job_sheet' => isset($request->ripe_only_assinged_job_sheet) ? 1 : 0,
            'ripe_view_all_job_sheet' => isset($request->ripe_view_all_job_sheet) ? 1 : 0,
        ];
        return $permissons;
    }

    // Super-admin permissions
    private function superadminPermissons($request)
    {
        $permissons = [
            'superadmin_access_pack_subscrip' => isset($request->superadmin_access_pack_subscrip) ? 1 : 0,
        ];
        return $permissons;
    }

    // E-commerce permissions
    private function eCommercePermissons($request)
    {
        $permissons = [
            'e_com_sync_pro_cate' => isset($request->e_com_sync_pro_cate) ? 1 : 0,
            'e_com_sync_pro' => isset($request->e_com_sync_pro) ? 1 : 0,
            'e_com_sync_order' => isset($request->e_com_sync_order) ? 1 : 0,
            'e_com_map_tax_rate' => isset($request->e_com_map_tax_rate) ? 1 : 0,
        ];
        return $permissons;
    }
}
