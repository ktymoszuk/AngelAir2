<!-- MAPPA PRINCIPALE -->
@include("libraries.maps")
<div id="mappa">
    <div class="container mx-auto" v-show="!isCaricamento">
        <div class="row">
            <div class="col">
                <h3 class="card-title text-center mt-5"><b>MAPPA PRINCIPALE</b></h3>
                <p class="text-center">Visualizzazione strutture nel sistema</p>
            </div>
        </div>
    </div>
    <div id="map" class="mx-auto" style="width: 90vw; height: 100vh;"></div>
</div>
<script src="{{ asset('js/mappa-strutture.js') }}"></script>