<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo titolo="Log" sottotitolo="del sistema"></titolo>

            <!-- Filtro data -->
            <nav class="row mb-1">
                <div class="col-12 col-md-4 pe-0">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                            Dal
                        </span>
                        <input type="date" class="form-control" id="dataDA" placeholder="yyyy-mm-dd" v-model="dataDA"
                            @change="showDati(1)">
                    </div>
                </div>
                <div class="col-12 col-md-4 px-2">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                            Al
                        </span>
                        <input type="date" class="form-control" id="dataA" placeholder="yyyy-mm-dd" v-model="dataA"
                            @change="showDati(1)">
                    </div>
                </div>
                <div class="col-12 col-md-4 ps-0 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
                    <button class="btn btn-primary text-uppercase w-100" type="button" @click="codice=''; showDati(1)">
                        <div v-if="!aggiornamentoInCorso">
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

            <!-- Filtri stato -->
            <nav class="row mb-4">
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnRadioTutti" autocomplete="off" checked>
                    <label class="alert alert-secondary w-100 text-center py-2 me-sm-0 cursor-pointer" for="btnRadioTutti" @click="showDati(1, '')">
                        TUTTI
                    </label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnRadioSuccess" autocomplete="off" @click="showDati(1, 'SUCCESS')">
                    <label class="alert alert-success w-100 text-center py-2 mx-sm-2 cursor-pointer" for="btnRadioSuccess">
                        SUCCESS
                    </label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnRadioError" autocomplete="off" @click="showDati(1, 'ERROR')">
                    <label class="alert alert-danger w-100 text-center py-2 ms-sm-0 cursor-pointer me-sm-0" for="btnRadioError">
                        ERROR
                    </label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnRadioEvent" autocomplete="off" @click="showDati(1, 'EVENT')">
                    <label class="alert alert-info w-100 text-center py-2 mx-sm-2 cursor-pointer" for="btnRadioEvent">
                        EVENT
                    </label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnRadioScenario" autocomplete="off" @click="showDati(1, 'SCENARIO')">
                    <label class="alert alert-warning w-100 text-center py-2 ms-sm-0 cursor-pointer me-sm-0" for="btnRadioScenario">
                        SCENARIO
                    </label>
                </div>
            </nav>

            <!-- Caricamento -->
            <template v-if="caricamento">
                <loader/>
            </template>
            <!-- Visualizzazione dati -->
            <template v-else>

                <!-- Elenco dei log -->
                <template v-if="logs && logs.data">
    
                    <!-- Se ci sono i log -->
                    <template v-if="logs.data.length > 0">
    
                        <!-- Cards log -->
                        <template v-for="log in logs.data">
                            <card-log :log="log"></card-log>
                        </template>
    
                        <!-- Paginazione -->
                        <div class="center mb-5 pb-5">
                            <Bootstrap5Pagination :data="logs" @pagination-change-page="showDati" class="mt-5 pt-4" />
                        </div>
    
                    </template>
    
                    <!-- Se non ci sono log -->
                    <template v-else>
                        <no-data-found contenuto="Nessun log trovato"></no-data-found>
                    </template>
    
                </template>

            </template>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import $ from 'jquery';
import { getRequest } from '../../ax-request.js';
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import Titolo from '../componenti/Titolo.vue';
import NoDataFound from '../componenti/NoDataFound.vue';
import CardLog from './CardLog.vue';
import Loader from '../componenti/Loader.vue';

export default {
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            caricamento: true,
            logs: [],

            // filtri
            dataDA: null,
            dataA: null,
            codice: '',

            caricamento: false,
        }
    },
    props: {
        'routeLogDati': {
            required: true,
        },
    },
    components: {
        Bootstrap5Pagination,
        Titolo,
        Loader,
        CardLog,
        NoDataFound,
    },
    created() {
        this.showDati(1, '');
    },
    methods: {
        async showDati(page = 1, codice) {
            try {
                this.caricamento = true;
                const da = document.getElementById('dataDA');
                const a = document.getElementById('dataA');
                let dal = null;
                let al = null;
                
                if (da && da.value) {
                    dal = da.value;
                }
                if (a && a.value) {
                    al = a.value;
                }
        
                const dataDA = moment(new Date(dal + " 00:00:00")).format('YYYY-MM-DD HH:mm:ss');
                const dataA = moment(new Date(al + " 23:59:59")).format('YYYY-MM-DD HH:mm:ss');
        
                this.logs = await getRequest(this.routeLogDati + "?page=" + page, {
                    "codice": codice,
                    "dataDA": dataDA,
                    "dataA": dataA
                });
        
                this.caricamento = false;
            } catch (e) {
                    console.log(e);
            }
        }
    },
}
</script>
<style scoped></style>