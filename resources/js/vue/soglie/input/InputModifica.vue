<template>
    <div class="position-relative">

        <!-- IMPOSTAZIONI GENERALI -->
        <div v-if="caricamento" class="position-absolute" style="top:0;left:50%; margin-top: -50px; transform: translateX(-50%);">
            <div class="spinner-grow spinner-grow-sm mx-1" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow spinner-grow-sm mx-1" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow spinner-grow-sm mx-1" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        

        <!-- Nome -->
        <div class="col-12">
            <div class="input-group">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Nome<asterisco/>
                </span>
                <input type="text" name="Nome" :value="soglia.Nome" class="form-control" placeholder="della soglia">
            </div>
        </div>
        <!-- Automazione -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Automazione<asterisco/>
                </span>
                <select :id="'selectAutomazioneSoglia' + chiave" name="codAutomazione" :value="soglia.codAutomazione" class="form-select" @change="cambia" required>
                    <option selected disabled value="">Seleziona</option>
                    <option v-for="option in automazioni" :value="option.id" :label="option.Nome"></option>
                </select>
            </div>
        </div>
        <!-- Categoria dispositivo -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Categoria dispositivo<asterisco/>
                </span>
                <select :id="'selectTipoDispositivoSoglia' + chiave" name="codTipoDisp" :value="soglia.codTipoDisp" class="form-select" @change="cambia">
                    <option selected disabled value="">Seleziona</option>
                    <template v-if="categorieDispositivi">
                        <option v-for="option in categorieDispositivi" :value="option.IdTipo" :label="option.Nome" @mousedown="codTipoDisp = option.id"></option>
                    </template>
                </select>
            </div>
        </div>
        <!-- Categoria soglia -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Categoria soglia<asterisco/>
                </span>
                <select name="codCategoriaSoglia" :value="soglia.codCategoriaSoglia" class="form-select" id="selectUpdCategoriaSoglia">
                    <option selected disabled value="">Seleziona</option>
                    <option disabled value="">Seleziona Categoria Soglia</option>
                    <option v-for="option in categorieSoglie" :value="option.id" :label="option.Nome"></option>
                </select>
            </div>
        </div>
        <!-- Colonna associata -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Colonna associata<asterisco/>
                </span>
                <select name="ColonnaAssociata" class="form-select" id="selectUpdColonnaAssociata" :value="soglia.ColonnaAssociata" @change="filtroSelect($event)">
                    <option selected disabled value="">Seleziona</option>
                    <option v-for="option in optionsColonnaAssociata" :value="option.Colonne"
                        :valore="option.Valori" :label="option.Valori"> 
                    </option>
                </select>
            </div>
        </div>
        <!-- Valore associato -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Valore associato<asterisco/>
                </span>
                <input type="text" name="ValoreAssociato" :value="soglia.ValoreAssociato" class="form-control">
            </div>
        </div>
        <!-- Tipologia soglia -->
        <div class="col-12">
            <div class="input-group mt-3">
                <div class="btn-group w-100" role="group" aria-label="Basic checkbox toggle button group">
                    <span class="btn border border-end-0 bg-light text-start" style="cursor: auto">Tipo soglia</span>
    
                    <input type="radio" name="TipoSoglia"
                    class="btn-check rUpdTipoSoglia" :id="'rUpdSingola' + chiave" value="Singola" :checked="soglia.TipoSoglia == 'Singola'" @change="updateTipoSoglia('Singola')">
                    <label class="btn btn-outline-primary" :for="'rUpdSingola' + chiave">Singola</label>
    
                    <input type="radio" name="TipoSoglia"
                    class="btn-check rUpdTipoSoglia" :id="'rUpdRange' + chiave" value="Range" :checked="soglia.TipoSoglia == 'Range'" @change="updateTipoSoglia('Range')">
                    <label class="btn btn-outline-primary" :for="'rUpdRange' + chiave">Range</label>
                </div>
            </div>
        </div>
        <!-- Tipologia dato -->
        <div class="col-12">
            <div class="input-group mt-3">
                <div class="btn-group w-100" role="group" aria-label="Basic checkbox toggle button group">
                    <span class="btn border border-end-0 bg-light text-start" style="cursor: auto">Tipo dato</span>
    
                    <input type="radio" name="TipoDatoSoglia"
                        class="btn-check rUpdTipoDatoSoglia" :id="'rUpdNumerica' + chiave" value="Numerica" :checked="soglia.TipoDatoSoglia == 'Numerica'"
                        @change="updateTipoDatoSoglia('Numerica')">
                    <label class="btn btn-outline-primary" :for="'rUpdNumerica' + chiave">Numerico</label>
    
                    <input name="TipoDatoSoglia"
                        class="btn-check rUpdTipoDatoSoglia" type="radio" :id="'rUpdTestuale' + chiave" :checked="soglia.TipoDatoSoglia == 'Testuale'"
                        value="Testuale" @change="updateTipoDatoSoglia('Testuale')">
                    <label class="btn btn-outline-primary" :for="'rUpdTestuale' + chiave">Testuale</label>
                </div>
            </div>
        </div>
        <!-- Descrizione -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">Descrizione</span>
                <textarea name="Descrizione" class="form-control" rows="3" :value="soglia.Descrizione"></textarea>
            </div>
        </div>   
        <!-- FINE IMPOSTAZIONI GENERALI -->

        <!-- IMPOSTAZIONI VALORE MINIMO -->
        <div class="row">

        <h2 class="fw-light mt-5">Impostazioni valore minimo</h2>

        <!-- Operatore logico -->
        <div class="col-12 col-lg-6">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Operatore logico<asterisco/>
                </span>
                <select name="OperatoreMinimo" class="form-select" :value="soglia.OperatoreMinimo"
                    id="selectUpdOperatoreMinimo">
                    <option :value="'=='">==</option>
                    <option :value="'!='">!=</option>
                    <option :value="'<'">&lt;</option>
                    <option :value="'>'">></option>
                    <option :value="'<='">
                        &lt;= </option>
                    <option :value="'>='">>=</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Valore<asterisco/>
                </span>
                <input :type="tipoDatoSoglia == 'Testuale' ? 'text' : 'number'" step="0.0000001" name="ValoreMinimo" :value="soglia.ValoreMinimo"
                    class="form-control" id="inpUpdValMin"
                    placeholder="minimo"
                >
            </div>
        </div>
        <!-- Alias -->
        <div class="col-12 col-lg-6">
            <div class="input-group mt-3 h-75">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Alias<asterisco/>
                </span>
                <input type="text" name="AliasMinimo" :value="soglia.AliasMinimo"
                class="form-control" id="inpUpdAliasMin" placeholder="valore minimo">
            </div>
        </div>
        <!-- Colore -->
        <div class="col-12 col-lg-6">
            <div class="input-group mt-3 h-75 w-100" style="overflow:hidden">
                <span class="btn border border-end-0 bg-light text-end pe-4" style="cursor: auto">Colore</span>
                <div class="form-control border-0 rounded-end p-0 bg-warning" style="overflow: hidden;">
                    <input type="color" name="ColoreMinimo" :value="soglia.ColoreMinimo"
                    class="h-100 form-control p-0 border border-start-0" id="inpUpdColoreMin">
                </div>
            </div>
        </div>
        </div>
        <!-- FINE IMPOSTAZIONI VALORE MINIMO -->

        <!-- IMPOSTAZIONI VALORE MASSIMO -->
        <div class="row">
            <h2 v-if="tipoSoglia == 'Range'" class="fw-light mt-5">Impostazioni valore massimo</h2>

            <div v-if="tipoSoglia == 'Range'" class="row">
                <!-- Operatore logico -->
                <div class="col-12 col-lg-6">
                    <div class="input-group mt-3">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">
                            Operatore logico<asterisco/>
                        </span>
                        <select name="OperatoreMassimo" :value="soglia.OperatoreMassimo"
                            class="form-select" id="selectUpdOperatoreMassimo">
                            <option value="">Seleziona</option>
                            <option :value="'=='">==</option>
                            <option :value="'!='">!=</option>
                            <option :value="'<'">
                                &lt; </option>
                            <option :value="'>'">></option>
                            <option :value="'<='">
                                &lt;= </option>
                            <option :value="'>='">>=</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="input-group mt-3">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">
                            Valore<asterisco/>
                        </span>
                        <input :type="tipoDatoSoglia == 'Testuale' ? 'text' : 'number'" :value="soglia.ValoreMassimo"
                            name="ValoreMassimo" step="0.0000001" class="form-control"
                            id="inpUpdValMax" placeholder="massimo">
                    </div>
                </div>
                <!-- Alias -->
                <div class="col-12 col-lg-6">
                    <div class="input-group mt-3 h-75">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">
                            Alias<asterisco/>
                        </span>
                        <input type="text" name="AliasMassimo" :value="soglia.AliasMassimo"
                            class="form-control" id="inpUpdAliasMax"
                            placeholder="valore massimo">
                    </div>
                </div>
                <!-- Colore -->
                <div class="col-12 col-lg-6">
                    <div class="input-group mt-3 h-75 w-100" style="overflow:hidden">
                        <span class="btn border border-end-0 bg-light text-end pe-4" style="cursor: auto">Colore</span>
                        <div class="form-control border-0 rounded-end p-0 bg-warning" style="overflow: hidden;">
                            <input type="color" name="ColoreMassimo" :value="soglia.ColoreMassimo"
                            class="h-100 form-control p-0 border border-start-0" id="inpUpdColoreMax">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FINEIMPOSTAZIONI VALORE MASSIMO -->

    </div>
