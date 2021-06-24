<?php

    public function store(Request $request)
    {
        //return $request->all();
        $prefixSettings = DB::table('general_settings')
        ->select(['id', 'prefix', 'contact_default_cr_limit'])
        ->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

        $branchInvoiceSchema = DB::table('branches')
            ->leftJoin('invoice_schemas', 'branches.invoice_schema_id', 'invoice_schemas.id')
            ->where('branches.id', auth()->user()->branch_id)
            ->select(
                'branches.*',
                'invoice_schemas.id as schema_id',
                'invoice_schemas.prefix',
                'invoice_schemas.start_from',
            )
            ->first();

        $invoicePrefix = '';
        if ($branchInvoiceSchema && $branchInvoiceSchema->prefix !== null) {
            $invoicePrefix = $branchInvoiceSchema->prefix . $branchInvoiceSchema->start_from;
        } else {
            $defaultSchemas = DB::table('invoice_schemas')->where('is_default', 1)->first();
            $invoicePrefix = $defaultSchemas->prefix . $defaultSchemas->start_from;
        }

        //return $request->all();
        if ($request->product_ids == null) {
            return response()->json(['errorMsg' => 'product table is empty']);
        }

        if ($request->paying_amount < $request->total_payable_amount && !$request->customer_id) {
            return response()->json(['errorMsg' => 'Listed customer is required when sale is due or partial.']);
        }

        if ($request->total_due > $prefixSettings->contact_default_cr_limit) {
            return response()->json(['errorMsg' => 'Due amount exceeds to default credit limit.']);
        }
      
        // generate invoice ID
        $i = 4;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $this->validate($request, [
                'warehouse_id' => 'required',
            ]);
        }

        $addSale = new Sale();
        $addSale->invoice_id = $request->invoice_id ? $request->invoice_id : $invoicePrefix . date('ymd') . $invoiceId;
        $addSale->admin_id = auth()->user()->id;

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $addSale->warehouse_id = $request->warehouse_id;
        } else {
            $addSale->branch_id = auth()->user()->branch_id;
        }

        // $addSale->customer_id = $request->customer_id;
        $addSale->customer_id = $request->customer_id != 0 ? $request->customer_id : NULL;
        $addSale->status = $request->status;
        
        if ($request->status == 1) {
            $addSale->is_fixed_challen = 1;
        }

        $addSale->pay_term = $request->pay_term;
        $addSale->date = $request->date;
        $addSale->time = date('h:i:s a');
        $addSale->report_date = date('Y-m-d', strtotime($request->date));
        $addSale->pay_term_number = $request->pay_term_number;
        $addSale->total_item = $request->total_item;
        $addSale->net_total_amount = $request->net_total_amount;
        $addSale->order_discount_type = $request->order_discount_type;
        $addSale->order_discount = $request->order_discount;
        $addSale->order_discount_amount = $request->order_discount_amount;
        $addSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $addSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $addSale->shipment_details = $request->shipment_details;
        $addSale->shipment_address = $request->shipment_address;
        $addSale->shipment_status = $request->shipment_status;
        $addSale->delivered_to = $request->delivered_to;
        $addSale->sale_note = $request->sale_note;
        $addSale->payment_note = $request->payment_note;
        $addSale->month = date('F');
        $addSale->year = date('Y');

        // Update customer due
        if ($request->status == 1) {
            $changedAmount = $request->change_amount > 0 ? $request->change_amount : 0.00;
            $paidAmount = $request->paying_amount - $changedAmount;
            if ($request->previous_due > 0) {
                $addSale->total_payable_amount = $request->total_invoice_payable;
                if ($paidAmount >= $request->total_invoice_payable) {
                    $addSale->paid = $request->total_invoice_payable;
                    $addSale->due = 0.00;
                    $payingPreviousDue = $paidAmount - $request->total_invoice_payable; // Comming Soon;
                } elseif ($paidAmount < $request->total_invoice_payable) {
                    $addSale->paid = $request->paying_amount;
                    $calcDue = $request->total_invoice_payable - $request->paying_amount;
                    $addSale->due = $calcDue;
                }
            } else {
                $addSale->total_payable_amount = $request->total_payable_amount;
                $addSale->paid = $request->paying_amount;
                $addSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0.00;
                $addSale->due = $request->total_due > 0 ? $request->total_due : 0.00;
            }
            $addSale->save();

            $customer = Customer::where('id', $request->customer_id)->first();
            if ($customer) {
                $customer->total_sale = $customer->total_sale + $request->total_payable_amount - $request->previous_due;
                $customer->total_paid = $customer->total_paid + ($request->paying_amount ? $request->paying_amount : 0);
                if ($request->paying_amount <= 0) {
                    $customer->total_sale_due = $request->total_payable_amount;
                } else {
                    if ($request->total_due > 0) {
                        $customer->total_sale_due = $request->total_due;
                    } else {
                        $customer->total_sale_due = 0;
                    }
                }

                $customer->save();
                $addCustomerLedger = new CustomerLedger();
                $addCustomerLedger->customer_id = $request->customer_id;
                $addCustomerLedger->sale_id = $addSale->id;
                $addCustomerLedger->save();
            }
        } else {
            $addSale->total_payable_amount = $request->total_invoice_payable;
            $addSale->save();
        }

        // update product quantity
        $quantities = $request->quantities;
        $units = $request->units;
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $unit_discount_types = $request->unit_discount_types;
        $unit_discounts = $request->unit_discounts;
        $unit_discount_amounts = $request->unit_discount_amounts;
        $unit_tax_percents = $request->unit_tax_percents;
        $unit_tax_amounts = $request->unit_tax_amounts;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $unit_prices_exc_tax = $request->unit_prices_exc_tax;
        $unit_prices = $request->unit_prices;
        $subtotals = $request->subtotals;
        $descriptions = $request->descriptions;

        // update product quantity and add sale product
        $index = 0;
        foreach ($product_ids as $product_id) {
            if ($request->status == 1) {
                if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                    $updateProductQty = Product::where('id', $product_id)->first();
                    if ($updateProductQty->type == 1) {
                        $updateProductQty->quantity = $updateProductQty->quantity - $quantities[$index];
                        $updateProductQty->number_of_sale = $updateProductQty->number_of_sale + $quantities[$index];
                        $updateProductQty->save();

                        $updateWarehouseProductQty = ProductWarehouse::where('warehouse_id', $request->warehouse_id)
                            ->where('product_id', $product_id)->first();
                        $updateWarehouseProductQty->product_quantity = $updateWarehouseProductQty->product_quantity - $quantities[$index];
                        $updateWarehouseProductQty->save();

                        if ($variant_ids[$index] != 'noid') {
                            $updateProductVariant = ProductVariant::where('id', $variant_ids[$index])
                                ->where('product_id', $product_id)->first();
                            $updateProductVariant->variant_quantity = $updateProductVariant->variant_quantity - $quantities[$index];
                            $updateProductVariant->number_of_sale = $updateProductVariant->number_of_sale + $quantities[$index];
                            $updateProductVariant->save();

                            $updateProductWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $updateWarehouseProductQty->id)
                                ->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
                            $updateProductWarehouseVariant->variant_quantity = $updateProductWarehouseVariant->variant_quantity - $quantities[$index];
                            $updateProductWarehouseVariant->save();
                        }
                    }
                } else {
                    $updateProductQty = Product::where('id', $product_id)->first();
                    if ($updateProductQty->type == 1) {
                        $updateProductQty->quantity = $updateProductQty->quantity - $quantities[$index];
                        $updateProductQty->number_of_sale = $updateProductQty->number_of_sale - $quantities[$index];
                        $updateProductQty->save();

                        $updateBranchProductQty = ProductBranch::where('branch_id', $request->branch_id)
                            ->where('product_id', $product_id)->first();
                        $updateBranchProductQty->product_quantity = $updateBranchProductQty->product_quantity - $quantities[$index];
                        $updateBranchProductQty->save();

                        if ($variant_ids[$index] != 'noid') {
                            $updateProductVariant = ProductVariant::where('id', $variant_ids[$index])
                                ->where('product_id', $product_id)->first();
                            $updateProductVariant->variant_quantity = $updateProductVariant->variant_quantity - $quantities[$index];
                            $updateProductVariant->number_of_sale = $updateProductVariant->number_of_sale + $quantities[$index];
                            $updateProductVariant->save();

                            $updateProductBranchVariant = ProductBranchVariant::where('product_branch_id', $updateBranchProductQty->id)
                                ->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
                            $updateProductBranchVariant->variant_quantity = $updateProductBranchVariant->variant_quantity - $quantities[$index];
                            $updateProductBranchVariant->save();
                        }
                    }
                }
            }

            $addSaleProduct = new SaleProduct();
            $addSaleProduct->sale_id = $addSale->id;
            $addSaleProduct->product_id = $product_id;
            $addSaleProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $addSaleProduct->quantity = $quantities[$index];
            $addSaleProduct->unit_discount_type = $unit_discount_types[$index];
            $addSaleProduct->unit_discount = $unit_discounts[$index];
            $addSaleProduct->unit_discount_amount = $unit_discount_amounts[$index];
            $addSaleProduct->unit_tax_percent = $unit_tax_percents[$index];
            $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$index];
            $addSaleProduct->unit = $units[$index];
            $addSaleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
            $addSaleProduct->unit_price_exc_tax = $unit_prices_exc_tax[$index];
            $addSaleProduct->unit_price_inc_tax = $unit_prices[$index];
            $addSaleProduct->subtotal = $subtotals[$index];
            $addSaleProduct->description = $descriptions[$index] ? $descriptions[$index] : NULL;
            $addSaleProduct->save();
            $index++;
        }

        // Add sale payment
        if ($request->status == 1) {
            if ($request->paying_amount > 0) {
                $changedAmount = $request->change_amount > 0 ? $request->change_amount : 0.00;
                $paidAmount = $request->paying_amount - $changedAmount;

                if ($request->previous_due > 0) {
                    if ($paidAmount >= $request->total_invoice_payable) {
                        $addSalePayment = new SalePayment();
                        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                        $addSalePayment->sale_id = $addSale->id;
                        $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
                        $addSalePayment->account_id = $request->account_id;
                        $addSalePayment->pay_mode = $request->payment_method;
                        $addSalePayment->paid_amount = $request->total_invoice_payable;
                        $addSalePayment->date = $request->date;
                        $addSalePayment->time = date('h:i:s a');
                        $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                        $addSalePayment->month = date('F');
                        $addSalePayment->year = date('Y');
                        $addSalePayment->note = $request->payment_note;

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

                        if ($request->account_id) {
                            // update account
                            $account = Account::where('id', $request->account_id)->first();
                            $account->credit = $account->credit + $request->total_invoice_payable;
                            $account->balance = $account->balance + $request->total_invoice_payable;
                            $account->save();

                            // Add cash flow
                            $addCashFlow = new CashFlow();
                            $addCashFlow->account_id = $request->account_id;
                            $addCashFlow->credit = $request->total_invoice_payable;
                            $addCashFlow->balance = $account->balance;
                            $addCashFlow->sale_payment_id = $addSalePayment->id;
                            $addCashFlow->transaction_type = 2;
                            $addCashFlow->cash_type = 2;
                            $addCashFlow->date = $request->date;
                            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                            $addCashFlow->month = date('F');
                            $addCashFlow->year = date('Y');
                            $addCashFlow->admin_id = auth()->user()->id;
                            $addCashFlow->save();
                        }

                        if ($request->customer_id) {
                            $addCustomerLedger = new CustomerLedger();
                            $addCustomerLedger->customer_id = $request->customer_id;
                            $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                            $addCustomerLedger->row_type = 2;
                            $addCustomerLedger->save();
                        }

                        $payingPreviousDue = $paidAmount - $request->total_invoice_payable;
                        if ($payingPreviousDue > 0) {
                            $dueAmounts = $payingPreviousDue;
                            $dueInvoices = Sale::where('customer_id', $request->customer_id)
                                ->where('due', '>', 0)
                                ->get();
                            if (count($dueInvoices) > 0) {
                                $index = 0;
                                foreach ($dueInvoices as $dueInvoice) {
                                    if ($dueInvoice->due > $dueAmounts) {
                                        $dueInvoice->paid = $dueInvoice->paid + $dueAmounts;
                                        $dueInvoice->due = $dueInvoice->due - $dueAmounts;
                                        $dueInvoice->save();
                                        $addSalePayment = new SalePayment();
                                        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                                        $addSalePayment->sale_id = $dueInvoice->id;
                                        $addSalePayment->customer_id = $request->customer_id;
                                        $addSalePayment->account_id = $request->account_id;
                                        $addSalePayment->paid_amount = $dueAmounts;
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
                                            $account->credit = $account->credit + $dueAmounts;
                                            $account->balance = $account->balance + $dueAmounts;
                                            $account->save();

                                            // Add cash flow
                                            $addCashFlow = new CashFlow();
                                            $addCashFlow->account_id = $request->account_id;
                                            $addCashFlow->credit = $dueAmounts;
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
                                            $addCustomerLedger->customer_id = $request->customer_id;
                                            $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                                            $addCustomerLedger->row_type = 2;
                                            $addCustomerLedger->save();
                                        }

                                        //$dueAmounts -= $dueAmounts; 
                                        if ($index == 1) {
                                            break;
                                        }
                                    } elseif ($dueInvoice->due == $dueAmounts) {
                                        $dueInvoice->paid = $dueInvoice->paid + $dueAmounts;
                                        $dueInvoice->due = $dueInvoice->due - $dueAmounts;
                                        $dueInvoice->save();
                                        $addSalePayment = new SalePayment();
                                        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                                        $addSalePayment->sale_id = $dueInvoice->id;
                                        $addSalePayment->customer_id = $request->customer_id;
                                        $addSalePayment->account_id = $request->account_id;
                                        $addSalePayment->paid_amount = $dueAmounts;
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
                                            $account->credit = $account->credit + $dueAmounts;
                                            $account->balance = $account->balance + $dueAmounts;
                                            $account->save();

                                            // Add cash flow
                                            $addCashFlow = new CashFlow();
                                            $addCashFlow->account_id = $request->account_id;
                                            $addCashFlow->credit = $dueAmounts;
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
                                            $addCustomerLedger->customer_id = $request->customer_id;
                                            $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                                            $addCustomerLedger->row_type = 2;
                                            $addCustomerLedger->save();
                                        }

                                        if ($index == 1) {
                                            break;
                                        }
                                    } elseif ($dueInvoice->due < $dueAmounts) {
                                        $addSalePayment = new SalePayment();
                                        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                                        $addSalePayment->sale_id = $dueInvoice->id;
                                        $addSalePayment->customer_id = $request->customer_id;
                                        $addSalePayment->account_id = $request->account_id;
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
                                            $account->credit = $account->credit + $dueInvoice->due;
                                            $account->balance = $account->balance + $dueInvoice->due;
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
                                            $addCustomerLedger->customer_id = $request->customer_id;
                                            $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                                            $addCustomerLedger->row_type = 2;
                                            $addCustomerLedger->save();
                                        }

                                        $dueAmounts = $dueAmounts - $dueInvoice->due;
                                        $dueInvoice->paid = $dueInvoice->paid + $dueInvoice->due;
                                        $dueInvoice->due = $dueInvoice->due - $dueInvoice->due;
                                        $dueInvoice->save();
                                    }
                                    $index++;
                                }
                            }
                        }
                    } elseif ($paidAmount < $request->invoice_payable_amount) {
                        $addSalePayment = new SalePayment();
                        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                        $addSalePayment->sale_id = $paidAmount;
                        $addSalePayment->customer_id = $request->customer_id;
                        $addSalePayment->account_id = $request->account_id;
                        $addSalePayment->paid_amount = $paidAmount;
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
                            $account->credit = $account->credit + $paidAmount;
                            $account->balance =$account->balance - $paidAmount;
                            $account->save();

                            // Add cash flow
                            $addCashFlow = new CashFlow();
                            $addCashFlow->account_id = $request->account_id;
                            $addCashFlow->credit = $paidAmount;
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

                        if ($request->customer_id) {
                            $addCustomerLedger = new CustomerLedger();
                            $addCustomerLedger->customer_id = $request->customer_id;
                            $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                            $addCustomerLedger->row_type = 2;
                            $addCustomerLedger->save();
                        }
                    }
                } else {
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addSalePayment->sale_id = $addSale->id;
                    $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->pay_mode = $request->payment_method;
                    $addSalePayment->paid_amount = $paidAmount;
                    $addSalePayment->date = $request->date;
                    $addSalePayment->time = date('h:i:s a');
                    $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addSalePayment->month = date('F');
                    $addSalePayment->year = date('Y');
                    $addSalePayment->note = $request->payment_note;

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

                    if ($request->account_id) {
                        // update account
                        $account = Account::where('id', $request->account_id)->first();
                        $account->credit = $account->credit + $paidAmount;
                        $account->balance = $account->balance + $paidAmount;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->credit = $paidAmount;
                        $addCashFlow->balance = $account->balance;
                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = $request->date;
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($request->customer_id) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $request->customer_id;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }
                }
            }
        }

        $previous_due = $request->previous_due;
        $total_payable_amount = $request->total_payable_amount;
        $paying_amount = $request->paying_amount;
        $total_due = $request->total_due;
        $change_amount = $request->change_amount;

        $sale = Sale::with([
            'customer',
            'branch',
            'branch.add_sale_invoice_layout',
            'sale_products',
            'sale_products.product',
            'sale_products.product.warranty',
            'sale_products.variant',
            'admin'
        ])->where('id', $addSale->id)->first();

        if ($request->action == 'save_and_print') {
            if ($request->status == 1) {
                return view('sales.save_and_print_template.sale_print', compact(
                    'sale',
                    'previous_due',
                    'total_payable_amount',
                    'paying_amount',
                    'total_due',
                    'change_amount'
                ));
                //return view('sales.save_and_print_template.sale_print', compact('addSale'));
            } elseif ($request->status == 2) {
                return view('sales.save_and_print_template.draft_print', compact('sale'));
            } elseif ($request->status == 4) {
                return view('sales.save_and_print_template.quotation_print', compact('sale'));
            } 
        } else {
            if ($request->status == 1) {
                session()->flash('successMsg', 'Sale created successfully');
                return response()->json(['finalMsg' => 'Sale created successfully']);
            } elseif ($request->status == 2) {
                session()->flash('successMsg', 'Sale draft created successfully');
                return response()->json(['draftMsg' => 'Sale draft created successfully']);
            } elseif ($request->status == 4) {
                session()->flash('successMsg', 'Sale quotation created successfully');
                return response()->json(['quotationMsg' => 'Sale quotation created successfully']);
            }
        }
    }