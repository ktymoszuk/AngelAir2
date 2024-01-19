@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<grafici
    route-statistiche="{{ route('statistiche') }}"
    route-real-time="{{ route('real_time') }}"
    route-dati-strutture="{{ route('dati_strutture') }}"
    route-dati-dispositivi="{{ route('dati_dispositivi') }}"
    route-dati-tipodisp="{{ route('dati_tipodisp') }}"
></grafici>

@endsection
