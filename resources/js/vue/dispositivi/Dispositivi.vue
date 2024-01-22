<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo titolo="Gestione" sottotitolo="dei dispositivi" :bottoni="bottoni" class="px-2"></titolo>

            <!-- Modal aggiunta nuovo dispositivo -->
            <nuovo :modal-id="idModalNuovo" titolo="un nuovo dispositivo" :route="routeNuovoDispositivo">
                <template v-slot:inputAggiunta>
                    <input-aggiunta :strutture="strutture" :categorie-dispositivi="categorieDispositivi"></input-aggiunta>
                </template>
            </nuovo>

            <nuovo :modal-id="idModalCsv" titolo="nuovi dispositivi" :route="routeNuoviDispositivi">
                <template v-slot:inputAggiunta>
                    <input-aggiunta-multipla :route-base-istruzioni="routeBaseIstruzioni" :route-download-excel="routeDownloadExcel" argomento-istruzioni="dispositivi"></input-aggiunta-multipla>
                </template>
            </nuovo>

            <!-- Filtri -->
            <nav class="row mb-5">
                <div class="col-12 col-md-5 col-lg-6 col-xl-4">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                            Cerca
                        </span>
                        <input type="text" class="form-control mx-auto" placeholder="per nome" v-model="testo" @keyup.enter="dispositiviPaginati(1)"/>
                    </div>
                </div>
                <div class="col-12 col-md-7 col-lg-6 col-xl-8 text-center text-md-end mt-1 mt-md-0">
                    <button class="btn btn-primary px-5 text-uppercase" @click="dispositiviPaginati(1)">
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
            </nav>


            <!-- Modal scelta modalità di aggiunta -->
            <scelta-modalita-aggiunta :modal-id="idModalScelta" :modal-id-nuovo="idModalNuovo" :modal-id-csv="idModalCsv"></scelta-modalita-aggiunta>

            <template v-if="caricamento">
                <loader/>
            </template>
            <template v-else>
                <!-- Quanti dispositivi in allarme/allerta ci sono -->
                <div id="alertAllarmi" class="row fs-4 fw-light justify-content-end">
                    <div class="col-12 col-md-6 shadow rounded-bottom-4 rounded-start-4 px-0 overflow-hidden me-2">
                        <div v-if="dispositiviInAllarme.length > 0" class="d-flex align-items-center justify-content-between text-uppercase py-2 ps-4 pe-3 colore-card-4">
                            <span>
                                {{ dispositiviInAllarme.length }} dispositivi in allarme
                            </span>
                            <button type="button"  class="btn-close fs-6" @click="chiudiAlert"></button>
                        </div>
                        <div v-if="dispositiviInAllerta.length > 0" class="text-start text-uppercase py-2 px-4 colore-card-2">
                            <span>
                                {{ dispositiviInAllerta.length }} dispositivi in allerta
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Elendo dei dispositivi -->
                <template v-if="dispositivi != null">
                    <section v-if="dispositivi && dispositivi.length > 0" class="container-fluid mb-5 pb-5 px-0">
    
                        <!-- Card dei dispositivi -->
                        <template v-for="(dispositivo, v) in dispositivi" >
                            <card-dispositivo :route-dettagli-dispositivo="routeDispositivo + '?deveui=' + dispositivo.DevEui" :asset="asset" :dispositivo="dispositivo" :chiave="v" :route-elimina-dispositivo="routeEliminaDispositivo" :route-modifica-dispositivo="routeModificaDispositivo" :strutture="strutture" :categorie-dispositivi="categorieDispositivi" :route-elimina-soglia-dispositivo="routeEliminaSogliaDispositivo" :route-elimina-comando-dispositivo="routeEliminaComandoDispositivo"></card-dispositivo>
                        </template>
                        
                        <!-- Paginazione -->
                        <div class="pb-5 my-5 d-flex justify-content-center">
                            <Bootstrap5Pagination :data="dispositivi" @pagination-change-page="dispositiviPaginati" />
                        </div>
                    
                    </section>
    
                    <!-- Nessun dato trovato -->
                    <section v-else>
                        <no-data-found contenuto="Nessun dispositivo trovato"></no-data-found>
                    </section>
                    
                </template>
            </template>

        </div>
    </div>
</template>

