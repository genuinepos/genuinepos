@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp 
 <!--Money Receipt design-->
 <div class="print_area">
    <div class="print_content">
        @if ($receipt->is_header_less == 0)
            <div class="heading_area">
                <div class="row">
                    <div class="col-6">
                        @if ($receipt->logo)
                            <img style="height: 70px;width:200px;" src="{{ asset('public/uploads/branch_logo/'.$receipt->logo) }}">  
                        @else 
                            <img style="height: 70px;width:200px;" src="{{asset('public/uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
                        @endif
                    </div>

                    <div class="col-6">
                        <div class="heading text-end">
                            <h3>Money Receipt Voucher</h3>
                            <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                            @if ($receipt->branch_name)
                                <h6 class="company_address">
                                    {{ $receipt->branch_name . '/' . $receipt->branch_code }} <br>
                                    {{ $receipt->city ? $receipt->city : '' }},{{ $receipt->state ? $receipt->state : '' }},{{ $receipt->zip_code ? $receipt->zip_code : '' }},{{ $receipt->country ? $receipt->country : '' }}.
                                </h6>
                                <p><strong>Phone :</strong> {{ $receipt->phone }}</p>
                                <p><strong>Email :</strong> {{ $receipt->email }}</p>
                            @else 
                                <h6 class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</h6>
                                <h6>Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</h6>
                                <h6>Email : {{ json_decode($generalSettings->business, true)['email'] }}</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div><br>
        @endif

        @if ($receipt->is_header_less == 1)
            @for ($i = 0; $i < $receipt->gap_from_top; $i++)
                <br>
            @endfor
        @endif

        <div class="row">
            <div class="col-md-4 col-sm-4 col-lg-4">
                <h6><b>Voucher No</b>  : {{ $receipt->is_invoice_id ? $receipt->invoice_id : '.......................................' }}</h6>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">

            </div>

            <div class="col-md-4 col-sm-4 col-lg-4 text-start">
                <h6> <b>Date</b> : {{ $receipt->is_date ? date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($receipt->date)) : '.......................................' }}</h6>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <b>
                            <h6> <b> Received With Thanks From </b> : {{ $receipt->cus_name }}</h6>
                        </b>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <b>
                            <h6><b>Amount Of Money</b> : {{ $receipt->is_amount ? json_decode($generalSettings->business, true)['currency'].' '.$receipt->amount : '' }}</h6>
                        </b>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            @if ($receipt->is_amount)
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="row">
                        <div class="col-md-12">
                            <b>
                                <h6><b>In Words</b> : <span class="inword"></span></h6>
                            </b>
                        </div>
                        <div class="col-md-12">
                            <h6 class="borderTop d-block"></h6>
                        </div>
                    </div>
                </div><br>
            @endif 

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <b>
                            <h6> <b>Paid To</b>  : </h6>
                        </b>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <b>
                            <h6><b>On Account Of</b>  : </h6>
                        </b>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <b>
                    <h6><b>Pay Method </b> : Cash/Card/Bank-Transfer/Cheque/Advanced</h6>
                </b>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 text-end">
                <b>
                    <h6> {{ $receipt->is_note ? $receipt->note : '' }}</h6>
                </b>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col-md-6">
                <div class="details_area">
                    <h5 class="borderTop">Customer's signature </h5>
                </div>
            </div>
            <div class="col-md-6">
                <div class="details_area text-end">
                    <h5 class="borderTop"> Signature Of Authority </h5>
                </div>
            </div>
        </div>

        <div class="row page_break">
            <div class="col-12 text-center">
                <img style="width: 170px; height:30px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($receipt->invoice_id, $generator::TYPE_CODE_128)) }}">
                @if (env('PRINT_SD_SALE') == true)
                    <small class="d-block">Software By <b>SpeedDigit Pvt. Ltd.</b></small>
                @endif
            </div>
        </div>
    </div>
    
    <div class="print_content">
        @if ($receipt->is_header_less == 0)
            <div class="heading_area">
                <div class="row">
                    <div class="col-6">
                        @if ($receipt->logo)
                            <img style="height: 70px;width:200px;" src="{{ asset('public/uploads/branch_logo/'.$receipt->logo) }}">  
                        @else 
                            <img style="height: 70px;width:200px;" src="{{asset('public/uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
                        @endif
                    </div>

                    <div class="col-6">
                        <div class="heading text-end">
                            <h3>Money Receipt Voucher</h3>
                            <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                            @if ($receipt->branch_name)
                                <h6 class="company_address">
                                    {{ $receipt->branch_name . '/' . $receipt->branch_code }} <br>
                                    {{ $receipt->city ? $receipt->city : '' }},{{ $receipt->state ? $receipt->state : '' }},{{ $receipt->zip_code ? $receipt->zip_code : '' }},{{ $receipt->country ? $receipt->country : '' }}.
                                </h6>
                                <p><strong>Phone :</strong> {{ $receipt->phone }}</p>
                                <p><strong>Email :</strong> {{ $receipt->email }}</p>
                            @else 
                                <h6 class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</h6>
                                <h6>Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</h6>
                                <h6>Email : {{ json_decode($generalSettings->business, true)['email'] }}</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div><br>
        @endif

        @if ($receipt->is_header_less == 1)
            @for ($i = 0; $i < $receipt->gap_from_top; $i++)
                <br>
            @endfor
        @endif

        <div class="row">
            <div class="col-md-4 col-sm-4 col-lg-4">
                <h6><b>Voucher No</b>  : {{ $receipt->is_invoice_id ? $receipt->invoice_id : '.......................................' }}</h6>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">

            </div>

            <div class="col-md-4 col-sm-4 col-lg-4 text-end">
                <h6><b>Date</b>  : {{ $receipt->is_date ? $receipt->date : '.......................................' }}</h6>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <b>
                            <h6><b>Received With Thanks From</b> : {{ $receipt->cus_name }}</h6>
                        </b>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <b>
                            <h6><b>Amount Of Money</b> : {{ $receipt->is_amount ? json_decode($generalSettings->business, true)['currency'].' '.$receipt->amount : '' }}</h6>
                        </b>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            @if ($receipt->is_amount)
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="row">
                        <div class="col-md-12">
                            <b>
                                <h6><b>In Words</b> : <span id="inWord"></span></h6>
                            </b>
                        </div>
                        <div class="col-md-12">
                            <h6 class="borderTop d-block"></h6>
                        </div>
                    </div>
                </div><br> 
            @endif
            

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <b>
                            <h6><b>Paid To</b> : </h6>
                        </b>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <b>
                            <h6><b>On Account Of</b>  : </h6>
                        </b>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <b>
                    <h6><b>Pay Method</b>  : Cash/Card/Bank-Transfer/Cheque/Advanced</h6>
                </b>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 text-end">
                <b>
                    <h6> {{ $receipt->is_note ? $receipt->note : '' }}</h6>
                </b>
            </div>
        </div><br><br>

        <div class="row">
            <div class="col-md-6">
                <div class="details_area">
                    <h5 class="borderTop">Customer's signature </h5>
                </div>
            </div>
            <div class="col-md-6">
                <div class="details_area text-end">
                    <h5 class="borderTop"> Signature Of Authority </h5>
                </div>
            </div>
        </div>

        <div class="row">
            @if (env('PRINT_SD_SALE') == true)
                <div class="col-12 text-center">
                    <img style="width: 170px; height:30px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($receipt->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <small class="d-block">Software By <b>SpeedDigit Pvt. Ltd.</b></small>
                </div>
            @endif
        </div>
    </div>
</div>
<!--Money Receipt design end-->

<script>
    var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
      var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];
  
      function inWords (num) {
          if ((num = num.toString()).length > 9) return 'overflow';
          n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
          if (!n) return; var str = '';
          str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
          str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
          str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
          str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
          str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'only ' : '';
          return str;
      }
      document.getElementById('inword').innerHTML = inWords(parseInt("{{ $receipt->amount }}"));
  </script>