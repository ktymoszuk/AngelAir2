@extends("layout.app")

@section("content")
<link rel="stylesheet" href="{{ asset('css/videojs.css') }}">
<div class="container" v-cloak>
    <h3 class="p-2"><b>@{{ nome }}</b> - @{{ deveui }}</h3>
    <hr>
    @include("navs.nav-channel")
    <video id="axatel-video-player" class="video-js vjs-big-play-centered mb-3" style="width: 100%;"></video>
    
</div>
<script src="{{ asset('js/video.js') }}">
</script>
@endsection