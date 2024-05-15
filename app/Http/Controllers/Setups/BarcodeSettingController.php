<?php

namespace App\Http\Controllers\Setups;

use App\Http\Controllers\Controller;
use App\Services\Setups\BarcodeSettingService;
use Illuminate\Http\Request;

class BarcodeSettingController extends Controller
{
    public function __construct(
        private BarcodeSettingService $barcodeSettingService,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('barcode_settings')) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            return $this->barcodeSettingService->barcodeSettingsTable();
        }

        return view('setups.barcode_settings.index');
    }

    public function create()
    {
        return view('setups.barcode_settings.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'top_margin' => 'required',
            'left_margin' => 'required',
            'sticker_width' => 'required',
            'sticker_height' => 'required',
            'paper_width' => 'required',
            'paper_height' => 'required',
            'row_distance' => 'required',
            'column_distance' => 'required',
            'stickers_in_a_row' => 'required',
            'stickers_in_one_sheet' => 'required',
        ]);

        $addBarcodeSetting = $this->barcodeSettingService->addBarcodeSetting($request);

        return response()->json(__('Barcode sticker setting created Successfully.'));
    }

    public function edit($id)
    {
        $bs = $this->barcodeSettingService->singleBarcodeSetting(id: $id);

        return view('setups.barcode_settings.edit', compact('bs'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'top_margin' => 'required',
            'left_margin' => 'required',
            'sticker_width' => 'required',
            'sticker_height' => 'required',
            'paper_width' => 'required',
            'paper_height' => 'required',
            'row_distance' => 'required',
            'column_distance' => 'required',
            'stickers_in_a_row' => 'required',
            'stickers_in_one_sheet' => 'required',
        ]);

        $updateBarcodeSetting = $this->barcodeSettingService->updateBarcodeSetting($id, $request);

        return response()->json(__('Barcode sticker setting updated Successfully.'));
    }

    public function delete(Request $request, $id)
    {
        $this->barcodeSettingService->deleteBarcodeSetting($id);

        return response()->json(__('Barcode sticker setting deleted Successfully.'));
    }

    public function setDefault($id)
    {
        $this->barcodeSettingService->setAsDefaultBarcodeSetting(id: $id);

        return response()->json(__('Default set successfully'));
    }

    public function designPage()
    {
        return view('setups.barcode_settings.design_pages');
    }
}
