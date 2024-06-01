<!-- js files -->
{{-- <script src="{{ asset('backend/js/jquery-1.7.1.min.js') }}"></script> --}}
<script src="{{ asset('backend/asset/cdn/js/jquery-3.6.0.js') }}"></script>
<script src="{{ asset('backend/asset/js/bootstrap.bundle.min.js') }}"></script>
{{-- <script src="{{ asset('backend/js/cart.js') }}"></script> --}}
<script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/number-bdt-formater.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script>
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
                        console.log('Canceled.');
                    }
                }
            }
        });
    });
</script>
