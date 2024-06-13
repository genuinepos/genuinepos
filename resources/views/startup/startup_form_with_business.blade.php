<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>{{ __('Startup') }}</title>

    @php
        $rtl = app()->isLocale('ar');
    @endphp

    @include('startup.css_partial.common_css')
</head>

<body class="inner">
    @include('startup.partials.header')

    <div class="tab-section py-120">
        <div class="container">
            <div class="row mt-2">
                <div class="col-12">
                    <div class="tab-nav">
                        <button class="single-nav businessSetupTab active" id="single-nav" data-tab="businessSetupTab">
                            <span class="txt">{{ __('Business Setup') }}</span>
                        </button>
                    </div>

                    <div class="tab-contents">
                        <form id="startup_from" action="{{ route('startup.finish') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="single-tab active" id="businessSetupTab">
                                @include('startup.partials.business_setup_partial', ['onlyBusinessSetup' => true])
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout form for global -->
    <form id="logout_form" class="d-hide" action="{{ route('logout') }}" method="POST">@csrf</form>
    <!-- Logout form for global end -->

    <!-- js files -->
    @include('startup.js_partials.common_js.common_js')

    @include('startup.js_partials.form_js.startup_form_with_business_js')
</body>

</html>
