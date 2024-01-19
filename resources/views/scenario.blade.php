@extends("layout.app")

@section("content")
<div class="container mx-auto border border-primary mt-5 mb-5" v-cloak>
    <div class="row">
        <h3 class="card-title text-center mt-5 mb-4">@{{ datiList[0].Nome }}</h3>
    </div>
    <div class="row w-75 mx-auto">
        <ul class="list-group" v-for="(item, i) in datiList">
            <li class="list-group-item bg-dark" v-for="item2 in item[i].Comandi">
                <b class="text-white">Comandi inviati</b>
                <p class="text-center" :class="{'text-danger': item2.Codice == 'B1D401', 'text-white': item2.Codice == 'B1D400'}">
                    @{{ item2.Nome }}</p>
                <hr>
                <b class="text-white">Dispositivi in ricezione</b>
                <p v-for="item3 in item[i].Dispositivi"
                    class="text-center"
                    :class="{'text-danger': item2.Codice == 'B1D401', 'text-white': item2.Codice == 'B1D400'}">
                    @{{ item3.Nome }}
                </p>
            </li>
        </ul>
    </div>
    <div class="row">
        <div id="buttons" class='mt-3 mb-5 text-center' v-if="isAttivo == 0">
            <a href='{{ route("dashboard") }}' type='button' class='btn btn-primary'
                style='margin-right: 2vw;'>CHIUDI</a>
            <button id="buttonConferma" type='button' class='btn btn-danger' data-bs-toggle="modal"
                data-bs-target="#modalScenario">CONFERMA</button>
        </div>
        <b class="text-center m-3" v-else-if="isAttivo == 1">Esecuzione Scenario in corso...</b>
        <b class="text-center m-3" v-else-if="isAttivo == 2">Esecuzione completata <a type='button'
                href='{{ route("dashboard") }}' class='btn btn-primary' style='margin-right: 2vw;'>CHIUDI</a></b>
    </div>
    <p class="text-center" :class="{'text-success': stato, 'text-danger': !stato}" v-show="messaggio != ''">@{{ this.messaggio }}</p>
</div>

@include("modals.scenario")

<script src="{{ asset('js/scenario.js') }}"></script>
@endsection