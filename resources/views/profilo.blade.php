@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<profilo
    route-dati-profilo="{{ route('dati_profilo') }}"
    route-modifica-profilo="{{ route('modifica_profilo') }}"
    route-modifica-password="{{ route('modifica_password') }}"
    asset="{{ asset('') }}"
></profilo>

@endsection
