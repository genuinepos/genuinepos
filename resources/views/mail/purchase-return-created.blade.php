@php
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<html>
<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
    <thead>
      <tr>
        <th colspan="3" style="text-align:left;">
            @if ($return->branch)
                @if ($return->branch->logo != 'default.png')
                    <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $return->branch->logo) }}">
                @else
                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $return->branch->name }}</span>
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
            {{-- <span class="purchase_status">
                @if ($return->purchase_status == 1)
                    {{ __('Purchased') }}
                @elseif($return->purchase_status == 2){
                    @lang('menu.pending')}
                @else
                @lang('menu.purchased_by_order')
                @endif
            </span> --}}
        </th>
        <th colspan="3" style="text-align:right;font-weight:400;">{{ date($generalSettings['business__date_format'] ,strtotime($return['date'])) . ' ' . date($timeFormat, strtotime($return['time'])) }}</th>
      </tr>
      <tr>
        <td colspan="9" style="border: solid 1px #ddd; padding:10px 20px;">
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.business_location') : </strong>
                @if ($return->branch)
                    {!! $return->branch->name.' '.$return->branch->branch_code.' <b>(BL)</b>' !!}
                @else
                    {{ $generalSettings['business__shop_name'] }} (<b>@lang('menu.head_office')</b>)
                @endif
            </p>
            <p style="font-size:14px;margin:0 0 6px 0;">
                <strong>{{ __('Ordered Location') }} </strong>
                @if($return->branch_id)
                    {{ $return->branch->city }}, {{ $return->branch->state }},
                    {{ $return->branch->zip_code }}, {{ $return->branch->country }}
                @else
                    {{ $generalSettings['business__address'] }}
                @endif
            </p>
        </td>
      </tr>
      <tr>
        <td colspan="5" style="border: solid 1px #ddd; padding:10px 20px;">
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.supplier') : - </strong></p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.name') : </strong> {{ $return->supplier ? $return->supplier->name : $return->purchase->supplier->name }}
            </p>
            <p style="font-size:14px;margin:0 0 6px 0;">
                @if($return->warehouse)
                <strong>{{ __('Return Stock Location') }} : {{ $return->warehouse->warehouse_name.'/'.$return->warehouse->warehouse_code }}<b>(WH)</b>
                @elseif($return->branch)
                    {{ $return->branch->name.'/'.$return->branch->branch_code }} <b>(B.L)</b>
                @else
                {{ $generalSettings['business__shop_name'] }}<b>(@lang('menu.head_office'))</b>
                @endif
                </p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.tax_number') : </strong> {{ $return->supplier->tax_number }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.phone') : </strong> {{ $return->supplier->phone }}</p>
        </td>
        <td colspan="4" style="border: solid 1px #ddd; padding:10px 20px;">
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>{{ __('PR. Invoice ID') }} : </strong> {{ $return->invoice_id }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.purchase_date') : </strong>
                {{ date($generalSettings['business__date_format'], strtotime($return->date)) . ' ' . date($timeFormat, strtotime($return->time)) }}
            </p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.delivery_date') : </strong>
                {{ $return->delivery_date ? date($generalSettings['business__date_format'], strtotime($return->delivery_date)) : '' }}
            </p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.created_by') : </strong>
                {{ $return->admin->prefix.' '.$return->admin->name.' '.$return->admin->last_name }}
            </p>
        </td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td colspan="9" style="border: solid 1px #ddd; padding:10px 20px;">
            <p style="font-size:14px;margin:0 0 6px 0;"> {{ __('PR. Invoice ID :') }}{{ $return['invoice_id'] }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"> {{ __('Total Item :') }}{{ $return['total_item'] }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"> {{ __('Total return Amount :') }}{{ $return['total_purchase_amount'] }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"><strong>@lang('menu.payment_status') : </strong>
                @php
                    $payable = $return->total_purchase_amount - $return->total_return_amount;
                @endphp
                @if ($return->due <= 0)
                @lang('menu.paid')
                @elseif($return->due > 0 && $return->due < $payable)
                @lang('menu.partial')
                @elseif($payable == $return->due)
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
            <th scope="col" style="font-size:11px;">@lang('menu.product')</th>
            <th scope="col" style="font-size:11px;">@lang('menu.unit_cost')</th>
            <th scope="col" style="font-size:11px;">@lang('menu.return_quantity') </th>
            <th scope="col" style="font-size:11px;">@lang('menu.sub_total')</th>
        </tr>
        @php $index = 0; @endphp
        @foreach ($return->purchase_return_products as $purchase_return_product)
            <tr>
                <td style="font-size:11px;">{{ $index + 1 }}</td>
                <td style="font-size:11px;">
                    {{ $purchase_return_product->product->name }}

                    @if ($purchase_return_product->variant)
                        -{{ $purchase_return_product->variant->variant_name }}
                    @endif
                    @if ($purchase_return_product->variant)
                        ({{ $purchase_return_product->variant->variant_code }})
                    @else
                        ({{ $purchase_return_product->product->product_code }})
                    @endif
                </td>
                <td style="font-size:11px;">
                    {{ App\Utils\Converter::format_in_bdt($purchase_return_product->unit_cost) }}
                </td>
                <td style="font-size:11px;">
                    {{ $purchase_return_product->return_qty }} ({{ $purchase_return_product->unit }})
                </td>
                <td style="font-size:11px;">
                    {{ $purchase_return_product->return_subtotal }}
                 </td>
            </tr>
            @php $index++; @endphp
        @endforeach
        <tr>
            <th colspan="4" class="text-end">@lang('menu.total_return_amount') : {{ $generalSettings['business__currency'] }}</th>
            <td colspan="2" class="text-end">{{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}</td>
        </tr>

        <tr>
            <th colspan="4" class="text-end">@lang('menu.total_due') : {{ $generalSettings['business__currency'] }}</th>

            <td colspan="2" class="text-end">

                @if ($return->purchase_id)

                    {{ App\Utils\Converter::format_in_bdt($return->total_return_due) }}
                @else
                @lang('menu.check_supplier_due')
                @endif
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
