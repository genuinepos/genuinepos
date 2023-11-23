@props(['title' => null])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ (isset($title) ? $title . ' | ' : '') . config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        #app {
            min-height: 100vh;
            height: 100% !important;
            background: linear-gradient(125deg, #ECFCFF 0%, #ECFCFF 40%, #B2FCFF calc(40% + 1px), #B2FCFF 60%, #5EDFFF calc(60% + 1px), #5EDFFF 72%, #3E64FF calc(72% + 1px), #3E64FF 100%);

            background: linear-gradient(110deg, #FFD9E8 4%, #FFD9E8 40%, #DE95BA calc(40% + 1px), #DE95BA 50%, #7F4A88 calc(50% + 1px), #7F4A88 70%, #4A266A calc(70% + 1px), #4A266A 100%);
        }
        .alert {
            margin-bottom: 0px !important;
        }
    </style>
    @stack('css')
    @vite(['resources/js/app.js'])
</head>

<body>
    <div id="app">
        <form class="d-none" id="logout-form" method="POST" action="{{ route('saas.logout') }}">
            @csrf
            @method('DELETE')
        </form>

        <x-saas::nav />
        <x-saas::message />
        {{ $slot }}
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}'
            }
        });

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_form').attr('action', url);
            console.log(url);
            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn btn-danger',
                        'action': function() {
                            console.log("goint to " + url);
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                processData: false,
                                dataType: false,
                                cache: false,
                                success: function(data) {
                                    toastr.error(data);
                                    $('.dataTable').DataTable().ajax.reload();
                                }
                            });
                        }
                    },
                    'No': {
                        'class': 'btn btn-secondary',
                        'action': function() {

                        }
                    }
                }
            });
        });


        $(document).on('click', '.delete-and-refresh-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_form').attr('action', url);
            console.log(url);
            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                processData: false,
                                dataType: false,
                                cache: false,
                                success: function(data) {
                                    window.location.reload();
                                    toastr.success(data);
                                }
                            });
                        }
                    },
                    'No': {
                        'class': 'btn-secondary',
                        'action': function() {

                        }
                    }
                }
            });
        });

        $('body').on('click', '.show-btn', function(e) {
            e.preventDefault();
            console.log($(this).attr('href'));
            $.ajax({
                url: $(this).attr('href'),
                success: function(html) {
                    $('#modal').html(html).modal('show');
                }
            });
        });
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}'
            }
        });

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_form').attr('action', url);
            console.log(url);
            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn btn-danger',
                        'action': function() {
                            console.log("goint to " + url);
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                processData: false,
                                dataType: false,
                                cache: false,
                                success: function(data) {
                                    toastr.error(data);
                                    $('.dataTable').DataTable().ajax.reload();
                                }
                            });
                        }
                    },
                    'No': {
                        'class': 'btn btn-secondary',
                        'action': function() {

                        }
                    }
                }
            });
        });


        $(document).on('click', '.delete-and-refresh-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_form').attr('action', url);
            console.log(url);
            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                processData: false,
                                dataType: false,
                                cache: false,
                                success: function(data) {
                                    window.location.reload();
                                    toastr.success(data);
                                }
                            });
                        }
                    },
                    'No': {
                        'class': 'btn-secondary',
                        'action': function() {

                        }
                    }
                }
            });
        });

        $('body').on('click', '.show-btn', function(e) {
            e.preventDefault();
            console.log($(this).attr('href'));
            $.ajax({
                url: $(this).attr('href'),
                success: function(html) {
                    $('#modal').html(html).modal('show');
                }
            });
        });
    </script>

    @auth
        <script>
            function handleLogout() {
                if (confirm("Are you sure logging out?")) {
                    document.getElementById('logout-form').submit();
                }
            }
        </script>
    @endauth

    @if (isset($errors) && $errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error("{{ $error }}");
            </script>
        @endforeach
    @endif

    {!! NoCaptcha::renderJs() !!}
    @stack('js')
</body>

</html>
