@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<dispositivi
    route-dispositivo="{{ route('dispositivo') }}"
    route-soglia-dispositivi="{{ route('soglia_dispositivi') }}"
    route-comando-dispositivi="{{ route('comando_dispositivi') }}"
    route-download-excel="{{ route('download_excel_dispositivi') }}"
    route-nuovo-dispositivo="{{ route('nuovo_dispositivo') }}"
    route-nuovi-dispositivi="{{ route('nuovi_dispositivi') }}"
    route-dati-dispositivi="{{ route('dati_paginati_dispositivi') }}"
    route-dati-comandi="{{ route('dati_comandi') }}"
    route-dati-strutture="{{ route('dati_strutture') }}"
    route-dati-tipodisp="{{ route('dati_tipodisp') }}"
    route-elimina-dispositivo="{{ route('elimina_dispositivo') }}"
    route-modifica-dispositivo="{{ route('modifica_dispositivo') }}"
    route-base-istruzioni="{{ route('base_istruzioni') }}"
    route-elimina-soglia-dispositivo="{{ route('elimina_soglia_dispositivo') }}"
    route-elimina-comando-dispositivo="{{ route('elimina_comando_dispositivo') }}"
    asset="{{ asset('') }}"
></dispositivi>

@endsection