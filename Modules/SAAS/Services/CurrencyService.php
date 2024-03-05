<?php
namespace Modules\SAAS\Services;

use Modules\SAAS\Entities\Currency;
use Modules\SAAS\Interfaces\CurrencyServiceInterface;

class CurrencyService implements CurrencyServiceInterface
{
    public function currencies(array $with = null): object
    {
        $query = Currency::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