<script>
import { getRequest } from "../../ax-request.js";
import Titolo from "../componenti/Titolo.vue";
import Nuovo from "../componenti/Nuovo.vue";
import InputAggiunta from "./input/InputAggiunta.vue";
import InputAggiuntaMultipla from "../componenti/InputAggiuntaMultipla.vue";
import CardDispositivo from "./CardDispositivo.vue";
import SceltaModalitaAggiunta from "../componenti/SceltaModalitaAggiunta.vue";
import NoDataFound from "../componenti/NoDataFound.vue";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import Loader from '../componenti/Loader.vue';

export default {
    data(){
        return {
            idModalNuovo: 'nuovoDispositivoModal',
            idModalCsv: 'nuoviDispositiviModal',
            idModalScelta: 'sceltaAggiuntaModal',
            bottoni: null,
            dispositivi: null,
            strutture: null,
            categorieDispositivi: null,
            caricamento: null,
            testo: '',
            dispositiviInAllarme: [],
            dispositiviInAllerta: [],
            aggiornamento: null
        }
    },
    props: {
        'routeSogliaDispositivi': {
            required: true,
        },
        'routeComandoDispositivi': {
            required: true,
        },
        'routeNuovoDispositivo': {
            required: true,
        },
        'routeNuoviDispositivi': {
            required: true,
        },
        'routeDatiDispositivi': {
            required: true,
        },
        'routeDatiStrutture': {
            required: true,
        },
        'routeDatiTipodisp': {
            required: true,
        },
        'routeModificaDispositivo': {
            required: true,
        },
        'routeEliminaDispositivo': {
            required: true,
        },
        'routeEliminaComandoDispositivo': {
            required: true,
        },
        'routeEliminaSogliaDispositivo': {
            required: true,
        },
        'routeBaseIstruzioni': {
            required: true,
        },
        'routeDownloadExcel': {
            required: true,
        },
        'routeDispositivo': {
            required: false,
        },
        'asset': {
            required: true,
        },
    },
    components: {
        Titolo,
        Nuovo,
        InputAggiunta,
        InputAggiuntaMultipla,
        CardDispositivo,
        SceltaModalitaAggiunta,
        NoDataFound,
        Bootstrap5Pagination,
        Loader,
    },
    mounted() {
        this.setup();
    },
    methods: {
        async setup() {
            try {
                this.caricamento = true;
                this.aggiornamento = true;

                this.bottoni = [
                    {
                        'tipo': 'modal',
                        'nome': 'Nuovo dispositivo',
                        'collegamento': this.idModalScelta,
                    },
                    {
                        'tipo': '!',
                        'nome': 'Aggiungi soglia a dispositivi',
                        'collegamento': this.routeSogliaDispositivi,
                    },
                    {
                        'tipo': '!',
                        'nome': 'Aggiungi comando a dispositivi',
                        'collegamento': this.routeComandoDispositivi,
                    },
                ];
    
                
                // this.categorieDispositivi = await getRequest(this.routeDatiTipodisp, null, null, null);
    
                this.dispositiviPaginati();

                this.tieniAggiornato();
            } catch (e) {
                console.log(e);
            }
        },
        tieniAggiornato() {
            setInterval(() => {
                this.dispositiviPaginati(); // Chiamare la funzione asincrona con il contesto corrente
            }, 10000);
        },
        async dispositiviPaginati(page = 1) {
            try {
                this.aggiornamento = true;
                const testo = this.testo;
                this.dispositivi = await getRequest(this.routeDatiDispositivi + '?page=' + page, {
                    'testo': testo,
                }, null);
                this.dispositiviInAllarme = [];
                this.dispositiviInAllerta = [];
                const array = Object.values(this.dispositivi);
                array.forEach(dispositivo => {
                    if (this.dispositivi.StatoComunicazioni == 1) {
                        this.dispositiviInAllerta.push(dispositivo.id);
                    } else if ( dispositivo.StatoComunicazioni == 2 ) {
                        this.dispositiviInAllarme.push(dispositivo.id);
                    }
                });
                // console.log(array);
                
                this.caricamento = false;
                this.aggiornamento = false;

            } catch (e) {
                console.log(e);
            }
        },
        chiudiAlert() {
            const alert = document.getElementById('alertAllarmi');
            alert.classList.add('d-none');
        }
    }
}
</script>