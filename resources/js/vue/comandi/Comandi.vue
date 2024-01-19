<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo titolo="Gestione" sottotitolo="dei comandi" :bottoni="bottoni"></titolo>

            <!-- Modal scelta modalità di aggiunta -->
            <scelta-modalita-aggiunta :modal-id="idModalScelta" :modal-id-nuovo="idModalNuovo" :modal-id-csv="idModalCsv"></scelta-modalita-aggiunta>

            <!-- Modal aggiunta nuovo comando -->
            <nuovo :modal-id="idModalNuovo" titolo="un nuovo comando" :route="routeNuovoComando">
                <template v-slot:inputAggiunta>
                    <input-aggiunta :tipi-dispositivi="tipiDispositivi" :automazioni="automazioni"></input-aggiunta>
                </template>
            </nuovo>

            <!-- Modal aggiunta nuovi comandi tramite csv -->
            <nuovo :modal-id="idModalCsv" titolo="nuovi comandi" :route="routeNuoviComandi">
                <template v-slot:inputAggiunta>
                    <!-- 'argomentoIstruzioni', 'routeBaseIstruzioni', 'routeDownloadExcel' -->
                    <input-aggiunta-multipla argomento-istruzioni="comandi" :route-base-istruzioni="routeBaseIstruzioni" :route-download-excel="routeDownloadExcel"></input-aggiunta-multipla>
                </template>
            </nuovo>

            <!-- Filtri -->
            <nav class="row mb-5">
                <div class="col-12 col-md-5 col-lg-6 col-xl-4">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                            Cerca
                        </span>
                        <input type="text" class="form-control mx-auto" placeholder="comando per nome" v-model="testo" @keyup="filtra" />
                    </div>
                </div>
                <div class="col-12 col-md-5 col-lg-6 col-xl-4 mt-1 mt-md-0">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                            Categoria dispositivo
                        </span>
                        <select id="selectTipoDisp" class="form-select" v-model="tipoDispositivo" @change="filtra" :disabled="tipiDispositivi == null">
                            <option v-for="tipo in tipiDispositivi" :value="tipo.IdTipo">{{ tipo.Nome }}</option>
                        </select>
                    </div>
                </div>
            </nav>

            <!-- Loader -->
            <template v-if="caricamento">
                <loader/>
            </template>

            <!-- Visualizzo i dati -->
            <template v-else>
                <!-- Lista comandi -->
                <template v-if="comandiFiltrati != null">
                    <section v-if="comandiFiltrati.length > 0" class="container-fluid">
                        <card-comando v-for="(comando, c) in comandiFiltrati" :asset="asset" :dispositivi="dispositivi" :comando="comando" :chiave="c" :tipi-dispositivi="tipiDispositivi" :automazioni="automazioni" :route-modifica-comando="routeModificaComando" :route-elimina-comando="routeEliminaComando"></card-comando>
                    </section>
                    <section v-else>
                        <no-data-found contenuto="Nessun comando trovato"></no-data-found>
                    </section>
                </template>
            </template>

        </div>
    </div>
</template>

<script>
import { getRequest } from "../../ax-request";
import Titolo from "../componenti/Titolo.vue";
import Loader from '../componenti/Loader.vue';
import Nuovo from "../componenti/Nuovo.vue";
import InputAggiunta from "./input/InputAggiunta.vue";
import InputAggiuntaMultipla from "../componenti/InputAggiuntaMultipla.vue";
import SceltaModalitaAggiunta from "../componenti/SceltaModalitaAggiunta.vue";
import CardComando from "./CardComando.vue";
import NoDataFound from "../componenti/NoDataFound.vue";

export default {
    data(){
        return {
            idModalScelta: 'sceltaModalitaInserimentoComandoModal',
            idModalNuovo: 'nuovoComandoModal',
            idModalCsv: 'nuoviComandiModal',
            bottoni: null,

            // Liste da getRequest
            tipiDispositivi: null,
            automazioni: null,
            comandi: null,
            comandiFiltrati: null,
            dispositivi: null,

            // Filtri
            testo: '',
            tipoDispositivo: '',

            caricamento: null,
        }
    },
    props: {
        'routeNuovoComando': {
            required: true,
        },
        'routeNuoviComandi': {
            required: true,
        },
        'routeModificaComando': {
            required: true,
        },
        'routeEliminaComando': {
            required: true,
        },
        'routeDatiCategorieDispositivi': {
            required: true,
        },
        'routeDatiAutomazioni': {
            required: true,
        },
        'routeDatiDispositivi': {
            required: true,
        },
        'routeDatiComandi': {
            required: true,
        },
        'routeBaseIstruzioni': {
            required: true,
        },
        'routeDownloadExcel': {
            required: true,
        },
        'routeComandoDispositivi': {
            required: true,
        },
        'asset': {
            required: true,
        },
    },
    components: {
        Titolo,
        Loader,
        Nuovo,
        InputAggiunta,
        InputAggiuntaMultipla,
        SceltaModalitaAggiunta,
        CardComando,
        NoDataFound,
    },
    mounted() {
        this.setup();
    },
    watch: {
        testo() {
            this.filtra();
        }
    },
    methods: {
        async setup() {
            try {
                this.caricamento = true;
                this.bottoni = [
                    {
                        'tipo': 'modal',
                        'nome': 'Nuovo comando',
                        'collegamento': this.idModalScelta,
                    },
                    {
                        'tipo': '!',
                        'nome': 'Aggiungi comando a dispositivi',
                        'collegamento': this.routeComandoDispositivi,
                    },
                ];
                this.comandi = await getRequest(this.routeDatiComandi, null, null, null);
                this.caricamento = false;
                this.filtra();
                this.tipiDispositivi = await getRequest(this.routeDatiCategorieDispositivi, null, null, null);
                this.automazioni = await getRequest(this.routeDatiAutomazioni, null, null, null);
                this.dispositivi = await getRequest(this.routeDatiDispositivi, null, null, null);
            } catch (e) {
                console.log(e);
            }
        },
        filtra() {
            try {
                const testo = this.testo.toLowerCase().trim();
    
                const comandi = this.comandi.slice();
    
                this.comandiFiltrati = comandi.filter( comando =>
                    comando.Nome.toLowerCase().includes(testo) || this.testo == ''
                );
            } catch (e) {
                console.log(e);
            }
        }
    }
}
</script>