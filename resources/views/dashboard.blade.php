@extends("layout.app")

@section("content")

<div style="position:fixed; top:66px; left:0px; width:100%">
    @include("components.alert")
</div>

<dashboard
    route-dati-mappa="{{ route('dati_mappa') }}"
></dashboard>

@endsection
