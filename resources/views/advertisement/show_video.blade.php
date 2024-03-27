<!DOCTYPE html>
<html>
<body>

<style>
    *{
        margin:0;
        padding:0;
        /* overflow:hidden; */
    }
</style>

<video style="width: 100%;height:95%;" controls autoplay>
  <source src="{{asset('slider/video/'. $data[0]->video)}}" type="video/mp4">
  Your browser does not support the video tag.
</video>

</body>
</html>

