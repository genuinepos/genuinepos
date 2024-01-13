<?php

namespace App\Services\Setups;

use App\Models\Setups\BarcodeSetting;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BarcodeSettingService
{
    public function barcodeSettingsTable(): ?object
    {
        $barcodeSettings = DB::table('barcode_settings')->where('is_fixed', 0)->orderBy('id', 'DESC')->get(['id', 'name', 'description', 'is_default']);

        return DataTables::of($barcodeSettings)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {

                return $row->name . ' ' . ($row->is_default == 1 ? '<span class="badge bg-primary">' . __('Default') . '</span>' : '');
            })
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';
                $html .= '<a href="' . route('barcode.settings.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';

                if ($row->is_default == 0) {

                    $html .= '<a href="' . route('barcode.settings.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                    $html .= '<a href="' . route('barcode.settings.set.default', [$row->id]) . '" class="bg-primary text-white rounded pe-1" id="set_default_btn"> ' . __('Set As Default') . '</a>';
                }

                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['action', 'name'])
            ->make(true);
    }

    public function addBarcodeSetting($request): void
    {
        if (isset($request->set_as_default)) {

            $defaultBarcodeSetting = $this->singleBarcodeSetting(customColName: 'is_default', customColValue: 1);

            if ($defaultBarcodeSetting) {

                $defaultBarcodeSetting->is_default = 0;
                $defaultBarcodeSetting->save();
            }
        }

        BarcodeSetting::insert([
            'name' => $request->name,
            'description' => $request->description,
            'is_continuous' => isset($request->is_continuous) ? 1 : 0,
            'top_margin' => $request->top_margin,
            'left_margin' => $request->left_margin,
            'sticker_width' => $request->sticker_width,
            'sticker_height' => $request->sticker_height,
            'paper_width' => $request->paper_width,
            'paper_height' => $request->paper_height,
            'row_distance' => $request->row_distance,
            'column_distance' => $request->column_distance,
            'stickers_in_a_row' => $request->stickers_in_a_row,
            'stickers_in_one_sheet' => $request->stickers_in_one_sheet,
            'is_default' => isset($request->set_as_default) ? 1 : 0,
        ]);

        $barcodeSettingCount = BarcodeSetting::count();

        if ($barcodeSettingCount == 1) {

            $barcodeSetting = $this->singleBarcodeSetting();
            $barcodeSetting->is_default = 1;
            $barcodeSetting->save();
        }
    }

    public function updateBarcodeSetting(int $id, object $request): void
    {
        if (isset($request->set_as_default)) {

            $defaultBarcodeSetting = $this->singleBarcodeSetting(id: $id, customColName: 'is_default', customColValue: 1);

            if ($defaultBarcodeSetting) {

                $defaultBarcodeSetting->is_default = 0;
                $defaultBarcodeSetting->save();
            }
        }

        $updateBs = $this->singleBarcodeSetting(id: $id);

        $updateBs->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_continuous' => isset($request->is_continuous) ? 1 : 0,
            'top_margin' => $request->top_margin,
            'left_margin' => $request->left_margin,
            'sticker_width' => $request->sticker_width,
            'sticker_height' => $request->sticker_height,
            'paper_height' => $request->paper_height,
            'paper_width' => $request->paper_width,
            'row_distance' => $request->row_distance,
            'column_distance' => $request->column_distance,
            'stickers_in_a_row' => $request->stickers_in_a_row,
            'stickers_in_one_sheet' => $request->stickers_in_one_sheet,
            'is_default' => isset($request->set_as_default) ? 1 : 0,
        ]);
    }

    public function deleteBarcodeSetting(int $id): void
    {
        $deleteBarcodeSetting = $this->singleBarcodeSetting(id: $id);

        if (!is_null($deleteBarcodeSetting)) {

            $deleteBarcodeSetting->delete();
        }
    }

    public function setAsDefaultBarcodeSetting(int $id): void
    {
        $defaultBs = $this->singleBarcodeSetting(customColName: 'is_default', customColValue: 1);

        if ($defaultBs) {

            $defaultBs->is_default = 0;
            $defaultBs->save();
        }

        $updateBs = $this->singleBarcodeSetting(id: $id);
        $updateBs->is_default = 1;
        $updateBs->save();
    }

    public function singleBarcodeSetting(
        int $id = null,
        string $customColName = null,
        string|int $customColValue = null,
        array $with = null
    ): ?object {

        $query = BarcodeSetting::query();

        if (isset($with)) {

            $query->with($with);
        }

        if (isset($id)) {

            $query->where('id', $id);
        }

        if (isset($customColName)) {

            $query->where($customColName, $customColValue);
        }

        return $query->first();
    }

    public function barcodeSettings(array $with = null): ?object
    {
        $query = BarcodeSetting::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
