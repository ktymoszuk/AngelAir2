@extends("layout.app")

@section("content")
@include("libraries.maps")
<div class="container text-center list-group-item-info">Clicca il <b>Tasto Destro</b> del mouse per visualizzare le coordinate del punto selezionato (Latitudine, Longitudine): <b><span>@{{ coordinate }}</span></b></div>
<div id="map" class="mx-auto" style="width: 90vw; height: 90vh;"></div>
<script src="{{ asset('js/sinottico.js') }}"></script>
@endsection