@php
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<html>
<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
    <thead>
      <tr>
        <th colspan="3" style="text-align:left;">
            @if ($purchase->branch)
                @if ($purchase->branch->logo != 'default.png')
                    <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $purchase->branch->logo) }}">
                @else
                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $purchase->branch->name }}</span>
                @endif
            @else
                @if ($generalSettings['business__business_logo'] != null)
                    <img src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                @else
                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__shop_name'] }}</span>
                @endif
            @endif
        </th>
        <th colspan="3" style="font-size:14px;margin:0 0 6px 0;">
        <strong>@lang('menu.purchases_status') : </strong>
            <span class="purchase_status">
                @if ($purchase->purchase_status == 1)
                    {{ __('Purchased') }}
                @elseif($purchase->purchase_status == 2){
                    @lang('menu.pending')}
                @else
                @lang('menu.purchased_by_order')
                @endif
            </span>
        </th>
        <th colspan="3" style="text-align:right;font-weight:400;">{{ __('Date:') }}{{ date($generalSettings['business__date_format'] ,strtotime($purchase->date)) }}</th>
      </tr>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td colspan="9" style="border: solid 1px #ddd; padding:10px 20px;">
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.business_location') : </strong>
                @if ($purchase->branch)
                    {!! $purchase->branch->name.' '.$purchase->branch->branch_code.' <b>(BL)</b>' !!}
                @else
                    {{ $generalSettings['business__shop_name'] }} (<b>@lang('menu.head_office')</b>)
                @endif
            </p>
            <p style="font-size:14px;margin:0 0 6px 0;">
                <strong>{{ __('Ordered Location') }} </strong>
                @if($purchase->branch_id)
                    {{ $purchase->branch->city }}, {{ $purchase->branch->state }},
                    {{ $purchase->branch->zip_code }}, {{ $purchase->branch->country }}
                @else
                    {{ $generalSettings['business__address'] }}
                @endif
            </p>
        </td>
      </tr>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td colspan="5" style="border: solid 1px #ddd; padding:10px 20px;">
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.supplier') : - </strong></p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.name') : </strong>{{ $purchase->supplier->name }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.address') : </strong>{{ $purchase->supplier->address }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.tax_number') : </strong> {{ $purchase->supplier->tax_number }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.phone') : </strong> {{ $purchase->supplier->phone }}</p>
        </td>
        <td colspan="4" style="border: solid 1px #ddd; padding:10px 20px;">
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>{{ __('P.Invoice ID') }} : </strong> {{ $purchase->invoice_id }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.purchase_date') : </strong>
                {{ date($generalSettings['business__date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}
            </p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.delivery_date') : </strong>
                {{ $purchase->delivery_date ? date($generalSettings['business__date_format'], strtotime($purchase->delivery_date)) : '' }}
            </p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.created_by') : </strong>
                {{ $purchase->admin->prefix.' '.$purchase->admin->name.' '.$purchase->admin->last_name }}
            </p>
        </td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td style="height:15px;"></td>
      </tr>
      <tr>
        <td colspan="9" style="border: solid 1px #ddd; padding:10px 20px;">
            <p style="font-size:14px;margin:0 0 6px 0;"> {{ __('P.Invoice ID :') }}{{ $purchase['invoice_id'] }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"> {{ __('Total Item :') }}{{ $purchase['total_item'] }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"> {{ __('Total Purchase Amount :') }}{{ $purchase['total_purchase_amount'] }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.payment_status') : </strong>
                @php
                    $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                @endphp
                @if ($purchase->due <= 0)
                @lang('menu.paid')
                @elseif($purchase->due > 0 && $purchase->due < $payable)
                @lang('menu.partial')
                @elseif($payable == $purchase->due)
                @lang('menu.due')
                @endif
            </p>
        </td>
      </tr>
      <tr>
        <td colspan="9" style="font-size:20px;padding:30px 15px 0 15px;">@lang('menu.description')</td>
      </tr>
        <tr>
            <th style="text-align:left; font-size:11px;">@lang('menu.sl')</th>
            <th scope="col" style="font-size:11px;">@lang('menu.description')</th>
            <th scope="col" style="font-size:11px;">@lang('menu.quantity')</th>
            <th scope="col" style="font-size:11px;">@lang('menu.unit_cost')({{ $generalSettings['business__currency'] }}) : </th>
            <th scope="col" style="font-size:11px;">@lang('menu.unit_cost')({{ $generalSettings['business__currency'] }})</th>
            <th scope="col" style="font-size:11px;">@lang('menu.tax')(%)</th>
            <th scope="col" style="font-size:11px;">{{ __('Net Unit Cost') }}({{ $generalSettings['business__currency'] }})</th>
            <th scope="col" style="font-size:11px;">@lang('menu.lot_number')</th>
            <th scope="col" style="font-size:11px; text-align:end">@lang('menu.subtotal')({{ $generalSettings['business__currency'] }})</th>
        </tr>
        @php $index = 0; @endphp
        @foreach ($purchase->purchase_products as $product)
            <tr>
                @php
                    $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : '';
                @endphp
                <td style="font-size:11px;">{{ $index + 1 }}</td>
                <td style="font-size:11px;">
                    {{ Str::limit($product->product->name, 25).' '.$variant }}
                    <small>{!! $product->description ? '<br/>'.$product->description : '' !!}</small>
                </td>
                <td style="font-size:11px;">{{ $product->quantity }}</td>
                <td style="font-size:11px;">
                    {{ App\Utils\Converter::format_in_bdt($product->unit_cost) }}
                </td>
                <td style="font-size:11px;">{{ App\Utils\Converter::format_in_bdt($product->unit_discount) }} </td>
                <td style="font-size:11px;">{{ $product->unit_tax.'('.$product->unit_tax_percent.'%)' }}</td>
                <td style="font-size:11px;">{{ App\Utils\Converter::format_in_bdt($product->net_unit_cost) }}</td>
                <td style="font-size:11px;">{{ $product->lot_no ? $product->lot_no : '' }}</td>
                <td style="font-size:14px; text-align:end">{{ App\Utils\Converter::format_in_bdt($product->line_total) }}</td>
            </tr>
            @php $index++; @endphp
        @endforeach
        <tr>
            <th colspan="7" style="font-size:14px; text-align:end">@lang('menu.net_total_amount') : {{ $generalSettings['business__currency'] }}</th>
            <td colspan="2" style="font-size:14px; text-align:end">
                {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
            </td>
        </tr>

        <tr>
            <th colspan="7" style="font-size:14px; text-align:end">@lang('menu.purchase_discount') : {{ $generalSettings['business__currency'] }}</th>
            <td colspan="2" style="font-size:14px; text-align:end">
                {{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }} {{$purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
            </td>
        </tr>

        <tr>
            <th colspan="7" style="font-size:14px; text-align:end">@lang('menu.purchase_tax') : {{ $generalSettings['business__currency'] }}</th>
            <td colspan="2" style="font-size:14px; text-align:end">
                {{ $purchase->purchase_tax_amount.' ('.$purchase->purchase_tax_percent.'%)' }}
            </td>
        </tr>

        <tr>
            <th colspan="7" style="font-size:14px; text-align:end">@lang('menu.shipment_charge') : {{ $generalSettings['business__currency'] }}</th>
            <td colspan="2" style="font-size:14px; text-align:end">
                {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
            </td>
        </tr>

        <tr>
            <th colspan="7" style="font-size:14px; text-align:end">{{ __('Purchase Total') }} : {{ $generalSettings['business__currency'] }}</th>
            <td colspan="2" style="font-size:14px; text-align:end">
                {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
            </td>
        </tr>

        <tr>
            <th colspan="7" style="font-size:14px; text-align:end">@lang('menu.paid') : {{ $generalSettings['business__currency'] }}</th>
            <td colspan="2" style="font-size:14px; text-align:end">
                {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
            </td>
        </tr>

        <tr>
            <th colspan="7" style="font-size:14px; text-align:end">@lang('menu.due') : {{ $generalSettings['business__currency'] }}</th>
            <td colspan="2" style="font-size:14px; text-align:end">
                {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
            </td>
        </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="9" style="font-size:14px;padding:50px 15px 0 15px;">
          <strong style="display:block;margin:0 0 10px 0;">Regards</strong> <br>
            If you need any support, Feel free to contact us.
            <br><br>
            <b>@lang('menu.phone')</b> {{ $generalSettings['business__phone'] }}<br>
            <b>@lang('menu.email')</b> {{ $generalSettings['business__email'] }}
        </td>
      </tr>
    </tfoot>
  </table>
</body>
</html>
