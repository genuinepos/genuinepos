<?php

namespace App\Services\Setups;

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

                $html = '<div class="dropdown table-dropdown">';

                $html .= '<a href="' . route('currencies.edit', [$row->id]) . '" class="action-btn c-edit" id="editCurrency" title="' . __("Edit") . '"><span class="fas fa-edit"></span></a>';

                $html .= '<a href="' . route('currencies.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteCurrency" title="' . __("Delete") . '"><span class="fas fa-trash"></span></a>';

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

    public function addCurrency(object $request): void
    {
        $addCurrency = new Currency();
        $addCurrency->country = $request->country;
        $addCurrency->currency = $request->currency;
        $addCurrency->code = $request->code;
        $addCurrency->symbol = $request->symbol;
        $addCurrency->currency_rate = $request->currency_rate;
        $addCurrency->save();
    }

    public function updateCurrency(int $id, object $request): void
    {
        $updateCurrency = $this->singleCurrency(id: $id);
        $updateCurrency->country = $request->country;
        $updateCurrency->currency = $request->currency;
        $updateCurrency->code = $request->code;
        $updateCurrency->symbol = $request->symbol;
        $updateCurrency->currency_rate = $request->currency_rate;
        $updateCurrency->save();
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
