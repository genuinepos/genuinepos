@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<div class="sale_payment_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            <h3>
                <b>
                    @if ($expense->branch)
                        {{ $expense->branch->name . '/' . $expense->branch->branch_code }}
                    @else
                        {{ $generalSettings['business']['shop_name'] }} (<b>@lang('menu.head_office')</b>)
                    @endif
                </b>
            </h3>
            <p>
                @if ($expense->branch)
                    {{ $expense->branch->city . ', ' . $expense->branch->state . ', ' . $expense->branch->zip_code . ', ' . $expense->branch->country }}
                @else
                    {{ $generalSettings['business']['address'] }}
                @endif
            </p>
            <h6><strong>Expense Details</strong></h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <div class="row">
            <div class="col-md-6">
                <p><b>@lang('menu.reference_no') :</b> {{ $expense->invoice_id }}</p>
            </div>

            <div class="col-md-6 text-end">
                <p><b>@lang('menu.date') :</b> {{ date('d/m/Y', strtotime($expense->date))  }}</p>
            </div>
        </div>
    </div>

    <div class="total_amount_table_area pt-3">
        <div class="row">
            <div class="col-md-12">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">@lang('menu.expense_for'):</th>
                            <td class="text-end">{{ $expense->admin ? $expense->admin->prefix.' '.$expense->admin->name.' '.$expense->admin->last_name : 'N/A' }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('menu.description')</th>
                            <th class="text-end">@lang('menu.amount')</th>
                        </tr>

                        @foreach ($expense->expense_descriptions as $expense_description)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}. {{ $expense_description->category->name }}</td>
                                <td class="text-end">{{ $generalSettings['business']['currency'] }} {{ $expense_description->amount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-end">@lang('menu.tax') :</th>
                            <th class="text-end">
                               <b>{{ $generalSettings['business']['currency'] }}
                                {{ $expense->tax_amount }}</b>
                            </th>
                        </tr>

                        <tr>
                            <th class="text-end">@lang('menu.total') :</th>
                            <th class="text-end"><b>{{ $generalSettings['business']['currency'] }} {{ $expense->net_total_amount }}</b></th>
                        </tr>

                        <tr>
                            <th class="text-end">@lang('menu.paid') :</th>
                            <th class="text-end">
                               <b>{{ $generalSettings['business']['currency'] }}
                                {{ $expense->paid }}</b>
                            </th>
                        </tr>

                        <tr>
                            <th class="text-end">@lang('menu.due') :</th>
                            <th class="text-end">
                               <b>{{ $generalSettings['business']['currency'] }}
                                {{ $expense->due }}</b>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>

    <div class="signature_area">
        <table class="w-100 mt-5">
            <tbody>
                <tr>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">Receiver</p> </th>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">Made By</p></th>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">Account Manger</p></th>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">Authority</p></th>
                </tr>

                <tr class="text-center">
                    <td colspan="4" class="text-center">
                        <img style="width:170px; height:40px;" class="mt-3" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($expense->invoice_id, $generator::TYPE_CODE_128)) }}">
                    </td>
                </tr>

                <tr class="text-center">
                    <td colspan="4" class="text-center">
                        {{ $expense->invoice_id }}
                    </td>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="4" class="text-navy-blue text-center"><small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small> </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
