<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo titolo="Gestione" sottotitolo="delle soglie" :bottoni="bottoni"></titolo>

            <!-- Modal aggiunta nuova azienda -->
            <nuovo :modal-id="idModalNuovo" titolo="una nuova soglia" :route="routeNuovaSoglia">
                <template v-slot:inputAggiunta>
                    <input-aggiunta :route-dati-colonne-associate="routeDatiColonneAssociate" :automazioni="automazioni" :categorie-dispositivi="categorieDispositivi" :categorie-soglie="categorieSoglie"></input-aggiunta>
                </template>
            </nuovo>

            <nav class="row mb-5">
                <div class="col-8 col-md-6 px-0 mt-md-0">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light cursor">Cerca</span>
                        <input type="text" class="form-control" id="filtroSoglie" placeholder="per nome soglia"
                            v-model="testo" @keyup="filtra" />
                    </div>
                </div>
                <div class="col-4 col-md-6 d-flex justify-content-end justify-content-md-end px-0 mt-md-0">
                    <button class="btn btn-primary text-uppercase px-5" type="button" @click="filtra">
                        <div v-if="!caricamento">
                            Cerca
                        </div>
                        <div v-else>
                            <div class="spinner-grow spinner-grow-sm text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow spinner-grow-sm text-light mx-1" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow spinner-grow-sm text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </button>
                </div>

                <div class="col-12 col-md-4 px-0 pe-0 mt-3 pe-md-2">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light cursor">Automazione</span>
                        <select id="selectAutomazione" class="form-select" v-model="automazione" @change="filtra" :disabled="automazioni === null">
                            <option selected value="0">Tutte</option>
                            <option v-for="option in automazioni" :value="option.id">{{ option.Nome }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-6 col-md-4 px-0 pe-0 mt-3 pe-2 ps-md-2">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light cursor">Categoria soglia</span>
                        <select id="selectCategoriaSoglia" class="form-select" v-model="categoriaSoglia" :disabled="categorieSoglie === null"
                            @change="filtra">
                            <option value="0">Tutte</option>
                            <option v-for="option in categorieSoglie" :value="option.id" :label="option.Nome"></option>
                        </select>
                    </div>
                </div>
                <div class="col-6 col-md-4 px-0 pe-0 mt-3 ps-2">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light cursor">Categoria dispositivo</span>
                        <select id="selectCategoriaDisp" class="form-select" v-model="categoriaDisp" :disabled="categorieDispositivi === null"
                            @change="filtra">
                            <option disabled value="">Seleziona Categoria Dispositivo</option>
                            <option value="0">Tutte</option>
                            <option v-for="option in categorieDispositivi" :value="option.id" :label="option.Nome"></option>
                        </select>
                    </div>
                </div>
            </nav>

            <!-- Caricamento -->
            <template v-if="caricamento">
                <loader/>
            </template>

            <!-- Visualizzo i dati -->
            <template v-else>
                <!-- Elenco delle soglie -->
                <template v-if="soglieFiltrate">
                    <card-soglia v-for="(soglia, s) in soglieFiltrate" :chiave="s" :soglia="soglia" :automazioni="automazioni" :categorie-soglie="categorieSoglie" :categorie-dispositivi="categorieDispositivi" :id-modal-modifica="idModalModifica" :id-modal-elimina="idModalElimina" :route-modifica-soglia="routeModificaSoglia" :route-elimina-soglia="routeEliminaSoglia" :route-dati-colonne-associate="routeDatiColonneAssociate"></card-soglia>
                </template>
            </template>

        </div>
    </div>
</template>

<script>
import { getRequest } from "../../ax-request.js";
import Titolo from "../componenti/Titolo.vue";
import Loader from '../componenti/Loader.vue';
import Nuovo from "../componenti/Nuovo.vue";
import InputAggiunta from "./input/InputAggiunta.vue";
import CardSoglia from "./CardSoglia.vue";

export default {
    data(){
        return {
            idModalNuovo: 'nuovaSogliaModal',
            idModalModifica: 'modificaSogliaModal',
            idModalElimina: 'eliminaSogliaModal',
            bottoni: null,
            caricamento: false,

            // Filtri
            testo: '',
            automazione: 0,
            categoriaSoglia: 0,
            categoriaDisp: 0,
            soglieFiltrate: null,

            soglie: null,
            automazioni: null,
            categorieDispositivi: null,
            categorieSoglie: null,
        }
    },
    props: {
        'routeNuovaSoglia': {
            required: true,
        },
        'routeModificaSoglia': {
            required: true,
        },
        'routeEliminaSoglia': {
            required: true,
        },
        'routeDatiSoglie': {
            required: true,
        },
        'routeDatiAutomazioni': {
            required: true,
        },
        'routeDatiCategorieDispositivi': {
            required: true,
        },
        'routeDatiCategorieSoglie': {
            required: true,
        },
        'routeDatiColonneAssociate': {
            required: true,
        },
        'routeSogliaDispositivi': {
            required: true,
        },
    },
    components: {
        Titolo,
        Loader,
        Nuovo,
        CardSoglia,
        InputAggiunta,
    },
    mounted() {
        this.setup();
    },
    methods: {
        async setup() {
            try {
                this.caricamento = true;
                this.bottoni = [
                    {
                        'tipo': 'modal',
                        'nome': 'Nuova Soglia',
                        'collegamento': this.idModalNuovo,
                    },
                    {
                        'tipo': '!',
                        'nome': 'Aggiungi soglia a dispositivi',
                        'collegamento': this.routeSogliaDispositivi,
                    },

                ];
    
                this.soglie = await getRequest(this.routeDatiSoglie, {
                    "automazione": this.automazione,
                    "categoriasoglia": this.categoriaSoglia,
                    "categoriadisp": this.categoriaDisp,
                    "testo": this.testo,
                }, null, null);
                
                this.filtra();

                this.caricamento = false;

                this.automazioni = await getRequest(this.routeDatiAutomazioni, null, null, null);
                this.categorieSoglie = await getRequest(this.routeDatiCategorieSoglie, null, null, null);
                this.categorieDispositivi = await getRequest(this.routeDatiCategorieDispositivi, null, null, null);
            } catch (e) {
                console.log(e);
            }
        
        },
        filtra() {
            try {
                const testo = this.testo.trim().toLowerCase();
                const soglie = this.soglie.data.slice();
    
                this.soglieFiltrate = soglie.filter(soglia =>
                    (soglia.Nome.trim().toLowerCase().includes(testo) || this.testo == '') &&
                    (soglia.codAutomazione == this.automazione || this.automazione == 0) &&
                    (soglia.codCategoriaSoglia == this.categoriaSoglia || this.categoriaSoglia == 0) &&
                    (soglia.codCategoriaDisp == this.categoriaDisp || this.categoriaDisp == 0)
                );
            } catch (e) {
                console.log(e);
            }
        }
    }
}
</script>