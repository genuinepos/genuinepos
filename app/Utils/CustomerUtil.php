<?php

namespace App\Utils;

use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Utils\Converter;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerUtil
{
    public $converter;
    public function __construct(
        Converter $converter,
    ) {
        $this->converter = $converter;
    }

    public function customerListTable()
    {
        $customers = DB::table('customers')
            ->leftJoin('customer_groups', 'customers.customer_group_id', 'customer_groups.id')
            ->select('customers.*', 'customer_groups.group_name');
        return DataTables::of($customers)
            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';

                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="' . url('contacts/customers/view', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';

                if (auth()->user()->permission->sale['sale_payment'] == '1') {
                    $html .= '<a class="dropdown-item" id="view_payment" href="' . route('customers.view.payment', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> View Payment</a>';

                    if ($row->total_sale_due > 0) {
                        $html .= '<a class="dropdown-item" id="pay_button" href="' . route('customers.payment', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Payment</a>';
                    }

                    if ($row->total_sale_return_due > 0) {
                        $html .= '<a class="dropdown-item" id="pay_return_button" href="' . route('customers.return.payment', $row->id) . '"><i class="far fa-money-bill-alt text-primary"></i> Pay Return Due</a>';
                    }
                }

                $html .= '<a class="dropdown-item" id="money_receipt_list" href="' . route('money.receipt.voucher.list', [$row->id]) . '"><i class="far fa-file-alt text-primary"></i> Payment Receipt Voucher</a>';

                if (auth()->user()->permission->contact['customer_edit'] == '1') {
                    $html .= '<a class="dropdown-item" href="' . route('contacts.customer.edit', [$row->id]) . '" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                }

                if (auth()->user()->permission->contact['customer_delete'] == '1') {
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
            ->editColumn('opening_balance', fn ($row) => '<span class="opening_balance" data-value="' . $row->opening_balance . '">' . $this->converter->format_in_bdt($row->opening_balance) . '</span>')
            ->editColumn('total_sale', fn ($row) => '<span class="total_sale" data-value="' . $row->total_sale . '">' . $this->converter->format_in_bdt($row->total_sale) . '</span>')
            ->editColumn('total_paid', fn ($row) => '<span class="total_paid text-success" data-value="' . $row->total_paid . '">' . $this->converter->format_in_bdt($row->total_paid) . '</span>')
            ->editColumn('total_sale_due', fn ($row) => '<span class="total_sale_due text-danger" data-value="' . $row->total_sale_due . '">' . $this->converter->format_in_bdt($row->total_sale_due) . '</span>')
            ->editColumn('total_return', fn ($row) => '<span class="total_return" data-value="' . $row->total_return . '">' . $this->converter->format_in_bdt($row->total_return) . '</span>')
            ->editColumn('total_sale_return_due', fn ($row) => '<span class="total_sale_return_due" data-value="' . $row->total_sale_return_due . '">' . $this->converter->format_in_bdt($row->total_sale_return_due) . '</span>')
            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<i class="far fa-thumbs-up text-success"></i>';
                } else {
                    return '<i class="far fa-thumbs-down text-danger"></i>';
                }
            })
            ->rawColumns(['action', 'business_name', 'tax_number', 'group_name', 'opening_balance', 'total_sale', 'total_paid', 'total_sale_due', 'total_return', 'total_sale_return_due', 'status'])
            ->make(true);
    }

    public function adjustCustomerAmountForSalePaymentDue($customerId)
    {
        $customer = Customer::where('id', $customerId)->first();
        $totalCustomerSale = DB::table('sales')->where('customer_id', $customerId)
            ->select(DB::raw('sum(total_payable_amount) as total_sale'))->groupBy('customer_id')->get();

        $totalCustomerPayment = DB::table('customer_payments')
            ->select(DB::raw('sum(paid_amount) as c_paid'))
            ->where('customer_id', $customerId)
            ->where('type', 1)
            ->groupBy('customer_id')->get();

        $totalSalePayment = DB::table('sale_payments')
            ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
            ->where('sale_payments.customer_payment_id', NULL)
            ->where('sale_payments.payment_type', 1)
            ->where('sales.customer_id', $customerId)->select(DB::raw('sum(paid_amount) as s_paid'))
            ->groupBy('sales.customer_id')->get();

        $totalSaleReturn = DB::table('sale_returns')
            ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
            ->where('sales.customer_id', $customerId)
            ->select(DB::raw('sum(total_return_amount) as total_return_amt'))
            ->groupBy('sales.customer_id')->get();

        $totalInvoiceReturnPayment = DB::table('sale_payments') // Paid on invoice return due.
            ->join('sales', 'sale_payments.sale_id', 'sales.id')
            ->where('sale_payments.customer_payment_id', NULL)
            ->where('sale_payments.payment_type', 2)
            ->where('sales.customer_id', $customerId)
            ->select(DB::raw('sum(paid_amount) as total_inv_return_paid'))
            ->groupBy('sales.customer_id')->get();

        $totalCustomerReturnPayment = DB::table('customer_payments') // Paid on Total customer return due.
            ->where('customer_id', $customerId)
            ->where('type', 2)
            ->select(DB::raw('sum(paid_amount) as cr_paid'))
            ->groupBy('customer_id')->get();


        $totalSale = $totalCustomerSale->sum('total_sale');
        $totalPaid = $totalCustomerPayment->sum('c_paid') + $totalSalePayment->sum('s_paid');
        $totalReturn = $totalSaleReturn->sum('total_return_amt');
        $totalReturnPaid = $totalInvoiceReturnPayment->sum('total_inv_return_paid') + $totalCustomerReturnPayment->sum('cr_paid');
        $totalDue = ($totalSale + $customer->opening_balance + $totalReturnPaid) - $totalPaid - $totalReturn;
        $totalReturnDue = $totalReturn - ($totalSale + $customer->opening_balance - $totalPaid) - $totalReturnPaid;

        $customer->total_sale = $totalSale;
        $customer->total_paid = $totalPaid;
        $customer->total_sale_due = $totalDue;
        $customer->total_return = $totalReturn;
        $customer->total_sale_return_due = $totalReturnDue > 0 ? $totalReturnDue : 0;;
        $customer->save();
        return $totalDue;
    }

    public static function voucherTypes()
    {
        return [
            1 => 'Sale',
            2 => 'Sale Return',
            3 => 'Received Payment',
            4 => 'Return Payment',
            5 => 'Receive From Customer',
            6 => 'Paid Return Amt.',
        ];
    }

    public function voucherType($voucher_type_id)
    {
        $data = [
            0 => [
                'name' => 'Opening Balance',
                'id' => 'sale_id',
                'voucher_no' =>
                'sale_inv_id',
                'amt' => 'debit',
                'par' => 'sale_par',
            ],
            1 => [
                'name' => 'Sale',
                'id' => 'sale_id',
                'voucher_no' => 'sale_inv_id',
                'amt' => 'debit',
                'par' => 'sale_par',
            ],
            2 => [
                'name' => 'Sale Return',
                'id' => 'sale_return_id',
                'voucher_no' => 'return_inv_id',
                'amt' => 'credit',
                'par' => 'sale_return_par',
            ],
            3 => [
                'name' => 'Received Payment',
                'id' => 'sale_payment_id',
                'voucher_no' => 'sale_payment_voucher',
                'amt' => 'credit',
                'par' => 'sale_payment_par',
            ],
            4 => [
                'name' => 'Return Payment',
                'id' => 'sale_payment_id',
                'voucher_no' => 'sale_payment_voucher',
                'amt' => 'debit',
                'par' => 'sale_payment_par',
            ],
            5 => [
                'name' => 'Receive From Customer',
                'id' => 'customer_payment_id',
                'voucher_no' => 'customer_payment_voucher',
                'amt' => 'credit',
                'par' => 'customer_payment_par',
            ],
            6 => [
                'name' => 'Paid Return Amt.',
                'id' => 'customer_payment_id',
                'voucher_no' => 'customer_payment_voucher',
                'amt' => 'debit',
                'par' => 'customer_payment_par',
            ],
        ];

        return $data[$voucher_type_id];
    }

    public function addCustomerLedger($voucher_type_id, $customer_id, $date, $trans_id, $amount, $fixed_date = null)
    {
        $voucher_type = $this->voucherType($voucher_type_id);
        $addCustomerLedger = new CustomerLedger();
        $addCustomerLedger->customer_id = $customer_id;
        $addCustomerLedger->report_date = $fixed_date ? $fixed_date : date('Y-m-d', strtotime($date . date(' H:i:s')));
        $addCustomerLedger->{$voucher_type['id']} = $trans_id;
        $addCustomerLedger->{$voucher_type['amt']} = $amount;
        $addCustomerLedger->amount = $amount;
        $addCustomerLedger->amount_type = $voucher_type['amt'];
        $addCustomerLedger->voucher_type = $voucher_type_id;
        $addCustomerLedger->running_balance = $this->adjustCustomerAmountForSalePaymentDue($customer_id);
        $addCustomerLedger->save();
    }

    public function updateCustomerLedger($voucher_type_id, $customer_id, $date, $trans_id, $amount, $fixed_date = null)
    {
        $voucher_type = $this->voucherType($voucher_type_id);

        $updateCustomerLedger = CustomerLedger::where('customer_id', $customer_id)
            ->where($voucher_type['id'], $trans_id)
            ->where('voucher_type', $voucher_type_id)
            ->first();

        if ($updateCustomerLedger) {

            //$updateCustomerLedger->customer_id = $customer_id;
            $updateCustomerLedger->report_date = $fixed_date ? $fixed_date : date('Y-m-d', strtotime($date . date(' H:i:s')));
            $updateCustomerLedger->{$voucher_type['amt']} = $amount;
            $updateCustomerLedger->amount = $amount;
            $updateCustomerLedger->running_balance = $this->adjustCustomerAmountForSalePaymentDue($customer_id);
            $updateCustomerLedger->save();
        } else {

            $this->addCustomerLedger($voucher_type_id, $customer_id, $date, $trans_id, $amount, $fixed_date);
        }
    }
}
