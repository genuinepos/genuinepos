<?php

namespace App\Http\Controllers\hrm;

use DateTime;
use Carbon\Carbon;
use App\Models\Account;
use App\Models\CashFlow;
use App\Models\hrm\Payroll;
use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Decimal;
use App\Models\Hrm\PayrollPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\hrm\PayrollAllowance;
use App\Models\hrm\PayrollDeduction;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //Index view of payroll
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $payrolls = '';
            $query = DB::table('hrm_payrolls')
                ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
                ->leftJoin('hrm_department', 'admin_and_users.department_id', 'hrm_department.id')
                ->leftJoin('hrm_designations', 'admin_and_users.designation_id', 'hrm_designations.id')
                ->leftJoin('admin_and_users as created_by', 'hrm_payrolls.admin_id', 'created_by.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('admin_and_users.branch_id', NULL);
                } else {
                    $query->where('admin_and_users.branch_id', $request->branch_id);
                }
            }

            if ($request->user_id) {
                $query->where('hrm_payrolls.user_id', $request->user_id);
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                //$form_date = date('Y-m-d', strtotime($date_range[0]. '-1 days'));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                //$to_date = date('Y-m-d', strtotime($date_range[1]));
                $query->whereBetween('hrm_payrolls.report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
                //$query->whereDate('report_date', '<=', $form_date.' 00:00:00')->whereDate('report_date', '>=', $to_date.' 00:00:00');
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 1) {
                $payrolls = $query->select(
                    'hrm_payrolls.*',
                    'admin_and_users.prefix as emp_prefix',
                    'admin_and_users.name as emp_name',
                    'admin_and_users.last_name as emp_last_name',
                    'admin_and_users.branch_id',
                    'hrm_department.department_name',
                    'hrm_designations.designation_name',
                    'created_by.prefix as user_prefix',
                    'created_by.name as user_name',
                    'created_by.last_name as user_last_name',
                )->get();
            } else {
                $payrolls = $query->select(
                    'hrm_payrolls.*',
                    'admin_and_users.prefix as emp_prefix',
                    'admin_and_users.name as emp_name',
                    'admin_and_users.last_name as emp_last_name',
                    'admin_and_users.branch_id',
                    'hrm_department.department_name',
                    'hrm_designations.designation_name',
                    'created_by.prefix as user_prefix',
                    'created_by.name as user_name',
                    'created_by.last_name as user_last_name',
                )->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
            }


            return DataTables::of($payrolls)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                        </button>';

                    $html .= '<div class="dropdown-menu">';
                    $html .= '<a href="' . route('hrm.payrolls.show', [$row->id]) . '" class="dropdown-item" id="view_payroll"><i class="far fa-eye text-primary"></i> View</a>';

                    $html .= '<a href="' . route('hrm.payrolls.payment.view', [$row->id]) . '" class="dropdown-item" id="view_payment"><i class="far fa-money-bill-alt text-primary"></i> View Payment</a>';

                    if (auth()->user()->branch_id == $row->branch_id) {
                        if ($row->due > 0) {
                            $html .= '<a href="' . route('hrm.payrolls.payment', [$row->id]) . '" class="dropdown-item" id="add_payment"><i class="far fa-money-bill-alt text-primary"></i> Pay Salary</a>';
                        }

                        $html .= '<a href="' . route('hrm.payrolls.edit', [$row->id]) . '" class="dropdown-item" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                        $html .= '<a href="' . route('hrm.payrolls.delete', [$row->id]) . '" class="dropdown-item" id="delete"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('employee', function ($row) {
                    return $row->emp_prefix . ' ' . $row->emp_name . ' ' . $row->emp_last_name;
                })
                ->editColumn('month_year', function ($row) {
                    return $row->month . '/' . $row->year;
                })
                ->editColumn('payment_status', function ($row) {
                    $html = '';
                    if ($row->due <= 0) {
                        $html = '<span class="badge bg-success">Paid</span>';
                    } elseif ($row->due > 0 && $row->due < $row->gross_amount) {
                        $html = '<span class="badge bg-primary text-white">Partial</span>';
                    } elseif ($row->gross_amount == $row->due) {
                        $html = '<span class="badge bg-danger text-white">Due</span>';
                    }
                    return $html;
                })
                ->editColumn('created_by', function ($row) {
                    return $row->user_prefix . ' ' . $row->user_name . ' ' . $row->user_last_name;
                })
                ->rawColumns(['action', 'employee', 'month_year', 'payment_status', 'created_by'])
                ->make(true);
        }

        $departments = DB::table('hrm_department')->get(['id', 'department_name']);
        $employee = DB::table('admin_and_users')->where('branch_id', auth()->user()->branch_id)
            ->get(['id', 'prefix', 'name', 'last_name']);
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('hrm.payroll.index', compact('employee', 'departments', 'branches'));
    }

    // Create payroll
    public function create(Request $request)
    {
        // return  $result = (float)$float + $hour;
        // $number = str_replace(['+', '-'], '', filter_var($a, FILTER_SANITIZE_NUMBER_INT));

        $month_year = explode('-', $request->month_year);
        $year = $month_year[0];
        $dateTime = DateTime::createFromFormat('m', $month_year[1]);
        $month = $dateTime->format("F");

        // return $employee = AdminAndUser::where('id', $request->employee_id)->first();
        $payroll = DB::table('hrm_payrolls')->where('user_id', $request->user_id)->where('month', $month)->where('year', $year)->first();
        if ($payroll) {
            return redirect()->route('hrm.payrolls.edit', $payroll->id);
        }

        $employee = DB::table('admin_and_users')->where('id', $request->user_id)->first();
        $attendances = DB::table('hrm_attendances')->where('user_id', $request->employee_id)
            ->where('month', $month)->where('is_completed', 1)->get();

        $totalHours = 0;
        foreach ($attendances as $attendance) {
            $startTime = Carbon::parse($attendance->clock_in_ts);
            $endTime = Carbon::parse($attendance->clock_out_ts);
            $totalSeconds = $startTime->diffInSeconds($endTime);
            $munites = $totalSeconds / 60;
            $hours = $munites / 60;
            $totalHours += $hours;
            //gmdate('H:i:s', $totalHours);
        }

        $allowances = DB::table('hrm_allowance')->where('type', 'Allowance')->get();
        $deductions = DB::table('hrm_allowance')->where('type', 'Deduction')->get();

        return view('hrm.payroll.create', compact('employee', 'month', 'year', 'totalHours', 'allowances', 'deductions'));
    }

    // Store payroll
    public function store(Request $request)
    {
        $this->validate($request, [
            'amount_per_unit' => 'required',
            'duration_time' => 'required',
            'duration_unit' => 'required',
        ]);

        $i = 4;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $addPayroll = new Payroll();
        $addPayroll->reference_no = 'EP' . date('dmy') . $invoiceId;
        $addPayroll->user_id = $request->user_id;
        $addPayroll->duration_time = $request->duration_time;
        $addPayroll->duration_unit = $request->duration_unit;
        $addPayroll->amount_per_unit = $request->amount_per_unit;
        $addPayroll->total_amount = $request->total_amount;
        $addPayroll->total_allowance_amount = $request->total_allowance_amount;
        $addPayroll->total_deduction_amount = $request->total_deduction_amount;
        $addPayroll->gross_amount = $request->gross_amount;
        $addPayroll->due = $request->gross_amount;
        $addPayroll->report_date_ts = Carbon::now();
        $addPayroll->date = date('d-m-Y');
        $addPayroll->month = $request->month;
        $addPayroll->year = $request->year;
        $addPayroll->admin_id = auth()->user()->id;
        $addPayroll->save();

        $allowance_names = $request->allowance_names;
        $al_amount_types = $request->al_amount_types;
        $allowance_percents = $request->allowance_percents;
        $allowance_amounts = $request->allowance_amounts;
        foreach ($allowance_names as $key => $allowance_name) {
            if ($allowance_amounts[$key] > 0) {
                $addPayrollAllowance = new PayrollAllowance();
                $addPayrollAllowance->payroll_id = $addPayroll->id;
                $addPayrollAllowance->allowance_name = $allowance_name;
                $addPayrollAllowance->amount_type = $al_amount_types[$key];
                $addPayrollAllowance->allowance_percent =  $allowance_percents[$key] ? $allowance_percents[$key] : 0;
                $addPayrollAllowance->allowance_amount = $allowance_amounts[$key] ? $allowance_amounts[$key] : 0;
                $addPayrollAllowance->save();
            }
        }

        $deduction_names = $request->deduction_names;
        $de_amount_types = $request->de_amount_types;
        $deduction_percents = $request->deduction_percents;
        $deduction_amounts = $request->deduction_amounts;

        foreach ($deduction_names as $key => $deduction_name) {
            if ($deduction_amounts[$key] > 0) {
                $addPayrollDeduction = new PayrollDeduction();
                $addPayrollDeduction->payroll_id = $addPayroll->id;
                $addPayrollDeduction->deduction_name = $deduction_name;
                $addPayrollDeduction->amount_type = $de_amount_types[$key];
                $addPayrollDeduction->deduction_percent = $deduction_percents[$key] ? $deduction_percents[$key] : 0;
                $addPayrollDeduction->deduction_amount = $deduction_amounts[$key] ? $deduction_amounts[$key] : 0;
                $addPayrollDeduction->save();
            }
        }

        session()->flash('successMsg', 'Successfully payroll is added');
        return response()->json('Successfully payroll is added');
    }

    // Payroll Edit view
    public function edit($payrollId)
    {
        $payroll = Payroll::with(['employee', 'allowances', 'deductions'])->where('id', $payrollId)->first();
        return view('hrm.payroll.edit', compact('payroll'));
    }

    // salary Update
    public function update(Request $request, $salaryId)
    {
        $this->validate($request, [
            'amount_per_unit' => 'required',
            'duration_time' => 'required',
            'duration_unit' => 'required',
        ]);
        //return $request->all();

        $updatePayroll = Payroll::with(['allowances', 'deductions'])->where('id', $salaryId)->first();
        $updatePayroll->duration_time = $request->duration_time;
        $updatePayroll->duration_unit = $request->duration_unit;
        $updatePayroll->amount_per_unit = $request->amount_per_unit;
        $updatePayroll->total_amount = $request->total_amount;
        $updatePayroll->total_allowance_amount = $request->total_allowance_amount;
        $updatePayroll->total_deduction_amount = $request->total_deduction_amount;
        $updatePayroll->gross_amount = $request->gross_amount;
        $updatePayroll->due = $request->gross_amount - $updatePayroll->paid;
        $updatePayroll->save();

        foreach ($updatePayroll->allowances as $allowance) {
            $allowance->is_delete_in_update = 1;
            $allowance->save();
        }

        foreach ($updatePayroll->deductions as $deduction) {
            $deduction->is_delete_in_update = 1;
            $deduction->save();
        }

        $allowance_id = $request->payroll_allowance_id;
        $allowance_names = $request->allowance_names;
        $al_amount_types = $request->al_amount_types;
        $allowance_percents = $request->allowance_percents;
        $allowance_amounts = $request->allowance_amounts;
        foreach ($allowance_names as $key => $allowance_name) {
            $salaryAllowance = PayrollAllowance::where('id', $allowance_id[$key])->first();
            if ($salaryAllowance) {
                $salaryAllowance->allowance_name = $allowance_name;
                $salaryAllowance->amount_type = $al_amount_types[$key];
                $salaryAllowance->allowance_percent = $al_amount_types[$key] == 2 ? $allowance_percents : 0;
                $salaryAllowance->allowance_amount = $allowance_amounts[$key] ? $allowance_amounts[$key] : 0;
                $salaryAllowance->is_delete_in_update = 0;
                $salaryAllowance->save();
            } else {
                if ($allowance_name || $allowance_amounts[$key]) {
                    $addSalaryAllowance = new PayrollAllowance();
                    $addSalaryAllowance->payroll_id = $updatePayroll->id;
                    $addSalaryAllowance->allowance_name = $allowance_name;
                    $addSalaryAllowance->amount_type = $al_amount_types[$key];
                    $addSalaryAllowance->allowance_percent = $al_amount_types[$key] == 2 ? $allowance_percents : 0;
                    $addSalaryAllowance->allowance_amount = $allowance_amounts[$key] ? $allowance_amounts[$key] : 0;
                    $addSalaryAllowance->save();
                }
            }
        }

        $deduction_id = $request->payroll_deduction_id;
        $deduction_names = $request->deduction_names;
        $de_amount_types = $request->de_amount_types;
        $deduction_percents = $request->deduction_percents;
        $deduction_amounts = $request->deduction_amounts;

        foreach ($deduction_names as $key => $deduction_name) {
            $salaryDeduction = PayrollDeduction::where('id', $deduction_id[$key])->first();
            if ($salaryDeduction) {
                $salaryDeduction->deduction_name = $deduction_name;
                $salaryDeduction->amount_type = $de_amount_types[$key];
                $salaryDeduction->deduction_percent = $de_amount_types[$key] == 2 ? $deduction_percents[$key] : 0;
                $salaryDeduction->deduction_amount = $deduction_amounts[$key];
                $salaryDeduction->is_delete_in_update = 0;
                $salaryDeduction->save();
            } else {
                if ($deduction_name || $deduction_amounts[$key]) {
                    $addSalaryDeduction = new PayrollDeduction();
                    $addSalaryDeduction->payroll_id = $updatePayroll->id;
                    $addSalaryDeduction->deduction_name = $deduction_name;
                    $addSalaryDeduction->amount_type = $de_amount_types[$key];
                    $addSalaryDeduction->deduction_percent = $de_amount_types[$key] == 2 ? $deduction_percents[$key] : 0;
                    $addSalaryDeduction->deduction_amount = $deduction_amounts[$key];
                    $addSalaryDeduction->save();
                }
            }
        }

        $allowances = PayrollAllowance::where('is_delete_in_update', 1)->get();
        if (count($allowances)) {
            foreach ($allowances as $allowance) {
                $allowance->delete();
            }
        }

        $deductions = PayrollDeduction::where('is_delete_in_update', 1)->get();
        if (count($deductions)) {
            foreach ($deductions as $deduction) {
                $deduction->delete();
            }
        }

        session()->flash('successMsg', 'Successfully salary is updated.');
        return response()->json('Successfully salary is updated.');
    }

    // Show payroll mentod
    public function show($payrollId)
    {
        $payroll = Payroll::with(['employee', 'allowances', 'deductions'])->where('id', $payrollId)->first();
        return view('hrm.payroll.ajax_view.show', compact('payroll'));
    }

    // Payroll delete method
    public function delete(Request $request, $payrollId)
    {
        $deletePayroll = Payroll::find($payrollId);
        if (!is_null($deletePayroll)) {
            $deletePayroll->delete();
        }
        return response()->json('Successfully payroll is deleted');
    }

    public function paymentView($payrollId)
    {
        $payroll = Payroll::with('payments', 'employee', 'employee.branch')->where('id', $payrollId)->first();
        return view('hrm.payroll.ajax_view.view_payment', compact('payroll'));
    }

    // Get payment list **requested by ajax**
    public function payment($payrollId)
    {
        $payroll = Payroll::with('employee', 'employee.branch')->where('id', $payrollId)->first();
        $accounts = DB::table('accounts')->where('status', 1)->get();
        return view('hrm.payroll.ajax_view.add_payment', compact('payroll', 'accounts'));
    }

    // Add payment menthod
    public function addPayment(Request $request, $payrollId)
    {
        $updatePayroll = Payroll::where('id', $payrollId)->first();
        $updatePayroll->paid = $request->amount;
        $updatePayroll->due -= $request->amount;
        $updatePayroll->save();

        // generate invoice ID
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }
        // Add sale payment
        $addPayrollPayment = new PayrollPayment();
        $addPayrollPayment->reference_no = 'PRP' . date('ymd') . $invoiceId;
        $addPayrollPayment->payroll_id = $updatePayroll->id;
        $addPayrollPayment->account_id = $request->account_id;
        $addPayrollPayment->pay_mode = $request->payment_method;
        $addPayrollPayment->paid = $request->amount;
        $addPayrollPayment->due = $updatePayroll->due;
        $addPayrollPayment->date = $request->date;
        $addPayrollPayment->time = date('h:i:s a');
        $addPayrollPayment->report_date = date('Y-m-d', strtotime($request->date));
        $addPayrollPayment->month = date('F');
        $addPayrollPayment->year = date('Y');
        $addPayrollPayment->note = $request->note;
        $addPayrollPayment->due = $updatePayroll->due;

        if ($request->payment_method == 'Card') {
            $addPayrollPayment->card_no = $request->card_no;
            $addPayrollPayment->card_holder = $request->card_holder_name;
            $addPayrollPayment->card_transaction_no = $request->card_transaction_no;
            $addPayrollPayment->card_type = $request->card_type;
            $addPayrollPayment->card_month = $request->month;
            $addPayrollPayment->card_year = $request->year;
            $addPayrollPayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $addPayrollPayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $addPayrollPayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $addPayrollPayment->transaction_no = $request->transaction_no;
        }

        $addPayrollPayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            $payrollPaymentAttachment = $request->file('attachment');
            $payrollPaymentAttachmentName = uniqid() . '-' . '.' . $payrollPaymentAttachment->getClientOriginalExtension();
            $payrollPaymentAttachment->move(public_path('uploads/payment_attachment/'), $payrollPaymentAttachmentName);
            $addPayrollPayment->attachment = $payrollPaymentAttachmentName;
        }
        $addPayrollPayment->save();

        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->debit += $request->amount;
            $account->balance -= $request->amount;
            $account->save();

            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->debit = $request->amount;
            $addCashFlow->balance = $account->balance;
            $addCashFlow->payroll_id = $updatePayroll->id;
            $addCashFlow->payroll_payment_id = $addPayrollPayment->id;
            $addCashFlow->transaction_type = 8;
            $addCashFlow->cash_type = 1;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
            Cache::forget('all-accounts');
        }
        return response()->json('Successfully payment is added.');
    }

    // Get payment details **requested by ajax**
    public function paymentDetails($paymentId)
    {
        $payment = PayrollPayment::with('payroll', 'payroll.employee')->where('id', $paymentId)->first();
        return view('hrm.payroll.ajax_view.payment_details', compact('payment'));
    }

    // Payroll payment delete
    public function paymentDelete($paymentId)
    {
        $deletePayrollPayment = PayrollPayment::with('payroll', 'account')->where('id', $paymentId)->first();

        if (!is_null($deletePayrollPayment)) {
            // Update purchase 
            $deletePayrollPayment->payroll->paid -= $deletePayrollPayment->paid;
            $deletePayrollPayment->payroll->due += $deletePayrollPayment->paid;
            $deletePayrollPayment->payroll->save();

            // Update previoues account and delete previous cashflow.
            if ($deletePayrollPayment->account) {
                $deletePayrollPayment->account->debit -= $deletePayrollPayment->paid;
                $deletePayrollPayment->account->balance += $deletePayrollPayment->paid;
                $deletePayrollPayment->account->save();
            }

            if ($deletePayrollPayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $deletePayrollPayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $deletePayrollPayment->attachment));
                }
            }

            $deletePayrollPayment->delete();
            Cache::forget('all-accounts');
        }
        return response()->json('Successfully payment is deleted.');
    }

    // Edit payroll payment modal view 
    public function paymentEdit($paymentId)
    {
        $accounts = DB::table('accounts')->where('status', 1)->get();
        $payment = PayrollPayment::with('payroll', 'payroll.employee')->where('id', $paymentId)->first();
        return view('hrm.payroll.ajax_view.edit_payment', compact('payment', 'accounts'));
    }

    // Update payroll payment
    public function paymentUpdate(Request $request, $paymentId)
    {
        //return $request->all();
        $updatePayrollPayment = PayrollPayment::with('account', 'payroll',)->where('id', $paymentId)->first();

        $updatePayrollPayment->payroll->paid -= $updatePayrollPayment->paid;
        $updatePayrollPayment->payroll->due += $updatePayrollPayment->paid;
        $updatePayrollPayment->payroll->paid += $request->amount;
        $updatePayrollPayment->payroll->due -= $request->amount;
        $updatePayrollPayment->payroll->save();

        // Update previoues account and delete previous cashflow.
        if ($updatePayrollPayment->account) {
            $updatePayrollPayment->account->debit -= $updatePayrollPayment->paid;
            $updatePayrollPayment->account->balance += $updatePayrollPayment->paid;
            $updatePayrollPayment->account->save();
        }

        // update purchase payment
        $updatePayrollPayment->account_id = $request->account_id;
        $updatePayrollPayment->pay_mode = $request->payment_method;
        $updatePayrollPayment->paid = $request->amount;
        $updatePayrollPayment->due = $updatePayrollPayment->payroll->due;
        $updatePayrollPayment->date = $request->date;
        $updatePayrollPayment->report_date = date('Y-m-d', strtotime($request->date));
        $updatePayrollPayment->month = date('F');
        $updatePayrollPayment->year = date('Y');
        $updatePayrollPayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $updatePayrollPayment->card_no = $request->card_no;
            $updatePayrollPayment->card_holder = $request->card_holder_name;
            $updatePayrollPayment->card_transaction_no = $request->card_transaction_no;
            $updatePayrollPayment->card_type = $request->card_type;
            $updatePayrollPayment->card_month = $request->month;
            $updatePayrollPayment->card_year = $request->year;
            $updatePayrollPayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $updatePayrollPayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $updatePayrollPayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $updatePayrollPayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            if ($updatePayrollPayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $updatePayrollPayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $updatePayrollPayment->attachment));
                }
            }

            $payrollPaymentAttachment = $request->file('attachment');
            $payrollPaymentAttachmentName = uniqid() . '-' . '.' . $payrollPaymentAttachment->getClientOriginalExtension();
            $payrollPaymentAttachment->move(public_path('uploads/payment_attachment/'), $payrollPaymentAttachmentName);
            $updatePayrollPayment->attachment = $payrollPaymentAttachmentName;
        }
        $updatePayrollPayment->save();

        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->debit += $request->amount;
            $account->balance -= $request->amount;
            $account->save();

            $cashFlow = CashFlow::where('payroll_id', $updatePayrollPayment->payroll->id)
                ->where('payroll_payment_id', $updatePayrollPayment->id)->first();
            if ($cashFlow) {
                $cashFlow->account_id = $request->account_id;
                $cashFlow->debit = $request->amount;
                $cashFlow->balance = $account->balance;
                $cashFlow->save();
            } else {
                // Add cash flow
                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->debit = $request->amount;
                $addCashFlow->balance = $account->balance;
                $addCashFlow->payroll_id = $updatePayrollPayment->payroll->id;
                $addCashFlow->payroll_payment_id = $updatePayrollPayment->id;
                $addCashFlow->transaction_type = 8;
                $addCashFlow->cash_type = 1;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
            }
        } else {
            $cashFlow = CashFlow::where('payroll_id', $updatePayrollPayment->payroll->id)
                ->where('payroll_payment_id', $updatePayrollPayment->id)->first();
            if (!is_null($cashFlow)) {
                $cashFlow->delete();
            }
        }
        Cache::forget('all-accounts');
        return response()->json('Successfully payment is updated.');
    }

    public function getAllEmployee()
    {
        $employee = DB::table('admin_and_users')->get();
        return response()->json($employee);
    }

    public function getAllDeparment()
    {
        $departments = DB::table('hrm_department')->get();
        return response()->json($departments);
    }

    public function getAllDesignation()
    {
        $designations = DB::table('hrm_designations')->get();
        return response()->json($designations);
    }
}
