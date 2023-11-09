<script>
     function showStock() {

        $('#stock_preloader').show();
        $('#showStockModal').modal('show');

        // sales.pos.branch.stock
        $.ajax({
            url:"#",
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
