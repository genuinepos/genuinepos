<script>
    $('#addCustomer').on('click', function () {

        $.get("{{ route('sales.pos.add.quick.customer.modal') }}", function(data) {

            $('#add_customer_modal_body').html(data);
            $('#addCustomerModal').modal('show');
        });
    });

    $('#customer_id').on('change', function () {

        var customerId = $(this).val();
        $('#previous_due').val(parseFloat(0).toFixed(2));
        $('#earned_point').val(0);
        $('#pre_redeemed').val(0);

        var pre_redeemed_amount = $('#pre_redeemed_amount').val() ? $('#pre_redeemed_amount').val() : 0;
        var order_discount = $('#order_discount').val() ? $('#order_discount').val() : 0;
        var calcDiscount = parseFloat(order_discount) - parseFloat(pre_redeemed_amount);
        $('#order_discount').val(parseFloat(calcDiscount).toFixed(2));
        $('#order_discount_amount').val(parseFloat(calcDiscount).toFixed(2));
        $('#pre_redeemed_amount').val(0);

        var url = "{{ route('contacts.customer.amounts.branch.wise', ':customerId') }}";
        var route = url.replace(':customerId', customerId);

        $.get(route, function(data) {

            $('#previous_due').val(data.total_sale_due);

            if (rpayment_settings.enable_rp == '1') {

                $('#earned_point').val(data.point);
                var __point_amount = parseFloat(data.point) * parseFloat(rpayment_settings.redeem_amount_per_unit_rp);
                $('#trial_point_amount').val(parseFloat(__point_amount).toFixed(2));
            }

            calculateTotalAmount();
        });

        calculateTotalAmount();
        document.getElementById('search_product').focus();
    });

    @if (auth()->user()->can('product_add'))

        $('#add_product').on('click', function() {
            $.ajax({
                url:"{{ route('sales.add.product.modal.view') }}",
                type:'get',
                success:function(data){

                    $('#add_product_body').html(data);
                    $('#addProductModal').modal('show');
                }
            });
        });

        // Add product by ajax
        $(document).on('submit', '#add_product_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    toastr.success('Successfully product is added.');

                    $.ajax({
                        url:"{{url('sales/pos/get/recent/product')}}"+"/"+data.id,
                        type:'get',
                        success:function(data){

                            $('.loading_button').hide();
                            $('#addProductModal').modal('hide');
                            if (!$.isEmptyObject(data.errorMsg)) {

                                toastr.error(data.errorMsg);
                            }else{

                                $('#product_list').prepend(data);
                                calculateTotalAmount();
                            }
                        }
                    });
                },
                error: function(err) {

                    $('.loading_button').hide();
                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                    $('.error').html('');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_sale_' + key + '').html(error[0]);
                    });
                }
            });
        });
    @endif

    $(document).on('click', '#suspends',function (e) {
        e.preventDefault();
        allSuspends();
    });

    function allSuspends() {

        $('#suspendedSalesModal').modal('show');
        $('#suspend_preloader').show();

        $.ajax({
            url:"{{ route('sales.pos.suspended.list') }}",
            async:true,
            success:function(data){

                $('#suspended_sale_list').html(data);
                $('#suspend_preloader').hide();
            }
        });
    }
</script>
