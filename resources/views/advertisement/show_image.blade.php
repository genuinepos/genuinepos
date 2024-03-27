<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html lang="en">
<head>
    <title>jQuery Nivo Slider Demo</title>
    <link rel="stylesheet" href="{{asset('image_slider/nivo-slider.css')}}" type="text/css" media="screen" />
</head>
<body>





<div id="wrapper">
<div class="slider-wrapper theme-default">
<div id="slider" class="nivoSlider"> 
  @foreach($data as $item)
    <?php
    $randomNumber = rand(0, 1);
    $transitions = ['slideInLeft', 'slideInRight'];
    $randomTransition = $transitions[$randomNumber];
    ?>
    @if (is_string($item)) {{-- Assuming captions are represented as strings --}}
      <img src="" data-thumb="" alt="" title="{{ $item }}" />
    @else
      <img src="{{ asset('slider/image/'.$item->image) }}" data-thumb="{{ asset('slider/image/'.$item->image) }}" alt="" data-transition="{{ $randomTransition }}" />
    @endif
  @endforeach 
</div>
</div>
</div>















<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
<script type="text/javascript" src="{{asset('image_slider/jquery.nivo.slider.js')}}"></script> 
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
    pauseOnHover: false // Stop animation while hovering      
  });
});
</script>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36251023-1']);
    _gaq.push(['_setDomainName', 'jqueryscript.net']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
</body>
</html>
