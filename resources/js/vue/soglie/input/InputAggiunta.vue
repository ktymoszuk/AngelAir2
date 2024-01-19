<template>
    <div>

        <!-- IMPOSTAZIONI GENERALI -->
        <div class="row">

            <!-- Nome -->
            <div class="col-12">
                <div class="input-group mt-3">
                    <span class="btn border border-end-0 bg-light" style="cursor: auto">
                        Nome<asterisco/>
                    </span>
                    <input type="text" name="Nome" class="form-control" placeholder="della soglia">
                </div>
            </div>
            <!-- Automazione -->
            <div class="col-12">
                <div class="input-group mt-3">
                    <span class="btn border border-end-0 bg-light" style="cursor: auto">
                        Automazione<asterisco/>
                    </span>
                    <select id="selectCreateAutomazione" name="codAutomazione" class="form-select" @change="cambiaAutomazione" required>
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
                    <select name="codTipoDisp" class="form-select" id="selectCreateCategoriaDisp" @change="cambiaCategoriaDispositivo">
                        <option selected disabled value="">Seleziona</option>
                        <option v-for="option in categorieDispositivi" :value="option.IdTipo" :label="option.Nome" @mousedown="codTipoDisp = option.id"></option>
                    </select>
                </div>
            </div>
            <!-- Categoria soglia -->
            <div class="col-12">
                <div class="input-group mt-3">
                    <span class="btn border border-end-0 bg-light" style="cursor: auto">
                        Categoria soglia<asterisco/>
                    </span>
                    <select name="codCategoriaSoglia" class="form-select" id="selectUpdCategoriaSoglia">
                        <option selected disabled value="">Seleziona</option>
                        <option disabled value="">Seleziona Categoria Soglia</option>
                        <option v-for="option in categorieSoglie" :value="option.id" :label="option.Nome"></option>
                    </select>
                </div>
            </div>
            <!-- Colonna associata -->
            <div class="col-12">
                <div v-if="codAutomazione && codTipoDisp" class="input-group mt-3">
                    <span class="btn border border-end-0 bg-light" style="cursor: auto">
                        Colonna associata<asterisco/>
                    </span>
                    <select name="ColonnaAssociata" class="form-select"
                        id="selectUpdColonnaAssociata" @change="filtroSelect($event)">
                        <option selected disabled value="">Seleziona</option>
                        <option v-for="option in optionsColonnaAssociata" :value="option.Colonne"
                            :valore="option.Valori" :label="option.Valori"> 
                        </option>
                    </select>
                </div>
                <div class="form-control mt-3" v-else>
                    <p class="m-0 text-uppercase">Seleziona prima <span class="text-danger">Automazione</span> e <span class="text-danger">Categoria dispositivo</span></p>
                </div>
            </div>
            <!-- Valore associato -->
            <div class="col-12">
                <div v-if="codAutomazione && codTipoDisp" class="input-group mt-3">
                    <span class="btn border border-end-0 bg-light" style="cursor: auto">
                        Valore associato<asterisco/>
                    </span>
                    <input type="text" name="ValoreAssociato" class="form-control">
                </div>
                <div class="form-control mt-3" v-else>
                    <p class="m-0 text-uppercase">Seleziona prima <span class="text-danger">Automazione</span> e <span class="text-danger">Categoria dispositivo</span></p>
                </div>
            </div>
            <!-- Tipologia soglia -->
            <div class="col-12">
                <div class="input-group mt-3">
                    <div class="btn-group w-100" role="group" aria-label="Basic checkbox toggle button group">
                        <span class="btn border border-end-0 bg-light text-start" style="cursor: auto">Tipo soglia</span>
        
                        <input type="radio" name="TipoSoglia"
                        class="btn-check rUpdTipoSoglia" id="rUpdSingola" value="Singola" checked @change="updateTipoSoglia('Singola')">
                        <label class="btn btn-outline-primary" for="rUpdSingola">Singola</label>
        
                        <input type="radio" name="TipoSoglia"
                        class="btn-check rUpdTipoSoglia" id="rUpdRange" value="Range" @change="updateTipoSoglia('Range')">
                        <label class="btn btn-outline-primary" for="rUpdRange">Range</label>
                    </div>
                </div>
            </div>
            <!-- Tipologia dato -->
            <div class="col-12">
                <div class="input-group mt-3">
                    <div class="btn-group w-100" role="group" aria-label="Basic checkbox toggle button group">
                        <span class="btn border border-end-0 bg-light text-start" style="cursor: auto">Tipo dato</span>
        
                        <input type="radio" name="TipoDatoSoglia"
                            class="btn-check rUpdTipoDatoSoglia" id="rUpdNumerica" value="Numerica" checked
                            @change="updateTipoDatoSoglia('Numerica')">
                        <label class="btn btn-outline-primary" for="rUpdNumerica">Numerico</label>
        
                        <input name="TipoDatoSoglia"
                            class="btn-check rUpdTipoDatoSoglia" type="radio" id="rUpdTestuale"
                            value="Testuale" @change="updateTipoDatoSoglia('Testuale')">
                        <label class="btn btn-outline-primary" for="rUpdTestuale">Testuale</label>
                    </div>
                </div>
            </div>
            <!-- Descrizione -->
            <div class="col-12">
                <div class="input-group mt-3">
                    <span class="btn border border-end-0 bg-light" style="cursor: auto">Descrizione</span>
                    <textarea name="Descrizione" class="form-control" rows="3"></textarea>
                </div>
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
                    <select name="OperatoreMinimo" class="form-select"
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
                    <input :type="tipoDatoSoglia == 'Testuale' ? 'text' : 'number'" step="0.0000001" name="ValoreMinimo"
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
                    <input type="text" name="AliasMinimo"
                    class="form-control" id="inpUpdAliasMin" placeholder="valore minimo">
                </div>
            </div>
            <!-- Colore -->
            <div class="col-12 col-lg-6">
                <div class="input-group mt-3 h-75 w-100" style="overflow:hidden">
                    <span class="btn border border-end-0 bg-light text-end pe-4" style="cursor: auto">Colore</span>
                    <div class="form-control border-0 rounded-end p-0 bg-warning" style="overflow: hidden;">
                        <input type="color" name="ColoreMinimo"
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
                        <select name="OperatoreMassimo"
                            class="form-select" id="selectUpdOperatoreMassimo">
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
                        <input :type="tipoDatoSoglia == 'Testuale' ? 'text' : 'number'"
                            name="ValoreMassimo" step="0.0000001" class="form-control"
                            id="inpUpdValMax" placeholder="Valore Massimo">
                    </div>
                </div>
                <!-- Alias -->
                <div class="col-12 col-lg-6">
                    <div class="input-group mt-3 h-75">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">
                            Alias<asterisco/>
                        </span>
                        <input type="text" name="AliasMassimo"
                            class="form-control" id="inpUpdAliasMax"
                            placeholder="valore massimo">
                    </div>
                </div>
                <!-- Colore -->
                <div class="col-12 col-lg-6">
                    <div class="input-group mt-3 h-75 w-100" style="overflow:hidden">
                        <span class="btn border border-end-0 bg-light text-end pe-4" style="cursor: auto">Colore</span>
                        <div class="form-control border-0 rounded-end p-0 bg-warning" style="overflow: hidden;">
                            <input type="color" name="ColoreMassimo"
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
            codTipoDisp: null,
            optionsColonnaAssociata: null,
            tipoDatoSoglia: 'Numerico',
            tipoSoglia: 'Singola',
        }
    },
    props: {
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
            required: true,
        },
    },
    components: {
        Asterisco,
    },
    methods: {
        updateTipoDatoSoglia(tipoDato) {
            this.tipoDatoSoglia = tipoDato;
        },
        updateTipoSoglia(tipo) {
            this.tipoSoglia = tipo;
        },
        async getOptionsColonnaAssociata() {
            this.optionsColonnaAssociata = [];
            if (this.codAutomazione && this.codTipoDisp) {
                this.optionsColonnaAssociata = await getRequest(this.routeDatiColonneAssociate, {
                    "categoriadisp": this.codTipoDisp,
                    "automazione": this.codAutomazione,
                }, null);
            }
            
        },
        cambiaAutomazione() {
            const automazione = document.getElementById('selectCreateAutomazione');
            this.codAutomazione = automazione.value;
            this.getOptionsColonnaAssociata();
        },
        cambiaCategoriaDispositivo() {
            const categoriaDisp = document.getElementById('selectCreateCategoriaDisp');
            this.codTipoDisp = categoriaDisp.value;
            this.getOptionsColonnaAssociata();
        },
        filtroSelect(e) {
            // prendo l'attributo dalla select soglia
            const options = e.target.options
            if (options.selectedIndex > -1) {
                this.valoreAssociato = options[options.selectedIndex].getAttribute("label");
            }
        },
    },

}
</script>