<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Choose Store/Business') }}</title>

    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome6/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/digitboard-style.css') }}">
    <!-- <link rel="stylesheet" id="primaryColor" href="assets/css/blue-color.css"> -->
    <!-- <link rel="stylesheet" id="rtlStyle" href="#"> -->

    <style>
        .main-content .steps-sidebar .sidebar-content .step-list li::after {
            display: none;
        }

        .main-content .all-steps .account-types .form-check label {
            padding: 8px;
            gap: 10px;
            min-width: 360px;
        }

        .main-content .all-steps .account-types .form-check label .part-icon {
            width: 20px;
        }

        .main-content .all-steps .account-types .form-check label .part-icon img {
            height: 20px !important;
            object-fit: 100%;
        }

        .main-content .all-steps .account-types .form-check label .title {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .main-content .all-steps .account-types {
            gap: 10px;
        }
    </style>
</head>

<body class="light-theme">
    <!-- theme color hidden button -->
    <button class="header-btn theme-color-btn d-none"><i class="fa-light fa-sun-bright"></i></button>
    <!-- theme color hidden button -->

    <!-- main content start -->
    <div class="main-content login-panel multi-step-signup-panel">
        <div class="steps-sidebar bg-primary">
            <div class="sidebar-content">
                <div class="sidebar-logo">
                    <a href="index.html">
                        <img style="height: 40px; width:auto;" src="{{ asset('assets/images/app_logo.png') }}" alt="Logo">
                    </a>
                </div>
                <ul class="step-list scrollable">
                    <li class="active">
                        <span class="step-txt">
                            <span class="step-name">Account Type</span>
                            <span class="step-info">Select your account type</span>
                        </span>
                    </li>
                    <li>
                        <span class="step-txt">
                            <span class="step-name">Account Info</span>
                            <span class="step-info">Setup your account settings</span>
                        </span>
                    </li>
                    <li>
                        <span class="step-txt">
                            <span class="step-name">Business Details</span>
                            <span class="step-info">Setup your business details</span>
                        </span>
                    </li>
                    <li>
                        <span class="step-txt">
                            <span class="step-name">Billing Details</span>
                            <span class="step-info">Provide your payment info</span>
                        </span>
                    </li>
                    <li>
                        <span class="step-txt">
                            <span class="step-name">Completed</span>
                            <span class="step-info">Your account is created</span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="all-steps">
            <div class="single-step scrollable show">
                <div class="step-content-wrap">
                    <div class="step-content">
                        <div class="step-heading">
                            <h4 class="step-title">{{ __('Choose Your Store/Company') }} <button class="btn-flush" data-bs-toggle="tooltip" data-bs-title="Billing is issued based on your selected account type"></button></h4>
                        </div>
                        <div class="account-types">
                            <div class="form-check border-primary">
                                <input class="form-check-input" type="radio" name="accountType" id="personalAccountType">
                                <label class="form-check-label" for="personalAccountType">
                                    <span class="part-icon">
                                        <img style="height: 40px; width:auto;" src="{{ asset('assets/images/app_logo.png') }}" alt="Logo">
                                    </span>
                                    <span class="part-txt">
                                        <span class="title">Apex</span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check border-primary">
                                <input class="form-check-input" type="radio" name="accountType" id="corporateAccountType" checked>
                                <label class="form-check-label" for="corporateAccountType">
                                    <span class="part-icon">
                                        <img style="height: 40px; width:auto;" src="{{ asset('assets/images/app_logo.png') }}" alt="Logo">
                                    </span>
                                    <span class="part-txt">
                                        <span class="title">Bata</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="btn-box w-100 d-flex justify-content-end">
                        <button class="btn btn-sm btn-primary next-button px-3">Continue <i class="fa-light fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer start -->
        <div class="footer">
            <p>Copyright©
                <script>
                    document.write(new Date().getFullYear())
                </script> All Rights Reserved By <span class="text-primary">SpeedDigit Software Solution</span>
            </p>
        </div>
        <!-- footer end -->
    </div>
    <!-- main content end -->

    <script src="assets/vendor/js/jquery-3.6.0.min.js"></script>
    <script src="assets/vendor/js/jquery.overlayScrollbars.min.js"></script>
    <script src="assets/vendor/js/select2.min.js"></script>
    <script src="assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/select2-init.js"></script>
    <!-- for demo purpose -->
    <script>
        var rtlReady = $('html').attr('dir', 'ltr');
        if (rtlReady !== undefined) {
            localStorage.setItem('layoutDirection', 'ltr');
        }
    </script>
    <!-- for demo purpose -->
</body>

</html>
