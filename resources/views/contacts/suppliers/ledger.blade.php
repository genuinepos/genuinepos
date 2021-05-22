@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <style>
        .account_summary_area .heading h4{background:#0F3057;color:white}
    </style>
    <br><br><br>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                 <a href="{{ route('contacts.supplier.index') }}" class="btn btn-sm btn-success float-end"><i class="fas fa-long-arrow-alt-left"></i> Back</a>
                <h3 style="color: #32325d">Supplier Ledger</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="select_supplier_area pb-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <form action="" method="get">
                                        <select id="supplier_id" class="form-control form-control-sm">
        
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                       
                    </div>
                </div>
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                        </div>
                        <div class="ledger">
                            <div class="row">
                                <div class="col-md-5 offset-7">
                                    <div class="company_info text-right">
                                        <ul class="list-unstyled">
                                            <li><strong
                                                    class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong>
                                            </li>
                                            <li><span class="company_address">Motijeel, Arambagh, Road-144, Dhaka</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-lg-6">
                                    <div class="account_summary_area">
                                        <div class="heading py-2">
                                            <h4 class="py-2 pl-1">To :</h4>
                                        </div>
                                    </div>
                                    <div class="sand_info">
                                        <ul class="list-unstyled">
                                            <li><strong class="name">Jamal Hosain</strong></li><br>
                                            <li>Phone:<span class="phone"> 01122555545545</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-lg-6">
                                    <div class="account_summary_area">
                                        <div class="heading py-2">
                                            <h4 class="py-2 pl-1">Account Summary</h4>
                                        </div>

                                        <div class="account_summary_table">
                                            <table class="table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Opening Balance :</strong></td>
                                                        <td><span class="opening_balance">0.00</span></td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Total Purchase :</strong></td>
                                                        <td><span class="total_purchase">100000.00</span></td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Total Paid :</strong></td>
                                                        <td><span class="total_paid">100000.00</span></td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Balance Due :</strong></td>
                                                        <td><span class="balance_due">0.00</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label> <strong>All Payments Of Current Year ({{date('Y')}})</strong></label>
                                    <div class="payment_table">
                                        <div class="table-responsive" id="payment_list_table">
                                            <table class="table">
                                                <thead>
                                                    <tr class="bg-navey-blue">
                                                        <th>Date</th>
                                                        <th>Invoice ID</th>
                                                        <th>Type</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                        <th>Payment Method</th>
                                                        <th>Others</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div><br><br>
@endsection
@push('scripts')
    <script>
        $('.data_preloader').show();
        // Get all supplier for filter form
        function setSuppliers(){
            $.ajax({
                url:"{{route('purchases.get.all.supplier')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(suppliers){
                    $.each(suppliers, function(key, val){
                        $('#supplier_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.phone+')'+'</option>');
                    });
                }
            });
        }
        setSuppliers();

        // Change supplier 
        $('#supplier_id').on('change', function () {
           var supplierId = $(this).val(); 
           window.location = "{{ url('contacts/suppliers/ledger') }}"+"/"+supplierId;
        });

        function getSupplierAllInformations() {
            // Supplier pyaments
            $.ajax({
                url: "{{ route('contacts.supplier.payment.list', $supplierId) }}",
                type: 'get',
                success: function(paymentList) {
                    console.log(paymentList);
                    $('#payment_list_table').html(paymentList);
                }
            });

             // Supplier info
             $.ajax({
                url: "{{ route('contacts.supplier.all.info', $supplierId) }}",
                type: 'get',
                dataType: 'json',
                success: function(supplier) {
                    console.log(supplier);
                    $('.name').html(supplier.name);
                    $('.address').html(supplier.address);
                    $('.business').html(supplier.business_name);
                    $('.phone').html(supplier.phone);
                    $('.tax_number').html(supplier.tax_number);
                    $('.total_purchase').html(supplier.total_purchase);
                    $('.total_paid').html(supplier.total_paid);
                    $('.total_purchase_due').html(supplier.total_purchase_due);
                    $('.balance_due').html(supplier.total_purchase_due);
                    $('.opening_balance').html(supplier.opening_balance);
                    $('#supplier_id').val(supplier.id);
                    $('.data_preloader').hide();
                }
            });
        }
        getSupplierAllInformations();
    </script>
@endpush