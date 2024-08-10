<?php

namespace App\Services\Setups;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Setups\CurrencyRate;
use Yajra\DataTables\Facades\DataTables;

class CurrencyRateService
{
    public function currencyRatesTable(int $currencyId, object $request): object
    {
        $generalSettings = config('generalSettings');
        $currencyRates = null;
        $query = DB::table('currency_rates')
            ->where('currency_rates.currency_id', $currencyId)
            ->leftJoin('currencies', 'currency_rates.currency_id', 'currencies.id');

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('currency_rates.date_ts', $date_range); // Final
        }

        $currencyRates = $query->select(
            'currency_rates.id',
            'currency_rates.currency_id',
            'currency_rates.rate',
            'currency_rates.type',
            'currency_rates.date_ts',
            'currencies.currency as currency_name',
            'currencies.code as currency_code',
        )->orderBy('date_ts', 'desc');

        return DataTables::of($currencyRates)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                $html .= '<a href="' . route('currencies.rates.edit', [$row->id]) . '" class="action-btn c-edit" id="editCurrencyRate" title="Edit"><span class="fas fa-edit"></span></a>';

                $html .= '<a href="' . route('currencies.rates.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteCurrencyRate" title="Delete"><span class="fas fa-trash "></span></a>';

                $html .= '</div>';

                return $html;
            })

            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = $generalSettings['business_or_shop__date_format'];
                $__time_format = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

                return date($__date_format . ' ' . $__time_format, strtotime($row->date_ts));
            })

            ->addColumn('rate', function ($row) use ($generalSettings) {

                if ($row->type == 1) {

                    return __('1') . ' ' . $row?->currency_name . ' = ' . $row->rate . '  ' . $generalSettings['base_currency_name'];
                }else{

                    return __('1') . ' ' . $generalSettings['base_currency_name'] . ' = ' . $row->rate . '  ' . $row?->currency_name;
                }
            })

            ->addColumn('created_by', function ($row) {

                return __('N/A');
            })

            ->rawColumns(['date', 'rate', 'created_by', 'action'])->make(true);
    }

    public function addCurrencyRate(int $currencyId, ?float $currencyRate = null, ?int $currencyType = null, ?string $currencyRateDate = null): void
    {
        if (isset($currencyRate)) {

            $addCurrencyRate = new CurrencyRate();
            $addCurrencyRate->currency_id = $currencyId;
            $addCurrencyRate->rate = $currencyRate;
            $addCurrencyRate->type = $currencyType ? $currencyType : 1;
            $addCurrencyRate->date_ts = isset($currencyRateDate) ? date('Y-m-d H:i:s', strtotime($currencyRateDate . date(' H:i:s'))) : date('Y-m-d H:i:s');
            $addCurrencyRate->save();
        }
    }

    public function updateCurrencyRate(?int $id, ?int $currencyId, ?float $currencyRate = null, ?int $currencyType = null, ?string $currencyRateDate = null): void
    {
        $updateCurrencyRate = $this->singleCurrencyRate(id: $id);
        if (isset($updateCurrencyRate)) {

            $updateCurrencyRate->rate = isset($currencyRate) ? $currencyRate : 0;
            $updateCurrencyRate->type = $currencyType ? $currencyType : 1;
            $time = date(' H:i:s', strtotime($updateCurrencyRate->date_ts));
            $updateCurrencyRate->date_ts = isset($currencyRateDate) ? date('Y-m-d H:i:s', strtotime($currencyRateDate . $time)) : date('Y-m-d H:i:s');
            $updateCurrencyRate->save();
        } elseif (isset($currencyRate)) {

            $this->addCurrencyRate(currencyId: $currencyId, currencyRate: $currencyRate, currencyType: $currencyType, currencyRateDate: $currencyRateDate);
        }
    }

    function deleteCurrencyRate(?int $id): object
    {
        $deleteCurrencyRate = $this->singleCurrencyRate(id: $id);
        if (isset($deleteCurrencyRate)) {

            $deleteCurrencyRate->delete();
        }

        return $deleteCurrencyRate;
    }

    public function singleCurrencyRate(?int $id, ?array $with = null)
    {
        $query = CurrencyRate::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
