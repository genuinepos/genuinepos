<script>
    function calculateSubtotal(tr) {

        console.log({tr});
        var quantity = $(tr).find('#ops_quantity').val() ? $(tr).find('#ops_quantity').val() : 0;
        var unit_cost_inc_tax = $(tr).find('#ops_unit_cost_inc_tax').val() ? $(tr).find('#ops_unit_cost_inc_tax').val() : 0;

        console.log({quantity});
        console.log({unit_cost_inc_tax});

        var subtotal = parseFloat(quantity) * parseFloat(unit_cost_inc_tax);
        $(tr).find('#ops_subtotal').val(parseFloat(subtotal).toFixed(2));
    }

    $(document).on('input', '#ops_quantity', function (e) {

        var tr = $(this).closest('tr');
        calculateSubtotal(tr);
    });

    $(document).on('input', '#ops_unit_cost_inc_tax', function (e) {

        var tr = $(this).closest('tr');
        calculateSubtotal(tr);
    });

    $('#add_or_edit_opening_stock_form').on('submit',function(e) {
        e.preventDefault();

        $('.opening_stock_loading_btn').show();
        var url = $(this).attr('action');

        $.ajax({
            url : url,
            type : 'post',
            data: new FormData(this),
            processData: false,
            cache: false,
            contentType: false,
            success:function(data){

                $('.opening_stock_loading_btn').hide();

                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg, 'ERROR');
                }

                $('#addOrEditOpeningStock').modal('hide');
                productTable.ajax.reload();
            }, error: function(err) {

                $('.opening_stock_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    return;
                } else if(err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if(err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_brand_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
