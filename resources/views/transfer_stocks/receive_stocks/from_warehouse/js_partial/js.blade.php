<script>
    $(document).on('input', '#received_qty', function(event) {

        var receivedQty = $(this).val() ? $(this).val() : 0;

        var tr = $(this).closest('tr');
        var sendQty = tr.find('#send_qty').val() ? tr.find('#send_qty').val() : 0;

        if (receivedQty > sendQty) {

            $(this).val(parseFloat(sendQty).toFixed(2));
            toastr.error("{{ __('Received quantity must not be greater then send quantity.') }}");
            $('#span_pending_qty').html(parseFloat(0).toFixed(2));
            calculateTotalAmount();
            return;
        }

        var calcPendingQty = parseFloat(sendQty) - parseFloat(receivedQty);
        tr.find('#span_pending_qty').html(parseFloat(calcPendingQty).toFixed(2));
        calculateTotalAmount();
    });

    function calculateTotalAmount() {

        var unitCostsIncTax = document.querySelectorAll('#unit_cost_inc_tax');
        var recevedQuantities = document.querySelectorAll('#received_qty');
        // Update Total Item
        var receivedStockValue = 0;
        var i = 0;
        recevedQuantities.forEach(function(qty) {

            var receivedQty = qty.value ? qty.value : 0;
            var unitCostIncTax = unitCostsIncTax[i].value ? unitCostsIncTax[i].value : 0;
            receivedStockValue += parseFloat(receivedQty) * parseFloat(unitCostIncTax);
            i++;
        });

        $('#received_stock_value').val(parseFloat(receivedStockValue).toFixed(2));
    }

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    document.onkeyup = function() {

        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.ctrlKey && e.which == 13) {

            $('#save_changes').click();
            return false;
        } else if (e.which == 27) {

            $('.select_area').hide();
            $('#list').empty();

            return false;
        }
    }

    $('#receive_from_branch_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                }

                toastr.success(data);
                window.location = "{{ url()->previous() }}";
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            $('#' + nextId).focus().select();
        }
    });
</script>
