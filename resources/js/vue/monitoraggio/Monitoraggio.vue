<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo titolo="Monitoraggio" sottotitolo="dei dati"></titolo>
            
            <!-- Filtri -->
            <nav class="row">

                <!-- Struttura -->
                <div class="col-12 col-md-6 col-lg-4 ps-0">
                    <div class="input-group mt-3">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">
                            Struttura<asterisco/>
                        </span>
                        <select v-model="struttura" class="form-select" :disabled="strutture === null">
                            <option value="">Tutti</option>
                            <template v-if="strutture != null">
                                <option v-for="struttura in strutture" :value="struttura.id">{{ struttura.Nome }}</option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- Tipo dispositivo -->
                <div class="col-12 col-md-6 col-lg-4 ps-0">
                    <div class="input-group mt-3">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">
                            Tipo dispositivo<asterisco/>
                        </span>
                        <select v-model="tipoDispositivo" class="form-select" :disabled="tipodisp === null">
                            <option value="">Tutti</option>
                            <template v-if="tipodisp != null">
                                <option v-for="tipo in tipodisp" :value="tipo.IdTipo">{{ tipo.Nome }}</option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- Tipo dispositivo -->
                <div class="col-12 col-md-6 col-lg-4 ps-0">
                    <div class="input-group mt-3">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">
                            Dispositivo<asterisco/>
                        </span>
                        <select v-model="dispositivo" class="form-select" :disabled="dispositivi === null">
                            <option value="">Tutti</option>
                            <template v-if="dispositivi != null">
                                <option v-for="dispositivo in dispositivi" :value="dispositivo.id">{{ dispositivo.Nome }}</option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- Data Dal -->
                <div class="col-12 col-md-6 col-lg-4 ps-0">
                    <div class="input-group mt-3">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">Dal</span>
                        <input type="date" v-model="dal" class="form-control">
                    </div>
                </div>

                <!-- Data Al -->
                <div class="col-12 col-md-6 col-lg-4 ps-0">
                    <div class="input-group mt-3">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">al</span>
                        <input type="date" v-model="al" class="form-control">
                    </div>
                </div>

                <!-- bottone cerca -->
                <div class="col-12 col-md-6 col-lg-4 ps-0 text-end">
                    <button class="btn btn-primary mt-3 w-50 text-uppercase" @click="filtra">
                        Cerca
                    </button>
                </div>

            </nav>

            <!-- Caricamento -->
            <template v-if="caricamento">
                <loader/>
            </template>
            <!-- Mostro i dati -->
            <template v-else>

                <!-- Tabella di monitoraggio dati -->
    
                <!-- Props: -->
                <!-- colonne, chiavi*, dati -->
                <!-- <tabella></tabella> -->
            </template>

        </div>
    </div>
</template>

<script>
import { getRequest } from "../../ax-request";
import Titolo from '../componenti/Titolo.vue';
import Tabella from '../componenti/Tabella.vue';
import Asterisco from '../componenti/Asterisco.vue';
import Loader from '../componenti/Loader.vue';


export default {
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

            // Setup
            strutture: null,
            dispositivi: null,
            tipodisp: null,

            // Filtro
            struttura: null,
            tipoDispositivo: null,
            dispositivo: null,
            dal: null,
            al: null,
            caricamento: null,
        }
    },
    props: {
        'routeDatiStrutture': {
            required: true,
        },
        'routeDatiDispositivi': {
            required: true,
        },
        'routeDatiTipodisp': {
            required: true,
        },
    },
    watch: {
    },
    components: {
        Titolo,
        Tabella,
        Loader,
        Asterisco,
    },
    mounted() {
        this.setup();
    },
    methods: {
        async setup() {
            try {
                this.caricamento = true;
                
                this.strutture = await getRequest(this.routeDatiStrutture, null, null, null);
                this.tipodisp = await getRequest(this.routeDatiTipodisp, null, null, null);
                this.dispositivi = await getRequest(this.routeDatiDispositivi, null, null, null);

                // Operazioni setup
    
                this.filtra();
    
                this.caricamento = false;
            } catch (e) {
                    console.log(e);
            }
        },
        filtra() {
        },
    }
}
</script>
