<?php

namespace App\Http\Controllers\hrm;

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

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //Index view of payroll
    public function index()
    {
        
        return view('hrm.payroll.index');
    }

    public function getPayrolls(Request $request)
    {
        $payrolls = '';
        $query = DB::table('hrm_payrolls')
            ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
            ->leftJoin('hrm_department', 'admin_and_users.department_id', 'hrm_department.id')
            ->leftJoin('hrm_designations', 'admin_and_users.designation_id', 'hrm_designations.id')
            ->leftJoin('admin_and_users as created_by', 'hrm_payrolls.admin_id', 'created_by.id');
        if ($request->employee_id) {
            $query->where('hrm_payrolls.user_id', $request->employee_id);
        }

        if ($request->department_id) {
            $query->where('admin_and_users.department_id', $request->department_id);
        }

        if ($request->designation_id) {
            $query->where('admin_and_users.designation_id', $request->designation_id);
        }

        if ($request->month_year) {
            $month_year = explode('-', $request->month_year);
            $month = $month_year[0];
            $year = $month_year[1];
            $query->where('hrm_payrolls.month', $month)->where('hrm_payrolls.year', $year);
        } else {
            $query->where('hrm_payrolls.month', date('F'))->where('hrm_payrolls.year', date('Y'));
        }

        $payrolls = $query->select(
            'admin_and_users.prefix',
            'admin_and_users.name',
            'admin_and_users.last_name',
            'hrm_payrolls.*',
            'hrm_department.department_name',
            'hrm_designations.designation_name',
            'created_by.name as created_by_prefix',
            'created_by.name as created_by_name',
            'created_by.last_name as created_by_last_name',
        )->get();
        return view('hrm.payroll.ajax_view.payroll_list', compact('payrolls'));
    }

    // Create payroll
    public function create(Request $request)
    {
        // return  $result = (float)$float + $hour;
        // $number = str_replace(['+', '-'], '', filter_var($a, FILTER_SANITIZE_NUMBER_INT));
        
        $month_year = explode('-', $request->month_year);
        $month = $month_year[0];
        $year = $month_year[1];
        // return $employee = AdminAndUser::where('id', $request->employee_id)->first();
        $payroll = Payroll::where('user_id', $request->employee_id)->where('month', $month)->where('year', $year)->first();
        if ($payroll) {
            return redirect()->route('hrm.payrolls.edit', $payroll->id);
        }

        $employee = DB::table('admin_and_users')->where('id', $request->employee_id)->first();
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

        $allowances = DB::table('allowance_employees')
            ->where('allowance_employees.user_id', $request->employee_id)
            ->leftJoin('hrm_allowance', 'allowance_employees.allowance_id', 'hrm_allowance.id')
            ->where('hrm_allowance.type', 'Allowance')
            ->get();

        $deductions = DB::table('allowance_employees')
            ->where('allowance_employees.user_id', $request->employee_id)
            ->leftJoin('hrm_allowance', 'allowance_employees.allowance_id', 'hrm_allowance.id')
            ->where('hrm_allowance.type', 'Deduction')
            ->get();

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
        //return $request->all();
        // generate invoice ID
        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $addPayroll = new Payroll();
        $addPayroll->reference_no = 'EP' . date('my') . $invoiceId;
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
                $addPayrollAllowance->allowance_percent = $al_amount_types[$key] == 2 ? $allowance_percents : 0;
                $addPayrollAllowance->allowance_amount = $allowance_amounts[$key];
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
                $addPayrollDeduction->deduction_percent = $de_amount_types[$key] == 2 ? $deduction_percents[$key] : 0;
                $addPayrollDeduction->deduction_amount = $deduction_amounts[$key];
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

    // Show payroll mentod
    public function show($payrollId)
    {
        $payroll = Payroll::with(['employee', 'allowances', 'deductions'])->where('id', $payrollId)->first();
        return view('hrm.payroll.ajax_view.payroll_view_modal', compact('payroll'));
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

    // Payroll payment view
    public function payment($payrollId)
    {
        $accounts = Cache::rememberForever('all-accounts', function () {
            return $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        });

        $payroll = DB::table('hrm_payrolls')
            ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
            ->leftJoin('hrm_department', 'admin_and_users.department_id', 'hrm_department.id')
            ->leftJoin('hrm_designations', 'admin_and_users.designation_id', 'hrm_designations.id')
            ->where('hrm_payrolls.id', $payrollId)
            ->select(
                'admin_and_users.prefix',
                'admin_and_users.name',
                'admin_and_users.last_name',
                'hrm_payrolls.*',
                'hrm_department.department_name',
                'hrm_designations.designation_name',
            )->first();
        return view('hrm.payroll.ajax_view.add_payment_modal', compact('payroll', 'accounts'));
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
            // $addCashFlow = new CashFlow();
            // $addCashFlow->account_id = $request->account_id;
            // $addCashFlow->debit = $request->amount;
            // $addCashFlow->balance = $account->balance;
            // $addCashFlow->payroll_payment_id = $addPayrollPayment->id;
            // $addCashFlow->transaction_type = 8;
            // $addCashFlow->cash_type = 1;
            // $addCashFlow->date = $request->date;
            // $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            // $addCashFlow->month = date('F');
            // $addCashFlow->year = date('Y');
            // $addCashFlow->admin_id = auth()->user()->id;
            // $addCashFlow->save();
            Cache::forget('all-accounts');
        }
        return response()->json('Successfully payment is added.');
    }

    // Get payment list **requested by ajax**
    public function paymentList($payrollId)
    {
        $payroll = Payroll::with('employee', 'payments', 'payments.account')->where('id', $payrollId)->first();
        return view('hrm.payroll.ajax_view.payment_list', compact('payroll'));
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
        $accounts = Cache::rememberForever('all-accounts', function () {
            return $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        });
        $payment = PayrollPayment::with('payroll', 'payroll.employee')->where('id', $paymentId)->first();
        return view('hrm.payroll.ajax_view.edit_payment', compact('payment', 'accounts'));
    }

    // Update payroll payment
    public function paymentUpdate(Request $request, $paymentId)
    {
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
        $updatePayrollPayment->paid= $request->amount;
        $updatePayrollPayment->due= $updatePayrollPayment->payroll->due;
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
