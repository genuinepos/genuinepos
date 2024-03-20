<?php

namespace Modules\SAAS\Interfaces;

interface CouponServiceInterface
{
    public function addCoupon(object $request): void;
    public function updateCoupon(object $request, int $id): void;
    public function deleteCoupon(int $id): array;
    public function singleCouponByCode(string $code, ?array $with = null): ?object;
    public function singleCouponById(int $id, ?array $with = null): ?object;
    public function coupons(?array $with = null): object;
}
