@extends('layout.master')
@section('content')
    <style>
        .contract_info_area ul li strong{color:#495677}.account_summary_area .heading h4{background:#0F3057;color:white}.contract_info_area ul li strong i {color: #495b77;font-size: 13px;}
    </style>
    <br><br><br>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <a href="{{ route('contacts.customer.index') }}" class="btn btn-sm btn-success float-end"><i class="fas fa-long-arrow-alt-left"></i> Back</a>
                <h3 style="color: #32325d">Contact Info</h3>
                <div class="row">
                    <div class="col-md-12">
                        
                        <div class="select_customer_area float-left pb-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="" method="get">
                                        <select id="customer_id" class="form-control form-control-sm">
        
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
                        <div class="tab_contant contract_info_area">
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
                                        <li><strong><i class="fas fa-info"></i> Tax Number</strong></li>
                                        <li><span class="tax_number">Tx0881087555558</span></li>
                                    </ul>
                                </div>

                                <div class="col-md-3">
                                    <ul class="list-unstyled">
                                        <li><strong> Total Sale :</strong></li>
                                        <li><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> <span class="total_sale">2000000.00</span></li>
                                        <li><strong> Total Paid :</strong></li>
                                        <li><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> <span class="total_paid">2000000.00</span></li>
                                        <li><strong> Total Sale Due :</strong></li>
                                        <li><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> <span class="total_sale_due">2000000.00</span></li>
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
        // Get all customer for filter form
        function setCustomers(){
            $.ajax({
                url:"{{route('sales.get.all.customer')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(customers){
                    $.each(customers, function(key, val){
                        $('#customer_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.phone+')'+'</option>');
                    });
                }
            });
        }
        setCustomers();

        // Change customer 
        $('#customer_id').on('change', function () {
           var customerId = $(this).val(); 
           window.location = "{{ url('contacts/customers/contact/info') }}"+"/"+customerId;
        });

        function getCustomerAllInformations() {
            // Supplier info
            $.ajax({
                url: "{{ route('contacts.customer.all.info', $customerId) }}",
                type: 'get',
                dataType: 'json',
                success: function(customer) {
                    console.log(customer);
                    $('.name').html(customer.name);
                    $('.address').html(customer.address);
                    $('.business').html(customer.business_name);
                    $('.phone').html(customer.phone);
                    $('.tax_number').html(customer.tax_number);
                    $('.total_sale').html(customer.total_sale);
                    $('.total_paid').html(customer.total_paid);
                    $('.total_sale_due').html(customer.total_sale_due);
                    $('.balance_due').html(customer.total_sale_due);
                    $('.opening_balance').html(customer.opening_balance);
                    $('#customer_id').val(customer.id);
                    $('.data_preloader').hide();
                }
            });
        }
        getCustomerAllInformations();

    </script>
@endpush
