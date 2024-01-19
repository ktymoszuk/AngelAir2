@extends("layout.app")

@section("content")
<div class="container border border-primary mx-auto mt-5" v-cloak>
    <!-- Selezione Struttura -->
    <div class="row m-3">
        <div class="col-sm-3">
            <label class="form-control lbl-info">Seleziona Struttura</label>
        </div>
        <div class="col-sm-5">
            <select id="selectStruttura" class="form-select" v-model="struttura"
                @change="showDati(categoriaDisp, struttura)">
                <option disabled value="">Seleziona Struttura</option>
                <option value="0">Tutte le Strutture</option>
                <option v-for="option in optionList" :value="option.id" :label="option.Nome"></option>
            </select>
        </div>
    </div>
    <!-- Selezione Categoria Dispositivo -->
    <div class="row m-3">
        <div class="col-sm-3">
            <label class="form-control lbl-info" style="font-weight: bold;">Seleziona Categoria
                Dispositivo</label>
        </div>
        <div class="col-sm-5">
            <select id="selectCategoriaDisp" class="form-select" :disabled="struttura == ''" v-model="categoriaDisp"
                @change="showDati(categoriaDisp, struttura)">
                <option disabled value="">Seleziona Categoria Dispositivo</option>
                <option value="0">Tutte le Categorie</option>
                <option v-for="option in optionList" :value="option.id" :label="option.Nome"></option>
            </select>
        </div>
    </div>
</div>

<div class="container border border-primary mx-auto mt-3 mb-3" v-cloak>
    <div class="row row-cols-1 row-cols-md-3 g-4 m-3">
        <div v-if="datiList.length == 0">
            <h2 class="text-center">Nessun Dispositivo trovato</h2>
        </div>
        <div class="col" v-for="item in datiList" v-else>
            <div class="card border border-primary card-situazione">
                <div class="card-header" :class="{ 'allarme': item.isAllarme }">
                    <div class="row">
                        <div class="col-sm-1 my-auto">
                            <div class="battery">
                                <div class="battery-level" :style="'height: ' + parseInt(100 * (item.Dati[1] - 11.5) / 1.7) + '%'">
                                </div>
                            </div>
                        </div>
                        <div class="col my-auto">
                            <h5>@{{ item.Nome }}</h5>
                        </div>
                        <div class="col my-auto"><i>@{{ item.Deveui }}</i></div>
                    </div>
                </div>
                <div :id="'card-dati-'+item.Deveui" class="card-text card-dati text-center h-100">
                    <div class="row" v-for="(item2, index) in item.Dati">
                        <div class="col-sm">
                            <b v-show="index > 0"> @{{ item.Valori[index] }} </b>
                        </div>
                        <div class="col-sm" v-show="index > 0">
                            @{{ item2 }}
                        </div>
                    </div>
                </div>
                <div class="card-footer"
                    :class="{ 'warning': statiList[i] == 1, 'ko': statiList[i] == 2 }">
                    <small class="text-muted">@{{ item.Dati[0] }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/situazione.js') }}"></script>
@endsection