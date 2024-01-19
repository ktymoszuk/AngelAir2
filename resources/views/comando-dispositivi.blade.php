@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<comandi-dispositivi
    route-dispositivi="{{ route('dispositivi') }}"
    route-dati-comandi="{{ route('dati_comandi_comandi_dispositivi') }}"
    route-dati-dispositivi="{{ route('dati_dispositivi_comandi_dispositivi')}}"
    route-associa-comando-dispositivo="{{ route('associa_comando_dispositivo') }}"
></comandi-dispositivi>

@endsection
