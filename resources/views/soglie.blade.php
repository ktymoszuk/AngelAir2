@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<soglie
    route-nuova-soglia="{{ route('nuova_soglia') }}"
    route-dati-soglie="{{ route('dati_soglie') }}"
    route-dati-automazioni="{{ route('dati_automazioni') }}"
    route-dati-categorie-dispositivi="{{ route('dati_categorie_dispositivi') }}"
    route-dati-categorie-soglie="{{ route('dati_categorie_soglie') }}"
    route-modifica-soglia="{{ route('modifica_soglia') }}"
    route-elimina-soglia="{{ route('elimina_soglia') }}"
    route-dati-colonne-associate="{{ route('dati_colonne_associate') }}"
    route-soglia-dispositivi="{{ route('soglia_dispositivi') }}"
></soglie>
@endsection