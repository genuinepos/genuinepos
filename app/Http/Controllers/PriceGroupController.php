<?php

namespace App\Http\Controllers;

use App\Models\PriceGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PriceGroupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $price_groups = DB::table('price_groups')->get(['id', 'name', 'description', 'status']);
            return DataTables::of($price_groups)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('product.selling.price.groups.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('product.selling.price.groups.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';

                    // if ($row->status == "Active") {
                    //     $html .= '<a href="'.route('product.selling.price.groups.change.status', [$row->id]).'" class="btn btn-sm btn-danger ms-1" id="change_status">Deactivate</a>';
                    // }else {
                    //     $html .= '<a href="'.route('product.selling.price.groups.change.status', [$row->id]).'" class="btn btn-sm btn-info text-white ms-1" id="change_status">Active</a>';
                    // }

                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == "Active") {
                        $html = '<div class="form-check form-switch">';
                        $html .= '<input class="form-check-input"  id="change_status" data-url="' . route('product.selling.price.groups.change.status', [$row->id]) . '" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked />';
                        $html .= '</div>';
                        return $html;
                    }else {
                        $html = '<div class="form-check form-switch">';
                        $html .= '<input class="form-check-input" id="change_status" data-url="' . route('product.selling.price.groups.change.status', [$row->id]) . '" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" />';
                        $html .= '</div>';
                        return $html;
                    }

                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('product.price_group.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:price_groups,name',
        ]);

        PriceGroup::insert([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json('Price group created Successfully');
    }

    public function edit($id)
    {
        $pg = DB::table('price_groups')->where('id', $id)->first();
        return view('product.price_group.ajax_view.edit', compact('pg'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:price_groups,name,'.$id,
        ]);
        $updatePg = PriceGroup::where('id', $id)->first();
        $updatePg->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json('Price group updated Successfully');
    }

    public function delete(Request $request, $id)
    {
        $delete = PriceGroup::find($id);
        if (!is_null($delete)) {
            $delete->delete();
        }
        return response()->json('Price group delete Successfully.');
    }

    public function changeStatus($id)
    {
        $statusChange = PriceGroup::where('id', $id)->first();
        if ($statusChange->status == 'Active') {
            $statusChange->status = 'Deactivate';
            $statusChange->save();
            return response()->json('Successfully Price group is deactivated');
        } else {
            $statusChange->status = 'Active';
            $statusChange->save();
            return response()->json('Successfully Price group is activated');
        }
    }
}
