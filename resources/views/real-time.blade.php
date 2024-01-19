@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<real-time
    route-allarmi-realtime="{{ route('allarmi_realtime') }}"
    route-grafici="{{ route('grafici') }}"
    route-statistiche="{{ route('statistiche') }}"
    route-dati-dispositivi="{{ route('dati_dispositivi') }}"
    route-dati-tipodisp="{{ route('dati_tipodisp') }}"
    asset="{{ asset('') }}"
></real-time>

@endsection
