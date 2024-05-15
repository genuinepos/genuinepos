<?php

namespace App\Services\Subscriptions;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Models\Subscriptions\Subscription;
use App\Jobs\SendSubscriptionPlanUpgradeMailQueueJob;
use App\Jobs\SendSubscriptionAddShopInvoiceMailQueueJob;
use App\Jobs\SendSubscriptionShopRenewInvoiceMainQueueJob;
use App\Jobs\SendSubscriptionAddBusinessInvoiceMailQueueJob;

class SubscriptionMailService
{
    public function sendPlanUpgradeSuccessMain(object $user, string $planName, array $data, int $isTrialPlan): void
    {
        if (!isset($user?->email)) {
            return;
        }

        dispatch(new SendSubscriptionPlanUpgradeMailQueueJob(user: $user, planName: $planName, data: $data, isTrialPlan: $isTrialPlan));
    }

    public function sendSubscriptionAddShopInvoiceMail(
        ?object $user,
        int $increasedShopCount,
        float $pricePerShop,
        string $pricePeriod,
        int $pricePeriodCount,
        int $subtotal,
        float $netTotalAmount,
        float $discount,
        float $totalPayable,
    ): void {

        if (!isset($user->email)) {
            return;
        }

        dispatch(
            new SendSubscriptionAddShopInvoiceMailQueueJob(
                user: $user,
                increasedShopCount: $increasedShopCount,
                pricePerShop: $pricePerShop,
                pricePeriod: $pricePeriod,
                pricePeriodCount: $pricePeriodCount,
                subtotal: $subtotal,
                netTotalAmount: $netTotalAmount,
                discount: $discount,
                totalPayable: $totalPayable,
            )
        );
    }

    public function sendSubscriptionShopRenewInvoiceMail(?object $user, array $data): void
    {
        if (!isset($user->email)) {
            return;
        }

        dispatch(new SendSubscriptionShopRenewInvoiceMainQueueJob(user: $user, data: $data));
    }

    public function sendSubscriptionAddBusinessInvoiceMail(?object $user, array $data): void
    {
        if (!isset($user->email)) {
            return;
        }

        dispatch(new SendSubscriptionAddBusinessInvoiceMailQueueJob(user: $user, data: $data));
    }
}
