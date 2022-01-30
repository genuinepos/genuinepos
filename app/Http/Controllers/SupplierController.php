<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Utils\AccountUtil;
use App\Utils\PurchaseUtil;
use App\Utils\SupplierUtil;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\SupplierLedger;
use App\Models\PurchasePayment;
use App\Models\SupplierPayment;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierPaymentInvoice;
use App\Utils\InvoiceVoucherRefIdUtil;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public $supplierUtil;
    public $purchaseUtil;
    public $accountUtil;
    public $invoiceVoucherRefIdUtil;
    public function __construct(
        SupplierUtil $supplierUtil,
        PurchaseUtil $purchaseUtil,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil
    ) {
        $this->supplierUtil = $supplierUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        if (auth()->user()->permission->contact['supplier_all'] == '0') {
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

        $generalSettings = DB::table('general_settings')->first('prefix');
        $firstLetterOfSupplier = str_split($request->name)[0];
        $supIdPrefix = json_decode($generalSettings->prefix, true)['supplier_id'];
        $addSupplier = Supplier::create([
            'contact_id' => $request->contact_id ? $request->contact_id : $supIdPrefix . str_pad($this->invoiceVoucherRefIdUtil->getLastId('suppliers'), 4, "0", STR_PAD_LEFT),
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
            'prefix' => $request->prefix ? $request->prefix : $firstLetterOfSupplier . $this->invoiceVoucherRefIdUtil->getLastId('suppliers'),
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0,
            'total_purchase_due' => $request->opening_balance ? $request->opening_balance : 0,
        ]);

        // Add supplier Ledger
        $this->supplierUtil->addSupplierLedger(
            voucher_type_id: 0,
            supplier_id: $addSupplier->id,
            date: date('Y-m-d'),
            trans_id: NULL,
            amount: $request->opening_balance ? $request->opening_balance : 0
        );

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

        Supplier::where('id', $request->id)->update([
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

    // Change status method
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
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'suppliers.name as supplier_name',
                'created_by.prefix as created_prefix',
                'created_by.name as created_name',
                'created_by.last_name as created_last_name',
            )->where('purchases.branch_id', auth()->user()->branch_id)
                ->where('purchases.supplier_id', $supplierId)
                ->where('purchases.is_purchased', 1)
                ->orderBy('purchases.report_date', 'desc');

            return DataTables::of($purchases)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item details_button" href="' . route('purchases.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';

                    if (auth()->user()->permission->purchase['purchase_edit'] == '1') {
                        $html .= '<a class="dropdown-item" href="' . route('purchases.edit', [$row->id, 'purchased']) . ' "><i class="far fa-edit text-primary"></i> Edit</a>';
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
                    if ($row->purchase_status == 1) {
                        return '<span class="text-success"><b>Purchased</b></span>';
                    } elseif ($row->purchase_status == 2) {
                        return '<span class="text-secondary"><b>Pending</b></span>';
                    } elseif ($row->purchase_status == 3) {
                        return '<span class="text-primary text-white"><b>Purchased By Order</b></span>';
                    }
                })
                ->editColumn('payment_status', function ($row) {
                    $payable = $row->total_purchase_amount - $row->purchase_return_amount;
                    if ($row->due <= 0) {
                        return '<span class="badge bg-success">Paid</span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        return '<span class="badge bg-primary text-white">Partial</span>';
                    } elseif ($payable == $row->due) {
                        return '<span class="badge bg-danger text-white">Due</span>';
                    }
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
        $supplier = DB::table('suppliers')->where('id', $supplierId)->first();
        return view('contacts.suppliers.view', compact('supplierId', 'supplier'));
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
        $addSupplierLedgerModelData = SupplierLedger::orderBy('report_date', 'ASC');
        $ledgers = $addSupplierLedgerModelData->with(['purchase', 'purchase_payment', 'purchase_payment.purchase', 'supplier_payment'])
            ->where('supplier_id', $supplierId)->get();

        // $ledgers = SupplierLedger::with(['purchase', 'purchase_payment', 'purchase_payment.purchase', 'supplier_payment'])
        //     ->where('supplier_id', $supplierId)->orderBy('report_date', 'ASC')->get();
        return view('contacts.suppliers.ajax_view.ledger_list', compact('ledgers', 'supplier'));
    }

    public function ledgerPrint($supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->select(
            'name',
            'contact_id',
            'phone',
            'address',
            'opening_balance',
            'total_paid',
            'total_purchase',
            'total_purchase_due'
        )->first();
        $addSupplierLedgerModelData = SupplierLedger::orderBy('report_date', 'asc');
        $ledgers = $addSupplierLedgerModelData->with(['purchase', 'purchase_payment', 'purchase_payment.purchase', 'supplier_payment'])
            ->where('supplier_id', $supplierId)->get();
        return view('contacts.suppliers.ajax_view.print_ledger', compact('ledgers', 'supplier'));
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

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $methods = DB::table('payment_methods')->select('id', 'name', 'account_id')->get();

        return view('contacts.suppliers.ajax_view.payment_modal', compact('supplier', 'accounts', 'methods'));
    }

    // Supplier Payment add
    public function paymentAdd(Request $request, $supplierId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];

        // Add Supplier Payment Record
        $supplierPayment = new SupplierPayment();
        $supplierPayment->voucher_no = 'SPV' . $this->invoiceVoucherRefIdUtil->getLastId('supplier_payments');
        $supplierPayment->branch_id = auth()->user()->branch_id;
        $supplierPayment->supplier_id = $supplierId;
        $supplierPayment->account_id = $request->account_id;
        $supplierPayment->paid_amount = $request->paying_amount;
        $supplierPayment->payment_method_id = $request->payment_method_id;
        $supplierPayment->date = $request->date;
        $supplierPayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $supplierPayment->time = date('h:i:s a');
        $supplierPayment->month = date('F');
        $supplierPayment->year = date('Y');

        if ($request->hasFile('attachment')) {
            $PaymentAttachment = $request->file('attachment');
            $paymentAttachmentName = uniqid() . '-' . '.' . $PaymentAttachment->getClientOriginalExtension();
            $PaymentAttachment->move(public_path('uploads/payment_attachment/'), $paymentAttachmentName);
            $supplierPayment->attachment = $paymentAttachmentName;
        }

        $supplierPayment->note = $request->note;
        $supplierPayment->save();

        // Add supplier Ledger
        $this->supplierUtil->addSupplierLedger(
            voucher_type_id: 5,
            supplier_id: $supplierId,
            date: $request->date,
            trans_id: $supplierPayment->id,
            amount: $request->paying_amount
        );

        // Add Bank/Cash-in-hand A/C Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 19,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $supplierPayment->id,
            amount: $request->paying_amount,
            balance_type: 'debit'
        );

        $dueInvoices = Purchase::where('supplier_id', $supplierId)
            ->where('due', '>', 0)
            ->get();

        if (count($dueInvoices) > 0) {
            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {
                if ($dueInvoice->due > $request->amount) {
                    if ($request->amount > 0) {
                        $addPurchasePayment = new PurchasePayment();
                        $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
                        $addPurchasePayment->purchase_id = $dueInvoice->id;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->paid_amount = $request->amount;
                        $addPurchasePayment->date = $request->date;
                        $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->pay_mode = $request->payment_method;
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
                        $request->amount -= $request->amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due == $request->amount) {
                    if ($request->amount > 0) {
                        $addPurchasePayment = new PurchasePayment();
                        $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
                        $addPurchasePayment->purchase_id = $dueInvoice->id;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->paid_amount = $request->amount;
                        $addPurchasePayment->date = $request->date;
                        $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->pay_mode = $request->payment_method;

                        $addPurchasePayment->admin_id = auth()->user()->id;
                        $addPurchasePayment->payment_on = 1;
                        $addPurchasePayment->save();

                        // Add Supplier Payment invoice
                        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                        $addSupplierPaymentInvoice->purchase_id = $dueInvoice->id;
                        $addSupplierPaymentInvoice->paid_amount = $request->amount;
                        $addSupplierPaymentInvoice->save();
                        $request->amount -= $request->amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due < $request->amount) {
                    if ($dueInvoice->due > 0) {
                        $addPurchasePayment = new PurchasePayment();
                        $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
                        $addPurchasePayment->purchase_id = $dueInvoice->id;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->paid_amount = $dueInvoice->due;
                        $addPurchasePayment->date = $request->date;
                        $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->pay_mode = $request->payment_method;
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
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                }
                $index++;
            }
        }

        $this->supplierUtil->adjustSupplierForSalePaymentDue($supplierId);
        return response()->json('Payment added successfully.');
    }

    public function returnPayment($supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->first();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $methods = DB::table('payment_methods')->select('id', 'name', 'account_id')->get();

        return view('contacts.suppliers.ajax_view.return_payment_modal', compact('supplier', 'accounts', 'methods'));
    }

    public function returnPaymentAdd(Request $request, $supplierId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        // Add Supplier Payment Record
        $supplierPayment = new SupplierPayment();
        $supplierPayment->voucher_no = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('supplier_payments');
        $supplierPayment->branch_id = auth()->user()->branch_id;
        $supplierPayment->supplier_id = $supplierId;
        $supplierPayment->account_id = $request->account_id;
        $supplierPayment->paid_amount = $request->amount;
        $supplierPayment->type = 2;
        $supplierPayment->payment_method_id = $request->payment_method_id;
        $supplierPayment->date = $request->date;
        $supplierPayment->report_date = date('Y-m-d', strtotime($request->date));
        $supplierPayment->time = date('h:i:s a');
        $supplierPayment->month = date('F');
        $supplierPayment->year = date('Y');

        if ($request->hasFile('attachment')) {
            $PaymentAttachment = $request->file('attachment');
            $paymentAttachmentName = uniqid() . '-' . '.' . $PaymentAttachment->getClientOriginalExtension();
            $PaymentAttachment->move(public_path('uploads/payment_attachment/'), $paymentAttachmentName);
            $supplierPayment->attachment = $paymentAttachmentName;
        }

        $supplierPayment->note = $request->note;
        $supplierPayment->save();

        // Add supplier Ledger
        $this->supplierUtil->addSupplierLedger(
            voucher_type_id: 6,
            supplier_id: $supplierId,
            date: $request->date,
            trans_id: $supplierPayment->id,
            amount: $request->paying_amount
        );

        // Add Bank A/C Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 21,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $supplierPayment->id,
            amount: $request->paying_amount,
            balance_type: 'debit'
        );

        $returnPurchases = Purchase::with(['purchase_return'])->where('purchase_return_due', '>', 0)->get();
        if (count($returnPurchases) > 0) {
            $index = 0;
            foreach ($returnPurchases as $returnPurchase) {
                if ($returnPurchase->purchase_return_due > $request->amount) {
                    if ($request->amount > 0) {
                        // Add purchase payment
                        $addPurchasePayment = new PurchasePayment();
                        $addPurchasePayment->invoice_id = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
                        $addPurchasePayment->purchase_id = $returnPurchase->id;
                        $addPurchasePayment->supplier_id = $supplierId;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->pay_mode = $request->payment_method;
                        $addPurchasePayment->paid_amount = $request->amount;
                        $addPurchasePayment->payment_type = 2;
                        $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                        $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->note = $request->note;

                        $addPurchasePayment->admin_id = auth()->user()->id;
                        $addPurchasePayment->save();

                        // Add Supplier return Payment invoice
                        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                        $addSupplierPaymentInvoice->purchase_id = $returnPurchase->id;
                        $addSupplierPaymentInvoice->paid_amount = $request->amount;
                        $addSupplierPaymentInvoice->type = 2;
                        $addSupplierPaymentInvoice->save();

                        if ($returnPurchase->purchase_return) {
                            $returnPurchase->purchase_return->total_return_due -= $request->amount;
                            $returnPurchase->purchase_return->total_return_due_received += $request->amount;
                            $returnPurchase->purchase_return->save();
                        }

                        $request->amount -= $request->amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($returnPurchase);
                    }
                } elseif ($returnPurchase->purchase_return_due == $request->amount) {
                    if ($request->amount > 0) {
                        // Add purchase payment
                        $addPurchasePayment = new PurchasePayment();
                        $addPurchasePayment->invoice_id = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
                        $addPurchasePayment->purchase_id = $returnPurchase->id;;
                        $addPurchasePayment->supplier_id = $supplierId;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->pay_mode = $request->payment_method;
                        $addPurchasePayment->paid_amount = $request->amount;
                        $addPurchasePayment->payment_type = 2;
                        $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                        $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->note = $request->note;

                        $addPurchasePayment->admin_id = auth()->user()->id;
                        $addPurchasePayment->save();

                        // Add Supplier return Payment invoice
                        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                        $addSupplierPaymentInvoice->purchase_id = $returnPurchase->id;
                        $addSupplierPaymentInvoice->paid_amount = $request->amount;
                        $addSupplierPaymentInvoice->type = 2;
                        $addSupplierPaymentInvoice->save();

                        if ($returnPurchase->purchase_return) {
                            $returnPurchase->purchase_return->total_return_due -= $request->amount;
                            $returnPurchase->purchase_return->total_return_due_received += $request->amount;
                            $returnPurchase->purchase_return->save();
                        }
                        $request->amount -= $request->amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($returnPurchase);
                    }
                } elseif ($returnPurchase->purchase_return_due < $request->amount) {
                    if ($request->amount > 0) {
                        // Add purchase payment
                        $addPurchasePayment = new PurchasePayment();
                        $addPurchasePayment->invoice_id = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
                        $addPurchasePayment->purchase_id = $returnPurchase->id;
                        $addPurchasePayment->supplier_id = $supplierId;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->pay_mode = $request->payment_method;
                        $addPurchasePayment->paid_amount = $returnPurchase->purchase_return_due;
                        $addPurchasePayment->payment_type = 2;
                        $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                        $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->note = $request->note;

                        $addPurchasePayment->admin_id = auth()->user()->id;
                        $addPurchasePayment->save();

                        // Add Supplier return Payment invoice
                        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                        $addSupplierPaymentInvoice->purchase_id = $returnPurchase->id;
                        $addSupplierPaymentInvoice->paid_amount = $returnPurchase->purchase_return_due;
                        $addSupplierPaymentInvoice->type = 2;
                        $addSupplierPaymentInvoice->save();

                        if ($returnPurchase->purchase_return) {
                            $returnPurchase->purchase_return->total_return_due -= $returnPurchase->purchase_return_due;
                            $returnPurchase->purchase_return->total_return_due_received += $returnPurchase->purchase_return_due;
                            $returnPurchase->purchase_return->save();
                        }

                        $request->amount -= $returnPurchase->purchase_return_due;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($returnPurchase);
                    }
                }
                $index++;
            }
        }

        if ($request->amount > 0) {
            $dueSupplierReturnInvoices = PurchaseReturn::where('supplier_id', $supplierId)
                ->where('total_return_due', '>', 0)
                ->where('purchase_id', NULL)
                ->get();
            if (count($dueSupplierReturnInvoices) > 0) {
                $index = 0;
                foreach ($dueSupplierReturnInvoices as $dueSupplierReturnInvoice) {
                    if ($dueSupplierReturnInvoice->total_return_due > $request->amount) {
                        if ($request->amount > 0) {
                            $dueSupplierReturnInvoice->total_return_due -= $request->amount;
                            $dueSupplierReturnInvoice->total_return_due_received += $request->amount;
                            $dueSupplierReturnInvoice->save();

                            // Add purchase payment
                            $addPurchasePayment = new PurchasePayment();
                            $addPurchasePayment->invoice_id = 'RPV' . date('my') . $this->invoiceVoucherRefIdUtil->purchasePaymentVoucherNo();
                            $addPurchasePayment->supplier_id = $supplierId;
                            $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                            $addPurchasePayment->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addPurchasePayment->account_id = $request->account_id;
                            $addPurchasePayment->pay_mode = $request->payment_method;
                            $addPurchasePayment->paid_amount = $request->amount;
                            $addPurchasePayment->payment_type = 2;
                            $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                            $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                            $addPurchasePayment->month = date('F');
                            $addPurchasePayment->year = date('Y');
                            $addPurchasePayment->note = $request->note;

                            $addPurchasePayment->admin_id = auth()->user()->id;
                            $addPurchasePayment->save();

                            // Add Supplier return Payment invoice
                            $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                            $addSupplierPaymentInvoice->supplier_payment_id = $addPurchasePayment->id;
                            $addSupplierPaymentInvoice->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addSupplierPaymentInvoice->paid_amount = $request->amount;
                            $addSupplierPaymentInvoice->type = 2;
                            $addSupplierPaymentInvoice->save();
                            $request->amount -= $request->amount;
                        }
                    } elseif ($dueSupplierReturnInvoice->total_return_due == $request->amount) {
                        if ($request->amount > 0) {
                            $dueSupplierReturnInvoice->total_return_due -= $request->amount;
                            $dueSupplierReturnInvoice->total_return_due_received += $request->amount;
                            $dueSupplierReturnInvoice->save();
                            // Add purchase payment
                            $addPurchasePayment = new PurchasePayment();
                            $addPurchasePayment->invoice_id = 'RPV' . date('my') . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
                            $addPurchasePayment->supplier_id = $supplierId;
                            $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                            $addPurchasePayment->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addPurchasePayment->account_id = $request->account_id;
                            $addPurchasePayment->pay_mode = $request->payment_method;
                            $addPurchasePayment->paid_amount = $request->amount;
                            $addPurchasePayment->payment_type = 2;
                            $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                            $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                            $addPurchasePayment->month = date('F');
                            $addPurchasePayment->year = date('Y');
                            $addPurchasePayment->note = $request->note;

                            $addPurchasePayment->admin_id = auth()->user()->id;
                            $addPurchasePayment->save();

                            // Add Supplier return Payment invoice
                            $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                            $addSupplierPaymentInvoice->supplier_payment_id = $addPurchasePayment->id;
                            $addSupplierPaymentInvoice->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addSupplierPaymentInvoice->paid_amount = $request->amount;
                            $addSupplierPaymentInvoice->type = 2;
                            $addSupplierPaymentInvoice->save();
                            $request->amount -= $request->amount;
                        }
                    } elseif ($dueSupplierReturnInvoice->total_return_due < $request->amount) {
                        if ($request->amount > 0) {
                            $dueSupplierReturnInvoice->total_return_due -=  $dueSupplierReturnInvoice->total_return_due;
                            $dueSupplierReturnInvoice->total_return_due_received +=  $dueSupplierReturnInvoice->total_return_due;
                            $dueSupplierReturnInvoice->save();
                            // Add purchase payment
                            $addPurchasePayment = new PurchasePayment();
                            $addPurchasePayment->invoice_id = 'RPV' . date('my') . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
                            $addPurchasePayment->supplier_id = $supplierId;
                            $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                            $addPurchasePayment->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addPurchasePayment->account_id = $request->account_id;
                            $addPurchasePayment->pay_mode = $request->payment_method;
                            $addPurchasePayment->paid_amount = $dueSupplierReturnInvoice->total_return_due;
                            $addPurchasePayment->payment_type = 2;
                            $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                            $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
                            $addPurchasePayment->month = date('F');
                            $addPurchasePayment->year = date('Y');
                            $addPurchasePayment->note = $request->note;

                            $addPurchasePayment->admin_id = auth()->user()->id;
                            $addPurchasePayment->save();

                            // Add Supplier return Payment invoice
                            $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                            $addSupplierPaymentInvoice->supplier_payment_id = $addPurchasePayment->id;
                            $addSupplierPaymentInvoice->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addSupplierPaymentInvoice->paid_amount = $dueSupplierReturnInvoice->total_return_due;
                            $addSupplierPaymentInvoice->type = 2;
                            $addSupplierPaymentInvoice->save();
                            $request->amount -= $dueSupplierReturnInvoice->total_return_due;
                        }
                    }
                    $index++;
                }
            }
        }

        $this->supplierUtil->adjustSupplierForSalePaymentDue($supplierId);
        return response()->json('Return amount received successfully.');
    }

    public function viewPayment($supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->first();
        $supplier_payments = DB::table('supplier_payments')
            ->leftJoin('accounts', 'supplier_payments.account_id', 'accounts.id')
            ->select('supplier_payments.*', 'accounts.name as ac_name', 'accounts.account_number as ac_no')
            ->where('supplier_payments.supplier_id', $supplier->id)
            ->orderBy('supplier_payments.report_date', 'desc')->get();
        return view('contacts.suppliers.ajax_view.view_payment_list', compact('supplier', 'supplier_payments'));
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
        $storedAccountId = $deleteSupplierPayment->account_id;
        $storedSupplierPayment = $deleteSupplierPayment;
        $storeSupplierPaymentInvoices = $deleteSupplierPayment->supplier_payment_invoices;
        if ($deleteSupplierPayment->attachment != null) {
            if (file_exists(public_path('uploads/payment_attachment/' . $deleteSupplierPayment->attachment))) {
                unlink(public_path('uploads/payment_attachment/' . $deleteSupplierPayment->attachment));
            }
        }
        $deleteSupplierPayment->delete();

        // Update supplier payment invoices
        if (count($storeSupplierPaymentInvoices) > 0) {
            if ($storedSupplierPayment->type == 1) {
                foreach ($storeSupplierPaymentInvoices as $pInvoice) {
                    $purchase = Purchase::where('id', $pInvoice->purchase_id)->first();
                    $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
                }
            } else {
                foreach ($storeSupplierPaymentInvoices as $pInvoice) {
                    if ($pInvoice->purchase_id) {
                        $purchase = Purchase::with('purchase_return')->where('id', $pInvoice->purchase_id)->first();
                        if ($purchase->purchase_return) {
                            $purchase->purchase_return->total_return_due += $pInvoice->paid_amount;
                            $purchase->purchase_return->total_return_due_received -= $pInvoice->paid_amount;
                            $purchase->purchase_return->save();
                        }
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
                    } elseif ($pInvoice->supplier_return_id) {
                        $supplierReturn = PurchaseReturn::where('id', $pInvoice->supplier_return_id)->first();
                        $supplierReturn->total_return_due += $pInvoice->paid_amount;
                        $supplierReturn->total_return_due_received -= $pInvoice->paid_amount;
                        $supplierReturn->save();
                    }
                }
            }
        }

        if ($storedAccountId) {
            $this->accountUtil->adjustAccountBalance('debit', $storedAccountId);
        }

        $this->supplierUtil->adjustSupplierForSalePaymentDue($deleteSupplierPayment->supplier_id);
        return response()->json('Payment deleted successfully.');
    }
}
