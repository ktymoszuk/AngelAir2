@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<statistiche
    route-grafici="{{ route('grafici') }}"
    route-real-time="{{ route('real_time') }}"
></statistiche>

@endsection
