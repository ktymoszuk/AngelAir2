@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<soglie-dispositivi
    route-associa-soglia-dispositivo="{{ route('associa_soglia_dispositivo') }}"
    route-dati-soglie-dispositivi="{{ route('dati_soglie_dispositivi') }}"
    route-dati-soglie="{{ route('dati_soglie_soglie_dispositivi') }}"
    route-dati-dispositivi="{{ route('dati_dispositivi_soglie_dispositivi')}}"
    route-dispositivi="{{ route('dispositivi') }}"
></soglie-dispositivi>

@endsection