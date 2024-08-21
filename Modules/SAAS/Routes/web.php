<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\SAAS\Http\Controllers\PlanController;
use Modules\SAAS\Http\Controllers\RoleController;
use Modules\SAAS\Http\Controllers\UserController;
use Modules\SAAS\Http\Controllers\LoginController;

use Modules\SAAS\Http\Controllers\CouponController;
use Modules\SAAS\Http\Controllers\TenantController;
use Modules\SAAS\Http\Controllers\ProfileController;
use Modules\SAAS\Http\Controllers\DashboardController;
use Modules\SAAS\Http\Controllers\Guest\TrialController;
use Modules\SAAS\Http\Controllers\UpgradePlanController;
use Modules\SAAS\Http\Controllers\RegistrationController;
use Modules\SAAS\Http\Controllers\EmailSettingsController;
use Modules\SAAS\Http\Controllers\Guest\SendEmailController;
use Modules\SAAS\Http\Controllers\Guest\PlanSelectController;
use Modules\SAAS\Http\Controllers\Auth\VerificationController;
use Modules\SAAS\Http\Controllers\Guest\GuestTenantController;
use Modules\SAAS\Http\Controllers\Guest\PlanConfirmController;
use Modules\SAAS\Http\Controllers\DomainAvailabilityController;
use Modules\SAAS\Http\Controllers\UpdatePaymentStatusController;
use Modules\SAAS\Http\Controllers\BusinessVerificationController;
use Modules\SAAS\Http\Controllers\Guest\CheckCouponCodeController;
use Modules\SAAS\Http\Controllers\Guest\DeleteFailedTenantController;
use Modules\SAAS\Http\Controllers\UserSubscriptionTransactionController;

Route::get('saas-test', function () {

    $subscriptionService = new \App\Services\Subscriptions\SubscriptionService();
    return $subscriptionService->test(tenantId: 'businesswithshop');
});

Route::get('welcome', fn () => Auth::check() ? redirect()->route('saas.dashboard') : redirect()->route('saas.login.showForm'))->name('welcome-page');
// Route::get('welcome', fn() => view('saas::guest.welcome-page'))->name('welcome-page');

// Guest User
Route::middleware('is_guest')->group(function () {
    // Route::get('register', [RegistrationController::class, 'showForm'])->name('register.showForm');
    // Route::post('register', [RegistrationController::class, 'register'])->name('register');
    Route::get('login', [LoginController::class, 'showForm'])->name('login.showForm');
    Route::post('login', [LoginController::class, 'login'])->name('login');
});

Route::get('/business-verification', [BusinessVerificationController::class, 'index'])->name('business-verification.index');
Route::post('/business-verification/send', [BusinessVerificationController::class, 'send'])->name('business-verification.send');
Route::get('/business-verification/{hash}/verify', [BusinessVerificationController::class, 'verify'])->name('business-verification.verify');

Route::get('domain/checkAvailability', [DomainAvailabilityController::class, 'checkAvailability'])->name('domain.checkAvailability');
Route::post('delete', [DeleteFailedTenantController::class, 'delete'])->name('delete.failed.tenant.destroy');

Route::controller(TrialController::class)->prefix('guest/trial')->group(function () {

    Route::get('create', 'create')->name('guest.trial.create');
    Route::post('store', 'store')->name('guest.trial.store');
    Route::post('validation', 'validation')->name('guest.trial.validation');
});

Route::controller(PlanConfirmController::class)->prefix('guest/plan-confirm')->group(function () {

    Route::get('create/{slug}/{pricePeriod?}', 'create')->name('guest.plan.confirm.create');
    Route::post('confirm', 'confirm')->name('guest.plan.confirm');
    Route::post('store', 'store')->name('guest.plan.confirm.tenant.store');
    Route::post('validation', 'validation')->name('guest.plan.confirm.validation');
    Route::get('check/coupon/code', 'checkCouponCode')->name('guest.plan.confirm.check.coupon.code');
});

