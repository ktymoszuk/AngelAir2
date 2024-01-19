@extends("layout.app")

@section("content")

@include("components.alert")
<reports
    :ruolo="{{ Auth::user()->Ruolo }}"
    route-dati-strutture="{{ route('dati_strutture') }}"
    route-dati-dispositivi="{{ route('dati_dispositivi') }}"
    route-scarica-report="{{ route('scarica-report') }}"
></reports>

@endsection
