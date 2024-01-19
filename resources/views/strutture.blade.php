@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<strutture
    :utente="{{ json_encode(Auth::user()) }}"
    route-modifica-struttura="{{ route('update_struttura') }}"
    route-elimina-struttura="{{ route('delete_struttura') }}"
    route-sinottici-struttura="{{ route('sinottici_struttura') }}"
    route-dati-automazioni="{{ route('dati_automazioni') }}"
    route-dati-strutture="{{ route('dati_strutture') }}"
    route-dati-filtrati-strutture="{{ route('dati_filtrati_strutture') }}"
    asset="{{ asset('') }}"
>
</strutture>
@endsection