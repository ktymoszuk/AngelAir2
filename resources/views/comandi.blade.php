@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<comandi
    route-download-excel="{{ route('download_excel_comandi') }}"
    route-nuovo-comando="{{ route('nuovo_comando') }}"
    route-nuovi-comandi="{{ route('nuovi_comandi') }}"
    route-dati-automazioni="{{ route('dati_automazioni') }}"
    route-dati-categorie-dispositivi="{{ route('dati_categorie_dispositivi') }}"
    route-dati-comandi="{{ route('dati_comandi') }}"
    route-dati-dispositivi="{{ route('dati_dispositivi') }}"
    route-base-istruzioni="{{ route('base_istruzioni') }}"
    route-modifica-comando="{{ route('modifica_comando') }}"
    route-elimina-comando="{{ route('elimina_comando') }}"
    route-comando-dispositivi="{{ route('comando_dispositivi') }}"
    asset="{{ asset('') }}"
></comandi>

@endsection
