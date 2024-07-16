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
        .light-theme .main-content .all-steps .account-types .form-check input:checked+label {
            width: 100%;
        }

        .light-theme .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #464646;
            font-size: 14px;
            border: 1px solid #cdcdcd;
            padding: 0 10px;
            height: 30px;
            line-height: 30px;
            background: #fff;
        }

        .light-theme .main-content .all-steps .account-types .form-check label {
            min-width: 366px;
        }

        .light-theme .main-content .all-steps .account-types .form-check label .title {
            color: #000000;
            font-size: 16px;
            line-height: 1.3;
        }

        .main-content .steps-sidebar .sidebar-logo {
            /* -webkit-filter: brightness(); */
            filter: brightness(1);
        }

        .main-content .steps-sidebar .sidebar-logo {

            height: auto;
            margin-bottom: 50px;
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }

        .main-content .steps-sidebar .sidebar-content {
            height: 100%;
        }

        .social_links li {
            display: inline-block;
            margin: 2px 3px;
        }

        .social_links li a {
            font-size: 17px;
            color: #fff;
        }
    </style>
</head>

<body class="light-theme">
    <!-- theme color hidden button -->
    <button class="header-btn theme-color-btn d-none"><i class="fa-light fa-sun-bright"></i></button>
    <!-- theme color hidden button -->

    <!-- main content start -->
    <div class="main-content login-panel multi-step-signup-panel">
        <div class="steps-sidebar bg-dark">
            <div class="sidebar-content">
                <div class="sidebar-logo text-center pb-3">
                    <img style="height: 50px; width:auto;" src="{{ asset('assets/images/app_logo.png') }}" alt="System Logo" class="logo__img">

                    <ul class="social_links mt-2">
                        <li>
                            <a href="{{ config('speeddigit.website') }}" target="_blank"><i class="fa-solid fa-globe"></i></a>
                        </li>

                        <li>
                            <a href="{{ config('speeddigit.facebook') }}" target="_blank"><i class="fa-brands fa-square-facebook"></i></a>
                        </li>

                        <li>
                            <a href="{{ config('speeddigit.twitter') }}" target="_blank"><i class="fa-brands fa-square-twitter"></i></a>
                        </li>

                        <li>
                            <a href="{{ config('speeddigit.instagram') }}" target="_blank"><i class="fa-brands fa-square-instagram"></i></a>
                        </li>

                        <li>
                            <a href="{{ config('speeddigit.youtube') }}" target="_blank"><i class="fa-brands fa-square-youtube"></i></a>
                        </li>

                        <li>
                            <a href="{{ config('speeddigit.linkedin') }}" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
                        </li>

                        <li>
                            <a href="{{ config('speeddigit.tiktok') }}" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
                        </li>
                    </ul>
                </div>

                {{-- <ul class="step-list scrollable">
                    <li class="active">
                        <span class="step-txt">
                            <span class="step-name">{{ __("Account Type") }}</span>
                            <span class="step-info">{{ __("Select your account type") }}</span>
                        </span>
                        <span class="step-number border-primary">1</span>
                    </li>
                    <li>
                        <span class="step-txt">
                            <span class="step-name">{{ __("Account Info") }}</span>
                            <span class="step-info">{{ __("Setup your account settings") }}</span>
                        </span>
                        <span class="step-number border-primary">{{ __("2") }}</span>
                    </li>
                    <li>
                        <span class="step-txt">
                            <span class="step-name">{{ __("Business Details") }}</span>
                            <span class="step-info">{{ __("Setup your business details") }}</span>
                        </span>
                        <span class="step-number border-primary">3</span>
                    </li>
                    <li>
                        <span class="step-txt">
                            <span class="step-name">{{ __("Billing Details") }}</span>
                            <span class="step-info">{{ __("Provide your payment info") }}</span>
                        </span>
                        <span class="step-number border-primary">4</span>
                    </li>
                    <li>
                        <span class="step-txt">
                            <span class="step-name">{{ __("Completed") }}</span>
                            <span class="step-info">{{ __("Your account is created") }}</span>
                        </span>
                        <span class="step-number border-primary">5</span>
                    </li>
                </ul> --}}
            </div>
        </div>
        <div class="all-steps">
            <div class="single-step scrollable show">
                <div class="step-content-wrap">
                    <form action="{{ route('change.location.confirm') }}" method="post">
                        @csrf
                        <div class="step-content">
                            <div class="step-heading">
                                <h4 class="step-title">{{ __('Choose Redirection Location') }} <button class="btn-flush" data-bs-toggle="tooltip" data-bs-title="Billing is issued based on your selected account type"><i class="fa-duotone fa-circle-info"></i></button></h4>

                                <span>{{ __('For further details, visit our') }} <a href="#">{{ __('Help Page') }}</a>.</span>
                            </div>

                            <div class="account-types">
                                @foreach ($errors->all() as $error)
                                    <p class="text-danger m-0 p-0">{{ $error }}</p>
                                @endforeach

                                @if ($generalSettings['subscription']->has_business == 1)
                                    <div class="form-check border-primary">
                                        <input required checked class="form-check-input" type="radio" name="select_type" id="personalAccountType" value="business">
                                        <label class="form-check-label" for="personalAccountType">
                                            <span class="part-icon">
                                                <i class="fa-solid fa-business-time"></i>
                                            </span>
                                            <span class="part-txt">
                                                <span class="title">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</span>
                                                <span class="dscr">{{ __('To Supervise All Shops') }}</span>
                                            </span>
                                        </label>
                                    </div>
                                @endif

                                <div class="form-check border-primary">
                                    <input required class="form-check-input" type="radio" name="select_type" id="corporateAccountType" value="shop">
                                    <label class="form-check-label" for="corporateAccountType">
                                        <span class="part-icon">
                                            <i class="fa-solid fa-shop"></i>
                                        </span>
                                        <span class="part-txt">
                                            <span class="title">{{ __('Choose Shop') }}</span>
                                            {{-- <span class="dscr">Create corporate account to manage users</span> --}}
                                            <select name="branch_id" class="select2">
                                                <option value="">{{ __('Select Shop') }}</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        @php
                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                            $branchCode = '-' . $branch->branch_code;
                                                        @endphp
                                                        {{ $branchName . $areaName . $branchCode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="btn-box w-100 d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-success next-button px-3">{{ __('Continue') }} <i class="fa-light fa-arrow-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- <div class="single-step scrollable">
                <div class="step-content-wrap">
                    <div class="step-content">
                        <div class="step-heading">
                            <h4 class="step-title">Account Info</h4>
                            <span>For further details, visit our <a href="#">Help Page</a>.</span>
                        </div>
                        <div class="account-info-wrap">
                            <form>
                                <span class="account-info-title">Set the Team Size. <button class="btn-flush fs-14" data-bs-toggle="tooltip" data-bs-title="Provide your team size to help us setup your billing"><i class="fa-duotone fa-circle-info"></i></button></span>
                                <div class="team-size">
                                    <div class="form-check text-primary">
                                        <input class="form-check-input" type="radio" name="teamSize" id="teamSize_1-1" checked>
                                        <label class="form-check-label" for="teamSize_1-1">
                                            1-1
                                        </label>
                                    </div>
                                    <div class="form-check text-primary">
                                        <input class="form-check-input" type="radio" name="teamSize" id="teamSize_2-10">
                                        <label class="form-check-label" for="teamSize_2-10">
                                            2-10
                                        </label>
                                    </div>
                                    <div class="form-check text-primary">
                                        <input class="form-check-input" type="radio" name="teamSize" id="teamSize_11-50">
                                        <label class="form-check-label" for="teamSize_11-50">
                                            11-50
                                        </label>
                                    </div>
                                    <div class="form-check text-primary">
                                        <input class="form-check-input" type="radio" name="teamSize" id="teamSize_50">
                                        <label class="form-check-label" for="teamSize_50">
                                            50+
                                        </label>
                                    </div>
                                </div>
                                <div class="team-title">
                                    <span class="account-info-title">Team Account Name</span>
                                    <input type="text" class="form-control" placeholder="Enter Name">
                                </div>
                                <div class="account-plan account-types gap-0">
                                    <span class="account-info-title">Select Account Plan <button class="btn-flush fs-14" data-bs-toggle="tooltip" data-bs-title="Monthly billing will be based on your account plan"><i class="fa-duotone fa-circle-info"></i></button></span>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="form-check border-primary">
                                                <input class="form-check-input" type="radio" name="accountPlan" id="companyAccount" checked>
                                                <label class="form-check-label" for="companyAccount">
                                                    <span class="part-icon">
                                                        <i class="fa-duotone fa-user-tie"></i>
                                                    </span>
                                                    <span class="part-txt">
                                                        <span class="title">Company Account</span>
                                                        <span class="dscr">Create an account for Compnay</span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check border-primary">
                                                <input class="form-check-input" type="radio" name="accountPlan" id="developerAccount">
                                                <label class="form-check-label" for="developerAccount">
                                                    <span class="part-icon">
                                                        <i class="fa-duotone fa-briefcase"></i>
                                                    </span>
                                                    <span class="part-txt">
                                                        <span class="title">Developer Account</span>
                                                        <span class="dscr">Create an account for Developer</span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="btn-box w-100 d-flex justify-content-between">
                        <button class="btn btn-sm btn-secondary prev-button px-3"><i class="fa-light fa-arrow-left"></i> Previous</button>
                        <button class="btn btn-sm btn-primary next-button px-3">Continue <i class="fa-light fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>

            <div class="single-step scrollable">
                <div class="step-content-wrap">
                    <div class="step-content">
                        <div class="step-heading">
                            <h4 class="step-title">Business Details</h4>
                            <span>For further details, visit our <a href="#">Help Page</a>.</span>
                        </div>
                        <div class="business-details-wrap">
                            <form class="row">
                                <div class="col-12">
                                    <label for="businessName" class="form-label">Business Name</label>
                                    <input type="text" id="businessName" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label for="shortenedDscr" class="form-label">Shortened Description</label>
                                    <input type="text" id="shortenedDscr" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Corporation Type</label>
                                    <select class="form-control">
                                        <option value="1">S Corporation</option>
                                        <option value="2">C Corporation</option>
                                        <option value="3">Sole Proprietorship</option>
                                        <option value="4">Non-profit</option>
                                        <option value="5">Limited Liability</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="businessDscr" class="form-label">Business Description</label>
                                    <textarea id="businessDscr" rows="6" class="form-control"></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="contactEmail" class="form-label">Contact Email</label>
                                    <input type="text" id="contactEmail" class="form-control">
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="btn-box w-100 d-flex">
                        <button class="btn btn-sm btn-secondary prev-button px-3"><i class="fa-light fa-arrow-left"></i> Previous</button>
                        <button class="btn btn-sm btn-primary next-button float-end">{{ __('Continue') }} <i class="fa-light fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>

            <div class="single-step scrollable">
                <div class="step-content-wrap">
                    <div class="step-content">
                        <div class="step-heading">
                            <h4 class="step-title">Billing Details</h4>
                            <span>For further details, visit our <a href="#">Help Page</a>.</span>
                        </div>
                        <div class="billing-details-wrap">
                            <form class="row align-items-end">
                                <div class="col-12">
                                    <label for="nameOnCard" class="form-label">Name On Card</label>
                                    <input type="text" id="nameOnCard" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label for="cardNumber" class="form-label">Card Number</label>
                                    <div class="input-group">
                                        <input type="number" id="cardNumber" class="form-control">
                                        <span class="input-group-text d-flex gap-1">
                                            <img src="assets/images/visa.svg" alt="visa">
                                            <img src="assets/images/mastercard.svg" alt="mastercard">
                                            <img src="assets/images/american-express.svg" alt="american-express">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Expiration Date</label>
                                    <select class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control">
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                        <option value="2028">2028</option>
                                        <option value="2029">2029</option>
                                        <option value="2030">2030</option>
                                        <option value="2031">2031</option>
                                        <option value="2032">2032</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <div class="input-group">
                                        <input type="number" id="cvv" class="form-control">
                                        <span class="input-group-text fs-5"><i class="fa-duotone fa-credit-card"></i></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="saveCardForFurtherBilling" checked>
                                        <label class="form-check-label" for="saveCardForFurtherBilling">Save Card for further billing?</label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="btn-box d-flex flex-wrap gap-2 justify-content-between">
                        <button class="btn btn-sm btn-secondary prev-button px-sm-3"><i class="fa-light fa-arrow-left"></i> Previous</button>
                        <button class="btn btn-sm btn-primary next-button px-sm-3">Complete Submission <i class="fa-light fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>

            <div class="single-step scrollable">
                <div class="step-content-wrap">
                    <div class="step-content mb-0">
                        <div class="step-heading">
                            <h4 class="step-title">Welcome on board!</h4>
                            <span>For further details, visit our <a href="#">Help Page</a>.</span>
                        </div>
                        <div class="success-wrap">
                            <p class="bg-success bg-opacity-50 border border-success rounded-1 py-2 px-3 fs-14">The United States Constitution, based on a socially agreed standard of individual rights, is an example of post-conventional morality.</p>
                            <div class="d-flex justify-content-center">
                                <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" class="check-mark" width="200" height="200" viewBox="0 0 300 300">
                                    <path class="circle" stroke="#1C9943" stroke-width="3" fill="#fff" fill-opacity="0" stroke-miterlimit="10" d="M150,47.9c18.4,0,35.4,4.6,51,13.8s28,21.6,37.2,37.2s13.8,32.6,13.8,51s-4.6,35.4-13.8,51s-21.6,28-37.2,37.2 s-32.6,13.8-51,13.8s-35.4-4.6-51-13.8s-28-21.6-37.2-37.2s-13.8-32.6-13.8-51s4.6-35.4,13.8-51s21.6-28,37.2-37.2 S131.7,47.9,150,47.9z" />
                                    <path class="tick" fill="#1C9943" stroke="" stroke-width="3" d="M208.4,118.6c0.8-0.8,1.2-1.9,1.2-3.3c0-1.4-0.4-2.6-1.2-3.7l-3.7-3.3c-0.8-1.1-1.9-1.6-3.3-1.6 s-2.6,0.4-3.7,1.2l-67,67l-28.4-28.8c-1.1-0.8-2.3-1.2-3.7-1.2c-1.4,0-2.5,0.4-3.3,1.2l-3.7,3.3c-0.8,1.1-1.2,2.3-1.2,3.7 s0.4,2.5,1.2,3.3l35.4,35.8c1.1,1.1,2.3,1.6,3.7,1.6c1.4,0,2.5-0.5,3.3-1.6L208.4,118.6z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="btn-box d-flex justify-content-between">
                        <button class="btn btn-sm btn-secondary prev-button px-3"><i class="fa-light fa-arrow-left"></i> Previous</button>
                    </div>
                </div>
            </div> --}}
        </div>

        <!-- footer start -->
        <div class="footer">
            <p>Copyright©
                <script>
                    document.write(new Date().getFullYear())
                </script> {{ __('All Rights Reserved By') }} <span class="text-primary">Gposs</span>
            </p>
        </div>
        <!-- footer end -->
    </div>
    <!-- main content end -->

    <script src="{{ asset('backend/asset/cdn/js/jquery-3.6.0.js') }}"></script>
    {{-- <script src="assets/vendor/js/jquery.overlayScrollbars.min.js"></script> --}}
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
    {{-- <script src="assets/vendor/js/bootstrap.bundle.min.js"></script> --}}
    {{-- <script src="assets/js/main.js"></script> --}}
    {{-- <script src="assets/js/select2-init.js"></script> --}}
    <!-- for demo purpose -->
    <script>
        jQuery('.select2').select2();
        var rtlReady = $('html').attr('dir', 'ltr');
        if (rtlReady !== undefined) {
            localStorage.setItem('layoutDirection', 'ltr');
        }
    </script>
    <!-- for demo purpose -->
</body>

</html>
