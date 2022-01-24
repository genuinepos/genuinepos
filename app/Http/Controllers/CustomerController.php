<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Account;
use App\Models\CashFlow;
use App\Models\Customer;
use App\Utils\Converter;
use App\Utils\AccountUtil;
use App\Models\SalePayment;
use App\Utils\CustomerUtil;
use Illuminate\Http\Request;
use App\Models\CustomerLedger;
use App\Models\CustomerPayment;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerPaymentInvoice;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\SaleUtil;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public $customerUtil;
    public $accountUtil;
    public $converter;
    public $invoiceVoucherRefIdUtil;
    public $saleUtil;
    
    public function __construct(CustomerUtil $customerUtil, AccountUtil $accountUtil, Converter $converter, SaleUtil $saleUtil, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        $this->customerUtil = $customerUtil;
        $this->accountUtil = $accountUtil;
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->saleUtil = $saleUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if (auth()->user()->permission->contact['customer_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            return $this->customerUtil->customerListTable();
        }

        $groups = DB::table('customer_groups')->get();
        return view('contacts.customers.index', compact('groups'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->permission->contact['customer_add'] == '0') {
            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $generalSettings = DB::table('general_settings')->first('prefix');
        $cusIdPrefix = json_decode($generalSettings->prefix, true)['customer_id'];
        $addCustomer = Customer::create([
            'contact_id' => $request->contact_id ? $request->contact_id : $cusIdPrefix . str_pad($this->invoiceVoucherRefIdUtil->getLastId('customers'), 4, "0", STR_PAD_LEFT),
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
            'credit_limit' => $request->credit_limit,
            'total_sale_due' => $request->opening_balance ? $request->opening_balance : 0.00,
        ]);

        $addCustomerLedger = new CustomerLedger();
        $addCustomerLedger->customer_id = $addCustomer->id;
        $addCustomerLedger->row_type = 3;
        $addCustomerLedger->report_date = date('Y-m-d H:i:s');
        $addCustomerLedger->amount = $request->opening_balance ? $request->opening_balance : 0.00;
        $addCustomerLedger->save();

        return response()->json('Customer created successfully');
    }

    public function edit($customerId)
    {
        if (auth()->user()->permission->contact['customer_edit'] == '0') {
            return response()->json('Access Denied');
        }
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
        if (auth()->user()->permission->contact['customer_edit'] == '0') {
            return response()->json('Access Denied');
        }
        
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        Customer::where('id', $request->id)->update([
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
            'credit_limit' => $request->credit_limit,
        ]);

        return response()->json('Customer updated successfully');
    }

    public function delete(Request $request, $customerId)
    {
        if (auth()->user()->permission->contact['customer_delete'] == '0') {
            return response()->json('Access Denied');
        }

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
                    ->where('sales.status', 1)
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
                    ->where('sales.status', 1)
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
                    $html .= $row->invoice_id;
                    $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo mr-1 text-white"></i></span>' : '';
                    return $html;
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('customer',  function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('total_payable_amount', fn ($row) => $this->converter->format_in_bdt($row->total_payable_amount))
                ->editColumn('paid', fn ($row) => '<span class="text-success">' . $this->converter->format_in_bdt($row->paid) . '</span>')
                ->editColumn('due', fn ($row) =>  '<span class="text-danger">' . $this->converter->format_in_bdt($row->due) . '</span>')
                ->editColumn('sale_return_amount', fn ($row) => $this->converter->format_in_bdt($row->sale_return_amount))
                ->editColumn('sale_return_due', fn ($row) => '<span class="text-danger">' . $this->converter->format_in_bdt($row->sale_return_due) . '</span>')
                ->editColumn('paid_status', function ($row) {
                    $payable = $row->total_payable_amount - $row->sale_return_amount;
                    if ($row->due <= 0) {
                        return '<span class="text-success"><b>Paid</b></span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        return '<span class="text-primary"><b>Partial</b></span>';
                    } elseif ($payable == $row->due) {
                        return '<span class="text-danger"><b>Due</b></span>';
                    }
                })
                ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status'])
                ->make(true);
        }
        $customer = DB::table('customers')->where('id', $customerId)->first();
        return view('contacts.customers.view', compact('customerId', 'customer'));
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

        // Add Customer Payment Record
        $customerPayment = new CustomerPayment();
        $customerPayment->voucher_no = 'CPV' . date('my') . $this->invoiceVoucherRefIdUtil->customerPaymentVoucherNo();
        $customerPayment->branch_id = auth()->user()->branch_id;
        $customerPayment->customer_id = $customerId;
        $customerPayment->account_id = $request->account_id;
        $customerPayment->paid_amount = $request->amount;
        $customerPayment->pay_mode = $request->payment_method;
        $customerPayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
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
            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->credit = $request->amount;
            $addCashFlow->customer_payment_id = $customerPayment->id;
            $addCashFlow->transaction_type = 13;
            $addCashFlow->cash_type = 2;
            $addCashFlow->date = date('d-m-Y', strtotime($request->date));
            $addCashFlow->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
            $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
            $addCashFlow->save();
        }

        // Add customer payment for direct payment
        $addCustomerLedger = new CustomerLedger();
        $addCustomerLedger->customer_id = $customerId;
        $addCustomerLedger->row_type = 5;
        $addCustomerLedger->customer_payment_id = $customerPayment->id;
        $addCustomerLedger->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addCustomerLedger->save();

        $dueInvoices = Sale::where('customer_id', $customerId)->where('due', '>', 0)->get();
        if (count($dueInvoices) > 0) {
            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {
                if ($dueInvoice->due > $request->amount) {
                    if ($request->amount > 0) {
                        $addSalePayment = new SalePayment();
                        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . date('my') . $this->invoiceVoucherRefIdUtil->salePaymentVoucherNo();
                        $addSalePayment->sale_id = $dueInvoice->id;
                        $addSalePayment->customer_id = $customerId;
                        $addSalePayment->account_id = $request->account_id;
                        $addSalePayment->customer_payment_id = $customerPayment->id;
                        $addSalePayment->paid_amount = $request->amount;
                        $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                        $addSalePayment->time = date('h:i:s a');
                        $addSalePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
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
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                        $request->amount -= $request->amount;
                    }
                } elseif ($dueInvoice->due == $request->amount) {
                    if ($request->amount > 0) {
                        $addSalePayment = new SalePayment();
                        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . date('my') . $this->invoiceVoucherRefIdUtil->salePaymentVoucherNo();
                        $addSalePayment->sale_id = $dueInvoice->id;
                        $addSalePayment->customer_id = $customerId;
                        $addSalePayment->account_id = $request->account_id;
                        $addSalePayment->customer_payment_id = $customerPayment->id;
                        $addSalePayment->paid_amount = $request->amount;
                        $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                        $addSalePayment->time = date('h:i:s a');
                        $addSalePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
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
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                        $request->amount -= $request->amount;
                    }
                } elseif ($dueInvoice->due < $request->amount) {
                    if ($dueInvoice->due > 0) {
                        $addSalePayment = new SalePayment();
                        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . date('my') . $this->invoiceVoucherRefIdUtil->salePaymentVoucherNo();
                        $addSalePayment->sale_id = $dueInvoice->id;
                        $addSalePayment->customer_id = $customerId;
                        $addSalePayment->account_id = $request->account_id;
                        $addSalePayment->customer_payment_id = $customerPayment->id;
                        $addSalePayment->paid_amount = $dueInvoice->due;
                        $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                        $addSalePayment->time = date('h:i:s a');
                        $addSalePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
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
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
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
        // Add Customer Payment Record
        $customerPayment = new CustomerPayment();
        $customerPayment->voucher_no = 'RPV' . date('my') . $this->invoiceVoucherRefIdUtil->customerPaymentVoucherNo();
        $customerPayment->branch_id = auth()->user()->branch_id;
        $customerPayment->customer_id = $customerId;
        $customerPayment->account_id = $request->account_id;
        $customerPayment->paid_amount = $request->amount;
        $customerPayment->type = 2;
        $customerPayment->pay_mode = $request->payment_method;
        $customerPayment->date = $request->date;
        $customerPayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
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
            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->debit = $request->amount;
            $addCashFlow->customer_payment_id = $customerPayment->id;
            $addCashFlow->transaction_type = 13;
            $addCashFlow->cash_type = 1;
            $addCashFlow->date = date('d-m-y', strtotime($request->date));
            $addCashFlow->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
            $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
            $addCashFlow->save();
        }

        // Add Customer payment for direct payment
        $addCustomerLedger = new CustomerLedger();
        $addCustomerLedger->customer_id = $customerId;
        $addCustomerLedger->row_type = 5;
        $addCustomerLedger->customer_payment_id = $customerPayment->id;
        $addCustomerLedger->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addCustomerLedger->save();

        $returnSales = Sale::with(['sale_return'])->where('sale_return_due', '>', 0)->get();
        if (count($returnSales) > 0) {
            $index = 0;
            foreach ($returnSales as $returnSale) {
                if ($returnSale->sale_return_due > $request->amount) {
                    if ($request->amount > 0) {
                        // Add sale payment
                        $addSalePayment = new SalePayment();
                        $addSalePayment->invoice_id = 'RPV' . date('my') . $this->invoiceVoucherRefIdUtil->salePaymentVoucherNo();
                        $addSalePayment->sale_id = $returnSale->id;
                        $addSalePayment->customer_id = $customerId;
                        $addSalePayment->customer_payment_id = $customerPayment->id;
                        $addSalePayment->account_id = $request->account_id;
                        $addSalePayment->pay_mode = $request->payment_method;
                        $addSalePayment->paid_amount = $request->amount;
                        $addSalePayment->payment_type = 2;
                        $addSalePayment->date = date('d-m-y', strtotime($request->date));
                        $addSalePayment->time = date('h:i:s a');
                        $addSalePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
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
                        $addSalePayment->save();

                        // Add customer return Payment invoice
                        $addCustomerPaymentInvoice = new CustomerPaymentInvoice();
                        $addCustomerPaymentInvoice->customer_payment_id = $customerPayment->id;
                        $addCustomerPaymentInvoice->sale_id = $returnSale->id;
                        $addCustomerPaymentInvoice->paid_amount = $request->amount;
                        $addCustomerPaymentInvoice->type = 2;
                        $addCustomerPaymentInvoice->save();

                        if ($returnSale->sale_return) {
                            $returnSale->sale_return->total_return_due -= $request->amount;
                            $returnSale->sale_return->total_return_due_pay += $request->amount;
                            $returnSale->sale_return->save();
                        }

                        $request->amount -= $request->amount;
                        $this->saleUtil->adjustSaleInvoiceAmounts($returnSale);
                    }
                } elseif ($returnSale->sale_return_due == $request->amount) {
                    if ($request->amount > 0) {
                        // Add sale payment
                        $addSalePayment = new SalePayment();
                        $addSalePayment->invoice_id = 'RPV' . date('my') . $this->invoiceVoucherRefIdUtil->salePaymentVoucherNo();
                        $addSalePayment->sale_id = $returnSale->id;
                        $addSalePayment->customer_id = $customerId;
                        $addSalePayment->customer_payment_id = $customerPayment->id;
                        $addSalePayment->account_id = $request->account_id;
                        $addSalePayment->pay_mode = $request->payment_method;
                        $addSalePayment->paid_amount = $request->amount;
                        $addSalePayment->payment_type = 2;
                        $addSalePayment->date = date('d-m-y', strtotime($request->date));
                        $addSalePayment->time = date('h:i:s a');
                        $addSalePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
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
                        $addSalePayment->save();

                        // Add Customer return Payment invoice
                        $addCustomerPaymentInvoice = new CustomerPaymentInvoice();
                        $addCustomerPaymentInvoice->customer_payment_id = $customerPayment->id;
                        $addCustomerPaymentInvoice->sale_id = $returnSale->id;
                        $addCustomerPaymentInvoice->paid_amount = $request->amount;
                        $addCustomerPaymentInvoice->type = 2;
                        $addCustomerPaymentInvoice->save();

                        if ($returnSale->sale_return) {
                            $returnSale->sale_return->total_return_due -= $request->amount;
                            $returnSale->sale_return->total_return_due_pay += $request->amount;
                            $returnSale->sale_return->save();
                        }
                        $request->amount -= $request->amount;
                        $this->saleUtil->adjustSaleInvoiceAmounts($returnSale);
                    }
                } elseif ($returnSale->sale_return_due < $request->amount) {
                    if ($request->amount > 0) {
                        // Add sale payment
                        $addSalePayment = new SalePayment();
                        $addSalePayment->invoice_id = 'RPV' . date('my') . $this->invoiceVoucherRefIdUtil->salePaymentVoucherNo();
                        $addSalePayment->sale_id = $returnSale->id;
                        $addSalePayment->customer_id = $customerId;
                        $addSalePayment->customer_payment_id = $customerPayment->id;
                        $addSalePayment->account_id = $request->account_id;
                        $addSalePayment->pay_mode = $request->payment_method;
                        $addSalePayment->paid_amount = $returnSale->sale_return_due;
                        $addSalePayment->payment_type = 2;
                        $addSalePayment->date = date('d-m-y', strtotime($request->date));
                        $addSalePayment->time = date('h:i:s a');
                        $addSalePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
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
                        $addSalePayment->save();

                        // Add Customer return Payment invoice
                        $addCustomerPaymentInvoice = new CustomerPaymentInvoice();
                        $addCustomerPaymentInvoice->customer_payment_id = $customerPayment->id;
                        $addCustomerPaymentInvoice->sale_id = $returnSale->id;
                        $addCustomerPaymentInvoice->paid_amount = $returnSale->sale_return_due;
                        $addCustomerPaymentInvoice->type = 2;
                        $addCustomerPaymentInvoice->save();

                        if ($returnSale->sale_return) {
                            $returnSale->sale_return->total_return_due -= $returnSale->sale_return_due;
                            $returnSale->sale_return->total_return_due_received += $returnSale->sale_return_due;
                            $returnSale->sale_return->save();
                        }

                        $request->amount -= $returnSale->sale_return_due;
                        $this->saleUtil->adjustSaleInvoiceAmounts($returnSale);
                    }
                }
                $index++;
            }
        }

        $this->customerUtil->adjustCustomerAmountForSalePaymentDue($customerId);
        return response()->json('Return amount paid successfully.');
    }

    public function viewPayment($customerId)
    {
        $customer = DB::table('customers')->where('id', $customerId)->first();
        $customer_payments = DB::table('customer_payments')
            ->leftJoin('accounts', 'customer_payments.account_id', 'accounts.id')
            ->leftJoin('payment_methods', 'customer_payments.payment_method_id', 'payment_methods.id')
            ->select('customer_payments.*', 'accounts.name as ac_name', 'accounts.account_number as ac_no', 'payment_methods.name as payment_method_name')
            ->where('customer_payments.customer_id', $customerId)
            ->orderBy('customer_payments.report_date', 'desc')->get();
        return view('contacts.customers.ajax_view.view_payment_list', compact('customer', 'customer_payments'));
    }

    // Customer Payment Details
    public function paymentDetails($paymentId)
    {
        $customerPayment = CustomerPayment::with(
            'branch',
            'customer',
            'account',
            'customer_payment_invoices',
            'customer_payment_invoices.sale:id,invoice_id,date',
            'paymentMethod'
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

        $storedAccountId = $deleteCustomerPayment->account_id;
        $storedCustomerId = $deleteCustomerPayment->customer_id;
        $storedCustomerPaymentInvoices = $deleteCustomerPayment->customer_payment_invoices;
        $deleteCustomerPayment->delete();
        // Update Customer payment invoices
        if (count($storedCustomerPaymentInvoices) > 0) {
            foreach ($deleteCustomerPayment->customer_payment_invoices as $customer_payment_invoice) {
                if ($customer_payment_invoice->type == 1) {
                    $sale = Sale::where('id', $customer_payment_invoice->sale_id)->first();
                    $this->saleUtil->adjustSaleInvoiceAmounts($sale);
                } else {
                    $sale = Sale::with('sale_return')->where('id', $customer_payment_invoice->sale_id)->first();
                    if ($sale->sale_return) {
                        $sale->sale_return->total_return_due += $customer_payment_invoice->paid_amount;
                        $sale->sale_return->total_return_due_pay -= $customer_payment_invoice->paid_amount;
                        $sale->sale_return->save();
                    }
                    $this->saleUtil->adjustSaleInvoiceAmounts($sale);
                }
            }
        }

        if ($storedAccountId) {
            $this->accountUtil->adjustAccountBalance($storedAccountId);
        }

        //Update customer info
        $this->customerUtil->adjustCustomerAmountForSalePaymentDue($storedCustomerId);
        return response()->json('Payment deleted successfully.');
    }
}
