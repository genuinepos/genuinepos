<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Exception;
use Illuminate\Routing\Controller;
use Modules\SAAS\Utils\UrlGenerator;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Services\DeleteUnusedTenantService;
use Modules\SAAS\Interfaces\CurrencyServiceInterface;
use Modules\SAAS\Http\Requests\TrialTenantStoreRequest;
use Modules\SAAS\Interfaces\EmailVerificationServiceInterface;

class TrialController extends Controller
{
    public function __construct(
        private CurrencyServiceInterface $currencyServiceInterface,
        private PlanServiceInterface $planServiceInterface,
        private EmailVerificationServiceInterface $emailVerificationServiceInterface,
        private TenantServiceInterface $tenantService,
        private DeleteUnusedTenantService $deleteUnusedTenantService,
    ) {
    }

    public function create()
    {
        $trialPlan = $this->planServiceInterface->trialPlan();
        $currencies = $this->currencyServiceInterface->currencies()->select('id', 'country', 'currency', 'code')->get();
        return view('saas::guest.trial.create', compact('trialPlan', 'currencies'));
    }

    public function store(TrialTenantStoreRequest $request)
    {
        $tenantRequest = $request->all();
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

    function validation(TrialTenantStoreRequest $request)
    {
        return true;
    }
}
