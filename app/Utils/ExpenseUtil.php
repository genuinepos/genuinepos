<?php

namespace App\Utils;

use App\Models\ExpansePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpenseUtil
{
    protected $invoiceVoucherRefIdUtil;

    protected $converter;

    protected $accountUtil;

    public function __construct(
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        Converter $converter,
        AccountUtil $accountUtil
    ) {
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->accountUtil = $accountUtil;
    }

    public function expenseListTable($request)
    {
        $generalSettings = config('generalSettings');
        $expenses = '';

        $query = DB::table('expanses')
            ->leftJoin('branches', 'expanses.branch_id', 'branches.id')
            ->leftJoin('users', 'expanses.admin_id', 'users.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('expanses.branch_id', null);
            } else {

                $query->where('expanses.branch_id', $request->branch_id);
            }
        }

        if ($request->admin_id) {

            $query->where('expanses.admin_id', $request->admin_id);
        }

        if ($request->cate_id) {

            $query->where('expanses.category_ids', 'LIKE', '%' . $request->cate_id . '%');
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
            'users.prefix as cr_prefix',
            'users.name as cr_name',
            'users.last_name as cr_last_name',
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

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('edit_expense')) {

                        $html .= '<a class="dropdown-item" href="' . route('expanses.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->can('delete_expense')) {

                        $html .= '<a class="dropdown-item" id="delete" href="' . route('expanses.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    if ($row->due > 0) {

                        $html .= '<a class="dropdown-item" id="add_payment" href="' . route('expanses.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Add Payment</a>';
                    }
                }

                $html .= '<a class="dropdown-item" id="view_payment" href="' . route('expanses.payment.view', [$row->id]) . '"><i class="far fa-money-bill-alt mr-1 text-primary"></i> View Payment</a>';

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })->editColumn('descriptions', function ($row) {

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

                    $html .= '<b>' . $exDescription->name . '(' . $exDescription->code . '):</b> ' . $exDescription->amount . '</br>';
                }

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                return date($generalSettings['business_or_shop__date_format'], strtotime($row->date));
            })->editColumn('from', function ($row) use ($generalSettings) {

                if ($row->branch_name) {

                    return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                } else {

                    return $generalSettings['business_or_shop__business_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('user_name', function ($row) {

                return $row->cr_prefix . ' ' . $row->cr_name . ' ' . $row->cr_last_name;
            })
            ->editColumn('payment_status', function ($row) {

                $html = '';
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
            ->editColumn('tax_percent', function ($row) {

                return $row->tax_percent . '%';
            })
            ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . $this->converter->format_in_bdt($row->net_total_amount) . '</span>')
            ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')
            ->rawColumns(['action', 'date', 'from', 'user_name', 'payment_status', 'tax_percent', 'due', 'net_total_amount', 'descriptions'])
            ->make(true);
    }

    public function categoryWiseExpenseListTable($request)
    {
        $generalSettings = config('generalSettings');
        $expenses = '';
        $query = DB::table('expense_descriptions')
            ->leftJoin('expanses', 'expense_descriptions.expense_id', 'expanses.id')
            ->leftJoin('expanse_categories', 'expense_descriptions.expense_category_id', 'expanse_categories.id')
            ->leftJoin('branches', 'expanses.branch_id', 'branches.id')
            ->leftJoin('users', 'expanses.admin_id', 'users.id');

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('expanses.branch_id', null);
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
            $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
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
            'users.prefix as cr_prefix',
            'users.name as cr_name',
            'users.last_name as cr_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $expenses = $query->orderBy('expanses.report_date', 'desc');
        } else {
            $expenses = $query->where('expanses.branch_id', auth()->user()->branch_id)
                ->orderBy('expanses.report_date', 'desc');
        }

        return DataTables::of($expenses)
            ->editColumn('date', function ($row) use ($generalSettings) {
                return date($generalSettings['business_or_shop__date_format'], strtotime($row->date));
            })->editColumn('from', function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {
                    return $generalSettings['business_or_shop__business_name'] . '(<b>HO</b>)';
                }
            })->editColumn('category_name', function ($row) {
                return $row->name . ' (' . $row->code . ')';
            })->editColumn('user_name', function ($row) {
                if ($row->cr_name) {
                    return $row->cr_prefix . ' ' . $row->cr_name . ' ' . $row->cr_last_name;
                } else {
                    return '---';
                }
            })->editColumn('amount', fn ($row) => '<span class="amount" data-value="' . $row->amount . '">' . $this->converter->format_in_bdt($row->amount) . '</span>')
            ->rawColumns(['date', 'from', 'category_name', 'user_name', 'amount'])
            ->make(true);
    }

    public function adjustExpenseAmount($expense)
    {
        $totalExpensePaid = DB::table('expanse_payments')
            ->where('expanse_payments.expanse_id', $expense->id)
            ->select(DB::raw('sum(paid_amount) as total_paid'))
            ->groupBy('expanse_payments.expanse_id')
            ->get();

        $due = $expense->net_total_amount - $totalExpensePaid->sum('total_paid');
        $expense->paid = $totalExpensePaid->sum('total_paid');
        $expense->due = $due;
        $expense->save();

        return $expense;
    }

    public function addPaymentGetId($voucher_prefix, $expense_id, $request, $another_amount = 0)
    {
        $addExpensePayment = new ExpansePayment();
        $addExpensePayment->invoice_id = ($voucher_prefix != null ? $voucher_prefix : 'EPV') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('expanse_payments'), 5, '0', STR_PAD_LEFT);
        $addExpensePayment->expanse_id = $expense_id;
        $addExpensePayment->account_id = $request->account_id;
        $addExpensePayment->payment_method_id = $request->payment_method_id;
        $addExpensePayment->paid_amount = isset($request->paying_amount) ? $request->paying_amount : $another_amount;
        $addExpensePayment->date = $request->date;
        $addExpensePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addExpensePayment->month = date('F');
        $addExpensePayment->year = date('Y');
        $addExpensePayment->note = $request->payment_note;
        $addExpensePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            $expensePaymentAttachment = $request->file('attachment');
            $expensePaymentAttachmentName = uniqid() . '-' . '.' . $expensePaymentAttachment->getClientOriginalExtension();
            $expensePaymentAttachment->move(public_path('uploads/payment_attachment/'), $expensePaymentAttachmentName);
            $addExpensePayment->attachment = $expensePaymentAttachmentName;
        }

        $addExpensePayment->save();

        return $addExpensePayment->id;
    }

    public function updatePayment($expensePayment, $request, $another_amount = 0)
    {
        $expensePayment->account_id = $request->account_id;
        $expensePayment->payment_method_id = $request->payment_method_id;
        $expensePayment->paid_amount = isset($request->paying_amount) ? $request->paying_amount : $another_amount;
        $expensePayment->date = $request->date;
        $expensePayment->report_date = date('Y-m-d', strtotime($request->date));
        $expensePayment->note = $request->payment_note;
        $expensePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            if ($expensePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $expensePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $expensePayment->attachment));
                }
            }
            $expensePaymentAttachment = $request->file('attachment');
            $expensePaymentAttachmentName = uniqid() . '-' . '.' . $expensePaymentAttachment->getClientOriginalExtension();
            // $expansePaymentAttachment->move(public_path('uploads/payment_attachment/'), $expensePaymentAttachmentName);
            $expensePayment->attachment = $expensePaymentAttachmentName;
        }

        $expensePayment->save();
    }

    public function expenseDelete($deleteExpense)
    {
        $storedExpenseAccountId = $deleteExpense->expense_account_id;

        $storedExpensePayments = $deleteExpense->expense_payments;

        if (!is_null($deleteExpense)) {

            $deleteExpense->delete();

            if (count($storedExpensePayments) > 0) {

                foreach ($storedExpensePayments as $payment) {

                    if ($payment->attachment) {

                        if (file_exists(public_path('uploads/payment_attachment/' . $payment->attachment))) {

                            unlink(public_path('uploads/payment_attachment/' . $payment->attachment));
                        }
                    }

                    // Update Bank/Cash-in-hand Balance
                    if ($payment->account_id) {

                        $this->accountUtil->adjustAccountBalance('debit', $payment->account_id);
                    }
                }
            }
        }

        // Update Expense A/C Balance
        if ($storedExpenseAccountId) {

            $this->accountUtil->adjustAccountBalance('credit', $storedExpenseAccountId);
        }
    }
}
