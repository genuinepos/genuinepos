<?php

namespace App\Http\Controllers;

use App\Models\LoanCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LoanCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $companies = DB::table('loan_companies')->orderBy('id', 'DESC')->get();
            $generalSettings = DB::table('general_settings')->first();
            return DataTables::of($companies)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="' . route('accounting.loan.companies.edit', [$row->id]) . '" id="edit_company"><i class="far fa-edit text-primary"></i> Edit</a>';
                    if ($row->pay_loan_due > 0) {
                        $html .= '<a class="dropdown-item" id="loan_payment" href="' . route('accounting.loan.payment.due.receive.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Due Amount</a>';
                    }

                    if ($row->get_loan_due > 0) {
                        $html .= '<a class="dropdown-item" id="loan_payment" href="' . route('accounting.loan.payment.due.pay.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Pay Due Amount</a>';
                    }

                    $html .= '<a class="dropdown-item" id="delete_company" href="' . route('accounting.loan.companies.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })->editColumn('pay_loan_amount', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->pay_loan_amount . '<br/>Due : ' . json_decode($generalSettings->business, true)['currency'] . ' <span class="text-danger">' . $row->pay_loan_due . '</span>';
                })->editColumn('get_loan_amount', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->get_loan_amount . '<br/>Due : ' . json_decode($generalSettings->business, true)['currency'] . ' <span class="text-danger">' . $row->get_loan_due . '</span>';
                })->editColumn('total_pay', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_pay;
                })->editColumn('total_receive', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_receive;
                })
                ->rawColumns(['pay_loan_amount', 'get_loan_amount', 'action'])->smart(true)->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $addCompany = new LoanCompany();
        $addCompany->name = $request->name;
        $addCompany->save();

        return response()->json('Company created Successfully');
    }

    public function edit($companyId)
    {
        $company = DB::table('loan_companies')->where('id', $companyId)->first();
        return view('accounting.loans.ajax_view.editCompany', compact('company'));
    }

    public function update(Request $request, $companyId)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $updateCompany =  LoanCompany::where('id', $companyId)->first();
        $updateCompany->name = $request->name;
        $updateCompany->save();
        return response()->json('Company updated Successfully');
    }

    public function delete(Request $request, $companyId)
    {
        $deleteCompany = LoanCompany::find($companyId);
        if (!is_null($deleteCompany)) {
            $deleteCompany->delete();
        }

        return response()->json('Company deleted Successfully');
    }
}
