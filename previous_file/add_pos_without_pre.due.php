// Store pos sale
    // public function store(Request $request)
    // {
    //     //return $request->all();
    //     $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
    //     $invoicePrefix = json_decode($prefixSettings->prefix, true)['sale_invoice'];
    //     $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

    //     //return $request->all();
    //     if ($request->product_ids == null) {
    //         return response()->json(['errorMsg' => 'product table is empty']);
    //     }

    //     if ($request->action == 1) {
    //         if ($request->paying_amount < $request->total_payable_amount && !$request->customer_id) {
    //             return response()->json(['errorMsg' => 'Customer is required when sale is due or partial.']);
    //         }

    //         if ($request->payment_status == 1 || $request->payment_status == 2) {
    //             if ($request->paying_amount == 0) {
    //                 return response()->json(['errorMsg' => 'If you want to sale in full due, so press due button.']);
    //             } elseif ($request->paying_amount < $request->total_payable_amount) {
    //                 return response()->json(['errorMsg' => 'If you want to sale in partial payment, so select partial.']);
    //             }
    //         }
    //     }

    //     // generate invoice ID
    //     $i = 6;
    //     $a = 0;
    //     $invoiceId = '';
    //     while ($a < $i) {
    //         $invoiceId .= rand(1, 9);
    //         $a++;
    //     }

    //     $this->validate($request, [
    //         'branch_id' => 'required',
    //     ]);

    //     $addSale = new Sale();
    //     $addSale->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'SI') . date('ymd') . $invoiceId;
    //     $addSale->admin_id = auth()->user()->id;

    //     if (auth()->user()->role == 1 || auth()->user()->role == 2) {
    //         $addSale->branch_id = $request->branch_id;
    //     } else {
    //         $addSale->branch_id = auth()->user()->branch_id;
    //     }

    //     // $addSale->customer_id = $request->customer_id;
    //     $addSale->customer_id = $request->customer_id != 0 ? $request->customer_id : NULL;
    //     $addSale->status = $request->action;

    //     if ($request->action == 3) {
    //         $addSale->is_fixed_challen = 1;
    //     }

    //     if ($request->action == 5) {
    //         $holdInvoice = Sale::where('branch_id', $request->branch_id)->where('status', 5)->where('admin_id', auth()->user()->id)->get();
    //         if ($holdInvoice->count() > 0) {
    //             return response()->json(['errorMsg' => 'You can hold only 3 invoices.']);
    //         }
    //     }

    //     $addSale->date = date('d-m-Y');
    //     $addSale->report_date = date('Y-m-d h:m:i');
    //     $addSale->month = date('F');
    //     $addSale->year = date('Y');
    //     //$addSale->pay_term = $request->pay_term;
    //     //$addSale->pay_term_number = $request->pay_term_number;
    //     $addSale->total_item = $request->total_item;
    //     $addSale->net_total_amount = $request->net_total_amount;
    //     $addSale->order_discount_type = 1;
    //     $addSale->order_discount = $request->order_discount_amount;
    //     $addSale->order_discount_amount = $request->order_discount_amount;
    //     $addSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
    //     $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
    //     $addSale->shipment_charge = 0.00;
    //     $addSale->total_payable_amount = $request->total_payable_amount;

    //     if ($request->action == 1) {
    //         $addSale->paid = $request->payment_status == 1 || $request->payment_status == 2 || $request->payment_status == 4 ? $request->paying_amount : 0.00;
    //         $addSale->change_amount = $request->change_amount >= 0 && $request->payment_status != 3 ? $request->change_amount : 0.00;

    //         if ($request->payment_status == 3) {
    //             $addSale->due = $request->total_payable_amount;
    //         } else {
    //             if ($request->total_due >= 0 && $request->payment_status != 3) {
    //                 $addSale->due =   $request->total_due;
    //             }
    //         }
    //     }

    //     $addSale->created_by = 2;
    //     $addSale->save();

    //     // Update customer due
    //     if ($request->action == 1) {
    //         $customer = Customer::where('id', $request->customer_id)->first();
    //         if ($customer) {
    //             $customer->total_sale += $request->total_payable_amount;
    //             if ($request->payment_status == 3) {
    //                 $customer->total_sale_due = $request->total_payable_amount;
    //             } else {
    //                 if ($request->total_due > 0) {
    //                     $customer->total_sale_due += $request->total_due;
    //                 } else {
    //                     $customer->total_sale_due = 0;
    //                 }
    //             }

    //             $customer->save();
    //             $addCustomerLedger = new CustomerLedger();
    //             $addCustomerLedger->customer_id = $request->customer_id;
    //             $addCustomerLedger->sale_id = $addSale->id;
    //             $addCustomerLedger->save();
    //         }
    //     }

    //     // update product quantity
    //     $quantities = $request->quantities;
    //     $units = $request->units;
    //     $product_ids = $request->product_ids;
    //     $variant_ids = $request->variant_ids;
    //     $unit_discount_types = $request->unit_discount_types;
    //     $unit_discounts = $request->unit_discounts;
    //     $unit_discount_amounts = $request->unit_discount_amounts;
    //     $unit_tax_percents = $request->unit_tax_percents;
    //     $unit_tax_amounts = $request->unit_tax_amounts;
    //     $unit_prices_exc_tax = $request->unit_prices_exc_tax;
    //     $unit_prices_inc_tax = $request->unit_prices_inc_tax;
    //     $subtotals = $request->subtotals;

    //     // update product quantity and add sale product
    //     $index = 0;
    //     foreach ($product_ids as $product_id) {
    //         if ($request->action == 1) {
    //             $updateProductQty = Product::where('id', $product_id)->first();
    //             if ($updateProductQty->type == 1) {
    //                 $updateProductQty->quantity -= $quantities[$index];
    //                 $updateProductQty->save();

    //                 $updateBranchProductQty = ProductBranch::where('branch_id', $request->branch_id)->where('product_id', $product_id)->first();
    //                 $updateBranchProductQty->product_quantity -= $quantities[$index];
    //                 $updateBranchProductQty->save();

    //                 if ($variant_ids[$index] != 'noid') {
    //                     $updateProductVariant = ProductVariant::where('id', $variant_ids[$index])->where('product_id', $product_id)->first();
    //                     $updateProductVariant->variant_quantity -= $quantities[$index];
    //                     $updateProductVariant->save();

    //                     $updateProductBranchVariant = ProductBranchVariant::where('product_branch_id', $updateBranchProductQty->id)->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
    //                     $updateProductBranchVariant->variant_quantity -= $quantities[$index];
    //                     $updateProductBranchVariant->save();
    //                 }
    //             }
    //         }

    //         $addSaleProduct = new SaleProduct();
    //         $addSaleProduct->sale_id = $addSale->id;
    //         $addSaleProduct->product_id = $product_id;
    //         $addSaleProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
    //         $addSaleProduct->quantity = $quantities[$index];
    //         $addSaleProduct->unit_discount_type = $unit_discount_types[$index];
    //         $addSaleProduct->unit_discount = $unit_discounts[$index];
    //         $addSaleProduct->unit_discount_amount = $unit_discount_amounts[$index];
    //         $addSaleProduct->unit_tax_percent = $unit_tax_percents[$index];
    //         $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$index];
    //         $addSaleProduct->unit = $units[$index];
    //         $addSaleProduct->unit_price_exc_tax = $unit_prices_exc_tax[$index];
    //         $addSaleProduct->unit_price_inc_tax = $unit_prices_inc_tax[$index];
    //         $addSaleProduct->subtotal = $subtotals[$index];
    //         $addSaleProduct->save();
    //         $index++;
    //     }

    //     if ($request->customer_id) {
    //         $addCustomerLedger = new CustomerLedger();
    //         $addCustomerLedger->customer_id = $request->customer_id;
    //         $addCustomerLedger->sale_id = $addSale->id;
    //         $addCustomerLedger->save();
    //         Cache::forget('all-customers');
    //     }

    //     // Add sale payment
    //     if ($request->payment_status == 1 || $request->payment_status == 2 || $request->payment_status == 4) {
    //         $payment_method = '';
    //         if ($request->paying_amount > 0) {
    //             $addSalePayment = new SalePayment();
    //             $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
    //             $addSalePayment->sale_id = $addSale->id;
    //             $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
    //             $addSalePayment->account_id = $request->account_id;

    //             $addSalePayment->paid_amount = $request->paying_amount;
    //             $addSalePayment->date = date('d-m-Y');
    //             $addSalePayment->report_date = date('Y-m-d');
    //             $addSalePayment->month = date('F');
    //             $addSalePayment->year = date('Y');
    //             $addSalePayment->note = $request->payment_note;

    //             if ($request->payment_status == 2) {
    //                 $payment_method = 'Card';
    //                 $addSalePayment->pay_mode = 'Card';
    //                 $addSalePayment->card_no = $request->card_no;
    //                 $addSalePayment->card_holder = $request->card_holder_name;
    //                 $addSalePayment->card_transaction_no = $request->card_transaction_no;
    //                 $addSalePayment->card_type = $request->card_type;
    //                 $addSalePayment->card_month = $request->month;
    //                 $addSalePayment->card_year = $request->year;
    //                 $addSalePayment->card_secure_code = $request->secure_code;
    //             } elseif ($request->payment_status == 1) {
    //                 $payment_method = 'Cash';
    //                 $addSalePayment->pay_mode = 'Cash';
    //             } elseif ($request->payment_status == 4) {
    //                 if ($request->payment_method == 'Card') {
    //                     $payment_method = $request->payment_method;
    //                     $addSalePayment->pay_mode = $request->payment_method;
    //                     $addSalePayment->card_no = $request->card_no;
    //                     $addSalePayment->card_holder = $request->card_holder_name;
    //                     $addSalePayment->card_transaction_no = $request->card_transaction_no;
    //                     $addSalePayment->card_type = $request->card_type;
    //                     $addSalePayment->card_month = $request->month;
    //                     $addSalePayment->card_year = $request->year;
    //                     $addSalePayment->card_secure_code = $request->secure_code;
    //                 } elseif ($request->payment_method == 'Cheque') {
    //                     $payment_method = $request->payment_method;
    //                     $addSalePayment->pay_mode = $request->payment_method;
    //                     $addSalePayment->cheque_no = $request->cheque_no;
    //                 } elseif ($request->payment_method == 'Bank-Transfer') {
    //                     $payment_method = $request->payment_method;
    //                     $addSalePayment->pay_mode = $request->payment_method;
    //                     $addSalePayment->account_no = $request->account_no;
    //                 } elseif ($request->payment_method == 'Custom') {
    //                     $payment_method = $request->payment_method;
    //                     $addSalePayment->pay_mode = $request->payment_method;
    //                     $addSalePayment->transaction_no = $request->transaction_no;
    //                 } elseif ($request->payment_method == 'Cash') {
    //                     $payment_method = $request->payment_method;
    //                     $addSalePayment->pay_mode = $request->payment_method;
    //                 } elseif ($request->payment_method == 'Other') {
    //                     $payment_method = $request->payment_method;
    //                     $addSalePayment->pay_mode = $request->payment_method;
    //                 } elseif ($request->payment_method == 'Advanced') {
    //                     $payment_method = $request->payment_method;
    //                     $addSalePayment->pay_mode = $request->payment_method;
    //                 }
    //             }

    //             $addSalePayment->admin_id = auth()->user()->id;
    //             $addSalePayment->save();

    //             $defaultAccount = BranchPaymentMethod::where('branch_id', $request->branch_id)->where('method_name', $payment_method)->first();
    //             if ($request->account_id || isset($defaultAccount->account_id)) {
    //                 // update account
    //                 if ($request->account_id) {
    //                     $account = Account::where('id', $request->account_id)->first();
    //                     $account->credit += $request->paying_amount;
    //                     $account->balance += $request->paying_amount;
    //                     $account->save();
    //                 } elseif ($defaultAccount->account_id) {
    //                     $account = Account::where('id', $defaultAccount->account_id)->first();
    //                     $account->credit += $request->paying_amount;
    //                     $account->balance += $request->paying_amount;
    //                     $account->save();
    //                 }

    //                 // Add cash flow
    //                 $addCashFlow = new CashFlow();
    //                 $addCashFlow->account_id = $request->account_id ? $request->account_id : $defaultAccount->account_id;
    //                 $addCashFlow->credit = $request->paying_amount;
    //                 $addCashFlow->balance = $account->balance;
    //                 $addCashFlow->sale_payment_id = $addSalePayment->id;
    //                 $addCashFlow->transaction_type = 2;
    //                 $addCashFlow->cash_type = 2;
    //                 $addCashFlow->date = date('d-m-Y');
    //                 $addCashFlow->report_date = date('Y-m-d');
    //                 $addCashFlow->month = date('F');
    //                 $addCashFlow->year = date('Y');
    //                 $addCashFlow->admin_id = auth()->user()->id;
    //                 $addCashFlow->save();
    //                 Cache::forget('all-accounts');
    //             }

    //             if ($request->customer_id) {
    //                 $addCustomerLedger = new CustomerLedger();
    //                 $addCustomerLedger->customer_id = $request->customer_id;
    //                 $addCustomerLedger->sale_payment_id = $addSalePayment->id;
    //                 $addCustomerLedger->row_type = 2;
    //                 $addCustomerLedger->save();
    //             }
    //         }
    //     }

    //     if ($request->action == 1) {
    //         Cache::forget('all-products');
    //         return view('sales.save_and_print_template.sale_print', compact('addSale'));
    //     } elseif ($request->action == 2) {
    //         return view('sales.save_and_print_template.draft_print', compact('addSale'));
    //     } elseif ($request->action == 4) {
    //         return view('sales.save_and_print_template.quotation_print', compact('addSale'));
    //     } elseif ($request->action == 3) {
    //         return view('sales.save_and_print_template.challan_print', compact('addSale'));
    //     } elseif ($request->action == 5) {
    //         return response()->json(['holdInvoiceMsg' => 'Invoice is holded.']);
    //     }
    // }