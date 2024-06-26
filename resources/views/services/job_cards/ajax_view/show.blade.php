@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    {{ __('Job Card Details') }} ({{ __('Job No') }} : <strong>{{ $jobCard->job_no }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Customer') }} : - </strong></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Name') }} : </strong><span>{{ $jobCard?->customer?->name }}</span></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Tax No.') }}: </strong><span>{{ $jobCard?->customer?->contact?->tax_no }}</span></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Address') }} : </strong><span>{{ $jobCard?->customer?->address }}</span></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Email') }}: </strong><span>{{ $jobCard?->customer?->contact?->email }}</span></li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }}: </strong><span>{{ $jobCard?->customer?->phone }}</span></li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong> {{ date($dateFormat, strtotime($jobCard->date_ts)) }}</li>

                            <li style="font-size:11px!important;"><strong>{{ __('Job No') }} : </strong> {{ $jobCard->job_no }}</li>

                            <li style="font-size:11px!important;"><strong>{{ __('Invoice ID') }} : </strong><span>{{ $jobCard?->sale?->invoice_id }}</span></li>

                            <li style="font-size:11px!important;"><strong>{{ __('Service Type') }} : </strong>
                                {{ str(\App\Enums\ServiceType::tryFrom($jobCard->service_type)->name)->headline() }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Status') }} : </strong>
                                <span class="fw-bold" style="{{ $jobCard?->status?->color_code }}">{{ $jobCard?->status?->name }}</span>
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Delivery Date') }} : </strong><span>{{ date($dateFormat, strtotime($jobCard->delivery_date_ts)) }}</span></li>

                            <li style="font-size:11px!important;"><strong>{{ __('Due Date') }} : </strong><span>{{ date($dateFormat, strtotime($jobCard->due_date_ts)) }}</span></li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Shop/Business') }} : </strong>
                                @php
                                    $branchName = '';
                                    if ($jobCard->branch_id) {
                                        if ($jobCard?->branch?->parentBranch) {
                                            $branchName = $jobCard?->branch?->parentBranch?->name . '(' . $jobCard?->branch?->area_name . ')' . '-(' . $jobCard?->branch?->branch_code . ')';
                                        } else {
                                            $branchName = $jobCard?->branch?->name . '(' . $jobCard?->branch?->area_name . ')' . '-(' . $jobCard?->branch?->branch_code . ')';
                                        }
                                    } else {
                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp

                                {{ $branchName }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Email') }} : </strong>
                                @if ($jobCard->branch)
                                    {{ $jobCard->branch->email }}
                                @else
                                    {{ $generalSettings['business_or_shop__email'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($jobCard->branch)
                                    {{ $jobCard->branch->phone }}
                                @else
                                    {{ $generalSettings['business_or_shop__phone'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>{{ __('Created By') }} : </strong>
                                {{ $jobCard?->createdBy?->prefix . ' ' . $jobCard?->createdBy?->name . ' ' . $jobCard?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table modal-table table-sm">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ __('Brand.') }}</td>
                                    <td style="font-size:11px!important;">: {{ $jobCard?->brand?->name }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ __('Device') }}</td>
                                    <td style="font-size:11px!important;">: {{ $jobCard?->device?->name }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ __('Device Model') }}</td>
                                    <td style="font-size:11px!important;">: {{ $jobCard?->deviceModel?->name }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ __('Serial Number') }}</td>
                                    <td style="font-size:11px!important;">: {{ $jobCard?->serial_no }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ __('Password') }}</td>
                                    <td style="font-size:11px!important;">: {{ $jobCard?->password }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table modal-table table-sm">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ __('Pre Service Checklist') }} </td>
                                    <td style="font-size:11px!important;">:
                                        @if (isset($jobCard->service_checklist) && is_array($jobCard->service_checklist))
                                            @foreach ($jobCard->service_checklist as $key => $value)
                                                <span>
                                                    @if ($value == 'yes')
                                                        <span class="text-success">✔</span>
                                                    @elseif ($value == 'no')
                                                        ❌
                                                    @else
                                                        🚫
                                                    @endif
                                                    {{ $key }}
                                                </span>
                                            @endforeach
                                            {{-- @elseif (isset($jobCard->service_checklist) && is_string($jobCard->service_checklist))
                                            @php
                                                $checklist = json_decode($jobCard->service_checklist, true);
                                            @endphp

                                            @if ($checklist === null)
                                                <p>Error decoding JSON: {{ json_last_error_msg() }}</p>
                                            @else
                                                @foreach ($checklist as $key => $value)
                                                    <span>
                                                        @if ($value == 'yes')
                                                            ✔
                                                        @elseif ($value == 'no')
                                                            ❌
                                                        @else
                                                            🚫
                                                        @endif
                                                        {{ $key }}
                                                    </span>
                                                @endforeach
                                            @endif --}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ __('Pick Up/On Site Address') }}</td>
                                    <td style="font-size:11px!important;">: {{ $jobCard?->address }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ __('Product Configuration') }}</td>
                                    <td style="font-size:11px!important;">: {{ $jobCard->product_configuration }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold"style="font-size:11px!important;">{{ __('Condition Of The Product') }}</td>
                                    <td style="font-size:11px!important;">: {{ $jobCard->product_condition }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ __('Comment By Technician') }}</td>
                                    <td style="font-size:11px!important;">: {{ $jobCard->technical_comment }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table modal-table table-sm">
                            <tbody>
                                <tr>
                                    <td colspan="3">
                                        @php
                                            $parts = $jobCard->jobCardProducts
                                                ->filter(function ($jobCardProduct) {
                                                    return $jobCardProduct?->product?->is_manage_stock == 1;
                                                })
                                                ->values();
                                        @endphp

                                        <table class="table modal-table table-sm">
                                            <thead>
                                                <tr>
                                                    <th colspan="8">
                                                        {{ __('Parts Description') }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('S/L') }}</th>
                                                    <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Description') }}</th>
                                                    <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Qty') }}</th>
                                                    <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price (Exc. Tax)') }}</th>
                                                    <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Discount') }}</th>
                                                    <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                                                    <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price (Inc. Tax)') }}</th>
                                                    <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                                                </tr>

                                                @if (count($parts) > 0)
                                                    @foreach ($parts as $index => $jobCardProduct)
                                                        <tr>
                                                            @php
                                                                $variant = $jobCardProduct->variant ? ' - ' . $jobCardProduct->variant->variant_name : '';
                                                            @endphp

                                                            <td class="text-start" style="font-size:11px!important;">{{ $index + 1 }}
                                                            </td>

                                                            <td class="text-start" style="font-size:11px!important;">{{ Str::limit($jobCardProduct->product->name, 25) . ' ' . $variant }}
                                                            </td>
                                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->quantity) }}</td>
                                                            <td class="text-start" style="font-size:11px!important;">
                                                                {{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_exc_tax) }}
                                                            </td>
                                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_discount) }} </td>
                                                            <td class="text-start" style="font-size:11px!important;">{{ '(' . $jobCardProduct->unit_tax_percent . '%)=' . $jobCardProduct->unit_tax_amount }}</td>
                                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_inc_tax) }}</td>
                                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->subtotal) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-center">{{ __('No Available') }}</td>
                                                    </tr>
                                                @endif
                                            </tbody>

                                            <thead>
                                                <tr>
                                                    <th colspan="8">
                                                        {{ __('Service Changes') }}
                                                    </th>
                                                </tr>
                                            </thead>

                                            @php
                                                $serviceProducts = $jobCard->jobCardProducts
                                                    ->filter(function ($jobCardProduct) {
                                                        return $jobCardProduct?->product?->is_manage_stock == 0;
                                                    })
                                                    ->values();
                                            @endphp

                                            <tr>
                                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('S/L') }}</th>
                                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Description') }}</th>
                                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Qty') }}</th>
                                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price (Exc. Tax)') }}</th>
                                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Discount') }}</th>
                                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price (Inc. Tax)') }}</th>
                                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                                            </tr>

                                            @if (count($serviceProducts) > 0)
                                                <tbody class="sale_print_product_list">
                                                    @foreach ($serviceProducts as $index => $jobCardProduct)
                                                        <tr>
                                                            @php
                                                                $variant = $jobCardProduct->variant ? ' - ' . $jobCardProduct->variant->variant_name : '';
                                                            @endphp

                                                            <td class="text-start" style="font-size:11px!important;">{{ $index + 1 }}
                                                            </td>

                                                            <td class="text-start" style="font-size:11px!important;">{{ Str::limit($jobCardProduct->product->name, 25) . ' ' . $variant }}
                                                            </td>
                                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->quantity) }}</td>
                                                            <td class="text-start" style="font-size:11px!important;">
                                                                {{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_exc_tax) }}
                                                            </td>
                                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_discount) }} </td>
                                                            <td class="text-start" style="font-size:11px!important;">{{ '(' . $jobCardProduct->unit_tax_percent . '%)=' . $jobCardProduct->unit_tax_amount }}</td>
                                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->unit_price_inc_tax) }}</td>
                                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($jobCardProduct->subtotal) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            @else
                                                <tr>
                                                    <td class="text-center">{{ __('No Available') }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-5 offset-md-7">
                    <div class="table-responsive">
                        <table class="display table modal-table table-sm">
                            <tr>
                                <td class="text-end" style="font-size:11px!important;">{{ __('Total Cost') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ \App\Utils\Converter::format_in_bdt($jobCard->total_cost) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table modal-table table-sm">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">
                                        {{ isset($generalSettings['service_settings__custom_field_1_label']) ? $generalSettings['service_settings__custom_field_1_label'] : __('Custom Field 1') }}
                                    </td>
                                    <td style="font-size:11px!important;">: {{ $jobCard->custom_field_1 }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">
                                        {{ isset($generalSettings['service_settings__custom_field_2_label']) ? $generalSettings['service_settings__custom_field_2_label'] : __('Custom Field 2') }}
                                    </td>
                                    <td style="font-size:11px!important;">: {{ $jobCard->custom_field_2 }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">
                                        {{ isset($generalSettings['service_settings__custom_field_3_label']) ? $generalSettings['service_settings__custom_field_3_label'] : __('Custom Field 3') }}
                                    </td>
                                    <td style="font-size:11px!important;">: {{ $jobCard->custom_field_3 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table modal-table table-sm">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">
                                        {{ isset($generalSettings['service_settings__custom_field_4_label']) ? $generalSettings['service_settings__custom_field_4_label'] : __('Custom Field 4') }}
                                    </td>
                                    <td>: {{ $jobCard->custom_field_4 }} </td>
                                </tr>

                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">
                                        {{ isset($generalSettings['service_settings__custom_field_5_label']) ? $generalSettings['service_settings__custom_field_5_label'] : __('Custom Field 5') }}
                                    </td>
                                    <td style="font-size:11px!important;">: {{ $jobCard->custom_field_5 }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ __('Problem Reported By The Customer') }}</td>
                                    <td style="font-size:11px!important;">{{ $jobCard->problems_report }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="btn-box">
                    @if (auth()->user()->can('job_cards_edit') && $jobCard->branch_id == auth()->user()->branch_id)
                        <a href="{{ route('services.job.cards.edit', [$jobCard->id]) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                    @endif

                    @php
                        $filename = __('Job Card') . '__' . $jobCard->job_no . '__' . $jobCard->date_ts . '__' . $branchName;
                    @endphp

                    <a href="{{ route('services.job.cards.print', $jobCard->id) }}" onclick="printJobCard(this); return false;" class="footer_btn btn btn-sm btn-success" id="printJobCardBtn" data-filename="{{ $filename }}">{{ __('Print') }}</a>

                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printJobCard(event) {

        var url = event.getAttribute('href');
        var filename = event.getAttribute('data-filename');
        var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 1000,
                    header: null,
                    footer: null,
                });

                document.title = filename;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }
</script>
