<script>
     function showStock() {

        $('#stock_preloader').show();
        $('#showStockModal').modal('show');

        $.ajax({
            url:"{{ route('sales.pos.branch.stock') }}",
            type:'get',
            success:function(data){

                $('#stock_modal_body').html(data);
                $('#stock_preloader').hide();
            }
        });
    }
    
    $(document).on('click', '#show_stock', function (e) {
        e.preventDefault();
        showStock();
    });
</script>
