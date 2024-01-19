@extends("layout.app")

@section("content")
<!-- SELEZIONE FILTRO NOTIFICHE -->
<div class="container mx-auto border border-primary mt-5 mb-5" v-cloak>
    <div class="row mt-3">
        <div class="col-sm-3"><label class="form-control lbl-info">Seleziona Automazione</label></div>
        <div class="col-sm-5">
            <select id="selectAutomazione" class="form-select" v-model="automazione"
                @change="showDati(paginazione.contatore[2])">
                <option disabled value="">Seleziona Automazione</option>
                <option value="0">Tutte le Automazioni</option>
                <option v-for="option in optionList" :value="option.id" :label="option.Nome"></option>
            </select>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-3"><label class="form-control lbl-info">Seleziona Categoria Dispositivo</label></div>
        <div class="col-sm-5">
            <select id="selectCategoriaDisp" class="form-select" v-model="categoriaDisp"
                @change="showDati(paginazione.contatore[2])">
                <option disabled value="">Seleziona Categoria Dispositivo</option>
                <option value="0">Tutte le Categorie Dispositivi</option>
                <option v-for="option in optionList" :value="option.id" :label="option.Nome"></option>
            </select>
        </div>
    </div>
    <hr>
    <div class="row mb-3">
        <div class="col-sm-3"><label class="form-control lbl-info">Ricerca Notifica</label></div>
        <div class="col-sm-5">
            <input type="text" class="form-control mb-3" id="filtroSoglie" placeholder="Ricerca notifica..."
                v-model="testo" @change="showDati(paginazione.contatore[2])" />
        </div>
        <div class="col-sm">
            <button class="btn btn-primary" type="button" @change="showDati(paginazione.contatore[2])">FILTRA</button>
        </div>
    </div>
</div>

<hr>

<ul class="list-group">
    <li :class="'list-group-item d-flex justify-content-between align-items-start list-group-item-'+statoInvioList[i]"
        v-for="(item, i) in messaggiInvioList">
        @{{ item }}
        <button type="button" class="btn-close shadow-none"
            @click="messaggiInvioList.pop(i); statoInvioList.pop(i);"></button>
    </li>
</ul>

<div class="container mt-3 mb-3">
    <ul class="list-group" id="list-group">
        <li class="list-group-item" v-for="(item, i) in datiList">
            <div class="row">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" :id="'inpIsNotifica'+item.id" :checked="item.notifica.isNotifica == 1"
                        @change="updateDati(item.notifica.id, item.notifica.isNotifica)">
                    <label class="form-check-label" :for="'inpIsNotifica'+item.id">@{{ item.Nome }}</label>
                </div>
            </div>
        </li>
    </ul>
</div>

@include("navs.nav-paginazione")

<script src="{{ asset('js/notifiche.js') }}"></script>
@endsection