{{-- <script src="{{asset('backend/js/jquery-1.7.1.min.js')}}"></script> --}}
<script src="{{ asset('backend/js/number-bdt-formater.js') }}"></script>
<!--Jquery Cdn-->
<script src="{{ asset('backend/asset/cdn/js/jquery-3.6.0.js') }}"></script>
<!--Jquery Cdn End-->

<script src="{{ asset('backend/asset/js/bootstrap.bundle.min.js') }}"></script>
{{-- <script src="{{ asset('backend/asset/js/jquery.fontstar.js') }}"></script> --}}
<script src="{{ asset('assets/plugins/custom/print_this/printThis.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<!--Toaster.js js link-->
<script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
<!--Toaster.js js link end-->

{{-- Test  --}}

<!-- DataTable Cdn -->
<script type="text/javascript" src="{{ asset('backend/asset/cdn/js/jquery.dataTables.min.js') }}"></script>
<!-- DataTable Cdn End-->

<script src="{{ asset('backend/js/bootstrap-dropdown.js') }}"></script>
<script src="{{ asset('backend/js/TableTools.min.js') }}"></script>
<script src="{{ asset('backend/js/jeditable.jquery.js') }}"></script>
{{-- <script src="{{asset('backend/js/custom-script.js')}}"></script> --}}
<script src="{{ asset('backend/asset/js/main.js') }}"></script>
<script src="{{ asset('backend/asset/js/SimpleCalculadorajQuery.js') }}" defer></script>
<script>
    toastr.options = {
        "positionClass": "toast-top-center",
    }
    $(document).on('click', '#logout_option', function(e) {
        e.preventDefault();
        $.confirm({
            'title': 'Logout Confirmation',
            'content': 'Are you sure, you want to logout?',
            'buttons': {
                'Yes': {
                    'btnClass': 'yes btn-modal-primary',
                    'action': function() {
                        $('#logout_form').submit();
                    }
                },
                'No': {
                    'btnClass': 'no btn-danger',
                    'action': function() {
                        console.log('Deleted canceled.');
                    }
                }
            }
        });
    });

    $(document).on('click', '.display tbody tr', function() {
        $('.display tbody tr').removeClass('active_tr');
        $(this).addClass('active_tr');
    });

    $(document).on('click', '.selectable tbody tr', function() {

        var data = $(this).data('active_disabled');

        if (data == undefined) {

            $('.selectable tbody tr').removeClass('active_tr');
            $(this).addClass('active_tr');
        }
    });

    $(document).on('click', '#hard_reload', function() {
        window.location.reload(true);
    });
</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.js"></script>

{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
<script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    $(window).scroll(function() {
        if ($('.select2').is(':visible')) {
            $('.select2-dropdown').css({
                "display": "none"
            });
        }
    });

    $(document).on('click', '.select2', function(e) {
        e.preventDefault();
        $('.select2-dropdown').css({
            "display": ""
        });
    });

    $(document).ready(function() {
        $('.back-button').on('click', function(e) {
            e.preventDefault();
            var menuActive = window.localStorage.getItem('menu_active');
            $('#' + menuActive).addClass('active')
        });

        $('.btn-default').addClass('btn-danger');
    });

    $(document).on('select2:open', () => {

        if ($('.select2-search--dropdown .select2-search__field').length > 0) {

            document.querySelector('.select2-search--dropdown .select2-search__field').focus();
        }
    });

    if ($(window).width() < 768) {
        $('.notify-menu .company-name').appendTo('.top-menu .logo__sec');
    }
</script>

<script>
    $(document).ready(function() {
        var inputBuffer = ''; // Store the barcode input
        var lastKeyTime = Date.now(); // Track the time between key presses
        var barcodeMinLength = 6; // Minimum length to assume it's a barcode
        var barcodeTimeout = 50; // Time in ms between keystrokes (typical for a barcode)
        var barcodeDetected = false; // Flag to check if barcode has been scanned

        // Listen for keydown events on a specific input field
        $('input').on('keydown', function(e) {
            // If barcode was detected, prevent all further keypresses
            if (barcodeDetected) {
                e.preventDefault(); // Block all keypresses
                return; // Stop further execution
            }

            var currentTime = Date.now();

            // If time between keystrokes is larger than the threshold, reset the buffer
            if (currentTime - lastKeyTime > barcodeTimeout) {
                inputBuffer = '';
            }

            lastKeyTime = currentTime;
            inputBuffer += e.key;

            // Check if the input buffer contains a barcode
            if (inputBuffer.length >= barcodeMinLength && e.key === 'Enter') {
                // Detach the element or process the barcode input
                // alert('Barcode detected: ' + inputBuffer);

                // Detach the input field
                // $('#e_description').detach();

                // Set barcode detected flag to true
                barcodeDetected = true;

                // Optionally, clear the input buffer
                inputBuffer = '';
            }

            if (barcodeDetected) {
                e.preventDefault(); // Block all keypresses like Enter, Ctrl+C, etc.
            }

            barcodeDetected = false;
        });

        $('textarea').on('keydown', function(e) {
            // If barcode was detected, prevent all further keypresses
            if (barcodeDetected) {
                e.preventDefault(); // Block all keypresses
                return; // Stop further execution
            }

            var currentTime = Date.now();

            // If time between keystrokes is larger than the threshold, reset the buffer
            if (currentTime - lastKeyTime > barcodeTimeout) {
                inputBuffer = '';
            }

            lastKeyTime = currentTime;
            inputBuffer += e.key;

            // Check if the input buffer contains a barcode
            if (inputBuffer.length >= barcodeMinLength && e.key === 'Enter') {
                // Detach the element or process the barcode input
                // alert('Barcode detected: ' + inputBuffer);

                // Detach the input field
                // $('#e_description').detach();

                // Set barcode detected flag to true
                barcodeDetected = true;

                // Optionally, clear the input buffer
                inputBuffer = '';
            }

            if (barcodeDetected) {
                e.preventDefault(); // Block all keypresses like Enter, Ctrl+C, etc.
            }

            barcodeDetected = false;
        });
    });
</script>
