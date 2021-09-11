<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CashFlow;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\SupplierLedger;
use App\Models\PurchasePayment;
use App\Models\SupplierPayment;
use App\Models\SupplierPaymentInvoice;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        if (auth()->user()->permission->supplier['supplier_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('contacts.suppliers.index');
    }

    public function getAllSupplier()
    {
        $suppliers = Supplier::orderBy('id', 'DESC')->get();
        return view('contacts.suppliers.ajax_view.supplier_list', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        // generate ID
        $i = 5;
        $a = 0;
        $id = '';
        while ($a < $i) {
            $id .= rand(1, 9);
            $a++;
        }
        $generalSettings = DB::table('general_settings')->first('prefix');
        $firstLetterOfSupplier = str_split($request->name)[0];
        $supIdPrefix = json_decode($generalSettings->prefix, true)['supplier_id'];
        $addSupplier = Supplier::create([
            'type' => $request->contact_type,
            'contact_id' => $request->contact_id ? $request->contact_id : $supIdPrefix . $id,
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
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'state' => $request->state,
            'shipping_address' => $request->shipping_address,
            'prefix' => $request->prefix ? $request->prefix : $firstLetterOfSupplier . $id,
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0,
            'total_purchase_due' => $request->opening_balance ? $request->opening_balance : 0,
        ]);

        if ($request->opening_balance && $request->opening_balance >= 0) {
            $addSupplierLedger = new SupplierLedger();
            $addSupplierLedger->supplier_id = $addSupplier->id;
            $addSupplierLedger->row_type = 3;
            $addSupplierLedger->amount = $request->opening_balance;
            $addSupplierLedger->save();
        }
        return response()->json('Supplier created successfully');
    }

    public function getSupplier($supplierId)
    {
        $supplier = Supplier::where('id', $supplierId)->first();
        return response()->json($supplier);
    }

    public function edit($supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->select('suppliers.*')->first();
        return view('contacts.suppliers.ajax_view.edit', compact('supplier'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        // generate prefix dode ID
        $i = 6;
        $a = 0;
        $code = '';
        while ($a < $i) {
            $code .= rand(1, 9);
            $a++;
        }
        $firstLetterOfSupplier = str_split($request->name)[0];
        Supplier::where('id', $request->id)->update([
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
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'state' => $request->state,
            'prefix' => $request->prefix ? $request->prefix : $firstLetterOfSupplier . $code,
            'shipping_address' => $request->shipping_address,
        ]);
        return response()->json('Supplier updated successfully');
    }

    public function delete(Request $request, $supplierId)
    {
        $deleteSupplier = Supplier::find($supplierId);
        if (!is_null($deleteSupplier)) {
            $deleteSupplier->delete();
        }
        return response()->json('supplier deleted successfully');
    }

    // Change stauts method
    public function changeStatus($supplierId)
    {
        $statusChange = Supplier::where('id', $supplierId)->first();
        if ($statusChange->status == 1) {
            $statusChange->status = 0;
            $statusChange->save();
            return response()->json('Supplier deactivated successfully');
        } else {
            $statusChange->status = 1;
            $statusChange->save();
            return response()->json('Supplier activated successfully');
        }
    }

    // Supplier view method
    public function view(Request $request, $supplierId)
    {
        $supplierId = $supplierId;
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $purchases = '';
            $query = DB::table('purchases')
                ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
                ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
                ->leftJoin('admin_and_users as created_by', 'purchases.admin_id', 'created_by.id');


            $purchases = $query->select(
                'purchases.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'suppliers.name as supplier_name',
                'created_by.prefix as created_prefix',
                'created_by.name as created_name',
                'created_by.last_name as created_last_name',
            )->where('purchases.branch_id', auth()->user()->branch_id)->where('supplier_id', $supplierId)->get();


            return DataTables::of($purchases)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item details_button" href="' . route('purchases.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';

                    if (auth()->user()->permission->purchase['purchase_edit'] == '1') {
                        $html .= '<a class="dropdown-item" href="' . route('purchases.edit', $row->id) . ' "><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->permission->purchase['purchase_delete'] == '1') {
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('purchase.delete', $row->id) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '<a class="dropdown-item" href="' . route('barcode.on.purchase.barcode', $row->id) . '"><i class="fas fa-barcode text-primary"></i> Barcode</a>';

                    if (auth()->user()->branch_id == $row->branch_id) {
                        if (auth()->user()->permission->purchase['purchase_payment'] == '1') {
                            if ($row->due > 0) {
                                $html .= '<a class="dropdown-item" data-type="1" id="add_payment" href="' . route('purchases.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Add Payment</a>';
                            }

                            if ($row->purchase_return_due > 0) {
                                $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('purchases.return.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Return Amount</a>';
                            }
                        }
                    }

                    $html .= '<a class="dropdown-item" id="view_payment" href="' . route('purchase.payment.list', $row->id) . '"><i class="far fa-money-bill-alt text-primary"></i> View Payment</a>';

                    if (auth()->user()->permission->purchase['purchase_return'] == '1') {
                        $html .= '<a class="dropdown-item" id="purchase_return" href="' . route('purchases.returns.create', $row->id) . '"><i class="fas fa-undo-alt text-primary"></i> Purchase Return</a>';
                    }

                    $html .= '<a class="dropdown-item" id="change_status" href="' . route('purchases.change.status', $row->id) . '" data-toggle="modal" data-target="#changeStatusModal"><i class="far fa-edit mr-1 text-primary"></i> Update Status</a>';
                    $html .= '<a class="dropdown-item" id="items_notification" href=""><i class="fas fa-envelope mr-1 text-primary"></i> Items Received Notification</a>';
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
                    if ($row->warehouse_name) {
                        return $row->warehouse_name . '<b>(WH)</b>';
                    } elseif ($row->branch_name) {
                        return $row->branch_name . '<b>(BL)</b>';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . ' (<b>HO</b>)';
                    }
                })
                ->editColumn('total_purchase_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_purchase_amount . '</b>';
                })
                ->editColumn('paid', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</b>';
                })
                ->editColumn('due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-danger">' . json_decode($generalSettings->business, true)['currency'] . ($row->due >= 0 ? $row->due :   0.00) . '</span></b>';
                })
                ->editColumn('return_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->purchase_return_amount . '</b>';
                })
                ->editColumn('return_due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-success">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->purchase_return_due . '</span></b>';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->purchase_status == 1) {
                        $html .= '<span class="badge bg-success">Received</span>';
                    } elseif ($row->purchase_status == 2) {
                        $html .= '<span class="badge bg-primary">Panding</span>';
                    } elseif ($row->purchase_status == 3) {
                        $html .= '<span class="badge bg-warning text-white">Ordered</span>';
                    }
                    return $html;
                })
                ->editColumn('payment_status', function ($row) {
                    $html = '';
                    $payable = $row->total_purchase_amount - $row->purchase_return_amount;
                    if ($row->due <= 0) {
                        $html .= '<span class="badge bg-success">Paid</span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        $html .= '<span class="badge bg-primary text-white">Partial</span>';
                    } elseif ($payable == $row->due) {
                        $html .= '<span class="badge bg-danger text-white">Due</span>';
                    }
                    return $html;
                })
                ->editColumn('created_by', function ($row) {
                    return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('purchases.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row')
                ->rawColumns(['action', 'date', 'invoice_id', 'from', 'total_purchase_amount', 'paid', 'due', 'return_amount', 'return_due', 'payment_status', 'status', 'created_by'])
                ->make(true);
        }
        return view('contacts.suppliers.view', compact('supplierId'));
    }

    // supplier all info
    public function SupplierAllInfo($supplierId)
    {
        $supplier = Supplier::where('id', $supplierId)->first();
        return response()->json($supplier);
    }

    // Supplier payment list
    public function paymentList($supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->select('name', 'contact_id')->first();
        $ledgers = SupplierLedger::with(['purchase', 'purchase_payment', 'purchase_payment.purchase', 'supplier_payment'])
            ->where('supplier_id', $supplierId)
            ->whereYear('created_at', date('Y'))->get();
        return view('contacts.suppliers.ajax_view.ledger_list', compact('ledgers', 'supplier'));
    }

    // Supplier ledger 
    public function ledger($supplierId)
    {
        $supplierId = $supplierId;
        return view('contacts.suppliers.ledger', compact('supplierId'));
    }

    // Supplier  contactInfo purchases
    public function contactInfo($supplierId)
    {
        $supplierId = $supplierId;
        return view('contacts.suppliers.contact_info', compact('supplierId'));
    }

    // Supplier  purchases
    public function purchases($supplierId)
    {
        $supplierId = $supplierId;
        return view('contacts.suppliers.purchases', compact('supplierId'));
    }

    // Supplier payment view
    public function payment($supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->first();
        $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        return view('contacts.suppliers.ajax_view.payment_modal', compact('supplier', 'accounts'));
    }

    // Supplier Payment add
    public function paymentAdd(Request $request, $supplierId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];

        // Update Supplier Amount
        $supplier = Supplier::where('id', $supplierId)->first();
        $supplier->total_paid += $request->amount;
        $supplier->total_purchase_due -= $request->amount;
        $supplier->save();

        // generate invoice ID
        $l = 6;
        $sv = 0;
        $voucherNo = '';
        while ($sv < $l) { $voucherNo .= rand(1, 9);$sv++; }

        // Add Supplier Payment Record
        $supplierPayment = new SupplierPayment();
        $supplierPayment->voucher_no = 'SPV'.$voucherNo;
        $supplierPayment->branch_id = auth()->user()->branch_id;
        $supplierPayment->supplier_id = $supplierId;
        $supplierPayment->account_id = $request->account_id;
        $supplierPayment->paid_amount = $request->amount;
        $supplierPayment->pay_mode = $request->payment_method;
        $supplierPayment->date = $request->date;
        $supplierPayment->time = date('h:i:s a');
        $supplierPayment->month = date('F');
        $supplierPayment->year = date('Y');

        if ($request->payment_method == 'Card') {
            $supplierPayment->card_no = $request->card_no;
            $supplierPayment->card_holder = $request->card_holder_name;
            $supplierPayment->card_transaction_no = $request->card_transaction_no;
            $supplierPayment->card_type = $request->card_type;
            $supplierPayment->card_month = $request->month;
            $supplierPayment->card_year = $request->year;
            $supplierPayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $supplierPayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $supplierPayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $supplierPayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            $PaymentAttachment = $request->file('attachment');
            $paymentAttachmentName = uniqid() . '-' . '.' . $PaymentAttachment->getClientOriginalExtension();
            $PaymentAttachment->move(public_path('uploads/payment_attachment/'), $paymentAttachmentName);
            $supplierPayment->attachment = $paymentAttachmentName;
        }

        $supplierPayment->note = $request->note;
        $supplierPayment->save();

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
            $addCashFlow->supplier_payment_id = $supplierPayment->id;
            $addCashFlow->transaction_type = 12;
            $addCashFlow->cash_type = 1;
            $addCashFlow->date = date('d-m-Y', strtotime($request->date));
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
        }

        // Add supplier payment for direct payment
        $addSupplierLedger = new SupplierLedger();
        $addSupplierLedger->supplier_id = $supplierId;
        $addSupplierLedger->row_type = 4;
        $addSupplierLedger->supplier_payment_id = $supplierPayment->id;
        $addSupplierLedger->report_date = date('Y-m-d', strtotime($request->date));
        $addSupplierLedger->save();

        // generate invoice ID
        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $dueInvoices = Purchase::where('supplier_id', $supplierId)
            ->where('due', '>', 0)
            ->get();
        if (count($dueInvoices) > 0) {
            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {
                if ($dueInvoice->due > $request->amount) {
                    $dueInvoice->paid += $request->amount;
                    $dueInvoice->due -= $request->amount;
                    $dueInvoice->save();
                    $addPurchasePayment = new PurchasePayment();
                    $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'PPI') . date('ymd') . $invoiceId;
                    $addPurchasePayment->purchase_id = $dueInvoice->id;
                    $addPurchasePayment->supplier_id = $supplierId;
                    $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                    $addPurchasePayment->account_id = $request->account_id;
                    $addPurchasePayment->paid_amount = $request->amount;
                    $addPurchasePayment->date = date('d-m-Y', strtotime($request->date));
                    $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addPurchasePayment->month = date('F');
                    $addPurchasePayment->year = date('Y');
                    $addPurchasePayment->pay_mode = $request->payment_method;

                    if ($request->payment_method == 'Card') {
                        $addPurchasePayment->card_no = $request->card_no;
                        $addPurchasePayment->card_holder = $request->card_holder_name;
                        $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
                        $addPurchasePayment->card_type = $request->card_type;
                        $addPurchasePayment->card_month = $request->month;
                        $addPurchasePayment->card_year = $request->year;
                        $addPurchasePayment->card_secure_code = $request->secure_code;
                    } elseif ($request->payment_method == 'Cheque') {
                        $addPurchasePayment->cheque_no = $request->cheque_no;
                    } elseif ($request->payment_method == 'Bank-Transfer') {
                        $addPurchasePayment->account_no = $request->account_no;
                    } elseif ($request->payment_method == 'Custom') {
                        $addPurchasePayment->transaction_no = $request->transaction_no;
                    }

                    $addPurchasePayment->admin_id = auth()->user()->id;
                    $addPurchasePayment->payment_on = 1;
                    $addPurchasePayment->save();

                    // Add Supplier Payment invoice
                    $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                    $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                    $addSupplierPaymentInvoice->purchase_id = $dueInvoice->id;
                    $addSupplierPaymentInvoice->paid_amount = $request->amount;
                    $addSupplierPaymentInvoice->save();

                    //$dueAmounts -= $dueAmounts; 
                    if ($index == 1) {
                        break;
                    }
                } elseif ($dueInvoice->due == $request->amount) {
                    $dueInvoice->paid += $request->amount;
                    $dueInvoice->due -= $request->amount;
                    $dueInvoice->save();
                    $addPurchasePayment = new PurchasePayment();
                    $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'PPI') . date('ymd') . $invoiceId;
                    $addPurchasePayment->purchase_id = $dueInvoice->id;
                    $addPurchasePayment->supplier_id = $supplierId;
                    $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                    $addPurchasePayment->account_id = $request->account_id;
                    $addPurchasePayment->paid_amount = $request->amount;
                    $addPurchasePayment->date = date('d-m-Y', strtotime($request->date));
                    $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addPurchasePayment->month = date('F');
                    $addPurchasePayment->year = date('Y');
                    $addPurchasePayment->pay_mode = $request->payment_method;

                    if ($request->payment_method == 'Card') {
                        $addPurchasePayment->card_no = $request->card_no;
                        $addPurchasePayment->card_holder = $request->card_holder_name;
                        $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
                        $addPurchasePayment->card_type = $request->card_type;
                        $addPurchasePayment->card_month = $request->month;
                        $addPurchasePayment->card_year = $request->year;
                        $addPurchasePayment->card_secure_code = $request->secure_code;
                    } elseif ($request->payment_method == 'Cheque') {
                        $addPurchasePayment->cheque_no = $request->cheque_no;
                    } elseif ($request->payment_method == 'Bank-Transfer') {
                        $addPurchasePayment->account_no = $request->account_no;
                    } elseif ($request->payment_method == 'Custom') {
                        $addPurchasePayment->transaction_no = $request->transaction_no;
                    }

                    $addPurchasePayment->admin_id = auth()->user()->id;
                    $addPurchasePayment->payment_on = 1;
                    $addPurchasePayment->save();

                    // Add Supplier Payment invoice
                    $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                    $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                    $addSupplierPaymentInvoice->purchase_id = $dueInvoice->id;
                    $addSupplierPaymentInvoice->paid_amount = $request->amount;
                    $addSupplierPaymentInvoice->save();

                    if ($index == 1) {
                        break;
                    }
                } elseif ($dueInvoice->due < $request->amount) {
                    $addPurchasePayment = new PurchasePayment();
                    $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addPurchasePayment->purchase_id = $dueInvoice->id;
                    $addPurchasePayment->supplier_id = $supplierId;
                    $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                    $addPurchasePayment->account_id = $request->account_id;
                    $addPurchasePayment->paid_amount = $dueInvoice->due;
                    $addPurchasePayment->date = date('d-m-Y', strtotime($request->date));
                    $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addPurchasePayment->month = date('F');
                    $addPurchasePayment->year = date('Y');
                    $addPurchasePayment->pay_mode = $request->payment_method;

                    if ($request->payment_method == 'Card') {
                        $addPurchasePayment->card_no = $request->card_no;
                        $addPurchasePayment->card_holder = $request->card_holder_name;
                        $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
                        $addPurchasePayment->card_type = $request->card_type;
                        $addPurchasePayment->card_month = $request->month;
                        $addPurchasePayment->card_year = $request->year;
                        $addPurchasePayment->card_secure_code = $request->secure_code;
                    } elseif ($request->payment_method == 'Cheque') {
                        $addPurchasePayment->cheque_no = $request->cheque_no;
                    } elseif ($request->payment_method == 'Bank-Transfer') {
                        $addPurchasePayment->account_no = $request->account_no;
                    } elseif ($request->payment_method == 'Custom') {
                        $addPurchasePayment->transaction_no = $request->transaction_no;
                    }

                    $addPurchasePayment->admin_id = auth()->user()->id;
                    $addPurchasePayment->payment_on = 1;
                    $addPurchasePayment->save();

                    // Add Supplier Payment invoice
                    $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                    $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                    $addSupplierPaymentInvoice->purchase_id = $dueInvoice->id;
                    $addSupplierPaymentInvoice->paid_amount = $dueInvoice->due;
                    $addSupplierPaymentInvoice->save();

                    // Calculate next payment amount
                    $request->amount -= $dueInvoice->due;
                    $dueInvoice->paid += $dueInvoice->due;
                    $dueInvoice->due -= $dueInvoice->due;
                    $dueInvoice->save();
                }
                $index++;
            }
        }
        return response()->json('Payment added successfully.');
    }

    public function returnPayment($supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->first();
        $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        return view('contacts.suppliers.ajax_view.return_payment_modal', compact('supplier', 'accounts'));
    }

    public function returnPaymentAdd(Request $request, $supplierId)
    {
        $supplier = Supplier::where('id', $supplierId)->first();
        $supplier->total_purchase_return_due -= $request->amount;
        $supplier->save();

        $dueReturnInvoices = Purchase::with(['purchase_return'])->where('supplier_id', $supplierId)->where('purchase_return_due', '>', 0)->get();

        if (count($dueReturnInvoices) > 0) {
            $index = 0;
            foreach ($dueReturnInvoices as $dueReturnInvoice) {
                if ($dueReturnInvoice->purchase_return_due > $request->amount) {
                    // Update purchase
                    $dueReturnInvoice->purchase_return_due -= $request->amount;
                    $dueReturnInvoice->save();

                    // update purchase return
                    $dueReturnInvoice->purchase_return->total_return_due_received += $request->amount;
                    $dueReturnInvoice->purchase_return->total_return_due -= $request->amount;
                    $dueReturnInvoice->purchase_return->save();

                    // generate invoice ID
                    $i = 5;
                    $a = 0;
                    $invoiceId = '';
                    while ($a < $i) {
                        $invoiceId .= rand(1, 9);
                        $a++;
                    }
                    // Add purchase payment
                    $addPurchasePayment = new PurchasePayment();
                    $addPurchasePayment->invoice_id = 'PRPI' . date('dmy') . $invoiceId;
                    $addPurchasePayment->purchase_id = $dueReturnInvoice->id;
                    $addPurchasePayment->supplier_id = $supplierId;
                    $addPurchasePayment->account_id = $request->account_id;
                    $addPurchasePayment->pay_mode = $request->payment_method;
                    $addPurchasePayment->paid_amount = $request->amount;
                    $addPurchasePayment->payment_type = 2;
                    $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                    $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addPurchasePayment->month = date('F');
                    $addPurchasePayment->year = date('Y');
                    $addPurchasePayment->note = $request->note;

                    if ($request->payment_method == 'Card') {
                        $addPurchasePayment->card_no = $request->card_no;
                        $addPurchasePayment->card_holder = $request->card_holder_name;
                        $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
                        $addPurchasePayment->card_type = $request->card_type;
                        $addPurchasePayment->card_month = $request->month;
                        $addPurchasePayment->card_year = $request->year;
                        $addPurchasePayment->card_secure_code = $request->secure_code;
                    } elseif ($request->payment_method == 'Cheque') {
                        $addPurchasePayment->cheque_no = $request->cheque_no;
                    } elseif ($request->payment_method == 'Bank-Transfer') {
                        $addPurchasePayment->account_no = $request->account_no;
                    } elseif ($request->payment_method == 'Custom') {
                        $addPurchasePayment->transaction_no = $request->transaction_no;
                    }
                    $addPurchasePayment->admin_id = auth()->user()->id;

                    if ($request->hasFile('attachment')) {
                        $purchasePaymentAttachment = $request->file('attachment');
                        $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
                        $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
                        $addPurchasePayment->attachment = $purchasePaymentAttachmentName;
                    }

                    $addPurchasePayment->save();

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
                        $addCashFlow->purchase_payment_id = $addPurchasePayment->id;
                        $addCashFlow->transaction_type = 3;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    $addSupplierLedger = new SupplierLedger();
                    $addSupplierLedger->supplier_id = $supplier->id;
                    $addSupplierLedger->purchase_payment_id = $addPurchasePayment->id;
                    $addSupplierLedger->row_type = 2;
                    $addSupplierLedger->save();

                    if ($index == 1) {
                        break;
                    }
                } elseif ($dueReturnInvoice->purchase_return_due == $request->amount) {
                    // Update purchase
                    $dueReturnInvoice->purchase_return_due -= $request->amount;
                    $dueReturnInvoice->save();

                    // update purchase return
                    $dueReturnInvoice->purchase_return->total_return_due_received += $request->amount;
                    $dueReturnInvoice->purchase_return->total_return_due -= $request->amount;
                    $dueReturnInvoice->purchase_return->save();

                    // generate invoice ID
                    $i = 5;
                    $a = 0;
                    $invoiceId = '';
                    while ($a < $i) {
                        $invoiceId .= rand(1, 9);
                        $a++;
                    }

                    // Add purchase payment
                    $addPurchasePayment = new PurchasePayment();
                    $addPurchasePayment->invoice_id = 'PRPI' . date('dmy') . $invoiceId;
                    $addPurchasePayment->purchase_id = $dueReturnInvoice->id;
                    $addPurchasePayment->supplier_id = $supplierId;
                    $addPurchasePayment->account_id = $request->account_id;
                    $addPurchasePayment->pay_mode = $request->payment_method;
                    $addPurchasePayment->paid_amount = $request->amount;
                    $addPurchasePayment->payment_type = 2;
                    $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                    $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addPurchasePayment->month = date('F');
                    $addPurchasePayment->year = date('Y');
                    $addPurchasePayment->note = $request->note;

                    if ($request->payment_method == 'Card') {
                        $addPurchasePayment->card_no = $request->card_no;
                        $addPurchasePayment->card_holder = $request->card_holder_name;
                        $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
                        $addPurchasePayment->card_type = $request->card_type;
                        $addPurchasePayment->card_month = $request->month;
                        $addPurchasePayment->card_year = $request->year;
                        $addPurchasePayment->card_secure_code = $request->secure_code;
                    } elseif ($request->payment_method == 'Cheque') {
                        $addPurchasePayment->cheque_no = $request->cheque_no;
                    } elseif ($request->payment_method == 'Bank-Transfer') {
                        $addPurchasePayment->account_no = $request->account_no;
                    } elseif ($request->payment_method == 'Custom') {
                        $addPurchasePayment->transaction_no = $request->transaction_no;
                    }
                    $addPurchasePayment->admin_id = auth()->user()->id;

                    if ($request->hasFile('attachment')) {
                        $purchasePaymentAttachment = $request->file('attachment');
                        $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
                        $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
                        $addPurchasePayment->attachment = $purchasePaymentAttachmentName;
                    }

                    $addPurchasePayment->save();

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
                        $addCashFlow->purchase_payment_id = $addPurchasePayment->id;
                        $addCashFlow->transaction_type = 3;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    $addSupplierLedger = new SupplierLedger();
                    $addSupplierLedger->supplier_id = $supplier->id;
                    $addSupplierLedger->purchase_payment_id = $addPurchasePayment->id;
                    $addSupplierLedger->row_type = 2;
                    $addSupplierLedger->save();

                    if ($index == 1) {
                        break;
                    }
                } elseif ($dueReturnInvoice->purchase_return_due < $request->amount) {
                    // Update purchase
                    $dueReturnInvoice->purchase_return_due -= $dueReturnInvoice->purchase_return_due;
                    $dueReturnInvoice->save();

                    // update purchase return
                    $request->amount -= $dueReturnInvoice->purchase_return_due;
                    $dueReturnInvoice->purchase_return->total_return_due_received += $dueReturnInvoice->purchase_return_due;
                    $dueReturnInvoice->purchase_return->total_return_due -= $dueReturnInvoice->purchase_return_due;
                    $dueReturnInvoice->purchase_return->save();

                    // generate invoice ID
                    $i = 5;
                    $a = 0;
                    $invoiceId = '';
                    while ($a < $i) {
                        $invoiceId .= rand(1, 9);
                        $a++;
                    }

                    // Add purchase payment
                    $addPurchasePayment = new PurchasePayment();
                    $addPurchasePayment->invoice_id = 'PRPI' . date('dmy') . $invoiceId;
                    $addPurchasePayment->purchase_id = $dueReturnInvoice->id;
                    $addPurchasePayment->supplier_id = $supplierId;
                    $addPurchasePayment->account_id = $request->account_id;
                    $addPurchasePayment->pay_mode = $request->payment_method;
                    $addPurchasePayment->paid_amount = $dueReturnInvoice->purchase_return_due;
                    $addPurchasePayment->payment_type = 2;
                    $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                    $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addPurchasePayment->month = date('F');
                    $addPurchasePayment->year = date('Y');
                    $addPurchasePayment->note = $request->note;

                    if ($request->payment_method == 'Card') {
                        $addPurchasePayment->card_no = $request->card_no;
                        $addPurchasePayment->card_holder = $request->card_holder_name;
                        $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
                        $addPurchasePayment->card_type = $request->card_type;
                        $addPurchasePayment->card_month = $request->month;
                        $addPurchasePayment->card_year = $request->year;
                        $addPurchasePayment->card_secure_code = $request->secure_code;
                    } elseif ($request->payment_method == 'Cheque') {
                        $addPurchasePayment->cheque_no = $request->cheque_no;
                    } elseif ($request->payment_method == 'Bank-Transfer') {
                        $addPurchasePayment->account_no = $request->account_no;
                    } elseif ($request->payment_method == 'Custom') {
                        $addPurchasePayment->transaction_no = $request->transaction_no;
                    }
                    $addPurchasePayment->admin_id = auth()->user()->id;

                    if ($request->hasFile('attachment')) {
                        $purchasePaymentAttachment = $request->file('attachment');
                        $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
                        $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
                        $addPurchasePayment->attachment = $purchasePaymentAttachmentName;
                    }
                    $addPurchasePayment->save();

                    if ($request->account_id) {
                        // update account
                        $account = Account::where('id', $request->account_id)->first();
                        $account->credit += $dueReturnInvoice->purchase_return_due;
                        $account->balance += $dueReturnInvoice->purchase_return_due;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->credit = $dueReturnInvoice->purchase_return_due;
                        $addCashFlow->balance = $account->balance;
                        $addCashFlow->purchase_payment_id = $addPurchasePayment->id;
                        $addCashFlow->transaction_type = 3;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    $addSupplierLedger = new SupplierLedger();
                    $addSupplierLedger->supplier_id = $supplier->id;
                    $addSupplierLedger->purchase_payment_id = $addPurchasePayment->id;
                    $addSupplierLedger->row_type = 2;
                    $addSupplierLedger->save();
                }
                $index++;
            }
        }

        return response()->json('Return amount received successfully.');
    }

    public function viewPayment($supplierId)
    {
        $supplier = Supplier::with(
            'supplier_payments',
            'supplier_payments.account:id,name'
        )->where('id', $supplierId)->first();
        return view('contacts.suppliers.ajax_view.view_payment_list', compact('supplier'));
    }

    // Supplier Payment Details
    public function paymentDetails($paymentId)
    {
        $supplierPayment = SupplierPayment::with(
            'branch',
            'supplier',
            'account',
            'supplier_payment_invoices',
            'supplier_payment_invoices.purchase:id,invoice_id,date'
        )->where('id', $paymentId)->first();
        return view('contacts.suppliers.ajax_view.payment_details', compact('supplierPayment'));
    }

    // Supplier Payment Delete
    public function paymentDelete(Request $request, $paymentId)
    {
        $deleteSupplierPayment = SupplierPayment::with('supplier_payment_invoices')->where('id', $paymentId)->first();
        if ($deleteSupplierPayment->attachment != null) {
            if (file_exists(public_path('uploads/payment_attachment/' . $deleteSupplierPayment->attachment))) {
                unlink(public_path('uploads/payment_attachment/' . $deleteSupplierPayment->attachment));
            }
        }
        
        //Update supplier info
        $supplier = Supplier::where('id', $deleteSupplierPayment->supplier_id)->first();
        if ($deleteSupplierPayment->type == 1) {
            $supplier->total_paid -= $deleteSupplierPayment->paid_amount;
            $supplier->total_purchase_due += $deleteSupplierPayment->paid_amount;
        }else {
            $supplier->total_purchase_return_due += $deleteSupplierPayment->paid_amount;
        }
        $supplier->save();

        if ($deleteSupplierPayment->account_id) {
            if ($deleteSupplierPayment->type == 1) {
                $account = Account::where('id', $deleteSupplierPayment->account_id)->first();
                $account->debit -= $deleteSupplierPayment->paid_amount;
                $account->balance += $deleteSupplierPayment->paid_amount;
                $account->save();
            } else {
                $account = Account::where('id', $deleteSupplierPayment->account_id)->first();
                $account->credit -= $deleteSupplierPayment->paid_amount;
                $account->balance -= $deleteSupplierPayment->paid_amount;
                $account->save();
            }
        }

        // Update supplier payment invoices
        if (count($deleteSupplierPayment->supplier_payment_invoices) > 0) {
            foreach ($deleteSupplierPayment->supplier_payment_invoices as $pInvoice) {
                if ($deleteSupplierPayment->type == 1) {
                    $updatePurchase = Purchase::where('id', $pInvoice->purchase_id)->first();
                    $updatePurchase->paid -= $pInvoice->paid_amount;
                    $updatePurchase->due += $pInvoice->paid_amount;
                    $updatePurchase->save();
                }
            }
        }

        $deleteSupplierPayment->delete();
        return response()->json('Payment deleted successfully.');
    }
}
