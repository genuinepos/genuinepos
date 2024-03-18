<?php

namespace Modules\SAAS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SAAS\Entities\Coupon;
use Modules\SAAS\Http\Requests\CouponStoreRequest;
use Modules\SAAS\Http\Requests\CouponUpdateRequest;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */

    public function index(Request $request)
    {
        // $this->authorize('users_index');

        $coupons = Coupon::query()->orderBy('created_at', 'desc');

        if ($request->ajax()) {
            return DataTables::of($coupons)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';

                    $html .= '<a href="' . route('saas.coupons.edit', $row->id) . '" class="px-2 edit-btn btn btn-primary btn-sm text-white" title="Edit"><span class="fas fa-edit pe-1"></span>' . __("Edit") . '</a>';

                    $html .= '<a href="' . route('saas.coupons.destroy', $row->id) . '" class="px-2 trash-btn btn btn-danger btn-sm text-white ms-2" id="trashUser" title="Trash"><span class="fas fa-trash pe-1"></span>' . __("Trash") . '</a>';
                    $html .= '</div>';

                    return $html;
                })->make(true);
        }

        return view('saas::coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */

    public function create()
    {
        //$this->authorize('users_create');

        return view('saas::coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */

    public function store(CouponStoreRequest $request)
    {
        $data = [
            'code' => $request->get('code'),
            'start_date' => date('Y-m-d', strtotime($request->get('start_date'))),
            'end_date' => date('Y-m-d', strtotime($request->get('end_date'))),
            'percent' => $request->get('percent'),
            'is_minimum_purchase' => $request->get('is_minimum_purchase'),
            'purchase_price' => $request->get('purchase_price'),
            'is_maximum_usage' => $request->get('is_maximum_usage'),
            'no_of_usage' => $request->get('no_of_usage'),
        ];

        Coupon::create($data);

        return redirect()->route('saas.coupons.index')->with('success', 'Coupon has been created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */

    public function edit(Coupon $coupon)
    {

        return view('saas::coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */

    public function update(CouponUpdateRequest $request, Coupon $coupon)
    {
        // $this->authorize('users_update');

        $data = [
            'code' => $request->get('code'),
            'start_date' => date('Y-m-d', strtotime($request->get('start_date'))),
            'end_date' => date('Y-m-d', strtotime($request->get('end_date'))),
            'percent' => $request->get('percent'),
            'is_minimum_purchase' => $request->get('is_minimum_purchase'),
            'purchase_price' => $request->get('purchase_price'),
            'is_maximum_usage' => $request->get('is_maximum_usage'),
            'no_of_usage' => $request->get('no_of_usage'),
        ];

        $coupon->update($data);

        return redirect()->route('saas.coupons.index')->with('success', 'Coupon has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */

    public function destroy(Coupon $coupon)
    {

        if ($coupon->no_of_used > 0) {

            return redirect()->route('saas.coupons.index')->with('error', 'Coupon already used you can not delete this');
        }
        // $this->authorize('users_destroy');
        $coupon->delete();

        return redirect()->route('saas.coupons.index')->with('success', 'Coupon has been deleted successfully');
    }
}
