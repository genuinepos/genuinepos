<?php

namespace App\Services\Setups;

use App\Enums\BooleanType;
use App\Models\Setups\Currency;
use Yajra\DataTables\Facades\DataTables;

class CurrencyService
{
    public function currenciesTable()
    {
        $generalSettings = config('generalSettings');
        $currencies = Currency::with(['isBaseCurrency'])->orderBy('country');

        return DataTables::of($currencies)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                if (auth()->user()->can('currencies_index') && config('generalSettings')['subscription']->has_business == BooleanType::True->value) {

                    $html .= '<a href="' . route('currencies.rates.index', $row->id) . '" class="dropdown-item">' . __('Manage Rate') . '</a>';
                }

                if (auth()->user()->can('currencies_create') && config('generalSettings')['subscription']->has_business == BooleanType::True->value) {

                    $html .= '<a href="' . route('currencies.rates.create', $row->id) . '" class="dropdown-item" id="addCurrencyRate">' . __('Add Rate') . '</a>';
                }

                if (auth()->user()->can('currencies_edit')) {

                    $html .= '<a href="' . route('currencies.edit', $row->id) . '" class="dropdown-item" id="editCurrency">' . __('Edit') . '</a>';
                }

                if (auth()->user()->can('currencies_delete')) {

                    $html .= '<a href="' . route('currencies.delete', $row->id) . '" class="dropdown-item" id="deleteCurrency">' . __('Delete') . '</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->addColumn('currency_rate', function ($row) use ($generalSettings) {

                if (isset($row->currency_rate) && $row->currency_rate != 0) {

                    return __('1') . ' ' . $row?->code . ' = ' . $row->currency_rate . '  ' . session('base_currency_symbol');
                }
            })
            ->addColumn('symbol', function ($row) {

                $isBaseCurrency = $row?->isBaseCurrency ? ' <span class="fw-bold text-success">' . __('Base Currency') . '</span>' : '';
                return $row->symbol . $isBaseCurrency;
            })
            ->rawColumns(['symbol', 'action'])->make(true);
    }

    public function addCurrency(object $request): object
    {
        $addCurrency = new Currency();
        $addCurrency->country = $request->country;
        $addCurrency->currency = $request->currency;
        $addCurrency->code = $request->code;
        $addCurrency->symbol = $request->symbol;
        $addCurrency->currency_rate = $request->currency_rate;
        $addCurrency->save();

        return $addCurrency;
    }

    public function updateCurrency(int $id, object $request): object
    {
        $updateCurrency = $this->singleCurrency(id: $id, with: ['currentCurrencyRate']);
        $updateCurrency->country = $request->country;
        $updateCurrency->currency = $request->currency;
        $updateCurrency->code = $request->code;
        $updateCurrency->symbol = $request->symbol;
        $updateCurrency->currency_rate = $request->currency_rate;
        $updateCurrency->save();

        return $updateCurrency;
    }

    public function updateCurrentCurrencyRate($id): void
    {
        $updateCurrentCurrencyRate = $this->singleCurrency(id: $id, with: ['currentCurrencyRate']);
        $updateCurrentCurrencyRate->currency_rate = $updateCurrentCurrencyRate?->currentCurrencyRate?->rate;
        $updateCurrentCurrencyRate->save();
    }

    public function deleteCurrency(int $id): array
    {
        $deleteCurrency = $this->singleCurrency(id: $id, with: ['assignedBranches']);

        if (isset($deleteCurrency)) {

            if (count($deleteCurrency->assignedBranches) > 0) {

                return ['pass' => false, 'msg' => __('Currency can not be deleted. This currency has already been assigned.')];
            }

            $deleteCurrency->delete();
        }

        return ['pass' => true];
    }

    public function singleCurrency(int $id, array $with = null)
    {
        $query = Currency::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function currencies()
    {
        return Currency::orderBy('country')->get();
    }
}
