<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\SAAS\Utils\UrlGenerator;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Services\DeleteUnusedTenantService;
use Modules\SAAS\Interfaces\CurrencyServiceInterface;
use Modules\SAAS\Http\Requests\GuestTenantStoreRequest;
use Modules\SAAS\Interfaces\EmailVerificationServiceInterface;

class PlanConfirmController extends Controller
{
    public function __construct(
        private CurrencyServiceInterface $currencyServiceInterface,
        private PlanServiceInterface $planServiceInterface,
        private EmailVerificationServiceInterface $emailVerificationServiceInterface,
        private TenantServiceInterface $tenantService,
        private DeleteUnusedTenantService $deleteUnusedTenantService,
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function create($slug, $pricePeriod = null)
    {
        $plan = $this->planServiceInterface->plans()->where('slug', $slug)->firstOrFail();
        $currencies = $this->currencyServiceInterface->currencies()->select('id', 'country', 'currency', 'code')->get();
        return view('saas::guest.plan_confirm.create', [
            'plan' => $plan,
            'pricePeriod' => $pricePeriod,
            'currencies' => $currencies,
        ]);
    }

    public function confirm(GuestTenantStoreRequest $request)
    {
        $tenantRequest = $request->all();
        // dd($tenantRequest);
        $emailIsVerified = $this->emailVerificationServiceInterface->singleEmailVerification(email: $tenantRequest['email'], isVerified: true);

        if (isset($emailIsVerified)) {

            $emailIsVerified->delete();
        } else {

            throw new Exception('Something went wrong, Business creation failed. Please try again!', 500);
        }

        $tenant = $this->tenantService->create($tenantRequest);
        if (isset($tenant)) {
            $domain = $tenant->domains()->where('domain', $tenantRequest['domain'])->first();
            $returningUrl = UrlGenerator::generateFullUrlFromDomain($domain->domain);

            return response()->json($returningUrl, 201);
            // return redirect()->intended($returningUrl);
        }

        $this->deleteUnusedTenantService->deleteTenant(domainName: $tenantRequest['domain']);
        throw new Exception('Something went wrong, Business creation failed. Please try again!', 500);
    }

    public function validation(GuestTenantStoreRequest $request) {

        return true;
    }
}
