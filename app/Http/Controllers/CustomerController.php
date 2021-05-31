<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Account;
use App\Models\CashFlow;
use App\Models\Customer;
use App\Models\SalePayment;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\CustomerLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        if (auth()->user()->permission->customers['customer_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('contacts.customers.index');
    }

    public function getAllCustomer()
    {
        $customers = Customer::orderBy('id', 'DESC')->get();
        return view('contacts.customers.ajax_view.customer_list', compact('customers'));
    }

    public function getAllGroup()
    {
        $groups =  CustomerGroup::all();
        return response()->json($groups);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $addCustomer = Customer::create([
            'type' => $request->contact_type,
            'contact_id' => $request->contact_id,
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternative_phone' => $request->phone,
            'landline' => $request->phone,
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
            'opening_balance' => $request->opening_balance,
            'total_sale_due' => $request->opening_balance ? $request->opening_balance : 0.00,
        ]);

        if ($request->opening_balance && $request->opening_balance >= 0) {
            $addCustomerLedger = new CustomerLedger();
            $addCustomerLedger->customer_id = $addCustomer->id;
            $addCustomerLedger->row_type = 3;
            $addCustomerLedger->amount = $request->opening_balance;
            $addCustomerLedger->save();
        }

        return response()->json('Successfully supplier is added');
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
            'contact_id' => $request->contact_id,
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'landline' => $request->alternative_phone,
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

        return response()->json('Successfully customer is updated');
    }

    public function delete(Request $request, $customerId)
    {
        $deleteCustomer = Customer::find($customerId);
        if (!is_null($deleteCustomer)) {
            $deleteCustomer->delete();
        }
        return response()->json('Successfully supplier is deleted');
    }

    // Change stauts method
    public function changeStatus($customerId)
    {
        $statusChange = Customer::where('id', $customerId)->first();
        if ($statusChange->status == 1) {
            $statusChange->status = 0;
            $statusChange->save();
            return response()->json('Successfully Customer is deactivated');
        } else {
            $statusChange->status = 1;
            $statusChange->save();
            return response()->json('Successfully Customer is activated');
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
                    ->leftJoin('warehouses', 'sales.warehouse_id', 'warehouses.id')
                    ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                    ->select(
                        'sales.*',
                        'branches.id as branch_id',
                        'branches.name as branch_name',
                        'branches.branch_code',
                        'warehouses.warehouse_name',
                        'warehouses.warehouse_code',
                        'customers.name as customer_name',
                    )->where('sales.customer_id', $customerId)
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $sales = DB::table('sales')
                    ->leftJoin('branches', 'sales.branch_id', 'branches.id')
                    ->leftJoin('warehouses', 'sales.warehouse_id', 'warehouses.id')
                    ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                    ->select(
                        'sales.*',
                        'branches.id as branch_id',
                        'branches.name as branch_name',
                        'branches.branch_code',
                        'warehouses.warehouse_name',
                        'warehouses.warehouse_code',
                        'customers.name as customer_name',
                    )->where('sales.customer_id', $customerId)
                    ->where('sales.branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'desc')
                    ->get();
            }

            return DataTables::of($sales)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                        </button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.show', [$row->id]) . '"><i
                                    class="far fa-eye mr-1 text-primary"></i> View</a>';
                   
                    $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i
                                    class="far fa-money-bill-alt mr-1 text-primary"></i> Packing Slip</a>';

                    if (auth()->user()->permission->sale['shipment_access'] == '1') {
                            $html .= '<a class="dropdown-item" id="edit_shipment"
                            href="' . route('sales.shipment.edit', [$row->id]) . '"><i
                            class="fas fa-truck mr-1 text-primary"></i> Edit Shipping</a>';
                    }

                    if (auth()->user()->branch_id == $row->branch_id) {
                        if ($row->due > 0) {
                            if (auth()->user()->permission->sale['sale_payment'] == '1') {
                                $html .= '<a class="dropdown-item" id="add_payment" href="' . route('sales.payment.modal', [$row->id]) . '" 
                            ><i class="far fa-money-bill-alt mr-1 text-primary"></i> Add Payment</a>';
                            }
                        }

                        if (auth()->user()->permission->sale['sale_payment'] == '1') {
                            $html .= '<a class="dropdown-item" id="view_payment" data-toggle="modal"
                        data-target="#paymentListModal" href="' . route('sales.payment.view', [$row->id]) . '"><i
                            class="far fa-money-bill-alt mr-1 text-primary"></i> View Payment</a>';
                        }

                        if (auth()->user()->permission->sale['return_access'] == '1') {
                            $html .= '<a class="dropdown-item" href="' . route('sales.returns.create', [$row->id]) . '"><i
                                    class="fas fa-undo-alt mr-1 text-primary"></i> Sale Return</a>';
                        }

                        if ($row->created_by == 1) {
                            $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                        }else {
                            $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                        }

                        $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i
                        class="far fa-trash-alt mr-1 text-primary"></i> Delete</a>';
                    }

                    if ($row->sale_return_due > 0) {
                        if (auth()->user()->permission->sale['sale_payment'] == '1') {
                            $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('sales.return.payment.modal', [$row->id]) . '" 
                        ><i class="far fa-money-bill-alt mr-1 text-primary"></i> Pay Return Amount</a>';
                        }
                    }

                    $html .= '<a class="dropdown-item" id="items_notification" href=""><i
                                    class="fas fa-envelope mr-1 text-primary"></i> New Sale Notification</a>';
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
                ->editColumn('from',  function ($row) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code.'(<b>BRANCH</b>)';
                    } else {
                        return $row->warehouse_name . '/' . $row->warehouse_code.'(<b>WAREHOUSE</b>)';
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
        return view('contacts.customers.view', compact('customerId'));
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
        $ledgers = CustomerLedger::with(['sale', 'sale_payment', 'sale_payment.sale', 'money_receipt'])->where('customer_id', $customerId)->orderBy('id', 'desc')->get();
        return view('contacts.customers.ajax_view.ledger_list', compact('ledgers'));
    }

    // Customer ledger 
    public function ledger($customerId)
    {
        $customerId = $customerId;
        return view('contacts.customers.ledger', compact('customerId'));
    }

    // Customer  contactInfo sales
    public function contactInfo($customerId)
    {
        $customerId = $customerId;
        return view('contacts.customers.contact_info', compact('customerId'));
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

        $customer = Customer::where('id', $customerId)->first();
        $customer->total_paid += $request->amount;
        $customer->total_sale_due -= $request->amount;
        $customer->save();

        // generate invoice ID
        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $dueInvoices = Sale::where('customer_id', $customerId)
            ->where('due', '>', 0)
            ->get();
        if (count($dueInvoices) > 0) {
            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {
                if ($dueInvoice->due > $request->amount) {
                    $dueInvoice->paid += $request->amount;
                    $dueInvoice->due -= $request->amount;
                    $dueInvoice->save();
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addSalePayment->sale_id = $dueInvoice->id;
                    $addSalePayment->customer_id = $customerId;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->paid_amount = $request->amount;
                    $addSalePayment->date = date('d-m-Y', strtotime($request->date));
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

                    if ($request->hasFile('attachment')) {
                        $SalePaymentAttachment = $request->file('attachment');
                        $salePaymentAttachmentName = uniqid() . '-' . '.' . $SalePaymentAttachment->getClientOriginalExtension();
                        $SalePaymentAttachment->move(public_path('uploads/payment_attachment/'), $SalePaymentAttachment);
                        $addSalePayment->attachment = $salePaymentAttachmentName;
                    }

                    $addSalePayment->admin_id = auth()->user()->id;
                    $addSalePayment->payment_on = 1;
                    $addSalePayment->save();

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
                        $addCashFlow->purchase_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-Y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($dueInvoice->customer_id) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $customerId;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }

                    //$dueAmounts -= $dueAmounts; 
                    if ($index == 1) {
                        break;
                    }
                } elseif ($dueInvoice->due == $request->amount) {
                    $dueInvoice->paid += $request->amount;
                    $dueInvoice->due -= $request->amount;
                    $dueInvoice->save();
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addSalePayment->sale_id = $dueInvoice->id;
                    $addSalePayment->customer_id = $customerId;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->paid_amount = $request->amount;
                    $addSalePayment->date = date('d-m-Y', strtotime($request->date));
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

                    if ($request->hasFile('attachment')) {
                        $salePaymentAttachment = $request->file('attachment');
                        $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
                        $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
                        $addSalePayment->attachment = $salePaymentAttachmentName;
                    }

                    $addSalePayment->admin_id = auth()->user()->id;
                    $addSalePayment->payment_on = 1;
                    $addSalePayment->save();

                    if ($request->account_id) {
                        // update account
                        $account = Account::where('id', $request->account_id)->first();
                        $account->debit += $request->amount;
                        $account->balance += $request->amount;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->credit = $request->amount;
                        $addCashFlow->balance = $account->balance;
                        $addCashFlow->purchase_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-Y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($dueInvoice->customer_id) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $customerId;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }

                    if ($index == 1) {
                        break;
                    }
                } elseif ($dueInvoice->due < $request->amount) {
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addSalePayment->sale_id = $dueInvoice->id;
                    $addSalePayment->customer_id = $customerId;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->paid_amount = $dueInvoice->due;
                    $addSalePayment->date = date('d-m-Y', strtotime($request->date));
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

                    if ($request->hasFile('attachment')) {
                        $salePaymentAttachment = $request->file('attachment');
                        $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
                        $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
                        $addSalePayment->attachment = $salePaymentAttachmentName;
                    }

                    $addSalePayment->admin_id = auth()->user()->id;
                    $addSalePayment->payment_on = 1;
                    $addSalePayment->save();

                    if ($request->account_id) {
                        // update account
                        $account = Account::where('id', $request->account_id)->first();
                        $account->debit += $dueInvoice->due;
                        $account->balance -= $dueInvoice->due;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->credit = $dueInvoice->due;
                        $addCashFlow->balance = $account->balance;
                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-Y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($dueInvoice->customer_id) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $customerId;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }

                    $request->amount -= $dueInvoice->due;
                    $dueInvoice->paid += $dueInvoice->due;
                    $dueInvoice->due -= $dueInvoice->due;
                    $dueInvoice->save();
                }
                $index++;
            }
        }
        return response()->json('Successfully payment is added.');
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

        return response()->json('Successfully return amount is paid.');
    }
}