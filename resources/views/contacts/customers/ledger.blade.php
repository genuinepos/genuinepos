@extends('layout.master')
@push('stylesheets')
<style>
    .account_summary_area .heading h4{background:#0F3057;color:white}
</style>
@endpush
@section('content')
    <br><br><br>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <a href="{{ route('contacts.customer.index') }}" class="btn btn-sm btn-success float-end"><i
                    class="fas fa-long-arrow-alt-left"></i> Back</a>
                <h3 style="color: #32325d">Customer Ledger</h3>
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

                        <div class="ledger">
                            <div class="row">
                                <div class="col-md-5 offset-7">
                                    <div class="company_info text-end">
                                        <ul class="list-unstyled">
                                            <li><strong
                                                    class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong>
                                            </li>
                                            <li><span
                                                    class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</span>
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
                                            <table class="table modal-table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-start"><strong>Opening Balance :</strong></td>
                                                        <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                                            <span class="opening_balance">0.00</span></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start"><strong>Total Sale :</strong></td>
                                                        <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                                            <span class="total_sale">100000.00</span></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start"><strong>Total Paid :</strong></td>
                                                        <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                                            <span class="total_paid">100000.00</span></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start"><strong>Balance Due :</strong></td>
                                                        <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                                            <span class="balance_due">0.00</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label> <strong>Customer Ledger</strong></label>
                                    <div class="payment_table">
                                        <div class="table-responsive" id="payment_list_table">
                                            <table class="table">
                                                <thead>
                                                    <tr class="bg-navey-blue">
                                                        <th>Date</th>
                                                        <th>Invoice ID</th>
                                                        <th>Type</th>
                                                        <th>Total</th>
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
        function setCustomers() {
            $.ajax({
                url: "{{ route('sales.get.all.customer') }}",
                type: 'get',
                dataType: 'json',
                success: function(customers) {
                    $.each(customers, function(key, val) {
                        $('#customer_id').append('<option value="' + val.id + '">' + val.name + ' (' +
                            val.phone + ')' + '</option>');
                    });
                }
            });
        }
        setCustomers();

        // Change customer 
        $('#customer_id').on('change', function() {
            var customerId = $(this).val();
            window.location = "{{ url('contacts/customers/ledger') }}" + "/" + customerId;
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
                }
            });

            // customer pyaments
            $.ajax({
                url: "{{ route('contacts.customer.ledger.list', $customerId) }}",
                type: 'get',
                success: function(paymentList) {
                    console.log(paymentList);
                    $('#payment_list_table').html(paymentList);
                    $('.data_preloader').hide();
                }
            });
        }
        getCustomerAllInformations();

    </script>
@endpush
