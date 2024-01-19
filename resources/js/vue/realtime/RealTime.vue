<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo class="px-2" titolo="Situazione" sottotitolo="real time" :bottoni="bottoni"></titolo>

            <!-- Quantità dispositivi funzionanti -->

            <div v-if="allarmi || allerte" class="shadow rounded-5 px-5 py-3 text-center mt-5" :class="{ 'colore-card-1': allarmi.length == 0 && allerte.length == 0, 'colore-card-2': allerte.length > 0, 'colore-card-4': allarmi.length > 0 }">

                <span class="fs-3 fw-light">
                    <template v-if="numeroDispositiviFunzionanti == numeroDispositiviTotali">
                        Tutti i dispositivi funzionano correttamente
                    </template>
                    <template v-if="numeroDispositiviFunzionanti == 0">
                        Al momento non ci sono dispositivi correttamente funzionanti
                    </template>
                    <template v-if="numeroDispositiviFunzionanti < numeroDispositiviTotali && numeroDispositiviFunzionanti != 0">
                        {{ numeroDispositiviNonFunzionanti }} su {{ numeroDispositiviTotali }} dispositivi necessitano di attenzione
                    </template>
                </span>
            </div>

            <nav v-if="numeroDispositiviNonFunzionanti > 0" class="mt-5 container-fluid">
                <div class="row">
                    <div class="col-4 px-0">
                        <div class="input-group mt-3">
                            <span class="btn border border-end-0 bg-light" style="cursor: auto">Nome</span>
                            <input type="text" class="form-control" v-model="testo" placeholder="Nome o DevEui del dispositivo" @keyup="filtra">
                        </div>
                    </div>         
                    <div class="col-4 px-0 ms-3">
                        <div class="input-group mt-3">
                            <span class="btn border border-end-0 bg-light" style="cursor: auto">Tipo dispositivo</span>
                            <select class="form-select" v-model="codTipoDisp" @change="filtra">
                                <option value="">Tutti</option>
                                <template v-if="tipiDispositivi">
                                    <option v-for="tipo in tipiDispositivi" :value="tipo.IdTipo">{{ tipo.Nome }}</option>
                                </template>
                            </select>
                        </div>
                    </div>         
                </div>
            </nav>

            <!-- Caricamento -->
            <template v-if="caricamento">
                <loader/>
            </template>
            <!-- Visualizzo i dati -->
            <template v-else>
                <template v-if="(allarmi && allarmi.length > 0) || (allerte && allerte.length > 0)">
                    <template v-if="allarmi && allarmi.length > 0">
                        <div class="container-fluid">
                            <dispositivo-card v-for="(allarme, am) in allarmi" :dispositivo="allarme" :chiave="am" :asset="asset"></dispositivo-card>
                        </div>
                    </template>
        
                    <template v-if="allerte && allerte.length > 0">
                        <div class="container-fluid mb-5 pb-5">
                            <dispositivo-card v-for="(allerta, at) in allerte" :dispositivo="allerta" :chiave="at" :asset="asset"></dispositivo-card>
                        </div>
                    </template>
                    <template v-else>
                        <no-data-found contenuto="Nessun dispositivo trovato"></no-data-found>
                    </template>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
import { getRequest } from '../../ax-request';
import Titolo from "../componenti/Titolo.vue";
import DispositivoCard from './DispositivoCard.vue';
import NoDataFound from '../componenti/NoDataFound.vue';
import Loader from '../componenti/Loader.vue';
// import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {
    data() {
        return {
            allarmi: null,
            allerte: null,
            allarmiOriginali: null,
            allerteOriginali: null,
            numeroDispositiviFunzionanti: null,
            numeroDispositiviNonFunzionanti: null,
            numeroDispositiviTotali: null,
            tipiDispositivi: null,
            codTipoDisp: null,
            testo: '',
            caricamento: null,

            mqtt: null,

            bottoni: [
                {
                    'tipo': '!',
                    'nome': 'Grafici',
                    'collegamento': this.routeGrafici,
                },
                {
                    'tipo': '!',
                    'nome': 'Statistiche',
                    'collegamento': this.routeStatistiche,
                },
            ],
        }
    },
    props: {
        'routeGrafici': {
            required: true,
        },
        'routeStatistiche': {
            required: true,
        },
        'routeDatiDispositivi': {
            required: true,
        },
        'routeAllarmiRealtime': {
            required: true,
        },
        'routeDatiTipodisp': {
            required: true,
        },
        'asset': {
            required: true,
        },
    },
    components: {
        Titolo,
        Loader,
        DispositivoCard,
        NoDataFound,
    },
    mounted() {
        this.setup();
    },
    watch: {
        mqtt() {
            //Aggiornamento della mappa
            this.setup();
        }
    },
    methods: {
        async setup() {
            try {
                this.caricamento = true;
                this.tipiDispositivi = await getRequest(this.routeDatiTipodisp, null, null, null);
                const allarmi = await getRequest(this.routeAllarmiRealtime, null, null, null);
                this.numeroDispositiviTotali = allarmi['quantitaTuttiDispositivi'];
                this.numeroDispositiviFunzionanti = this.numeroDispositiviTotali - allarmi['quantitaDispositiviNonFunzionanti'];       
                this.numeroDispositiviNonFunzionanti = allarmi.quantitaDispositiviNonFunzionanti;
                this.allarmiOriginali = allarmi['allarme'];
                this.allerteOriginali = allarmi['allerta'];
                this.filtra(); 
                this.caricamento = false;
            } catch (e) {
                    console.log(e);
            }
        },
        filtra() {
            try {
                const testo = this.testo.trim().toLowerCase();
                const allarmi = this.allarmiOriginali.slice();
                const allerte = this.allerteOriginali.slice();
    
                const a = allarmi.filter(allarme =>
                    (allarme.Nome.toLowerCase().includes(testo) || allarme.DevEui.toLowerCase().includes(testo) || this.testo == "") &&
                    (allarme.codTipoDisp == this.codTipoDisp || !this.codTipoDisp)
                );
                const b = allerte.filter(allerta =>
                    (allerta.Nome.toLowerCase().includes(testo) || allerta.DevEui.toLowerCase().includes(testo) || this.testo == "") &&
                    (allerta.codTipoDisp == this.codTipoDisp || !this.codTipoDisp)
                );
                this.allarmi = a;
                this.allerte = b;
            } catch (e) {
                    console.log(e);
            }

        },
    }
}
</script>