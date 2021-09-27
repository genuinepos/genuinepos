<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Account;
use App\Models\CashFlow;
use App\Models\Customer;
use App\Models\SalePayment;
use Illuminate\Http\Request;
use App\Models\CustomerLedger;
use App\Models\CustomerPayment;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerPaymentInvoice;
use App\Utils\AccountUtil;
use App\Utils\Converter;
use App\Utils\CustomerUtil;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public $customerUtil;
    public $accountUtil;
    public $converter;
    public function __construct(CustomerUtil $customerUtil, AccountUtil $accountUtil, Converter $converter)
    {
        $this->customerUtil = $customerUtil;
        $this->accountUtil = $accountUtil;
        $this->converter = $converter;
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if (auth()->user()->permission->customers['customer_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            
            $generalSettings = DB::table('general_settings')->first();
            $customers = DB::table('customers')
                ->leftJoin('customer_groups', 'customers.customer_group_id', 'customer_groups.id')
                ->select('customers.*', 'customer_groups.group_name');
            return DataTables::of($customers)
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';

                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="' . url('contacts/customers/view', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';

                    if ($row->total_sale_due > 0) {
                        $html .= '<a class="dropdown-item" id="pay_button" href="' . route('customers.payment', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Payment</a>';
                    }

                    $html .= '<a class="dropdown-item" id="view_payment" href="' . route('customers.view.payment', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> View Payment</a>';
                    $html .= '<a class="dropdown-item" id="money_receipt_list" href="' . route('money.receipt.voucher.list', [$row->id]) . '"><i class="far fa-file-alt text-primary"></i> Payment Receipt Voucher</a>';

                    if ($row->total_sale_return_due > 0) {
                        $html .= '<a class="dropdown-item" id="pay_return_button" href="' . route('customers.return.payment', $row->id) . '"><i class="far fa-money-bill-alt text-primary"></i> Pay Return Due</a>';
                    }

                    if (auth()->user()->permission->customers['customer_edit'] == '1') {
                        $html .= '<a class="dropdown-item" href="' . route('contacts.customer.edit', [$row->id]) . '" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->permission->customers['customer_delete'] == '1') {
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('contacts.customer.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    if ($row->status == 1) {
                        $html .= '<a class="dropdown-item" id="change_status" href="' . route('contacts.customer.change.status', [$row->id]) . '"><i class="far fa-thumbs-up text-success"></i> Change Status</a>';
                    } else {
                        $html .= '<a class="dropdown-item" id="change_status" href="' . route('contacts.customer.change.status', [$row->id]) . '"><i class="far fa-thumbs-down text-danger"></i> Change Status</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('business_name', function ($row) {
                    return $row->business_name ? $row->business_name : '...';
                })
                ->editColumn('tax_number', function ($row) {
                    return $row->tax_number ? $row->tax_number : '...';
                })
                ->editColumn('group_name', function ($row) {
                    return $row->group_name ? $row->group_name : '...';
                })
                ->editColumn('opening_balance', fn ($row) => $this->converter->format_in_bdt($row->opening_balance))
                ->editColumn('total_sale', fn ($row) => $this->converter->format_in_bdt($row->total_sale))
                ->editColumn('total_paid', fn ($row) => '<span class="text-success">' .$this->converter->format_in_bdt($row->total_paid) . '</span>')
                ->editColumn('total_sale_due', fn ($row) => '<span class="text-danger">' . $this->converter->format_in_bdt($row->total_sale_due) . '</span>')
                ->editColumn('total_return', fn ($row) => $this->converter->format_in_bdt($row->total_return))
                ->editColumn('total_sale_return_due', fn ($row) => $this->converter->format_in_bdt($row->total_sale_return_due))
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<i class="far fa-thumbs-up text-success"></i>';
                    } else {
                        return '<i class="far fa-thumbs-down text-danger"></i>';
                    }
                })
                ->rawColumns(['action', 'business_name', 'tax_number', 'group_name', 'opening_balance', 'total_sale', 'total_paid', 'total_sale_due', 'total_sale_return_due', 'status'])
                ->make(true);
        }

        $groups = DB::table('customer_groups')->get();
        return view('contacts.customers.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        // generate prefix dode ID
        $i = 5;
        $a = 0;
        $id = '';
        while ($a < $i) {
            $id .= rand(1, 9);
            $a++;
        }
        $generalSettings = DB::table('general_settings')->first('prefix');
        $cusIdPrefix = json_decode($generalSettings->prefix, true)['customer_id'];
        $addCustomer = Customer::create([
            'type' => $request->contact_type,
            'contact_id' => $request->contact_id ? $request->contact_id : $cusIdPrefix . $id,
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'landline' => $request->landline,
            'date_of_birth' => $request->date_of_birth,
            'tax_number' => $request->tax_number,
            'pay_term' => $request->pay_term,
            'pay_term_number' => $request->pay_term_number,
            'customer_group_id' => $request->customer_group_id,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'state' => $request->state,
            'shipping_address' => $request->shipping_address,
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0.00,
            'total_sale_due' => $request->opening_balance ? $request->opening_balance : 0.00,
        ]);

        $addCustomerLedger = new CustomerLedger();
        $addCustomerLedger->customer_id = $addCustomer->id;
        $addCustomerLedger->row_type = 3;
        $addCustomerLedger->report_date = date('Y-m-d');
        $addCustomerLedger->amount = $request->opening_balance ? $request->opening_balance : 0.00;
        $addCustomerLedger->save();

        return response()->json('Customer created successfully');
    }

    public function edit($customerId)
    {
        $customer = DB::table('customers')->where('id', $customerId)->first();
        $groups = DB::table('customer_groups')->get();
        return view('contacts.customers.ajax_view.edit', compact('customer', 'groups'));
    }

    public function getCustomer($customerId)
    {
        $customer = Customer::where('id', $customerId)->first();
        return response()->json($customer);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        Customer::where('id', $request->id)->update([
            'type' => $request->contact_type,
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'landline' => $request->landline,
            'date_of_birth' => $request->date_of_birth,
            'tax_number' => $request->tax_number,
            'pay_term' => $request->pay_term,
            'pay_term_number' => $request->pay_term_number,
            'customer_group_id' => $request->customer_group_id,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'state' => $request->state,
            'shipping_address' => $request->shipping_address,
        ]);

        return response()->json('Customer updated successfully');
    }

    public function delete(Request $request, $customerId)
    {
        $deleteCustomer = Customer::find($customerId);
        if (!is_null($deleteCustomer)) {
            $deleteCustomer->delete();
        }
        return response()->json('Customer deleted successfully');
    }

    // Change status method
    public function changeStatus($customerId)
    {
        $statusChange = Customer::where('id', $customerId)->first();
        if ($statusChange->status == 1) {
            $statusChange->status = 0;
            $statusChange->save();
            return response()->json('Customer deactivated successfully');
        } else {
            $statusChange->status = 1;
            $statusChange->save();
            return response()->json('Customer activated successfully');
        }
    }

    // Customer view method
    public function view(Request $request, $customerId)
    {
        $customerId = $customerId;
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $sales = '';
            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $sales = DB::table('sales')
                    ->leftJoin('branches', 'sales.branch_id', 'branches.id')
                    ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                    ->select(
                        'sales.*',
                        'branches.name as branch_name',
                        'branches.branch_code',
                        'customers.name as customer_name',
                    )->where('sales.customer_id', $customerId)
                    ->orderBy('id', 'desc');
            } else {
                $sales = DB::table('sales')
                    ->leftJoin('branches', 'sales.branch_id', 'branches.id')
                    ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                    ->select(
                        'sales.*',
                        'branches.name as branch_name',
                        'branches.branch_code',
                        'customers.name as customer_name',
                    )->where('sales.customer_id', $customerId)
                    ->where('sales.branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'desc');
            }

            return DataTables::of($sales)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.show', [$row->id]) . '"><i
                                    class="far fa-eye text-primary"></i> View</a>';

                    $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Packing Slip</a>';

                    if (auth()->user()->permission->sale['shipment_access'] == '1') {
                        $html .= '<a class="dropdown-item" id="edit_shipment"
                            href="' . route('sales.shipment.edit', [$row->id]) . '"><i
                            class="fas fa-truck text-primary"></i> Edit Shipping</a>';
                    }

                    if (auth()->user()->branch_id == $row->branch_id) {
                        if ($row->due > 0) {
                            if (auth()->user()->permission->sale['sale_payment'] == '1') {
                                $html .= '<a class="dropdown-item" id="add_payment" href="' . route('sales.payment.modal', [$row->id]) . '" 
                            ><i class="far fa-money-bill-alt text-primary"></i> Add Payment</a>';
                            }
                        }

                        if (auth()->user()->permission->sale['sale_payment'] == '1') {
                            $html .= '<a class="dropdown-item" id="view_payment" data-toggle="modal"
                        data-target="#paymentListModal" href="' . route('sales.payment.view', [$row->id]) . '"><i
                            class="far fa-money-bill-alt text-primary"></i> View Payment</a>';
                        }

                        if (auth()->user()->permission->sale['return_access'] == '1') {
                            $html .= '<a class="dropdown-item" href="' . route('sales.returns.create', [$row->id]) . '"><i class="fas fa-undo-alt text-primary"></i> Sale Return</a>';
                        }

                        if ($row->created_by == 1) {
                            $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                        } else {
                            $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                        }

                        $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i
                        class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    if ($row->sale_return_due > 0) {
                        if (auth()->user()->permission->sale['sale_payment'] == '1') {
                            $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('sales.return.payment.modal', [$row->id]) . '" 
                        ><i class="far fa-money-bill-alt text-primary"></i> Pay Return Amount</a>';
                        }
                    }

                    $html .= '<a class="dropdown-item" id="items_notification" href=""><i
                                    class="fas fa-envelope text-primary"></i> New Sale Notification</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('invoice_id', function ($row) {
                    $html = '';
                    $html .= $row->is_return_available ? '<br>' : '';
                    $html .= $row->invoice_id;
                    $html .= $row->is_return_available ? '<span class="badge rounded bg-danger p-0 pl-1 pb-1"><i style="font-size:11px;margin-top:3px;" class="fas fa-undo mr-1 text-white"></i></span>' : '';
                    return $html;
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('customer',  function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('total_payable_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</b>';
                })
                ->editColumn('paid', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</b>';
                })
                ->editColumn('due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-success">' . json_decode($generalSettings->business, true)['currency'] . ($row->due >= 0 ? $row->due :   0.00) . '</span></b>';
                })
                ->editColumn('sale_return_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->sale_return_amount . '</b>';
                })
                ->editColumn('sale_return_due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-danger">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->sale_return_due . '</span></b>';
                })
                ->editColumn('paid_status', function ($row) {
                    $payable = $row->total_payable_amount - $row->sale_return_amount;
                    $html = '';
                    if ($row->due <= 0) {
                        $html .= '<span class="badge bg-success">Paid</span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        $html .= '<span class="badge bg-primary text-white">Partial</span>';
                    } elseif ($payable == $row->due) {
                        $html .= '<span class="badge bg-danger text-white">Due</span>';
                    }
                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('sales.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row')
                ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status'])
                ->make(true);
        }
        $customer = DB::table('customers')->where('id', $customerId)->first(['name', 'contact_id']);
        return view('contacts.customers.view', compact('customerId', 'customer'));
    }

    // Customer all info
    public function cutomerAllInfo($customerId)
    {
        $customer = Customer::where('id', $customerId)->first();
        return response()->json($customer);
    }

    // Customer ledger list
    public function ledgerList($customerId)
    {
        $ledgers = CustomerLedger::with(
            [
                'sale',
                'sale.sale_products',
                'sale.sale_products.product',
                'sale.sale_products.variant',
                'sale_payment',
                'sale_payment.account',
                'sale_payment.sale',
                'money_receipt',
                'customer_payment'
            ]
        )->where('customer_id', $customerId)->orderBy('report_date', 'ASC')->get();

        $customer = DB::table('customers')->where('id', $customerId)->select('id', 'contact_id', 'name')->first();
        return view('contacts.customers.ajax_view.ledger_list', compact('ledgers', 'customer'));
    }

    // Customer ledger list
    public function ledgerPrint($customerId)
    {
        $ledgers = CustomerLedger::with(
            [
                'sale',
                'sale.sale_products',
                'sale.sale_products.product',
                'sale.sale_products.variant',
                'sale_payment',
                'sale_payment.account',
                'sale_payment.sale',
                'money_receipt',
                'customer_payment'
            ]
        )->where('customer_id', $customerId)->orderBy('report_date', 'ASC')->get();

        $customer = DB::table('customers')->where('id', $customerId)->select(
            'id',
            'contact_id',
            'name',
            'phone',
            'address',
            'opening_balance',
            'total_sale',
            'total_paid',
            'total_sale_due',
            'total_sale_return_due',
        )->first();
        return view('contacts.customers.ajax_view.print_ledger', compact('ledgers', 'customer'));
    }

    // Customer payment view
    public function payment($customerId)
    {
        $customer = DB::table('customers')->where('id', $customerId)->first();
        $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        return view('contacts.customers.ajax_view.payment_modal', compact('customer', 'accounts'));
    }

    public function paymentAdd(Request $request, $customerId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

        // generate invoice ID
        $l = 6;
        $sv = 0;
        $voucherNo = '';
        while ($sv < $l) {
            $voucherNo .= rand(1, 9);
            $sv++;
        }

        // Add Customer Payment Record
        $customerPayment = new CustomerPayment();
        $customerPayment->voucher_no = 'CPV' . $voucherNo;
        $customerPayment->branch_id = auth()->user()->branch_id;
        $customerPayment->customer_id = $customerId;
        $customerPayment->account_id = $request->account_id;
        $customerPayment->paid_amount = $request->amount;
        $customerPayment->pay_mode = $request->payment_method;
        $customerPayment->date = $request->date;
        $customerPayment->time = date('h:i:s a');
        $customerPayment->month = date('F');
        $customerPayment->year = date('Y');

        if ($request->payment_method == 'Card') {
            $customerPayment->card_no = $request->card_no;
            $customerPayment->card_holder = $request->card_holder_name;
            $customerPayment->card_transaction_no = $request->card_transaction_no;
            $customerPayment->card_type = $request->card_type;
            $customerPayment->card_month = $request->month;
            $customerPayment->card_year = $request->year;
            $customerPayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $customerPayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $customerPayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $customerPayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            $PaymentAttachment = $request->file('attachment');
            $paymentAttachmentName = uniqid() . '-' . '.' . $PaymentAttachment->getClientOriginalExtension();
            $PaymentAttachment->move(public_path('uploads/payment_attachment/'), $paymentAttachmentName);
            $customerPayment->attachment = $paymentAttachmentName;
        }

        $customerPayment->note = $request->note;
        $customerPayment->save();

        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->credit += $request->amount;
            $account->balance += $request->amount;
            $account->save();

            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->credit = $request->amount;
            $addCashFlow->balance = $account->balance;
            $addCashFlow->customer_payment_id = $customerPayment->id;
            $addCashFlow->transaction_type = 13;
            $addCashFlow->cash_type = 2;
            $addCashFlow->date = date('d-m-Y', strtotime($request->date));
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
        }

        // Add customer payment for direct payment
        $addCustomerLedger = new CustomerLedger();
        $addCustomerLedger->customer_id = $customerId;
        $addCustomerLedger->row_type = 5;
        $addCustomerLedger->customer_payment_id = $customerPayment->id;
        $addCustomerLedger->report_date = date('Y-m-d', strtotime($request->date));
        $addCustomerLedger->save();

        // generate invoice ID
        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $dueInvoices = Sale::where('customer_id', $customerId)->where('due', '>', 0)->get();
        if (count($dueInvoices) > 0) {
            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {
                if ($dueInvoice->due > $request->amount) {
                    if ($request->amount > 0) {
                        $dueInvoice->paid += $request->amount;
                        $dueInvoice->due -= $request->amount;
                        $dueInvoice->save();
                        $addSalePayment = new SalePayment();
                        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                        $addSalePayment->sale_id = $dueInvoice->id;
                        $addSalePayment->customer_id = $customerId;
                        $addSalePayment->account_id = $request->account_id;
                        $addSalePayment->customer_payment_id = $customerPayment->id;
                        $addSalePayment->paid_amount = $request->amount;
                        $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                        $addSalePayment->time = date('h:i:s a');
                        $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                        $addSalePayment->month = date('F');
                        $addSalePayment->year = date('Y');
                        $addSalePayment->pay_mode = $request->payment_method;

                        if ($request->payment_method == 'Card') {
                            $addSalePayment->card_no = $request->card_no;
                            $addSalePayment->card_holder = $request->card_holder_name;
                            $addSalePayment->card_transaction_no = $request->card_transaction_no;
                            $addSalePayment->card_type = $request->card_type;
                            $addSalePayment->card_month = $request->month;
                            $addSalePayment->card_year = $request->year;
                            $addSalePayment->card_secure_code = $request->secure_code;
                        } elseif ($request->payment_method == 'Cheque') {
                            $addSalePayment->cheque_no = $request->cheque_no;
                        } elseif ($request->payment_method == 'Bank-Transfer') {
                            $addSalePayment->account_no = $request->account_no;
                        } elseif ($request->payment_method == 'Custom') {
                            $addSalePayment->transaction_no = $request->transaction_no;
                        }

                        $addSalePayment->admin_id = auth()->user()->id;
                        $addSalePayment->payment_on = 1;
                        $addSalePayment->save();

                        // Add Customer Payment invoice
                        $addCustomerPaymentInvoice = new CustomerPaymentInvoice();
                        $addCustomerPaymentInvoice->customer_payment_id = $customerPayment->id;
                        $addCustomerPaymentInvoice->sale_id = $dueInvoice->id;
                        $addCustomerPaymentInvoice->paid_amount = $request->amount;
                        $addCustomerPaymentInvoice->save();
                        $request->amount -= $request->amount;
                    }
                } elseif ($dueInvoice->due == $request->amount) {
                    if ($request->amount > 0) {
                        $dueInvoice->paid += $request->amount;
                        $dueInvoice->due -= $request->amount;
                        $dueInvoice->save();
                        $addSalePayment = new SalePayment();
                        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                        $addSalePayment->sale_id = $dueInvoice->id;
                        $addSalePayment->customer_id = $customerId;
                        $addSalePayment->account_id = $request->account_id;
                        $addSalePayment->customer_payment_id = $customerPayment->id;
                        $addSalePayment->paid_amount = $request->amount;
                        $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                        $addSalePayment->time = date('h:i:s a');
                        $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                        $addSalePayment->month = date('F');
                        $addSalePayment->year = date('Y');
                        $addSalePayment->pay_mode = $request->payment_method;

                        if ($request->payment_method == 'Card') {
                            $addSalePayment->card_no = $request->card_no;
                            $addSalePayment->card_holder = $request->card_holder_name;
                            $addSalePayment->card_transaction_no = $request->card_transaction_no;
                            $addSalePayment->card_type = $request->card_type;
                            $addSalePayment->card_month = $request->month;
                            $addSalePayment->card_year = $request->year;
                            $addSalePayment->card_secure_code = $request->secure_code;
                        } elseif ($request->payment_method == 'Cheque') {
                            $addSalePayment->cheque_no = $request->cheque_no;
                        } elseif ($request->payment_method == 'Bank-Transfer') {
                            $addSalePayment->account_no = $request->account_no;
                        } elseif ($request->payment_method == 'Custom') {
                            $addSalePayment->transaction_no = $request->transaction_no;
                        }

                        $addSalePayment->admin_id = auth()->user()->id;
                        $addSalePayment->payment_on = 1;
                        $addSalePayment->save();

                        // Add Customer Payment invoice
                        $addCustomerPaymentInvoice = new CustomerPaymentInvoice();
                        $addCustomerPaymentInvoice->customer_payment_id = $customerPayment->id;
                        $addCustomerPaymentInvoice->sale_id = $dueInvoice->id;
                        $addCustomerPaymentInvoice->paid_amount = $request->amount;
                        $addCustomerPaymentInvoice->save();
                        $request->amount -= $request->amount;
                    }
                } elseif ($dueInvoice->due < $request->amount) {
                    if ($dueInvoice->due > 0) {
                        $addSalePayment = new SalePayment();
                        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                        $addSalePayment->sale_id = $dueInvoice->id;
                        $addSalePayment->customer_id = $customerId;
                        $addSalePayment->account_id = $request->account_id;
                        $addSalePayment->customer_payment_id = $customerPayment->id;
                        $addSalePayment->paid_amount = $dueInvoice->due;
                        $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                        $addSalePayment->time = date('h:i:s a');
                        $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                        $addSalePayment->month = date('F');
                        $addSalePayment->year = date('Y');
                        $addSalePayment->pay_mode = $request->payment_method;

                        if ($request->payment_method == 'Card') {
                            $addSalePayment->card_no = $request->card_no;
                            $addSalePayment->card_holder = $request->card_holder_name;
                            $addSalePayment->card_transaction_no = $request->card_transaction_no;
                            $addSalePayment->card_type = $request->card_type;
                            $addSalePayment->card_month = $request->month;
                            $addSalePayment->card_year = $request->year;
                            $addSalePayment->card_secure_code = $request->secure_code;
                        } elseif ($request->payment_method == 'Cheque') {
                            $addSalePayment->cheque_no = $request->cheque_no;
                        } elseif ($request->payment_method == 'Bank-Transfer') {
                            $addSalePayment->account_no = $request->account_no;
                        } elseif ($request->payment_method == 'Custom') {
                            $addSalePayment->transaction_no = $request->transaction_no;
                        }

                        $addSalePayment->admin_id = auth()->user()->id;
                        $addSalePayment->payment_on = 1;
                        $addSalePayment->save();

                        // Add Customer Payment invoice
                        $addCustomerPaymentInvoice = new CustomerPaymentInvoice();
                        $addCustomerPaymentInvoice->customer_payment_id = $customerPayment->id;
                        $addCustomerPaymentInvoice->sale_id = $dueInvoice->id;
                        $addCustomerPaymentInvoice->paid_amount = $dueInvoice->due;
                        $addCustomerPaymentInvoice->save();

                        $request->amount -= $dueInvoice->due;
                        $dueInvoice->paid += $dueInvoice->due;
                        $dueInvoice->due -= $dueInvoice->due;
                        $dueInvoice->save();
                    }
                }
                $index++;
            }
        }

        $this->customerUtil->adjustCustomerAmountForSalePaymentDue($customerId);
        return response()->json('payment added successfully.');
    }

    public function returnPayment($customerId)
    {
        $customer = DB::table('customers')->where('id', $customerId)->first();
        $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        return view('contacts.customers.ajax_view.return_payment_modal', compact('customer', 'accounts'));
    }

    public function returnPaymentAdd(Request $request, $customerId)
    {
        $customer = Customer::where('id', $customerId)->first();
        $customer->total_sale_return_due -= $request->amount;
        $customer->save();
        $dueReturnInvoices = Sale::with('sale_return')->where('customer_id', $customerId)->where('sale_return_due', '>', 0)->get();

        if (count($dueReturnInvoices) > 0) {
            $index = 0;
            foreach ($dueReturnInvoices as $dueReturnInvoice) {
                if ($dueReturnInvoice->sale_return_due > $request->amount) {
                    // Update sale
                    $dueReturnInvoice->sale_return_due -= $request->amount;
                    $dueReturnInvoice->save();

                    // update sale return
                    $dueReturnInvoice->sale_return->total_return_due_pay += $request->amount;
                    $dueReturnInvoice->sale_return->total_return_due -= $request->amount;
                    $dueReturnInvoice->sale_return->save();

                    // generate invoice ID
                    $i = 5;
                    $a = 0;
                    $invoiceId = '';
                    while ($a < $i) {
                        $invoiceId .= rand(1, 9);
                        $a++;
                    }

                    // Add sale payment
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = 'SRPI' . date('dmy') . $invoiceId;
                    $addSalePayment->sale_id = $dueReturnInvoice->id;
                    $addSalePayment->customer_id = $dueReturnInvoice->customer_id ? $dueReturnInvoice->customer_id : NULL;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->pay_mode = $request->payment_method;
                    $addSalePayment->payment_type = 2;
                    $addSalePayment->paid_amount = $request->amount;
                    $addSalePayment->date = date('d-m-y', strtotime($request->date));
                    $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addSalePayment->month = date('F');
                    $addSalePayment->year = date('Y');
                    $addSalePayment->note = $request->note;

                    if ($request->payment_method == 'Card') {
                        $addSalePayment->card_no = $request->card_no;
                        $addSalePayment->card_holder = $request->card_holder_name;
                        $addSalePayment->card_transaction_no = $request->card_transaction_no;
                        $addSalePayment->card_type = $request->card_type;
                        $addSalePayment->card_month = $request->month;
                        $addSalePayment->card_year = $request->year;
                        $addSalePayment->card_secure_code = $request->secure_code;
                    } elseif ($request->payment_method == 'Cheque') {
                        $addSalePayment->cheque_no = $request->cheque_no;
                    } elseif ($request->payment_method == 'Bank-Transfer') {
                        $addSalePayment->account_no = $request->account_no;
                    } elseif ($request->payment_method == 'Custom') {
                        $addSalePayment->transaction_no = $request->transaction_no;
                    }
                    $addSalePayment->admin_id = auth()->user()->id;

                    if ($request->hasFile('attachment')) {
                        $salePaymentAttachment = $request->file('attachment');
                        $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
                        $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
                        $addSalePayment->attachment = $salePaymentAttachmentName;
                    }
                    $addSalePayment->save();

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
                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 1;
                        $addCashFlow->date = date('d-m-y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($customer) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $customer->id;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }
                    if ($index == 1) {
                        break;
                    }
                } elseif ($dueReturnInvoice->sale_return_due == $request->amount) {
                    // Update sale
                    $dueReturnInvoice->sale_return_due -= $request->amount;
                    $dueReturnInvoice->save();

                    // update sale return
                    $dueReturnInvoice->sale_return->total_return_due_pay += $request->amount;
                    $dueReturnInvoice->sale_return->total_return_due -= $request->amount;
                    $dueReturnInvoice->sale_return->save();

                    // generate invoice ID
                    $i = 5;
                    $a = 0;
                    $invoiceId = '';
                    while ($a < $i) {
                        $invoiceId .= rand(1, 9);
                        $a++;
                    }

                    // Add sale payment
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = 'SRPI' . date('dmy') . $invoiceId;
                    $addSalePayment->sale_id = $dueReturnInvoice->id;
                    $addSalePayment->customer_id = $dueReturnInvoice->customer_id ? $dueReturnInvoice->customer_id : NULL;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->pay_mode = $request->payment_method;
                    $addSalePayment->payment_type = 2;
                    $addSalePayment->paid_amount = $request->amount;
                    $addSalePayment->date = date('d-m-y', strtotime($request->date));
                    $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addSalePayment->month = date('F');
                    $addSalePayment->year = date('Y');
                    $addSalePayment->note = $request->note;

                    if ($request->payment_method == 'Card') {
                        $addSalePayment->card_no = $request->card_no;
                        $addSalePayment->card_holder = $request->card_holder_name;
                        $addSalePayment->card_transaction_no = $request->card_transaction_no;
                        $addSalePayment->card_type = $request->card_type;
                        $addSalePayment->card_month = $request->month;
                        $addSalePayment->card_year = $request->year;
                        $addSalePayment->card_secure_code = $request->secure_code;
                    } elseif ($request->payment_method == 'Cheque') {
                        $addSalePayment->cheque_no = $request->cheque_no;
                    } elseif ($request->payment_method == 'Bank-Transfer') {
                        $addSalePayment->account_no = $request->account_no;
                    } elseif ($request->payment_method == 'Custom') {
                        $addSalePayment->transaction_no = $request->transaction_no;
                    }
                    $addSalePayment->admin_id = auth()->user()->id;

                    if ($request->hasFile('attachment')) {
                        $salePaymentAttachment = $request->file('attachment');
                        $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
                        $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
                        $addSalePayment->attachment = $salePaymentAttachmentName;
                    }
                    $addSalePayment->save();

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
                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 1;
                        $addCashFlow->date = date('d-m-y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($customer) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $customer->id;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }
                    if ($index == 1) {
                        break;
                    }
                } elseif ($dueReturnInvoice->sale_return_due < $request->amount) {
                    // Update sale
                    $dueReturnInvoice->sale_return_due -= $dueReturnInvoice->sale_return_due;
                    $dueReturnInvoice->save();

                    // update sale return
                    $request->amount -= $dueReturnInvoice->sale_return_due;
                    $dueReturnInvoice->sale_return->total_return_due_pay += $dueReturnInvoice->sale_return_due;
                    $dueReturnInvoice->sale_return->total_return_due -= $dueReturnInvoice->sale_return_due;
                    $dueReturnInvoice->sale_return->save();

                    // generate invoice ID
                    $i = 5;
                    $a = 0;
                    $invoiceId = '';
                    while ($a < $i) {
                        $invoiceId .= rand(1, 9);
                        $a++;
                    }

                    // Add sale payment
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = 'SRPI' . date('dmy') . $invoiceId;
                    $addSalePayment->sale_id = $dueReturnInvoice->id;
                    $addSalePayment->customer_id = $dueReturnInvoice->customer_id ? $dueReturnInvoice->customer_id : NULL;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->pay_mode = $request->payment_method;
                    $addSalePayment->payment_type = 2;
                    $addSalePayment->paid_amount = $dueReturnInvoice->sale_return_due;
                    $addSalePayment->date = date('d-m-y', strtotime($request->date));
                    $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addSalePayment->month = date('F');
                    $addSalePayment->year = date('Y');
                    $addSalePayment->note = $request->note;

                    if ($request->payment_method == 'Card') {
                        $addSalePayment->card_no = $request->card_no;
                        $addSalePayment->card_holder = $request->card_holder_name;
                        $addSalePayment->card_transaction_no = $request->card_transaction_no;
                        $addSalePayment->card_type = $request->card_type;
                        $addSalePayment->card_month = $request->month;
                        $addSalePayment->card_year = $request->year;
                        $addSalePayment->card_secure_code = $request->secure_code;
                    } elseif ($request->payment_method == 'Cheque') {
                        $addSalePayment->cheque_no = $request->cheque_no;
                    } elseif ($request->payment_method == 'Bank-Transfer') {
                        $addSalePayment->account_no = $request->account_no;
                    } elseif ($request->payment_method == 'Custom') {
                        $addSalePayment->transaction_no = $request->transaction_no;
                    }
                    $addSalePayment->admin_id = auth()->user()->id;

                    if ($request->hasFile('attachment')) {
                        $salePaymentAttachment = $request->file('attachment');
                        $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
                        $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
                        $addSalePayment->attachment = $salePaymentAttachmentName;
                    }
                    $addSalePayment->save();

                    if ($request->account_id) {
                        // update account
                        $account = Account::where('id', $request->account_id)->first();
                        $account->debit += $dueReturnInvoice->sale_return_due;
                        $account->balance -= $dueReturnInvoice->sale_return_due;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->debit = $dueReturnInvoice->sale_return_due;
                        $addCashFlow->balance = $account->balance;
                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 1;
                        $addCashFlow->date = date('d-m-y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($customer) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $customer->id;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }
                }
                $index++;
            }
        }

        return response()->json('Return amount paid successfully.');
    }

    public function viewPayment($customerId)
    {
        $customer = Customer::with(
            'customer_payments',
            'customer_payments.account:id,name'
        )->where('id', $customerId)->first();
        return view('contacts.customers.ajax_view.view_payment_list', compact('customer'));
    }

    // Customer Payment Details
    public function paymentDetails($paymentId)
    {
        $customerPayment = CustomerPayment::with(
            'branch',
            'customer',
            'account',
            'customer_payment_invoices',
            'customer_payment_invoices.sale:id,invoice_id,date'
        )->where('id', $paymentId)->first();
        return view('contacts.customers.ajax_view.payment_details', compact('customerPayment'));
    }

    // Customer Payment Delete
    public function paymentDelete(Request $request, $paymentId)
    {
        $deleteCustomerPayment = CustomerPayment::with('customer_payment_invoices')->where('id', $paymentId)->first();
        if ($deleteCustomerPayment->attachment != null) {
            if (file_exists(public_path('uploads/payment_attachment/' . $deleteCustomerPayment->attachment))) {
                unlink(public_path('uploads/payment_attachment/' . $deleteCustomerPayment->attachment));
            }
        }

        if ($deleteCustomerPayment->account_id) {
            if ($deleteCustomerPayment->type == 1) {
                $account = Account::where('id', $deleteCustomerPayment->account_id)->first();
                $account->credit -= $deleteCustomerPayment->paid_amount;
                $account->balance -= $deleteCustomerPayment->paid_amount;
                $account->save();
            } else {
                $account = Account::where('id', $deleteCustomerPayment->account_id)->first();
                $account->debit -= $deleteCustomerPayment->paid_amount;
                $account->balance += $deleteCustomerPayment->paid_amount;
                $account->save();
            }
        }

        // Update Customer payment invoices
        if (count($deleteCustomerPayment->customer_payment_invoices) > 0) {
            foreach ($deleteCustomerPayment->customer_payment_invoices as $sInvoice) {
                if ($deleteCustomerPayment->type == 1) {
                    $updateSale = Sale::where('id', $sInvoice->sale_id)->first();
                    $updateSale->paid -= $sInvoice->paid_amount;
                    $updateSale->due += $sInvoice->paid_amount;
                    $updateSale->save();
                }
            }
        }

        $deleteCustomerPayment->delete();

        //Update customer info
        $customer = Customer::where('id', $deleteCustomerPayment->customer_id)->first();
        if ($deleteCustomerPayment->type == 1) {
            $this->customerUtil->adjustCustomerAmountForSalePaymentDue($deleteCustomerPayment->customer_id);
        } else {
            if ($customer) {
                $customer->total_sale_return_due += $deleteCustomerPayment->paid_amount;
                $customer->save();
            }
        }
        return response()->json('Payment deleted successfully.');
    }
}
