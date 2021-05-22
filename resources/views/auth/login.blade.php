
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpeedDigitPosPro</title>
    <link rel="stylesheet" href="{{asset('public')}}/backend/asset/css/fontawesome/css/all.css">
    <link rel="stylesheet" href="{{asset('public')}}/backend//asset/css/bootstrap.min.css">
    <link href="{{asset('public')}}/backend/css/typography.css" rel="stylesheet" type="text/css">
    <link href="{{asset('public')}}/backend/css/body.css" rel="stylesheet" type="text/css">
    <link href="{{asset('public')}}/backend/css/form.css" rel="stylesheet" type="text/css">
    <link href="{{asset('public')}}/backend/css/wizard.css" rel="stylesheet" type="text/css">
    <link href="{{asset('public')}}/backend/css/gradient.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{asset('public')}}/backend/asset/css/comon.css">
    <link rel="stylesheet" href="{{asset('public')}}/backend/asset/css/layout.css">
    <link rel="stylesheet" href="{{asset('public')}}/backend/asset/css/style.css">
</head>

<body>
    <div class="form-wraper">
        <div class="container">
            <div class="form-content">
                <div class="col-lg-3 col-md-4 col-12">
                    <div class="form-head">
                        <div class="head">
                            <img src="{{asset('public')}}/backend/asset/img/logo.png" alt="" class="logo">
                            <span class="head-text"><b>The Complete Software Solution.</b> </span>
                        </div>
                    </div>

                    <div class="main-form">
                        <div class="form-title">
                            <p>Admin Login</p>
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="left-inner-addon input-container">
                                <i class="fa fa-user"></i>
                                <input type="text" name="username" class="form-control form-st" placeholder="User Name" />
                                <span class="error error_username">{{ $errors->first('username') }}</span>
                            </div>
                            
                            <div class="left-inner-addon input-container">
                                <i class="fa fa-key"></i>
                                <input type="Password" name="password" class="form-control form-st rounded-bottom" placeholder="Password" />
                                <span class="error error_password">{{ $errors->first('password') }}</span>
                            </div>
                            @if (Session::has('errorMsg'))
									<span class="bg-danger text-white px-1">{{ session('errorMsg') }}</span>
							@endif
                            <button type="submit" class="submit-button">Login</button>
                        </form>
                        <div class="login_opt_link">
                            <a href="" class="forget-pw">Forgot Password </a>
                            <div class="form-group cx-box">
                                <input type="checkbox" id="remembar">
                                <label for="remembar">Remembar me</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>