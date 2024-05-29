<?php

namespace App\Services\Startup\MethodContainerServices;

use App\Services\Users\RoleService;
use App\Services\Setups\BranchService;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\TimezoneService;
use App\Services\Startup\StartupService;
use App\Services\Setups\CashCounterService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\InvoiceLayoutService;
use App\Services\GeneralSettingServiceInterface;
use App\Services\Subscriptions\SubscriptionService;
use App\Interfaces\Startup\StartupControllerMethodContainerInterface;

class StartupControllerMethodContainerService implements StartupControllerMethodContainerInterface
{
    public function __construct(
        private StartupService $startupService,
        private CurrencyService $currencyService,
        private TimezoneService $timezoneService,
        private RoleService $roleService,
        private GeneralSettingServiceInterface $generalSettingService,
        private BranchService $branchService,
        private CashCounterService $cashCounterService,
        private InvoiceLayoutService $invoiceLayoutService,
        private BranchSettingService $branchSettingService,
        private SubscriptionService $subscriptionService,
    ) {
    }

    public function startupFromContainer(): array
    {
    }

    public function finishMethodContainer(object $request): void
    {
    }
}
