@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <style>
        .contract_info_area ul li strong{color:#495677}.account_summary_area .contract_info_area ul li strong i{color:#495677}.account_summary_area .heading h4{background:#0F3057;color:white}.contract_info_area ul li strong i {color: #495b77;font-size: 13px;}
    </style><br><br><br>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->

          
            
            <div class="container">
                 <a href="{{ route('contacts.supplier.index') }}" class="btn btn-sm btn-success float-end"><i class="fas fa-long-arrow-alt-left"></i> Back</a>
                <h3 style="color: #32325d">Contact Info</h3>
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

                </div>
                <div class="card card-custom">
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                        </div>
                        
                        <div class="contract_info_area">
                            <div class="row">
                                <div class="col-md-3">
                                    <ul class="list-unstyled">
                                        <li><strong class="name">Jamal Hosain</strong></li><br>
                                        <li><strong><i class="fas fa-map-marker-alt"></i> Address</strong></li>
                                        <li><span class="address">Dhaka, Bangladesh.</span></li><br>
                                        <li><strong><i class="fas fa-briefcase"></i> Business Name</strong></li>
                                        <li><span class="business">Premium Multi Trade</span></li>
                                    </ul>
                                </div>

                                <div class="col-md-3">
                                    <ul class="list-unstyled">
                                        <li><strong><i class="fas fa-phone-square"></i> Phone</strong></li>
                                        <li><span class="phone">+0881087555558</span></li>
                                    </ul>
                                </div>

                                <div class="col-md-3">
                                    <ul class="list-unstyled">
                                        <li><strong><i class="fas fa-info"></i> Tex Number</strong></li>
                                        <li><span class="tax_number">Tx0881087555558</span></li>
                                    </ul>
                                </div>

                                <div class="col-md-3">
                                    <ul class="list-unstyled">
                                        <li><strong> Total Purchase :</strong></li>
                                        <li><span class="total_purchase">2000000.00</span></li>
                                        <li><strong> Total Paid :</strong></li>
                                        <li><span class="total_paid">2000000.00</span></li>
                                        <li><strong> Total Purchase Due :</strong></li>
                                        <li><span class="total_purchase_due">2000000.00</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--end: Datatable-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection
@push('scripts')
    <script>
        $('.data_preloader').show();
        // Get all supplier for filter form
        function setSuppliers(){
            $.ajax({
                url:"{{route('purchases.get.all.supplier')}}",
                async:false,
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
           window.location = "{{ url('contacts/suppliers/contact/info') }}"+"/"+supplierId;
        });

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').hide();
            var show_content = $(this).data('show');
            $('.' + show_content).show();
            $(this).addClass('tab_active');
        });

        function getSupplierAllInformations() {
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