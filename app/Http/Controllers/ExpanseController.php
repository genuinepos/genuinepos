<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expanse;
use App\Models\CashFlow;
use App\Utils\Converter;
use App\Utils\AccountUtil;
use App\Utils\ExpenseUtil;
use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use App\Models\ExpansePayment;
use App\Models\ExpanseCategory;
use App\Models\ExpenseDescription;
use App\Utils\InvoiceVoucherRefIdUtil;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class ExpanseController extends Controller
{
    protected $expenseUtil;
    protected $accountUtil;
    protected $converter;
    protected $invoiceVoucherRefIdUtil;
    public function __construct(
        ExpenseUtil $expenseUtil,
        AccountUtil $accountUtil,
        Converter $converter,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil
    ) {
        $this->expenseUtil = $expenseUtil;
        $this->accountUtil = $accountUtil;
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Expanse index view
    public function index(Request $request)
    {
        if (auth()->user()->permission->expense['view_expense'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $expenses = '';
            $query = DB::table('expanses')
                ->leftJoin('branches', 'expanses.branch_id', 'branches.id')
                ->leftJoin('admin_and_users', 'expanses.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('expanses.branch_id', NULL);
                } else {
                    $query->where('expanses.branch_id', $request->branch_id);
                }
            }

            if ($request->admin_id) {
                $query->where('expanses.admin_id', $request->admin_id);
            }

            if ($request->cate_id) {
                $query->where('expanses.category_ids', 'LIKE',  '%'. $request->cate_id . '%');
            }

            if ($request->from_date) {
                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('expanses.report_date', $date_range); // Final
            }

            $query->select(
                'expanses.*',
                'branches.name as branch_name',
                'branches.branch_code',
                'admin_and_users.prefix as cr_prefix',
                'admin_and_users.name as cr_name',
                'admin_and_users.last_name as cr_last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $expenses = $query->orderBy('expanses.report_date', 'desc');
            } else {
                $expenses = $query->where('expanses.branch_id', auth()->user()->branch_id)
                    ->orderBy('expanses.report_date', 'desc');
            }

            return DataTables::of($expenses)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->permission->expense['edit_expense'] == '1') :
                        $html .= '<a class="dropdown-item" href="' . route('expanses.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                    endif;

                    if (auth()->user()->permission->expense['delete_expense'] == '1') :
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('expanses.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    endif;

                    if (auth()->user()->branch_id == $row->branch_id) :
                        if ($row->due > 0) :
                            $html .= '<a class="dropdown-item" id="add_payment" href="' . route('expanses.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Add Payment</a>';
                        endif;

                        $html .= '<a class="dropdown-item" id="view_payment" href="' . route('expanses.payment.view', [$row->id]) . '"><i class="far fa-money-bill-alt mr-1 text-primary"></i> View Payment</a>';
                    endif;

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })->editColumn('descriptions', function ($row) use ($generalSettings) {
                    $expenseDescriptions = DB::table('expense_descriptions')
                        ->where('expense_id', $row->id)
                        ->leftJoin('expanse_categories', 'expense_descriptions.expense_category_id', 'expanse_categories.id')
                        ->select(
                            'expanse_categories.name',
                            'expanse_categories.code',
                            'expense_descriptions.amount'
                        )->get();
                    $html = '';
                    foreach ($expenseDescriptions as $exDescription) {
                        $html .= '<b>' . $exDescription->name . ':</b> ' . $exDescription->amount . '</br>';
                    }
                    return $html;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {
                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('user_name',  function ($row) {
                    return $row->cr_prefix . ' ' . $row->cr_name . ' ' . $row->cr_last_name;
                })
                ->editColumn('payment_status',  function ($row) {
                    $html = "";
                    $payable = $row->net_total_amount;
                    if ($row->due <= 0) {
                        $html .= '<span class="badge bg-success">Paid</span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        $html .= '<span class="badge bg-primary text-white">Partial</span>';
                    } elseif ($payable == $row->due) {
                        $html .= '<span class="badge bg-danger text-white">Due</span>';
                    }
                    return $html;
                })
                ->editColumn('tax_percent',  function ($row) {
                    return $row->tax_percent . '%';
                })
                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . $this->converter->format_in_bdt($row->net_total_amount) . '</span>')
                ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')
                ->rawColumns(['action', 'date', 'from', 'user_name', 'payment_status', 'tax_percent', 'due', 'net_total_amount', 'descriptions'])
                ->make(true);
        }

        $ex_cates = DB::table('expanse_categories')->get();

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('expanses.index', compact('branches', 'ex_cates'));
    }

    public function categoryWiseExpense(Request $request)
    {
        if (auth()->user()->permission->expense['category_wise_expense'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $expenses = '';
            $query = DB::table('expense_descriptions')
                ->leftJoin('expanses', 'expense_descriptions.expense_id', 'expanses.id')
                ->leftJoin('expanse_categories', 'expense_descriptions.expense_category_id', 'expanse_categories.id')
                ->leftJoin('branches', 'expanses.branch_id', 'branches.id')
                ->leftJoin('admin_and_users', 'expanses.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('expanses.branch_id', NULL);
                } else {
                    $query->where('expanses.branch_id', $request->branch_id);
                }
            }

            if ($request->admin_id) {
                $query->where('expanses.admin_id', $request->admin_id);
            }

            if ($request->category_id) {
                $query->where('expense_descriptions.expense_category_id', $request->category_id);
            }

            if ($request->from_date) {
                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('expanses.report_date', $date_range); // Final
            }

            $query->select(
                'expense_descriptions.amount',
                'expanses.invoice_id',
                'expanses.date',
                'expanse_categories.name',
                'expanse_categories.code',
                'branches.name as branch_name',
                'branches.branch_code',
                'admin_and_users.prefix as cr_prefix',
                'admin_and_users.name as cr_name',
                'admin_and_users.last_name as cr_last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $expenses = $query->orderBy('expanses.report_date', 'desc');
            } else {
                $expenses = $query->where('expanses.branch_id', auth()->user()->branch_id)
                    ->orderBy('expanses.report_date', 'desc');
            }

            return DataTables::of($expenses)
                ->editColumn('date', function ($row) use ($generalSettings) {
                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })->editColumn('category_name', function ($row) {
                    return $row->name . ' (' . $row->code . ')';
                })->editColumn('user_name',  function ($row) {
                    if ($row->cr_name) {
                        return $row->cr_prefix . ' ' . $row->cr_name . ' ' . $row->cr_last_name;
                    } else {
                        return '---';
                    }
                })->editColumn('amount', fn ($row) => '<span class="amount" data-value="' . $row->amount . '">' . $this->converter->format_in_bdt($row->amount) . '</span>')
                ->rawColumns(['date', 'from', 'category_name', 'user_name', 'amount'])
                ->make(true);
        }



        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('expanses.category_wise_expense_list', compact('branches'));
    }

    // Create expanse view
    public function create()
    {
        if (auth()->user()->permission->expense['add_expense'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        return view('expanses.create');
    }

    // Store Expanse
    public function store(Request $request)
    {
        if (auth()->user()->permission->expense['add_expense'] == '0') {
            return response()->json('Access Denied');
        }

        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['expenses'];
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['expanse_payment'];
        $this->validate($request, [
            'date' => 'required',
            'total_amount' => 'required',
        ]);

        // generate invoice ID
        $invoiceId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_returns'), 4, "0", STR_PAD_LEFT);

        // Add expanse
        $addExpanse = new Expanse();
        $addExpanse->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . $invoiceId;

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $addExpanse->branch_id = NULL;
        } else {
            $addExpanse->branch_id = auth()->user()->branch_id;
        }

        $addExpanse->tax_percent = $request->tax ? $request->tax : 0;
        $addExpanse->total_amount = $request->total_amount;
        $addExpanse->net_total_amount = $request->net_total_amount;
        $addExpanse->paid = $request->paying_amount;
        $addExpanse->due = $request->total_due;
        $addExpanse->date = $request->date;
        $addExpanse->report_date = date('Y-m-d', strtotime($request->date));
        $addExpanse->month = date('F');
        $addExpanse->year = date('Y');
        $addExpanse->admin_id = $request->admin_id;
        $addExpanse->category_ids = $request->admin_id;

        $category_ids = '';
        foreach ($request->category_ids as $category_id) {
            $category_ids .= $category_id.', ';
        }

        $addExpanse->category_ids = $category_ids;

        if ($request->hasFile('attachment')) {
            $expanseAttachment = $request->file('attachment');
            $expanseAttachmentName = uniqid() . '-' . '.' . $expanseAttachment->getClientOriginalExtension();
            $expanseAttachment->move(public_path('uploads/expanse_attachment/'), $expanseAttachmentName);
            $addExpanse->attachment = $expanseAttachmentName;
        }

        $addExpanse->save();

        $index = 0;
        foreach ($request->category_ids as $category_id) {
            $addExDescription = new ExpenseDescription();
            $addExDescription->expense_id = $addExpanse->id;
            $addExDescription->expense_category_id = $category_id;
            $addExDescription->amount = $request->amounts[$index];
            $addExDescription->save();
            $index++;
        }

        if ($request->paying_amount > 0) {
            $paymentVoucher = str_pad($this->invoiceVoucherRefIdUtil->getLastId('expanse_payments'), 4, "0", STR_PAD_LEFT);
            $addExpansePayment = new ExpansePayment();
            $addExpansePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . $paymentVoucher;
            $addExpansePayment->expanse_id = $addExpanse->id;
            $addExpansePayment->account_id = $request->account_id;
            $addExpansePayment->pay_mode = $request->payment_method;
            $addExpansePayment->paid_amount = $request->paying_amount;
            $addExpansePayment->date = $request->date;
            $addExpansePayment->report_date = date('Y-m-d', strtotime($request->date));
            $addExpansePayment->month = date('F');
            $addExpansePayment->year = date('Y');
            $addExpansePayment->note = $request->payment_note;

            if ($request->payment_method == 'Card') {
                $addExpansePayment->card_no = $request->card_no;
                $addExpansePayment->card_holder = $request->card_holder_name;
                $addExpansePayment->card_transaction_no = $request->card_transaction_no;
                $addExpansePayment->card_type = $request->card_type;
                $addExpansePayment->card_month = $request->month;
                $addExpansePayment->card_year = $request->year;
                $addExpansePayment->card_secure_code = $request->secure_code;
            } elseif ($request->payment_method == 'Cheque') {
                $addExpansePayment->cheque_no = $request->cheque_no;
            } elseif ($request->payment_method == 'Bank-Transfer') {
                $addExpansePayment->account_no = $request->account_no;
            } elseif ($request->payment_method == 'Custom') {
                $addExpansePayment->transaction_no = $request->transaction_no;
            }
            $addExpansePayment->admin_id = auth()->user()->id;
            $addExpansePayment->save();

            if ($request->account_id) {
                // Add cash flow
                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->debit = $request->paying_amount;
                $addCashFlow->expanse_payment_id = $addExpansePayment->id;
                $addCashFlow->transaction_type = 6;
                $addCashFlow->cash_type = 1;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
                $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
                $addCashFlow->save();
            }
        }

        $expense = Expanse::with(['expense_descriptions', 'expense_descriptions.category', 'admin'])
            ->where('id', $addExpanse->id)->first();
        return view('expanses.ajax_view.expense_print', compact('expense'));
    }

    //Delete Expanse
    public function delete(Request $request, $expanseId)
    {
        if (auth()->user()->permission->expense['delete_expense'] == '0') {
            return response()->json('Access Denied');
        }

        $deleteExpanse = Expanse::with(['expense_payments'])->where('id', $expanseId)->first();
        $storedPayments = $deleteExpanse->expense_payments;
        if (!is_null($deleteExpanse)) {
            if ($deleteExpanse->attachment) {
                if (file_exists(public_path('uploads/expanse_attachment/' . $deleteExpanse->attachment))) {
                    unlink(public_path('uploads/expanse_attachment/' . $deleteExpanse->attachment));
                }
            }

            $deleteExpanse->delete();

            if (count($storedPayments) > 0) {
                foreach ($storedPayments as $payment) {
                    if ($payment->attachment) {
                        if (file_exists(public_path('uploads/payment_attachment/' . $payment->attachment))) {
                            unlink(public_path('uploads/payment_attachment/' . $payment->attachment));
                        }
                    }

                    if ($payment->account_id) {
                        $this->accountUtil->adjustAccountBalance($payment->account_id);
                    }
                }
            }
        }

        return response()->json('Successfully expense is deleted');
    }

    // Edit view
    public function edit($expenseId)
    {
        if (auth()->user()->permission->expense['edit_expense'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $expense = Expanse::with('expense_descriptions')->where('id', $expenseId)->first();
        $categories = DB::table('expanse_categories')->get();
        $taxes = DB::table('taxes')->get();
        $users = '';
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $users = DB::table('admin_and_users')->get(['id', 'prefix', 'name', 'last_name']);
        } else {
            $users = DB::table('admin_and_users')
                ->where('branch_id', auth()->user()->branch_id)
                ->get(['id', 'prefix', 'name', 'last_name']);
        }

        return view('expanses.edit', compact('expense', 'categories', 'users', 'taxes'));
    }

    // Update expanse
    public function update(Request $request, $expenseId)
    {
        if (auth()->user()->permission->expense['edit_expense'] == '0') {
            return response()->json('Access Denied');
        }

        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['expenses'];
        $this->validate($request, [
            'date' => 'required',
            'total_amount' => 'required',
        ]);


        // Add expanse
        $updateExpanse = Expanse::where('id', $expenseId)->first();
        $updateExpanse->note = $request->expanse_note;
        $updateExpanse->tax_percent = $request->tax ? $request->tax : 0;
        $updateExpanse->total_amount = $request->total_amount;
        $updateExpanse->net_total_amount = $request->net_total_amount;
        $updateExpanse->due = $request->net_total_amount - $updateExpanse->paid;
        $updateExpanse->date = $request->date;
        $updateExpanse->report_date = date('Y-m-d', strtotime($request->date));
        $updateExpanse->month = date('F');
        $updateExpanse->year = date('Y');
        $updateExpanse->admin_id = $request->admin_id;

        if ($request->hasFile('attachment')) {
            if ($updateExpanse->attachment != null) {
                if (file_exists(public_path('uploads/expanse_attachment/' . $updateExpanse->attachment))) {
                    unlink(public_path('uploads/expanse_attachment/' . $updateExpanse->attachment));
                }
            }

            $expanseAttachment = $request->file('attachment');
            $expanseAttachmentName = uniqid() . '-' . '.' . $expanseAttachment->getClientOriginalExtension();
            $expanseAttachment->move(public_path('uploads/expanse_attachment/'), $expanseAttachmentName);
            $updateExpanse->attachment = $expanseAttachmentName;
        }

        $category_ids = '';
        foreach ($request->category_ids as $category_id) {
            $category_ids .= $category_id.', ';
        }

        $updateExpanse->category_ids = $category_ids;

        $updateExpanse->save();

        $exDescriptions = ExpenseDescription::where('expense_id', $updateExpanse->id)->get();
        foreach ($exDescriptions as  $exDescription) {
            $exDescription->is_delete_in_update = 1;
            $exDescription->save();
        }

        $index = 0;
        foreach ($request->category_ids as $category_id) {
            $description = ExpenseDescription::where('id', $request->description_ids[$index])->first();
            if ($description) {
                $description->expense_category_id = $category_id;
                $description->amount = $request->amounts[$index];
                $description->is_delete_in_update = 0;
                $description->save();
            } else {
                $addExDescription = new ExpenseDescription();
                $addExDescription->expense_id = $updateExpanse->id;
                $addExDescription->expense_category_id = $category_id;
                $addExDescription->amount = $request->amounts[$index];
                $addExDescription->save();
            }

            $index++;
        }

        $deleteAbleExDescriptions = ExpenseDescription::where('expense_id', $updateExpanse->id)
            ->where('is_delete_in_update', 1)->get();
        foreach ($deleteAbleExDescriptions as  $exDescription) {
            $exDescription->delete();
        }

        return response()->json(['successMsg' => 'Successfully expense is updated']);
    }

    // Get all form Categories by ajax request
    public function allCategories()
    {
        $categories = ExpanseCategory::orderBy('id', 'DESC')->get();
        return response()->json($categories);
    }

    // Payment view method
    public function paymentView($expanseId)
    {
        $expense = Expanse::with(['branch',  'expense_payments'])->where('id', $expanseId)->first();
        return view('expanses.ajax_view.payment_view', compact('expense'));
    }

    // Payment details
    public function paymentDetails($paymentId)
    {
        $payment = ExpansePayment::with(['expense', 'expense.expense_descriptions', 'expense.expense_descriptions.category', 'expense.admin'])->where('id', $paymentId)->first();
        return view('expanses.ajax_view.payment_details', compact('payment'));
    }

    public function paymentModal($expenseId)
    {
        $expense = Expanse::with('branch')->where('id', $expenseId)->first();
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number', 'balance')->get();
        return view('expanses.ajax_view.add_payment', compact('expense', 'accounts'));
    }

    // Expanse payment method
    public function payment(Request $request, $expenseId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['expanse_payment'];
        $expanse = Expanse::where('id', $expenseId)->first();
        // Update expanse
        $expanse->paid += $request->amount;
        $expanse->due -= $request->amount;
        $expanse->save();

        // generate invoice ID
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        // Add Expanse payment
        $addExpansePayment = new ExpansePayment();
        $addExpansePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'EPI') . date('ymd') . $invoiceId;
        $addExpansePayment->expanse_id = $expanse->id;
        $addExpansePayment->account_id = $request->account_id;
        $addExpansePayment->pay_mode = $request->payment_method;
        $addExpansePayment->paid_amount = $request->amount;
        $addExpansePayment->date = $request->date;
        $addExpansePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addExpansePayment->month = date('F');
        $addExpansePayment->year = date('Y');
        $addExpansePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $addExpansePayment->card_no = $request->card_no;
            $addExpansePayment->card_holder = $request->card_holder_name;
            $addExpansePayment->card_transaction_no = $request->card_transaction_no;
            $addExpansePayment->card_type = $request->card_type;
            $addExpansePayment->card_month = $request->month;
            $addExpansePayment->card_year = $request->year;
            $addExpansePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $addExpansePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $addExpansePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $addExpansePayment->transaction_no = $request->transaction_no;
        }
        $addExpansePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            $expansePaymentAttachment = $request->file('attachment');
            $expansePaymentAttachmentName = uniqid() . '-' . '.' . $expansePaymentAttachment->getClientOriginalExtension();
            $expansePaymentAttachment->move(public_path('uploads/payment_attachment/'), $expansePaymentAttachmentName);
            $addExpansePayment->attachment = $expansePaymentAttachmentName;
        }

        $addExpansePayment->save();

        if ($request->account_id) {
            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->debit = $request->amount;
            $addCashFlow->expanse_payment_id = $addExpansePayment->id;
            $addCashFlow->transaction_type = 6;
            $addCashFlow->cash_type = 1;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
            $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
            $addCashFlow->save();
        }
        return response()->json('Successfully payment is added.');
    }

    public function paymentEdit($paymentId)
    {
        $payment = ExpansePayment::with(['expense'])->where('id', $paymentId)->first();
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number', 'balance')->get();
        return view('expanses.ajax_view.edit_payment', compact('payment', 'accounts'));
    }

    // Update payment
    public function paymentUpdate(Request $request, $paymentId)
    {
        $updateExpansePayment = ExpansePayment::with('account', 'expense', 'cashFlow')->where('id', $paymentId)->first();

        // Update Expanse 
        $updateExpansePayment->expense->paid -= $updateExpansePayment->paid_amount;
        $updateExpansePayment->expense->due += $updateExpansePayment->paid_amount;
        $updateExpansePayment->expense->paid += $request->amount;
        $updateExpansePayment->expense->due -= $request->amount;
        $updateExpansePayment->expense->save();

        // update Expanse payment
        $updateExpansePayment->account_id = $request->account_id;
        $updateExpansePayment->pay_mode = $request->payment_method;
        $updateExpansePayment->paid_amount = $request->amount;
        $updateExpansePayment->date = $request->date;
        $updateExpansePayment->report_date = date('Y-m-d', strtotime($request->date));
        $updateExpansePayment->month = date('F');
        $updateExpansePayment->year = date('Y');
        $updateExpansePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $updateExpansePayment->card_no = $request->card_no;
            $updateExpansePayment->card_holder = $request->card_holder_name;
            $updateExpansePayment->card_transaction_no = $request->card_transaction_no;
            $updateExpansePayment->card_type = $request->card_type;
            $updateExpansePayment->card_month = $request->month;
            $updateExpansePayment->card_year = $request->year;
            $updateExpansePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $updateExpansePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $updateExpansePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $updateExpansePayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            if ($updateExpansePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $updateExpansePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $updateExpansePayment->attachment));
                }
            }
            $expansePaymentAttachment = $request->file('attachment');
            $expansePaymentAttachmentName = uniqid() . '-' . '.' . $expansePaymentAttachment->getClientOriginalExtension();
            $expansePaymentAttachment->move(public_path('uploads/payment_attachment/'), $expansePaymentAttachmentName);
            $updateExpansePayment->attachment = $expansePaymentAttachmentName;
        }
        $updateExpansePayment->save();

        if ($request->account_id) {
            // Add or update cash flow
            $cashFlow = CashFlow::where('account_id', $request->account_id)
                ->where('expanse_payment_id', $updateExpansePayment->id)->first();
            if ($cashFlow) {
                $cashFlow->debit = $request->amount;
                $cashFlow->date = $request->date;
                $cashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $cashFlow->month = date('F');
                $cashFlow->year = date('Y');
                $cashFlow->admin_id = auth()->user()->id;
                $cashFlow->save();
                $cashFlow->balance = $this->accountUtil->adjustAccountBalance($cashFlow->account_id);
                $cashFlow->save();
            } else {
                if ($updateExpansePayment->cashFlow) {
                    $storedAccountId = $updateExpansePayment->cashFlow->account_id;
                    $updateExpansePayment->cashFlow->delete();
                    $this->accountUtil->adjustAccountBalance($storedAccountId);
                }

                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->debit = $request->amount;
                $addCashFlow->expanse_payment_id = $updateExpansePayment->id;
                $addCashFlow->transaction_type = 6;
                $addCashFlow->cash_type = 1;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
                $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
                $addCashFlow->save();
            }
        } else {
            if ($updateExpansePayment->cashFlow) {
                $storedAccountId = $updateExpansePayment->cashFlow->account_id;
                $updateExpansePayment->cashFlow->delete();
                $this->accountUtil->adjustAccountBalance($storedAccountId);
            }
        }
        return response()->json('Successfully payment is added.');
    }

    public function paymentDelete(Request $request, $paymentId)
    {
        $deleteExpansePayment = ExpansePayment::with('account', 'expense', 'cashFlow')->where('id', $paymentId)->first();
        $storedAccountId = $deleteExpansePayment->account_id;
        if (!is_null($deleteExpansePayment)) {
            // Update expanse 
            $deleteExpansePayment->expense->paid -= $deleteExpansePayment->paid_amount;
            $deleteExpansePayment->expense->due += $deleteExpansePayment->paid_amount;
            $deleteExpansePayment->expense->save();

            if ($deleteExpansePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $deleteExpansePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $deleteExpansePayment->attachment));
                }
            }
            $deleteExpansePayment->delete();
        }

        if ($storedAccountId) {
            $this->accountUtil->adjustAccountBalance($storedAccountId);
        }

        return response()->json('Successfully payment is deleted.');
    }

    // Get all form user **requested by ajax**
    public function allAdmins()
    {
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $admins = AdminAndUser::select(['id', 'prefix', 'name', 'last_name'])->orderBy('id', 'asc')
                //  ->where('allow_login', 1)
                ->get();
            return response()->json($admins);
        } else {
            $admins = AdminAndUser::select(['id', 'prefix', 'name', 'last_name'])->orderBy('id', 'asc')
                ->where('branch_id', auth()->user()->branch_id)
                //  ->where('allow_login', 1)
                ->get();
            return response()->json($admins);
        }
    }
}