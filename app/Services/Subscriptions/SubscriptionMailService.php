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
    public function sendPlanUpgradeSuccessMain(object $user): void
    {
        dispatch(new SendSubscriptionPlanUpgradeMailQueueJob(to: $user->email, user: $user));

        if ($user->id != 1) {

            $superadmin = (new \App\Services\Users\UserService())->singleUser(id: 1);
            dispatch(new SendSubscriptionPlanUpgradeMailQueueJob(to: $superadmin->email, user: $superadmin));
        }
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

        $superadmin = (new \App\Services\Users\UserService())->singleUser(id: 1);
        $prepareUser = (object) null;
        if (!isset($user)) {

            $prepareUser->email = isset($superadmin) ? $superadmin->email : null;
            $prepareUser->phone = isset($superadmin) ? $superadmin->phone : null;
            $prepareUser->address = isset($superadmin) ? $superadmin->present_address : null;
            $prepareUser->name = isset($superadmin) ? $superadmin->prefix . ' ' . $superadmin->name . ' ' . $superadmin->last_name : null;
        } else {

            $prepareUser = $user;
            $prepareUser->email = isset($user->email) ? $user->email : (isset($superadmin) ? $superadmin->email : null);
        }

        if (!isset($prepareUser->email)) {
            return;
        }

        dispatch(
            new SendSubscriptionAddShopInvoiceMailQueueJob(
                user: $prepareUser,
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

    public function sendSubscriptionShopRenewInvoiceMail(?object $user, array $data): void {

        $superadmin = (new \App\Services\Users\UserService())->singleUser(id: 1);
        $prepareUser = (object) null;
        if (!isset($user)) {

            $prepareUser->email = isset($superadmin) ? $superadmin->email : null;
            $prepareUser->phone = isset($superadmin) ? $superadmin->phone : null;
            $prepareUser->address = isset($superadmin) ? $superadmin->present_address : null;
            $prepareUser->name = isset($superadmin) ? $superadmin->prefix . ' ' . $superadmin->name . ' ' . $superadmin->last_name : null;
        } else {

            $prepareUser = $user;
            $prepareUser->email = isset($user->email) ? $user->email : (isset($superadmin) ? $superadmin->email : null);
        }

        if (!isset($prepareUser->email)) {
            return;
        }

        dispatch(new SendSubscriptionShopRenewInvoiceMainQueueJob(user: $prepareUser, data: $data));
    }

    public function sendSubscriptionAddBusinessInvoiceMail(?object $user, array $data): void {

        $superadmin = (new \App\Services\Users\UserService())->singleUser(id: 1);
        $prepareUser = (object) null;
        if (!isset($user)) {

            $prepareUser->email = isset($superadmin) ? $superadmin->email : null;
            $prepareUser->phone = isset($superadmin) ? $superadmin->phone : null;
            $prepareUser->address = isset($superadmin) ? $superadmin->present_address : null;
            $prepareUser->name = isset($superadmin) ? $superadmin->prefix . ' ' . $superadmin->name . ' ' . $superadmin->last_name : null;
        } else {

            $prepareUser = $user;
            $prepareUser->email = isset($user->email) ? $user->email : (isset($superadmin) ? $superadmin->email : null);
        }

        if (!isset($prepareUser->email)) {
            return;
        }

        dispatch(new SendSubscriptionAddBusinessInvoiceMailQueueJob(user: $prepareUser, data: $data));
    }
}
