<script>
    $(document).on('click', '.resent-tn',function (e) {
        e.preventDefault();

        showRecentTransectionModal();
    });

    function showRecentTransectionModal() {

        recentSales();
        $('#recentTransModal').modal('show');
        $('.tab_btn').removeClass('tab_active');
        $('#tab_btn').addClass('tab_active');
    }

    function recentSales() {

        $('#recent_trans_preloader').show();
        $.ajax({
            url:"{{url('common/ajax/call/recent/sales/2')}}",
            type:'get',

            success:function(data) {

                $('#transection_list').html(data);
                $('#recent_trans_preloader').hide();
            }
        });
    }

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();
        $('#recent_trans_preloader').show();
        var url = $(this).attr('href');

        $.ajax({
            url:url,
            type:'get',
            success:function(data){

                $('#transection_list').html(data);
                $('#recent_trans_preloader').hide();
            }
        });

        $('.tab_btn').removeClass('tab_active');
        $(this).addClass('tab_active');
    });

    $(document).on('click', '#only_print', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            $(data).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header : null,
                footer : null,
            });
        });
    });
</script>