Route::controller(SendEmailController::class)->prefix('guest/email')->group(function () {

    Route::get('send/verification/code', 'sendVerificationCode')->name('guest.email.send.verification.code');
    Route::get('verification/code/match', 'emailVerificationCodeMatch')->name('guest.email.verification.code.match');
});

// Auth User **Not-Verified
Route::middleware('is_auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::post('/email/verify/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
    Route::delete('logout', [LoginController::class, 'logout'])->name('logout');
});

// Auth and Verified
Route::middleware(['is_verified'])->group(function () {

    Route::prefix('dashboard')->group(function () {

        Route::controller(DashboardController::class)->group(function () {

            Route::get('/', 'index')->name('dashboard');
        });
    });

    Route::get('profile/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile/{user}/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('plans', PlanController::class);
    Route::get('plans/single/plan/by/{id}', [PlanController::class, 'singlePlanById'])->name('plans.single.by.id');

    Route::controller(TenantController::class)->prefix('tenants')->group(function () {

        Route::get('/', 'index')->name('tenants.index');
        Route::get('show/{id}', 'show')->name('tenants.show');
        Route::get('create', 'create')->name('tenants.create');
        Route::post('store', 'store')->name('tenants.store');
        Route::get('delete/{id}', 'delete')->name('tenants.delete');
        Route::delete('destroy/{id}', 'destroy')->name('tenants.destroy');

        Route::controller(UpgradePlanController::class)->prefix('upgrade-plan')->group(function () {

            Route::get('cart/{tenantId}', 'cart')->name('tenants.upgrade.plan.cart');
            Route::post('confirm/{tenantId}', 'confirm')->name('tenants.upgrade.plan.confirm');
        });

        Route::controller(UpdateExpireDateController::class)->prefix('update-expire-date')->group(function () {

            Route::get('index/{tenantId}', 'index')->name('tenants.update.expire.date.index');
            Route::post('confirm/{tenantId}', 'confirm')->name('tenants.update.expire.date.confirm');
        });

        Route::controller(UpdatePaymentStatusController::class)->prefix('update-payment-status')->group(function () {

            Route::get('index/{tenantId}', 'index')->name('tenants.update.payment.status.index');
            Route::post('update/{tenantId}', 'update')->name('tenants.update.payment.status.update');
        });

        Route::controller(UserSubscriptionTransactionController::class)->prefix('user-subscription-transactions')->group(function () {

            Route::get('index/{userId?}', 'index')->name('tenants.user.subscription.transaction.index');
            Route::get('pdf/details/{id}', 'pdfDetails')->name('tenants.user.subscription.transaction.pdf.details');
        });
    });

    Route::controller(UserController::class)->prefix('users')->group(function () {

        Route::get('/', 'index')->name('users.index');
        Route::get('create', 'create')->name('users.create');
        Route::post('store', 'store')->name('users.store');
        Route::get('edit/{id}', 'edit')->name('users.edit');
        Route::patch('update/{id}', 'update')->name('users.update');
        Route::delete('delete/{id}', 'delete')->name('users.delete');
    });

    Route::controller(RoleController::class)->prefix('roles')->group(function () {

        Route::get('/', 'index')->name('roles.index');
        Route::get('create', 'create')->name('roles.create');
        Route::post('store', 'store')->name('roles.store');
        Route::get('edit/{id}', 'edit')->name('roles.edit');
        Route::patch('update/{id}', 'update')->name('roles.update');
        Route::delete('delete/{id}', 'delete')->name('roles.delete');
    });

    Route::controller(CouponController::class)->prefix('coupons')->group(function () {

        Route::get('/', 'index')->name('coupons.index');
        Route::get('create', 'create')->name('coupons.create');
        Route::post('store', 'store')->name('coupons.store');
        Route::get('edit/{id}', 'edit')->name('coupons.edit');
        Route::patch('update/{id}', 'update')->name('coupons.update');
        Route::delete('delete/{id}', 'delete')->name('coupons.delete');
        Route::get('code/check', 'checkCouponCode')->name('coupons.code.check');
    });

    //Email Settings Route
    Route::resource('email-settings', EmailSettingsController::class);
});