</template>

<script>
import { getRequest } from "../../../ax-request.js";
import Asterisco from "../../componenti/Asterisco.vue";

export default {
    data() {
        return {
            codAutomazione: null,
            optionsColonnaAssociata: null,
            tipoDatoSoglia: null,
            tipoSoglia: null,
            caricamento: true,
        }
    },
    props: {
        'chiave': {
            required: false,
        },
        'soglia': {
            required: false,
        },
        'automazioni': {
            required: false,
        },
        'categorieDispositivi': {
            required: false,
        },
        'categorieSoglie': {
            required: false,
        },
        'routeDatiColonneAssociate': {
            required: false,
        },
    },
    components: {
        Asterisco,
    },
    watch: {
        categorieSoglie() {
            this.tipoSoglia = this.soglia.TipoSoglia;
            this.tipoDatoSoglia = this.soglia.tipoDatoSoglia;
            this.cambia();
        },
    },
    methods: {
        updateTipoDatoSoglia(tipoDato) {
            this.tipoDatoSoglia = tipoDato;
        },
        updateTipoSoglia(tipo) {
            this.tipoSoglia = tipo;
        },
        cambia() {
            const automazione = document.getElementById('selectAutomazioneSoglia' + this.chiave);
            const categoriaDisp = document.getElementById('selectTipoDispositivoSoglia' + this.chiave);
            this.codTipoDisp = categoriaDisp.value;
            this.codAutomazione = automazione.value;
            this.getOptionsColonnaAssociata();
        },
        async getOptionsColonnaAssociata() {
            this.optionsColonnaAssociata = [];
            if (this.categorieDispositivi) {
                const res = this.categorieDispositivi.filter(tipo => tipo);
                if (this.codAutomazione && this.codTipoDisp) {
                    this.optionsColonnaAssociata = await getRequest(this.routeDatiColonneAssociate, {
                        "categoriadisp": this.codTipoDisp,
                        "automazione": this.codAutomazione,
                    }, null);
                }
            }

            this.caricamento = false;
            
        },
    },

}
</script>