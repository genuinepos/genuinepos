 <!--Money Receipt design-->
 <div class="print_area">
    <div class="print_content">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4">
                    @if ($receipt->logo)
                        <img style="height: 75px;width:200px;" src="{{ asset('public/uploads/branch_logo/'.$receipt->logo) }}">  
                    @else 
                        <img style="height: 75px;width:200px;" src="{{asset('public/uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
                    @endif
                </div>

                <div class="col-md-4 col-sm-4 col-lg-4">
                    <div class="heading text-center">
                        <h4>Money Receipt Voucher</h4>

                        <h5 class="company_name mt-1">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>

                        @if ($receipt->branch_name)
                            <h6 class="company_address mt-1">
                                {{ $receipt->branch_name . '/' . $receipt->branch_code }} <br>
                                {{ $receipt->city ? $receipt->city : '' }},{{ $receipt->state ? $receipt->state : '' }},{{ $receipt->zip_code ? $receipt->zip_code : '' }},{{ $receipt->country ? $receipt->country : '' }}.
                            </h6>
                            <h6>Phone : {{ $receipt->phone }}</h6>
                            <h6>Email : {{ $receipt->email }}</h6>
                        @else 
                            <h6 class="company_address mt-1">{{ json_decode($generalSettings->business, true)['address'] }}</h6>
                            <h6>Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</h6>
                            <h6>Email : {{ json_decode($generalSettings->business, true)['email'] }}</h6>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-4 col-lg-4">
                <h6><b>Voucher No</b>  : {{ $receipt->is_invoice_id ? $receipt->invoice_id : '.......................................' }}</h6>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">

            </div>

            <div class="col-md-4 col-sm-4 col-lg-4 text-end">
                <h6> <b>Date</b> : {{ $receipt->is_date ? $receipt->date : '.......................................' }}</h6>
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
                            <h6><b>Amount Of Money</b> : {{ $receipt->is_amount ? $receipt->amount : '' }}</h6>
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
                            <h6><b>In Words</b> : <span class="inWord"></span></h6>
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
            <div class="col-md-12 col-sm-12 col-lg-12">
                <b>
                    <h6><b>Note</b> : {{ $receipt->is_note ? $receipt->note : '' }}</h6>
                </b>
            </div>
        </div>
        <br><br>

        <div class="row page_break">
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

            @if (env('PRINT_SD_PAYMENT') == true)
                <div class="col-md-12 text-center">
                    <small>Software By <b>SpeedDigit Pvt. Ltd.</b> </small> 
                </div>
            @endif
        </div><br>
    </div>
    
    <div class="print_content">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4">
                    @if ($receipt->logo)
                        <img style="height: 75px;width:200px;" src="{{ asset('public/uploads/branch_logo/'.$receipt->logo) }}">  
                    @else 
                        <img style="height: 75px;width:200px;" src="{{asset('public/uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
                    @endif
                </div>

                <div class="col-md-4 col-sm-4 col-lg-4">
                    <div class="heading text-center">
                        <h4>Money Receipt Voucher</h4>
                        <h5 class="company_name mt-1">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                        @if ($receipt->branch_name)
                            <h6 class="company_address mt-1">
                                {{ $receipt->branch_name . '/' . $receipt->branch_code }} <br>
                                {{ $receipt->city ? $receipt->city : '' }},{{ $receipt->state ? $receipt->state : '' }},{{ $receipt->zip_code ? $receipt->zip_code : '' }},{{ $receipt->country ? $receipt->country : '' }}.
                            </h6>
                            <h6>Phone : {{ $receipt->phone }}</h6>
                            <h6>Email : {{ $receipt->email }}</h6>
                        @else 
                            <h6 class="company_address mt-1">{{ json_decode($generalSettings->business, true)['address'] }}</h6>
                            <h6>Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</h6>
                            <h6>Email : {{ json_decode($generalSettings->business, true)['email'] }}</h6>
                        @endif
                    </div>
                </div>
            </div>
        </div>

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
                            <h6><b>Amount Of Money</b> : {{ $receipt->is_amount ? $receipt->amount : '' }}</h6>
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
                            <h6><b>In Words</b> : <span class="inWord"></span></h6>
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
            <div class="col-md-12 col-sm-12 col-lg-12">
                <b>
                    <h6><b>Note</b> : {{ $receipt->is_note ? $receipt->note : '' }}</h6>
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

            @if (env('PRINT_SD_PAYMENT') == true)
                <div class="col-md-12 text-center">
                    <small>Software By <b>SpeedDigit Pvt. Ltd.</b> </small> 
                </div>
            @endif
        </div>
    </div>
</div>
<!--Money Receipt design end-->