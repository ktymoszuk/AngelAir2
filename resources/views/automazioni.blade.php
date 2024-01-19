@extends("layout.app")

@section("content")
<!-- FILTRO VISUALIZZAZIONE DATI -->
<div class="container-sm border border-primary mx-auto mt-5" v-cloak>
    <div class="row mt-3 mb-3">
        <div class="col-sm-3">
            <label class="form-control lbl-info">Filtro Automazione</label>
        </div>
        <div class="col-sm">
            <input type="text" class="form-control mx-auto" placeholder="Ricerca automazione..." v-model="testo"
                @change="showDati(paginazione.contatore[2])" />
        </div>
        <div class="col-sm">
            <button type="submit" class="btn btn-primary" @change="showDati(paginazione.contatore[2])">FILTRO</button>
        </div>
    </div>
</div>
<hr>

<div class="container-sm mt-5 mb-5" v-cloak>
    <ul class="list-group" id="list-group">
        <li class="list-group-item" :class="{'allarme': isAllarmeList[i]}" v-for="(item, i) in datiList">
            <div class="row">
                <div class="col-sm my-auto">
                    <h5>@{{ item.Nome }}</h5>
                </div>
                <div class="col-sm my-auto">
                    <b>Latitudine</b>: @{{ item.Latitudine }}<br>
                    <b>Longitudine</b>: @{{ item.Longitudine }}<br>
                </div>
                <div class="col-sm my-auto">
                    <b>KeepAlive</b>: <i>@{{ item.DataUpdate }}</i>
                </div>
                <div class="col-sm my-auto">
                    <div class="d-flex justify-content-end">
                        @if(Auth::user()->Ruolo == 0)
                        <button class="btn btn-primary m-1" id="btnModifica" data-bs-toggle="modal"
                            data-bs-target="#modalModifica" @click="updateDati(item.id)">MODIFICA</button>
                        @endif
                        @if(Auth::user()->Ruolo == 0 || Auth::user()->Ruolo == 1)
                        <button class="btn btn-warning m-1" id="btnRiavvia" data-bs-toggle="modal"
                            data-bs-target="#modalRiavvio" @click="riavvioAutomazione(item.id)"
                            v-if="item.Stato == 1">RIAVVIA</button>
                        @endif
                        <button class="btn btn-primary m-1" type="button" v-else disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Riavvio in corso...
                        </button>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</div>

@include("navs.nav-paginazione")

@include("modals.upd-automazione")
@include("modals.riavvio-automazione")

<script src="{{ asset('js/automazioni.js') }}"></script>
@endsection