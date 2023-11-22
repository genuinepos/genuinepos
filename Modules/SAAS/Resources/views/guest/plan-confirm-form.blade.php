<x-saas::guest title="{{ __('Confirm Plan') }}">
    <div class="container ck-container mt-3 pb-5">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Plan Name: {{ $plan->name }}</h5>
                <h5>{{ __('Plan Confirmation and Registration') }}</h5>
                {{-- <div>
                    <x-back-button href="{{ route('saas.plan.all') }}"></x-back-button>
                </div> --}}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('saas.guest.tenants.store') }}" id="tenantStoreForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="fullname" class="form-label text-bold">
                                    <b>{{ __('Full Name') }}</b>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="fullname" id="fullname"
                                    class="form-control @error('fullname') is-invalid  @enderror"
                                    placeholder="{{ __('Enter Full Name') }}" required />
                            </div>
                            <div class="mb-2">
                                <label for="email" class="form-label text-bold">
                                    <b>{{ __('Email Address') }}</b>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid  @enderror"
                                    placeholder="{{ __('Enter Email Address') }}" required />
                            </div>
                            <div class="mb-2">
                                <label for="password" class="form-label text-bold">
                                    <b>{{ __('Password') }}</b>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid  @enderror"
                                    placeholder="{{ __('Enter Password') }}" required />
                            </div>
                            <div class="mb-2">
                                <label for="phone" class="form-label text-bold">
                                    <b>{{ __('Phone Number') }}</b>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" phone="phone" id="phone"
                                    class="form-control @error('phone') is-invalid  @enderror"
                                    placeholder="{{ __('Enter Phone Number') }}" required />
                            </div>
                            <hr />
                            <div class="mb-2">
                                <label for="name" class="form-label text-bold">
                                    <b>{{ __('Business Name') }}</b>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid  @enderror"
                                    placeholder="{{ __('Enter Business Name') }}" required />
                            </div>
                            <div class="mb-2">
                                <label for="name" class="form-label text-bold">
                                    <b>{{ __('Domain Name') }}</b>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="domain" id="domain"
                                    class="form-control @error('name') is-invalid  @enderror"
                                    placeholder="{{ __('Enter Domain Name') }}" {{-- placeholder="{{ __('Enter Domain Name') }}" oninput="domainPreview()" --}} required />
                                <p class="mt-2">
                                    <span id="domainPreview" class="monospace"></span>
                                </p>
                            </div>
                            <div class="mb-2">
                                <input type="submit" class="btn btn-primary" value="{{ __('Register') }}"
                                    id="submitBtn" />
                            </div>
                        </div>
                    </div>
                </form>

                <div id="response-message" class="mt-3 d-none text-start" style="height: 100px;">
                    <div class="mt-2">
                        <h6 id="response-message-text">
                            {{ __('Creating your Business. It can take a while, please wait...') }}
                            Elapsed Time: <span id="timespan"></span> Seconds.
                            </h3>
                    </div>
                    <div class="mt-3">
                        <div class="spinner-border text-dark" role="status">
                            <span class="visually-hidden">{{ __('Loading') }}...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $('table').addClass('table table-striped');
            $('.ck-container ul').addClass('list-group');
            $('.ck-container ul li').addClass('list-group-item');

            $('#btn').click(function() {
                $('#exampleModalToggle').modal('show');
            });

            var isAvailable = false;
            $('#submitBtn').click(function(e) {
                e.preventDefault();
                $(this).attr('disabled', 'disabled');
                let url = $('#tenantStoreForm').attr('action');
                $('#response-message').removeClass('d-none');
                console.log(url);

                $('#timespan').text(0);
                setInterval(function() {
                    let currentValue = parseInt($('#timespan').text() || 0);
                    $('#timespan').text(currentValue + 1);
                }, 1000);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: $('#name').val(),
                        domain: $('#domain').val(),
                        fullname: $('#fullname').val(),
                        email: $('#email').val(),
                        password: $('#password').val(),
                        phone: $('#phone').val()
                    },
                    success: function(res) {
                        $('#response-message-text').addClass('text-success');
                        $('#response-message-text').text(
                            '{{ __('Successfully created! Redirecting you to your Domain...') }}');
                        window.location = res;
                    },
                    error: function(err) {
                        $('#response-message').addClass('d-none');
                        toastr.error(err.responseJSON.message);
                    }
                });
            });

            // Domain Check
            var typingTimer; //timer identifier
            var doneTypingInterval = 800; //time in ms, 5 seconds for example
            var $input = $('#domain');

            //on keyup, start the countdown
            $input.on('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
            });

            //on keydown, clear the countdown
            $input.on('keydown', function() {
                clearTimeout(typingTimer);
            });

            //user is "finished typing," do something
            function doneTyping() {
                $('#domainPreview').html(`<span class="">🔍Checking availability...<span>`);
                var domain = $('#domain').val();
                $.ajax({
                    url: "{{ route('saas.domain.checkAvailability') }}",
                    type: 'GET',
                    data: {
                        domain: domain
                    },
                    success: function(res) {
                        if (res.isAvailable) {
                            isAvailable = true;
                            $('#domainPreview').html(`<span class="text-success">✔ Doamin is available<span>`);
                        }
                    },
                    error: function(err) {
                        isAvailable = false;
                        $('#domainPreview').html(`<span class="text-danger">❌ Doamin is not available<span>`);
                    }
                });
            }
        </script>
    @endpush
</x-saas::guest>
