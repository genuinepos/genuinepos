<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\CurrencyService;
use App\Http\Requests\Setups\CurrencyEditRequest;
use App\Http\Requests\Setups\CurrencyIndexRequest;
use App\Http\Requests\Setups\CurrencyStoreRequest;
use App\Http\Requests\Setups\CurrencyCreateRequest;
use App\Http\Requests\Setups\CurrencyDeleteRequest;
use App\Http\Requests\Setups\CurrencyUpdateRequest;

class CurrencyController extends Controller
{
    public function __construct(private CurrencyService $currencyService)
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
        $this->currencyService->addCurrency(request: $request);
        return response()->json(__('Currency created successfully.'));
    }

    public function edit($id, CurrencyEditRequest $request)
    {
        $currency = $this->currencyService->singleCurrency(id: $id);

        return view('setups.currencies.ajax_view.edit', compact('currency'));
    }

    public function update($id, CurrencyUpdateRequest $request)
    {
        $this->currencyService->updateCurrency(id: $id, request: $request);
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
