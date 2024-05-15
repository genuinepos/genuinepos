<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy;
use App\Http\Controllers\Controller;
use Modules\SAAS\Services\TenantServiceInterface;

class DeleteFailedTenantController extends Controller
{
    public function __construct(
        private TenantServiceInterface $tenantService,
    ) {
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $this->tenantService->deleteTenant(id: $request->delete_domain);
        return true;
    }
}
