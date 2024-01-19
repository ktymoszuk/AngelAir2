@extends("layout.app")

@section("content")
<div class="container-sm border border-warning mx-auto mt-5 mb-5" v-cloak>
    <!-- Selezione Struttura -->
    <div class="row mt-3">
        <div class="col-sm-3">
            <label class="form-control lbl-info">Seleziona Struttura</label>
        </div>
        <div class="col-sm">
            <select id="selectStruttura" class="form-select w-50" v-model="struttura" @change="showDati">
                <option disabled value="">Seleziona Struttura</option>
                <option value="0">Tutte le Strutture</option>
                <option v-for="option in optionList" :value="option.id" :label="option.Nome"></option>
            </select>
        </div>
    </div>
    <div class="row mt-3 mb-3">
        <div class="col-sm-3"><label class="form-control lbl-info">Ricerca Telecamera</label></div>
        <div class="col-sm-5">
            <input type="text" class="form-control mb-3" id="filtroSoglie" placeholder="Ricerca telecamera..."
                v-model="testo" @change="showDati" />
        </div>
        <div class="col-sm">
            <button class="btn btn-primary" type="button" @change="getFiltri">FILTRA</button>
        </div>
    </div>
</div>

<hr>

<div class="container" v-cloak>
    <p class="text-center text-danger" v-show="messaggio != ''">@{{ messaggio }}</p>
    <ul class="list-group" v-for="(item, i) in datiList">
        <li class="list-group-item d-flex justify-content-between align-items-start" v-for="item2 in item.telecamere">
            <div class="ms-2 me-auto">
                <div class="fw-bold">@{{ item2.Nome }}</div>
                <p class="my-0">@{{ item.Nome }}</p>
                <small><i>@{{ item2.Indirizzo }}</i></small>
            </div>
            @if(Auth::user()->Ruolo == 0 || Auth::user()->Ruolo == 1)
                <button class="btn btn-warning my-auto" v-if="item2.Stato == 0 && !isAzione[i]" @click="invioComando(item.id, 1); isAzione[i] = true;">ACCENDI</button>
            @endif
            <div class="my-auto" v-else-if="item2.Stato == 2 && !isAzione[i]">
                @if(Auth::user()->Ruolo == 0 || Auth::user()->Ruolo == 1)
                    <button class="btn btn-danger m-2" @click="invioComando(item.id, 0); isAzione[i] = true;">SPEGNI</button>
                @endif
                <a class="btn btn-primary" :href="'/geoangel/video/?id='+item2.id" target="_blank">VISUALIZZA</a>
            </div>
            @if(Auth::user()->Ruolo == 0 || Auth::user()->Ruolo == 1)
            <button class="btn btn-primary my-auto" type="button" disabled v-else>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Azione in corso...
            </button>
            @endif
        </li>
    </ul>
</div>

<script src="{{ asset('js/telecamere.js') }}"></script>
@endsection