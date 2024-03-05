<?php

namespace Modules\SAAS\Interfaces;

interface CurrencyServiceInterface
{
    /**
     * @return \Modules\SAAS\Services\CurrencyService
     */

    public function currencies(array $with = null): object;
}
