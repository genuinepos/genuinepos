<?php

namespace App\Http\Controllers\Setups;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\CurrencyRateService;
use App\Http\Requests\Setups\CurrencyEditRequest;
use App\Http\Requests\Setups\CurrencyIndexRequest;
use App\Http\Requests\Setups\CurrencyStoreRequest;
use App\Http\Requests\Setups\CurrencyCreateRequest;
use App\Http\Requests\Setups\CurrencyDeleteRequest;
use App\Http\Requests\Setups\CurrencyUpdateRequest;

class CurrencyController extends Controller
{
    public function __construct(private CurrencyService $currencyService, private CurrencyRateService $currencyRateService)
    {
    }

    public function index(CurrencyIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->currencyService->currenciesTable();
        }

        return view('setups.currencies.index');
    }

    public function create(CurrencyCreateRequest $request)
    {
        return view('setups.currencies.ajax_view.create');
    }

    public function store(CurrencyStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $addCurrency = $this->currencyService->addCurrency(request: $request);

            if (config('generalSettings')['subscription__has_business'] == BooleanType::True->value) {

                $this->currencyRateService->addCurrencyRate(currencyId: $addCurrency->id, currencyRate: $request->currency_rate, date: $request->currency_rate_date);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Currency created successfully.'));
    }

    public function edit($id, CurrencyEditRequest $request)
    {
        $currency = $this->currencyService->singleCurrency(id: $id, with: ['currentCurrencyRate']);
        return view('setups.currencies.ajax_view.edit', compact('currency'));
    }

    public function update($id, CurrencyUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $updateCurrency = $this->currencyService->updateCurrency(id: $id, request: $request);

            if (config('generalSettings')['subscription__has_business'] == BooleanType::True->value) {

                $this->currencyRateService->updateCurrencyRate(id: $updateCurrency?->currentCurrencyRate?->id, currencyId: $updateCurrency->id, currencyRate: $request->currency_rate, currencyRateDate: $request->currency_rate_date);

                $this->currencyService->updateCurrentCurrencyRate(id: $updateCurrency->id);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Currency updated successfully.'));
    }

    public function delete($id, CurrencyDeleteRequest $request)
    {
        $deleteCurrency = $this->currencyService->deleteCurrency(id: $id);

        if ($deleteCurrency['pass'] == false) {

            return response()->json(['errorMsg' => $deleteCurrency['msg']]);
        }

        return response()->json(__('Currency deleted successfully.'));
    }
}
