<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\CurrencyRateService;
use App\Http\Requests\Setups\CurrencyRateEditRequest;
use App\Http\Requests\Setups\CurrencyRateIndexRequest;
use App\Http\Requests\Setups\CurrencyRateStoreRequest;
use App\Http\Requests\Setups\CurrencyRateCreateRequest;
use App\Http\Requests\Setups\CurrencyRateDeleteRequest;
use App\Http\Requests\Setups\CurrencyRateUpdateRequest;

class CurrencyRateController extends Controller
{
    public function __construct(private CurrencyService $currencyService, private CurrencyRateService $currencyRateService)
    {
    }

    public function index($currencyId, CurrencyRateIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->currencyRateService->currencyRatesTable(currencyId: $currencyId, request: $request);
        }

        $currency = $this->currencyService->singleCurrency(id: $currencyId);

        return view('setups.currencies.rates.index', compact('currency'));
    }

    public function create($currencyId, CurrencyRateCreateRequest $request)
    {
        $currency = $this->currencyService->singleCurrency(id: $currencyId);

        return view('setups.currencies.rates.ajax_view.create', compact('currency'));
    }

    public function store($currencyId, CurrencyRateStoreRequest $request)
    {
        $this->currencyRateService->addCurrencyRate(currencyId: $currencyId, currencyRate: $request->rate, currencyType: $request->type, currencyRateDate: $request->date);
        $this->currencyService->updateCurrentCurrencyRate(id: $currencyId);

        return response()->json(__('Currency rate added successfully.'));
    }

    public function edit($id, CurrencyRateEditRequest $request)
    {
        $currencyRate = $this->currencyRateService->singleCurrencyRate(id: $id, with: ['currency']);
        return view('setups.currencies.rates.ajax_view.edit', compact('currencyRate'));
    }

    public function update($id, CurrencyRateUpdateRequest $request)
    {
        $this->currencyRateService->updateCurrencyRate(id: $id, currencyId: $request->currency_id, currencyRate: $request->rate, currencyType: $request->type, currencyRateDate: $request->date);
        $this->currencyService->updateCurrentCurrencyRate(id: $request->currency_id);

        return response()->json(__('Currency rate updated successfully.'));
    }

    public function delete($id, CurrencyRateDeleteRequest $request)
    {
        $deleteCurrencyRate = $this->currencyRateService->deleteCurrencyRate(id: $id);
        $this->currencyService->updateCurrentCurrencyRate(id: $deleteCurrencyRate->currency_id);

        return response()->json(__('Currency rate deleted successfully.'));
    }
}
