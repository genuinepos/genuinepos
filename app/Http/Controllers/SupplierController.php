<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CashFlow;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Utils\Converter;
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
    public $converter;
    public function __construct(
        SupplierUtil $supplierUtil,
        PurchaseUtil $purchaseUtil,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        Converter $converter,
    ) {
        $this->supplierUtil = $supplierUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->converter = $converter;
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

        $updateSupplier = Supplier::where('id', $request->id)->first();

        $updateSupplier->contact_id = $request->contact_id;
        $updateSupplier->name = $request->name;
        $updateSupplier->business_name = $request->business_name;
        $updateSupplier->email = $request->email;
        $updateSupplier->phone = $request->phone;
        $updateSupplier->alternative_phone = $request->phone;
        $updateSupplier->landline = $request->phone;
        $updateSupplier->date_of_birth = $request->date_of_birth;
        $updateSupplier->tax_number = $request->tax_number;
        $updateSupplier->pay_term = $request->pay_term;
        $updateSupplier->pay_term_number = $request->pay_term_number;
        $updateSupplier->address = $request->address;
        $updateSupplier->city = $request->city;
        $updateSupplier->zip_code = $request->zip_code;
        $updateSupplier->country = $request->country;
        $updateSupplier->state = $request->state;
        $updateSupplier->shipping_address = $request->shipping_address;
        $updateSupplier->opening_balance = $request->opening_balance ? $request->opening_balance : 0;
        $updateSupplier->save();

        // Add supplier Ledger
        $this->supplierUtil->updateSupplierLedger(
            voucher_type_id : 0,
            supplier_id : $updateSupplier->id,
            date : $updateSupplier->created_at,
            trans_id : NULL,
            amount : $updateSupplier->opening_balance,
            fixed_date : $updateSupplier->created_at,
        );

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

    // Supplier payment list
    public function ledgers(Request $request, $supplierId)
    {
        if ($request->ajax()) {
            $settings = DB::table('general_settings')->first();

            $supplierUtil = $this->supplierUtil;

            $supplierLedgers = '';

            $query = DB::table('supplier_ledgers')->where('supplier_ledgers.supplier_id', $supplierId)
                ->leftJoin('purchases', 'supplier_ledgers.purchase_id', 'purchases.id')
                ->leftJoin('purchase_returns', 'supplier_ledgers.purchase_return_id', 'purchase_returns.id')
                ->leftJoin('purchase_payments', 'supplier_ledgers.purchase_payment_id', 'purchase_payments.id')
                ->leftJoin('supplier_payments', 'supplier_ledgers.supplier_payment_id', 'supplier_payments.id')
                ->leftJoin('purchases as agp_purchase', 'purchase_payments.purchase_id', 'agp_purchase.id')
                ->select(
                    'supplier_ledgers.report_date',
                    'supplier_ledgers.voucher_type',
                    'supplier_ledgers.debit',
                    'supplier_ledgers.credit',
                    'supplier_ledgers.running_balance',
                    'purchases.invoice_id as purchase_inv_id',
                    'purchases.purchase_note as purchase_par',
                    'purchase_returns.invoice_id as return_inv_id',
                    'purchase_returns.date as purchase_return_par',
                    'purchase_payments.invoice_id as payment_voucher_no',
                    'purchase_payments.note as purchase_payment_par',
                    'supplier_payments.voucher_no as supplier_payment_voucher',
                    'supplier_payments.note as supplier_payment_par',
                    'agp_purchase.invoice_id as agp_purchase',
                )->orderBy('supplier_ledgers.report_date', 'asc');

            if ($request->voucher_type) {
                $query->where('supplier_ledgers.voucher_type', $request->voucher_type); // Final
            }

            if ($request->from_date) {
                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('supplier_ledgers.report_date', $date_range); // Final
            }

            $supplierLedgers = $query;

            return DataTables::of($supplierLedgers)
                ->editColumn('date', function ($row) use ($settings) {
                    $dateFormat = json_decode($settings->business, true)['date_format'];
                    $__date_format = str_replace('-', '/', $dateFormat);
                    return date($__date_format, strtotime($row->report_date));
                })

                ->editColumn('particulars', function ($row) use ($supplierUtil) {
                    $type = $supplierUtil->voucherType($row->voucher_type);
                    $__agp = $row->agp_purchase ? '/' . 'AGP:<b>' . $row->agp_purchase . '</b>' : '';
                    return '<b>' . $type['name'] . '</b>' . $__agp . ($row->{$type['par']} ? '/' . $row->{$type['par']} : '');
                })

                ->editColumn('voucher_no',  function ($row) use ($supplierUtil) {
                    $type = $supplierUtil->voucherType($row->voucher_type);
                    return $row->{$type['voucher_no']};
                })

                ->editColumn('debit', fn ($row) => '<span class="debit" data-value="' . $row->debit . '">' . $this->converter->format_in_bdt($row->debit) . '</span>')

                ->editColumn('credit', fn ($row) => '<span class="credit" data-value="' . $row->credit . '">' . $this->converter->format_in_bdt($row->credit) . '</span>')

                ->editColumn('running_balance', fn ($row) => '<span class="running_balance" data-value="' . $row->running_balance . '">' . $this->converter->format_in_bdt($row->running_balance) . '</span>')

                ->rawColumns(['date', 'particulars', 'voucher_no', 'debit', 'credit', 'running_balance'])
                ->make(true);
        }
    }

    public function ledgerPrint(Request $request, $supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->select(
            'name',
            'contact_id',
            'phone',
            'address',
        )->first();

        $supplierUtil = $this->supplierUtil;

        $ledgers = '';

        $query = DB::table('supplier_ledgers')->where('supplier_ledgers.supplier_id', $supplierId)
            ->leftJoin('purchases', 'supplier_ledgers.purchase_id', 'purchases.id')
            ->leftJoin('purchase_returns', 'supplier_ledgers.purchase_return_id', 'purchase_returns.id')
            ->leftJoin('purchase_payments', 'supplier_ledgers.purchase_payment_id', 'purchase_payments.id')
            ->leftJoin('supplier_payments', 'supplier_ledgers.supplier_payment_id', 'supplier_payments.id')
            ->leftJoin('purchases as agp_purchase', 'purchase_payments.purchase_id', 'agp_purchase.id')
            ->select(
                'supplier_ledgers.report_date',
                'supplier_ledgers.voucher_type',
                'supplier_ledgers.debit',
                'supplier_ledgers.credit',
                'supplier_ledgers.running_balance',
                'purchases.invoice_id as purchase_inv_id',
                'purchases.purchase_note as purchase_par',
                'purchase_returns.invoice_id as return_inv_id',
                'purchase_returns.date as purchase_return_par',
                'purchase_payments.invoice_id as payment_voucher_no',
                'purchase_payments.note as purchase_payment_par',
                'supplier_payments.voucher_no as supplier_payment_voucher',
                'supplier_payments.note as supplier_payment_par',
                'agp_purchase.invoice_id as agp_purchase',
            )->orderBy('supplier_ledgers.report_date', 'asc');

        if ($request->voucher_type) {
            $query->where('supplier_ledgers.voucher_type', $request->voucher_type); // Final
        }

        $fromDate = '';
        $toDate = '';

        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('supplier_ledgers.report_date', $date_range); // Final

            $fromDate = $from_date;
            $toDate = $to_date;
        }

        $ledgers = $query->get();

        return view('contacts.suppliers.ajax_view.print_ledger', compact('ledgers', 'supplier', 'supplierUtil', 'fromDate', 'toDate'));
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

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

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
        $supplierPayment->voucher_no = 'SPV' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('supplier_payments'), 5, "0", STR_PAD_LEFT);
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

                if ($dueInvoice->due > $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $addPurchasePayment = new PurchasePayment();
                        $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT);
                        $addPurchasePayment->purchase_id = $dueInvoice->id;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->paid_amount = $request->paying_amount;
                        $addPurchasePayment->date = $request->date;
                        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->payment_method_id = $request->payment_method_id;
                        $addPurchasePayment->admin_id = auth()->user()->id;
                        $addPurchasePayment->payment_on = 1;
                        $addPurchasePayment->save();

                        // Add Supplier Payment invoice
                        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                        $addSupplierPaymentInvoice->purchase_id = $dueInvoice->id;
                        $addSupplierPaymentInvoice->paid_amount = $request->paying_amount;
                        $addSupplierPaymentInvoice->save();

                        //$dueAmounts -= $dueAmounts; 
                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due == $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $addPurchasePayment = new PurchasePayment();
                        $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'PPV') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT);;
                        $addPurchasePayment->purchase_id = $dueInvoice->id;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->paid_amount = $request->paying_amount;
                        $addPurchasePayment->date = $request->date;
                        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->payment_method_id = $request->payment_method_id;

                        $addPurchasePayment->admin_id = auth()->user()->id;
                        $addPurchasePayment->payment_on = 1;
                        $addPurchasePayment->save();

                        // Add Supplier Payment invoice
                        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                        $addSupplierPaymentInvoice->purchase_id = $dueInvoice->id;
                        $addSupplierPaymentInvoice->paid_amount = $request->paying_amount;
                        $addSupplierPaymentInvoice->save();
                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due < $request->paying_amount) {

                    if ($dueInvoice->due > 0) {

                        $addPurchasePayment = new PurchasePayment();

                        $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'PPV') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT);

                        $addPurchasePayment->purchase_id = $dueInvoice->id;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->payment_method_id = $request->payment_method_id;
                        $addPurchasePayment->paid_amount = $dueInvoice->due;
                        $addPurchasePayment->date = $request->date;
                        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
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
                        $request->paying_amount -= $dueInvoice->due;
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
            ->get([
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance'
            ]);

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

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
        $supplierPayment->voucher_no = 'RPV' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('supplier_payments'), 5, "0", STR_PAD_LEFT);
        $supplierPayment->branch_id = auth()->user()->branch_id;
        $supplierPayment->supplier_id = $supplierId;
        $supplierPayment->account_id = $request->account_id;
        $supplierPayment->paid_amount = $request->paying_amount;
        $supplierPayment->type = 2;
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

                if ($returnPurchase->purchase_return_due > $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        // Add purchase payment
                        $addPurchasePayment = new PurchasePayment();

                        $addPurchasePayment->invoice_id = 'RPV' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT);

                        $addPurchasePayment->purchase_id = $returnPurchase->id;
                        $addPurchasePayment->supplier_id = $supplierId;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->payment_method_id = $request->payment_method_id;
                        $addPurchasePayment->paid_amount = $request->paying_amount;
                        $addPurchasePayment->payment_type = 2;
                        $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->note = $request->note;

                        $addPurchasePayment->admin_id = auth()->user()->id;
                        $addPurchasePayment->save();

                        // Add Supplier return Payment invoice
                        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                        $addSupplierPaymentInvoice->purchase_id = $returnPurchase->id;
                        $addSupplierPaymentInvoice->paid_amount = $request->paying_amount;
                        $addSupplierPaymentInvoice->type = 2;
                        $addSupplierPaymentInvoice->save();

                        if ($returnPurchase->purchase_return) {

                            $returnPurchase->purchase_return->total_return_due -= $request->paying_amount;
                            $returnPurchase->purchase_return->total_return_due_received += $request->paying_amount;
                            $returnPurchase->purchase_return->save();
                        }

                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($returnPurchase);
                    }
                } elseif ($returnPurchase->purchase_return_due == $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        // Add purchase payment
                        $addPurchasePayment = new PurchasePayment();

                        $addPurchasePayment->invoice_id = 'RPV' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT);

                        $addPurchasePayment->purchase_id = $returnPurchase->id;;
                        $addPurchasePayment->supplier_id = $supplierId;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->payment_method_id = $request->payment_method_id;
                        $addPurchasePayment->paid_amount = $request->paying_amount;
                        $addPurchasePayment->payment_type = 2;
                        $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->note = $request->note;
                        $addPurchasePayment->admin_id = auth()->user()->id;
                        $addPurchasePayment->save();

                        // Add Supplier return Payment invoice
                        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                        $addSupplierPaymentInvoice->purchase_id = $returnPurchase->id;
                        $addSupplierPaymentInvoice->paid_amount = $request->paying_amount;
                        $addSupplierPaymentInvoice->type = 2;
                        $addSupplierPaymentInvoice->save();

                        if ($returnPurchase->purchase_return) {

                            $returnPurchase->purchase_return->total_return_due -= $request->paying_amount;
                            $returnPurchase->purchase_return->total_return_due_received += $request->paying_amount;
                            $returnPurchase->purchase_return->save();
                        }

                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($returnPurchase);
                    }
                } elseif ($returnPurchase->purchase_return_due < $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        // Add purchase payment
                        $addPurchasePayment = new PurchasePayment();

                        $addPurchasePayment->invoice_id = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');

                        $addPurchasePayment->purchase_id = $returnPurchase->id;
                        $addPurchasePayment->supplier_id = $supplierId;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->payment_method_id = $request->payment_method_id;
                        $addPurchasePayment->paid_amount = $returnPurchase->purchase_return_due;
                        $addPurchasePayment->payment_type = 2;
                        $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
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

                        $request->paying_amount -= $returnPurchase->purchase_return_due;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($returnPurchase);
                    }
                }
                $index++;
            }
        }

        if ($request->paying_amount > 0) {

            $dueSupplierReturnInvoices = PurchaseReturn::where('supplier_id', $supplierId)
                ->where('total_return_due', '>', 0)
                ->where('purchase_id', NULL)
                ->get();

            if (count($dueSupplierReturnInvoices) > 0) {

                $index = 0;
                foreach ($dueSupplierReturnInvoices as $dueSupplierReturnInvoice) {
                    
                    if ($dueSupplierReturnInvoice->total_return_due > $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $dueSupplierReturnInvoice->total_return_due -= $request->paying_amount;
                            $dueSupplierReturnInvoice->total_return_due_received += $request->paying_amount;
                            $dueSupplierReturnInvoice->save();

                            // Add purchase payment
                            $addPurchasePayment = new PurchasePayment();

                            $addPurchasePayment->invoice_id = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');

                            $addPurchasePayment->supplier_id = $supplierId;
                            $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                            $addPurchasePayment->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addPurchasePayment->account_id = $request->account_id;
                            $addPurchasePayment->payment_method_id = $request->payment_method_id;
                            $addPurchasePayment->paid_amount = $request->paying_amount;
                            $addPurchasePayment->payment_type = 2;
                            $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                            $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                            $addPurchasePayment->month = date('F');
                            $addPurchasePayment->year = date('Y');
                            $addPurchasePayment->note = $request->note;
                            $addPurchasePayment->admin_id = auth()->user()->id;
                            $addPurchasePayment->save();

                            // Add Supplier return Payment invoice
                            $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                            $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                            $addSupplierPaymentInvoice->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addSupplierPaymentInvoice->paid_amount = $request->paying_amount;
                            $addSupplierPaymentInvoice->type = 2;
                            $addSupplierPaymentInvoice->save();
                            $request->paying_amount -= $request->paying_amount;
                        }
                    } elseif ($dueSupplierReturnInvoice->total_return_due == $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $dueSupplierReturnInvoice->total_return_due -= $request->paying_amount;
                            $dueSupplierReturnInvoice->total_return_due_received += $request->paying_amount;
                            $dueSupplierReturnInvoice->save();
                            // Add purchase payment
                            $addPurchasePayment = new PurchasePayment();

                            $addPurchasePayment->invoice_id = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');

                            $addPurchasePayment->supplier_id = $supplierId;
                            $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                            $addPurchasePayment->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addPurchasePayment->account_id = $request->account_id;
                            $addPurchasePayment->payment_method_id = $request->payment_method_id;
                            $addPurchasePayment->paid_amount = $request->paying_amount;
                            $addPurchasePayment->payment_type = 2;
                            $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                            $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                            $addPurchasePayment->month = date('F');
                            $addPurchasePayment->year = date('Y');
                            $addPurchasePayment->note = $request->note;
                            $addPurchasePayment->admin_id = auth()->user()->id;
                            $addPurchasePayment->save();

                            // Add Supplier return Payment invoice
                            $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                            $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                            $addSupplierPaymentInvoice->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addSupplierPaymentInvoice->paid_amount = $request->paying_amount;
                            $addSupplierPaymentInvoice->type = 2;
                            $addSupplierPaymentInvoice->save();
                            $request->paying_amount -= $request->paying_amount;
                        }
                    } elseif ($dueSupplierReturnInvoice->total_return_due < $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $dueSupplierReturnInvoice->total_return_due -=  $dueSupplierReturnInvoice->total_return_due;
                            $dueSupplierReturnInvoice->total_return_due_received +=  $dueSupplierReturnInvoice->total_return_due;
                            $dueSupplierReturnInvoice->save();
                            // Add purchase payment
                            $addPurchasePayment = new PurchasePayment();

                            $addPurchasePayment->invoice_id = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');

                            $addPurchasePayment->supplier_id = $supplierId;
                            $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                            $addPurchasePayment->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addPurchasePayment->account_id = $request->account_id;
                            $addPurchasePayment->payment_method_id = $request->payment_method_id;
                            $addPurchasePayment->paid_amount = $dueSupplierReturnInvoice->total_return_due;
                            $addPurchasePayment->payment_type = 2;
                            $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                            $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                            $addPurchasePayment->month = date('F');
                            $addPurchasePayment->year = date('Y');
                            $addPurchasePayment->note = $request->note;
                            $addPurchasePayment->admin_id = auth()->user()->id;
                            $addPurchasePayment->save();

                            // Add Supplier return Payment invoice
                            $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                            $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                            $addSupplierPaymentInvoice->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addSupplierPaymentInvoice->paid_amount = $dueSupplierReturnInvoice->total_return_due;
                            $addSupplierPaymentInvoice->type = 2;
                            $addSupplierPaymentInvoice->save();
                            $request->paying_amount -= $dueSupplierReturnInvoice->total_return_due;
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
            ->leftJoin('payment_methods', 'supplier_payments.payment_method_id', 'payment_methods.id')
            ->select(
                'supplier_payments.*',
                'accounts.name as ac_name',
                'accounts.account_number as ac_no',
                'payment_methods.name as payment_method'
            )
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
            'supplier_payment_invoices.purchase:id,invoice_id,date',
            'paymentMethod:id,name'
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
