<template>
    <div>
        <div class="container">
            <div class="row mt-5 pt-5 mb-5">
                <div class="col-12 col-lg-8">
                    <h1 class="pt-4 text-uppercase fw-light">Dati di <span class="fw-normal">{{ dispositivo['Nome'] }}</span></h1>
                    <h3 class="fw-light">con deveui {{ dispositivo['DevEui'] }}</h3>
                </div>
                <div class="col-12 col-lg-4 d-flex flex-column pt-4 text-start text-lg-end">
                    <a class="btn btn-primary text-uppercase px-5 mt-2" :href="routeHome">Torna indietro</a>
                    <button class="btn btn-outline-primary text-uppercase px-5 mt-2" :class="{ 'disabled': rawData === null }" data-bs-toggle="modal" data-bs-target="#exampleModal">Raw Data</button>
                    <raw-data :raw-data="rawData" :nome="dispositivo.Nome" :deveui="dispositivo.DevEui"></raw-data>
                </div>
            </div>
        </div>
        <template v-if="caricamento">
            <loader></loader>
        </template>
        <template v-else>
            <div v-if="statoDisp">
                <h1 v-if="statoDisp.length == 0" class="text-uppercase fw-light text-center pt-5">Non ci sono dati</h1>
            </div>
            <table class="table table-hover" v-if="statoDisp && statoDisp.length > 0">
                <thead class="bg-primary text-light border border-dark">
                    <th v-for="colonna in colonne" class="fw-normal px-2" :class="{'text-end pe-3': colonna == 'DataOra'}">
                        {{ colonna }}
                    </th>
                </thead>
                <tbody v-if="statoDisp">
                    <tr v-for="dato in statoDisp">
                        <td v-for="(col, c) in colonneGrezze" :title="[
                            col == 'Numero6' ? dato.titleNox : '',
                            col == 'Numero7' ? dato.titleTvox : '',
                            col == 'Vento' ? dato.titleVento : '',
                        ]" :class="{
                            'ps-4': col == 'Frame',
                            'text-end pe-3': col == 'DataOra',
                            'bg-light-success cursor-pointer': (col == 'Numero6' && dato.statoNox == 0) || (col == 'Numero7' && dato.statoTvox == 0) || (col == 'Vento' && dato.statoVento == 0),
                            'bg-light-yellow cursor-pointer': (col == 'Numero6' && dato.statoNox == 1) || (col == 'Numero7' && dato.statoTvox == 1) || (col == 'Vento' && dato.statoVento == 1),
                            'bg-light-warning cursor-pointer': (col == 'Numero6' && dato.statoNox == 2) || (col == 'Numero7' && dato.statoTvox == 2) || (col == 'Vento' && dato.statoVento == 2),
                            'bg-light-danger cursor-pointer': (col == 'Numero6' && dato.statoNox == 3) || (col == 'Numero7' && dato.statoTvox == 3) || (col == 'Vento' && dato.statoVento == 3),
                            }">{{ dato[col] }}</td>
                    </tr>
                </tbody>
            </table>
        </template>
    </div>
</template>

<script>
import { getRequest } from '../../ax-request.js';
import RawData from './RawData.vue';
import Loader from '../componenti/Loader.vue';

export default {
    data() {
        return {
            dati: null,
            pagine: 3,
            pag: 1,
            soglieNox: {
                'minimo': 2,
                'abbastanza': 6,
                'massimo': 8,
            },
            soglieTvox: {
                'minimo': 150,
                'abbastanza': 250,
                'massimo': 400,
            },
            rawData: null,
            statoDisp: null,
            caricamento: null,
        }
    },
    props: {
        'routeDatiDispositivo': {
            required: false,
        },
        'dispositivo': {
            required: false,
        },
        'colonneGrezze': {
            required: false,
        },
        'colonne': {
            required: false,
        },
        'soglieVento': {
            required: false,
        },
        'routeHome': {
            required: false,
        },
        'routeDatiRawdata': {
            required: false,
        },
        'routeDatiStatodisp': {
            required: false,
        },
    },
    components: {
        RawData,
        Loader,
    },
    mounted() {
        this.setup();
    },
    methods: {
        async setup() {
            this.caricamento = true;
            this.dati = await getRequest(this.routeDatiDispositivo, {
                "deveui": this.dispositivo.DevEui,
            }, null, null);
            this.api();
            this.caricamento = false;
            this.tieniAggiornato();
        },
        async api() {
            const rawData = await getRequest(this.routeDatiRawdata, {
                'deveui': this.dispositivo.DevEui
            }, null, null);
            const statoDisp = await getRequest(this.routeDatiStatodisp, {
                'deveui': this.dispositivo.DevEui
            }, null, null);
            this.rawData = rawData;
            this.statoDisp = statoDisp;
        },
        async tieniAggiornato() {
            setInterval(async () => {
                await this.api();
            }, 10000);
        },
    },
}
</script>

<style scoped></style>