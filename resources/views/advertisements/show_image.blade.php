<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html lang="en">

<head>
    <title>{{ __('Ads - ') }}{{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/nivo_slider/nivo-slider.css') }}" type="text/css" media="screen" />
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            /* Prevent scrollbars */
        }

        .slider-wrapper,
        .nivoSlider,
        .nivoSlider img {
            width: 100%;
            height: 100vh;
            position: relative;
        }

        .nivoSlider img {
            object-fit: cover;
        }

        .nivo-caption {
            font-size: 16px;
            color: #fff;
        }

        .nivo-caption strong {
            font-size: 24px;
            /* Larger font for the title */
            display: block;
            /* margin-bottom: 5px; */
        }

        .nivo-caption span {
            font-size: 14px;
            /* Smaller font for the caption */
        }

        .nivo-caption {
            left: 0px;
            top: 0%;
            /* bottom: 9%; */
            color: #fff;
            width: 100%;
            z-index: 8;
            padding: 8px 10px;
            overflow: hidden;
            display: none;
            height: 100%;
            /* font-size: 36px; */
            background: none !important;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <div class="slider-wrapper theme-default">
            <div id="slider" class="nivoSlider">
                @foreach ($advertisement->attachments as $attachment)
                    <?php
                    $randomNumber = rand(0, 1);
                    $transitions = ['slideInLeft', 'slideInRight'];
                    $randomTransition = $transitions[$randomNumber];
                    ?>

                    <img src="{{ file_link('advertisementAttachment', $attachment->image) }}" title="{{ $attachment->content_title }}" data-caption="{{ $attachment->caption }}" data-thumb="{{ file_link('advertisementAttachment', $attachment->image) }}" alt="" data-transition="{{ $randomTransition }}" />
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/custom/nivo_slider/jquery.nivo.slider.js') }}"></script>
<script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider({
            effect: 'sliceDown', // Specify sets like: 'fold,fade,sliceDown'
            animSpeed: 500, // Slide transition speed
            pauseTime: 4000, // How long each slide will show
            startSlide: 0, // Set starting Slide (0 index)
            directionNav: false, // Next & Prev navigation
            controlNav: false, // 1,2,3... navigation
            controlNavThumbs: false, // Use thumbnails for Control Nav
            pauseOnHover: false, // Stop animation while hovering
            afterLoad: function() {
                // Initialize the caption for the first slide
                var firstSlide = $('#slider img').eq(0);
                var title = firstSlide.attr('title');
                var caption = firstSlide.data('caption');
                if (caption) {

                    $('.nivo-caption').html('<strong>' + title + '</strong><span>' + caption + '</span>');
                }

            },
            afterChange: function() {
                // Update caption after each slide change
                var currentSlide = $('#slider').data('nivo:vars').currentSlide;
                var currentImage = $('#slider img').eq(currentSlide);
                var title = currentImage.attr('title');
                var caption = currentImage.data('caption');
                if (caption) {

                    $('.nivo-caption').html('<strong>' + title + '</strong><span>' + caption + '</span>');
                }
            }
        });
    });
</script>
{{-- <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36251023-1']);
    _gaq.push(['_setDomainName', 'jqueryscript.net']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
</script> --}}
</body>

</html>
