<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use DB;
use Exception;
use Illuminate\Routing\Controller;
use Modules\SAAS\Entities\Tenant;
use Modules\SAAS\Http\Requests\GuestTenantStoreRequest;
use Modules\SAAS\Utils\UrlGenerator;

class GuestTenantController extends Controller
{
    public function store(GuestTenantStoreRequest $request)
    {
        $tenantRequest = $request->validated();
        $admin = $this->getAdmin();
        $admin['username'] = $tenantRequest['email'];
        $admin['email'] = $tenantRequest['email'];
        $admin['password'] = bcrypt($tenantRequest['password']);

        try {
            $tenant = Tenant::create([
                'id' => $tenantRequest['domain'],
                'name' => $tenantRequest['name'],
            ]);

            if ($tenant) {
                $domain = $tenant->domains()->create(['domain' => $tenantRequest['domain']]);
                $returningUrl = UrlGenerator::generateFullUrlFromDomain($domain->domain);
                DB::statement('use '.$tenant->tenancy_db_name);
                $admin = \App\Models\User::create($admin);
                $adminRole = \App\Models\Role::first();
                $admin->assignRole($adminRole);
                DB::reconnect();

                return response()->json($returningUrl, 200);
            }
        } catch (Exception $e) {
            if (config('app.debug')) {
                return redirect()->back()->with('error', 'Tenant creation failed.'.$e->getMessage());
            }

            return redirect()->back()->with('error', 'Something went wrong! please try again!');
        }
    }

    public function getAdmin(): array
    {
        $admin = [
            'id' => 1,
            'prefix' => 'Md.',
            'name' => 'Super',
            'last_name' => 'Admin',
            'emp_id' => null,
            'username' => 'superadmin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => '2023-01-11 11:35:53',
            'shift_id' => null,
            'role_type' => 1,
            'allow_login' => 1,
            'branch_id' => null,
            'status' => 1,
            'sales_commission_percent' => '00.00',
            'max_sales_discount_percent' => '5.00',
            'phone' => '01700000000',
            'date_of_birth' => '0000-00-00',
            'gender' => 'Male',
            'marital_status' => 'Married',
            'blood_group' => 'a+',
            'photo' => 'default.png',
            'facebook_link' => null,
            'twitter_link' => null,
            'instagram_link' => null,
            'social_media_1' => null,
            'social_media_2' => null,
            'custom_field_1' => null,
            'custom_field_2' => null,
            'guardian_name' => null,
            'id_proof_name' => null,
            'id_proof_number' => null,
            'permanent_address' => null,
            'current_address' => null,
            'bank_ac_holder_name' => 'Businesss',
            'bank_ac_no' => '012441200152',
            'bank_name' => 'Sonali Bank',
            'bank_identifier_code' => '0000000000000000',
            'bank_branch' => 'Uttara, Dhaka',
            'tax_payer_id' => '5222222215310',
            'language' => 'en',
            'department_id' => null,
            'designation_id' => null,
            'salary' => '200000000.00',
            'salary_type' => 'Yearly',
            'created_at' => '2021-04-07T07:04:03.000000Z',
            'updated_at' => '2022-12-31T10:36:37.000000Z',
        ];

        return $admin;
    }
}
