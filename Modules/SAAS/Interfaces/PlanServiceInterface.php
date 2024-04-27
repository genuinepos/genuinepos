<?php

namespace Modules\SAAS\Interfaces;

interface PlanServiceInterface
{
    /**
     * @return \Modules\SAAS\Services\PlanService
     */

    public function storePlan(object $request): ?array;
    public function updatePlan(int $id, object $request): ?array;
    public function trialPlan(array $with = null): ?object;
    public function singlePlanById(int $id, array $with = null): ?object;
    public function deletePlan(int $id): array;
    public function plans(array $with = null): object;
}
