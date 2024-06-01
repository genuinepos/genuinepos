@if ($rtl)
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic&display=swap" rel="stylesheet">
@else
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
@endif

<link rel="stylesheet" href="{{ asset('assets/fontawesome6/css/all.min.css') }}">
<link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/css/cart.css') }}">
<link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

<style>
    .error {
        font-size: 11px;
        color: red;
    }

    .tab-section .tab-nav .single-nav {
        height: 35px;
        font-size: 15px;
    }

    .def-btn {
        height: 40px;
        line-height: 40px;
        padding: 0 30px;
        font-size: 13px;
        cursor: pointer;
    }

    .tab-section .tab-contents .tab-next-btn {
        font-size: 13px;
        text-align: center;
    }

    .tab-section .tab-contents .billing-details .form-row {
        gap: 10px 20px;
    }

    .tab-section .tab-contents .billing-details .form-row .form-control {
        font-size: 14px;
        height: 35px;
        line-height: 33px;
        padding: 0 15px;
    }

    .domain-field span.txt {
        font-size: 17px;
    }

    .tab-section .tab-contents .billing-details .title {
        font-size: 16px;
    }

    .form-row .col-md-4 {
        width: 32%;
    }

    .col-md-8 {
        flex: 0 0 auto;
        width: 65.666667%;
    }

    label {
        font-size: 13px !important;
    }

    .tab-section .tab-nav {
        margin-bottom: 28px;
    }

    span.selection {
        width: 100%;
    }

    .select2-container .select2-selection--single {
        height: 35px;
        background: rgba(241, 241, 241, 0.5);
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 33px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px;
    }

    .dropify-wrapper {
        height: 120px !important;
    }

    .tab-section .tab-contents .billing-details .form-row {
        margin-bottom: 12px;
    }

    .form-control {
        -webkit-appearance: listbox;
    }

    /* Startup form header style start */
    .startup-form-header .navigation {
        display: flex;
        align-items: center;
        /* position: fixed; */
        width: 100%;
        z-index: 99;
        padding: 0px;
        left: 0px;
    }

    .startup-form-header .panel__nav {
        background: linear-gradient(#444444, #0a0b0c);
        border-top: 1px solid #29b0fd;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .top-menu {
        width: 100%;
    }

    .logo__sec {
        width: 142px;
        height: 45px;
        display: flex;
        justify-content: center;
        align-items: center;
        float: left;
    }

    img.logo__img {
        width: auto !important;
        height: auto !important;
        max-height: 35px;
        max-width: 110px;
        vertical-align: middle;
    }

    .notify-menu {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .company-name {
        border-left: 1px solid rgba(255, 255, 255, 0.2);
        padding-left: 15px;
    }

    .company-name p {
        font-size: 13px;
        line-height: 100%;
        font-weight: 500;
        color: #fff;
        margin-bottom: 4px;
    }

    .company-name span {
        display: block;
        font-size: 11px;
        line-height: 100%;
        color: #d1d1d1;
    }

    .company-name span strong {
        color: #e9e9e9;
    }

    .startup-form-header ul.head__cn {
        border-left: 1px solid #000000;
        display: flex;
    }

    .startup-form-header .top-icon {
        background: #0a0b0c;
    }

    .startup-form-header ul.head__cn li .nav-btn {
        background: linear-gradient(#424242, #000000) !important;
        border-left: 1px solid #424242;
        border-right: 1px solid #000000;

        display: flex !important;
        align-items: center;
        text-align: left;
        height: 45px !important;
        max-height: none;
        color: #fff;
        border: 0;
        border-radius: 0;
        padding: 0 10px;
        position: relative;
    }

    .startup-form-header ul.head__cn li a {
        color: #ffffff;
    }

    .startup-form-header ul.head__cn li a span {
        font-size: 11px;
        font-weight: 300;
    }
</style>
