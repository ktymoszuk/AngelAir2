@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<utenti
    route-dati-utenti="{{ route('dati_utenti') }}"
    route-modifica-utente="{{ route('modifica_utente') }}"
    route-elimina-utente="{{ route('elimina_utente') }}"
    asset="{{ asset('') }}"
></utenti>

@endsection
