<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo titolo="Elenco" sottotitolo="delle strutture"></titolo>
            
            <!-- Filtri -->
            <nav class="row">
                <div class="col-12 col-md-4 ps-md-0">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                            Cerca
                        </span>
                        <input type="text" class="form-control mx-auto" placeholder="struttura per nome" v-model="testo"
                        @keyup="filtra()" />
                    </div>
                </div>
                <div class="col-12 col-md-4 mt-1 mt-md-0 px-md-0">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                            Automazione
                        </span>
                        <select id="selectAutomazione" class="form-select" v-model="automazione" :disabled="automazioni === null"
                            @change="filtra()">
                            <option selected value="">Tutte</option>
                            <option v-for="automazione in automazioni" :value="automazione.id" :label="automazione.Nome"></option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 offset-lg-1 mt-1 mt-md-0 pe-md-0">
                    <button class="btn btn-primary text-uppercase w-100" type="button"
                        @click="filtra()">
                        <div v-if="!aggiornamento">
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
            </nav>

            <!-- Caricamento -->
            <template v-if="caricamento">
                <loader/>
            </template>

            <!-- Visualizzo i dati -->
            <template v-else>

                <template v-if="dati">
                    <section v-if="dati.length > 0" class="container-fluid">
                        <template v-for="(struttura, s) in dati">
                            <card-struttura :asset="asset" :strutture="strutture" :cartografie="cartografie" :struttura="struttura" :chiave="s" :route-modifica-struttura="routeModificaStruttura" :route-elimina-struttura="routeEliminaStruttura"></card-struttura>
                        </template>
                    </section>
                    <section v-else>
                        <no-data-found contenuto="Nessuna struttura trovata"></no-data-found>
                    </section>
                </template>

            </template>
        </div>
    </div>
</template>

<script>
import { getRequest } from '../../ax-request.js';
import Titolo from '../componenti/Titolo.vue';
import NoDataFound from '../componenti/NoDataFound.vue';
import CardStruttura from './CardStruttura.vue';
import Loader from '../componenti/Loader.vue';

export default {
    components: {
        // Bootstrap5Pagination,
        Titolo,
        CardStruttura,
        NoDataFound,
        Loader,
    },
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            caricamento: null,
            dati: null,
            strutture: null,
            automazioni: null,

            // filtri
            automazione: '',
            testo: "",

            aggiornamento: null,
        }
    },
    props: {
        'utente': {
            required: true,
        },
        'routeEliminaStruttura': {
            required: true,
        },
        'routeModificaStruttura': {
            required: true,
        },
        'routeSinotticiStruttura': {
            required: true,
        },
        'routeDatiAutomazioni': {
            required: true,
        },
        'routeDatiStrutture': {
            required: true,
        },
        'asset': {
            required: true,
        },
        'routeDatiFiltratiStrutture': {
            required: true,
        },
    },
    created() {
        this.setup();
        this.filtra();
    },
    watch: {
        cartografiaSelezionata(before, after) {
            try {
                this.dati.forEach(row => {
                    if (row.id == this.id) {

                        this.cartografia = row[before];
                    }
                });
            } catch (e) {
                console.log(e);
            }
        }
    },
    watch: {
        strutture() {
            this.filtra();
        }
    },
    methods: {
        async setup() {
            try {
                this.caricamento = true;
                this.aggiornamento = true;
                this.cartografie = await getRequest(this.routeSinotticiStruttura, null, null);
                this.automazioni = await getRequest(this.routeDatiAutomazioni, null, null);
                this.strutture = await getRequest(this.routeDatiStrutture, null, null);
            } catch (e) {
                console.log(e);
            }
        },
        // visualizzazione dati
        async filtra() {
            try {
                if (this.strutture) {
                    this.aggiornamento = true;
                    const testo = this.testo.trim().toLowerCase();
                    const automazione = this.automazione;
    
                    const dati = this.strutture.slice();
    
                    const datiFiltrati = dati.filter(struttura => 
                        (struttura.Nome.toLowerCase().includes(testo) || this.testo == '') &&
                        ((struttura.automazione.id == automazione || automazione == null) || this.automazione == '')
                    );
                    this.dati = datiFiltrati;
                    
                    this.aggiornamento = false;
                    this.caricamento = false;
                }
            } catch (e) {
                console.log(e);
            }
        },
    }
}
</script>

<style scoped></style>