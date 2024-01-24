<template>
    <div class="container">

        <!-- Titolo -->
        <titolo titolo="Grafici" sottotitolo="dei dati" :bottoni="bottoni"></titolo>
        <!-- Filtri -->
        <nav class="row">

            <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-end align-items-start mt-3 mt-md-0">
                <div class="input-group">
                    <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                        Struttura
                    </span>
                    <select id="selectStruttura" class="form-select" v-model="codStruttura" @change="dispositiviValidiPerSelect">
                        <option disabled selected value="">Seleziona</option>
                        <option :value="0">
                            <span v-if="selectStrutture != null">
                                Tutte
                            </span>
                            <span v-else>
                                Caricamento...
                            </span>
                        </option>
                        <option v-for="struttura in selectStrutture" :value="struttura.id" :label="struttura.Nome"></option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-end align-items-start mt-3 mt-md-0">
                <div class="input-group">
                    <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                        Categoria
                    </span>
                    <select id="selectCategoriaDisp" class="form-select" v-model="codCategoriaDisp" :disabled="codStruttura === null || !selectCategorieDispositivi" @change="dispositiviValidiPerSelect">
                        <option selected :value="0">
                            <span v-if="selectCategorieDispositivi != null">
                                Tutte
                            </span>
                            <span v-else>
                                Caricamento...
                            </span>
                        </option>
                        <template v-for="categoria in selectCategorieDispositivi">
                            <option :value="categoria.IdTipo">
                                {{ categoria.Nome }}
                            </option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-end align-items-start mt-3 mt-md-0">
                <div class="input-group">
                    <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                        Dispositivo<asterisco/>
                    </span>
                    <select id="selectDispositivo" class="form-select" :disabled="codStruttura === null || !dispositiviFiltrati"
                            v-model="dispositivo" @change="getValori()">
                            <option disabled value="">
                                <span v-if="dispositiviFiltrati != null">
                                    Seleziona
                                </span>
                                <span v-else>
                                    Caricamento...
                                </span>
                            </option>
                            <template v-for="option in dispositiviFiltrati">
                                <!-- <option v-if="
                                    (option.codTipoDisp == codCategoriaDisp && option.codStruttura == codStruttura) ||
                                    (codStruttura != 0 && option.codStruttura == codStruttura && )
                                    "
                                    :value="option.Deveui" :id="option.id" :nome="option.Nome"
                                    :appTag="option.AppTag" :label="option.Nome">
                                </option> -->
                                <option
                                    :value="option.DevEui" :id="option.id" :nome="option.Nome"
                                    :appTag="option.AppTag" :label="option.Nome">
                                </option>
                            </template>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-end align-items-start mt-3">
                <div class="input-group">
                    <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                        Dal
                    </span>
                    <input type="datetime-local" v-model="dataDA" class="form-control" id="dataDA" placeholder="yyyy-mm-dd" :disabled="codStruttura === null" />
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-end align-items-start mt-3">
                <div class="input-group">
                    <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                        al
                    </span>
                    <input type="datetime-local" v-model="dataA" class="form-control" id="dataA" placeholder="yyyy-mm-dd" :disabled="codStruttura === null"/>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-end align-items-start mt-3">
                <div class="input-group">
                    <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                        Range date
                    </span>
                    <select id="selectDispositivo" class="form-select" v-model="codRange" @change="getDateRange()" :disabled="codStruttura === null">
                        <option value="">Seleziona</option>
                        <option v-for="(option, key) in rangeDate" :value="key" :label="key">
                        </option>
                    </select>
                </div>
            </div>

            <!-- Multiselect valori -->
            <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-end align-items-start mt-3">
                <div class="input-group w-100">

                    <multiselect :dispositivo="dispositivo" @toggleMostraElementi="toggleMostraElementi" :mostra-elementi="mostraElementi" dbid="colonna" :options="valoriDisponibili" option-label="nome" name-select="Valori" name-checkbox="colonna" :gia-selezionati="valoriSelezionati" @aggiorna-selezione-valori="aggiornaSelezioneValori"></multiselect>
                
                </div>
            </div>

            <div class="col-12 col-sm-6 offset-md-4 col-md-4 col-lg-3 offset-lg-1 col-xl-2 offset-xl-2 d-flex justify-content-end align-items-start mt-3">
                <button class="btn btn-primary text-uppercase w-100" type="button" :disabled="codStruttura === null || valoriSelezionati.length == 0"
                    @click="showDati('line')">
                    <div v-if="!aggiornamento">
                        Visualizza
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
    </div>
    <!-- Fine filtri -->

    <!-- Caricamento -->
    <template v-if="caricamento">
        <loader/>
    </template>
    <template v-else>
        <!-- Grafici -->
        <div v-if="dispositivoScelto" class="container-fluid">
            <section class="row" :class="[formNascosto ? 'pb-4' : 'mt-5 py-4']">
                <div class="col-12">
                    <div class="container">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center pt-4">
                                <div class="input-group w-100">
                                    <span class="btn border border-end-0 bg-light d-flex pe-4" style="cursor: auto">
                                        Grafico
                                    </span>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn rounded-start-0 px-5"
                                            :class="[tipologiaVisualizzazioneGrafico == 'line' ? 'btn-primary' : 'btn-outline-primary']"
                                            @click="showDati('line')">Lineare</button>
                                        <button type="button" class="btn px-5"
                                            :class="[tipologiaVisualizzazioneGrafico == 'bar' ? 'btn-primary' : 'btn-outline-primary']"
                                            @click="showDati('bar')">Barra</button>
                                        <button type="button" class="btn px-5"
                                            :class="[tipologiaVisualizzazioneGrafico == 'polar' ? 'btn-primary' : 'btn-outline-primary']"
                                            @click="showDati('polar')">Polare</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <div v-if="dispositivoScelto" class="ps-2">
                                    <button v-if="isFullScreen" type="button" class="btn btn-primary p-2 rounded-circle" @click="graficoChiusuraFullscreen">
                                        <span class="material-icons icon-centered">
                                            close_fullscreen
                                        </span>
                                    </button>
                                </div>
                                <div v-if="dispositivoScelto" class="ps-2">
                                    <button v-if="!isFullScreen" type="button" class="btn btn-primary p-2 rounded-circle" @click="graficoFullscreen">
                                        <span class="material-icons icon-centered">
                                            fullscreen
                                        </span>
                                    </button>
                                </div>
                                <div v-if="dispositivoScelto" class="ps-2">
                                    <button type="button" class="btn btn-primary p-2 rounded-circle" @click="printGrafico">
                                        <span class="material-icons icon-centered">
                                            print
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div id="chartDispositivo" class="mb-5" style="width: 100%; height: 70vh;"></div>
        <div v-if="dispositivoScelto" id="area-grafico" class="bg-light px-2">
            <div v-if="isFullScreen" class="d-flex justify-content-end my-5 pe-5 me-5">
                <button type="button" class="btn btn-primary p-2 rounded-circle" @click="graficoChiusuraFullscreen">
                    <span class="material-icons icon-centered">
                        close_fullscreen
                    </span>
                </button>
                <button type="button" class="btn btn-primary p-2 rounded-circle ms-2" @click="printGrafico">
                    <span class="material-icons icon-centered">
                        print
                    </span>
                </button>
            </div>
            <div class="row pb-5 mb-5" id="printarea">
            </div>
        </div>
        <div v-else class="text-uppercase fw-light fs-1 mt-5 pt-5 text-center">Seleziona un dispositivo</div>
    </template>
</template>

<script>
import { getRequest } from '../../ax-request';
import $ from 'jquery';
import * as echarts from 'echarts';
import { jsPDF } from "jspdf";

import Titolo from '../componenti/Titolo.vue';
import Loader from '../componenti/Loader.vue';
import Asterisco from '../componenti/Asterisco.vue';
import Multiselect from './MultiselectValori.vue';

export default {
components: {
    Titolo,
    Asterisco,
    Multiselect,
    Loader,
},
data() {
    return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        caricamento: false,
        rangeDate: [],
        msgErrore: "",
        categoria: '',
        dispositiviFiltrati: null,
        elementiSelezionati: [],
        giaSelezionati: [],
        elementiSelezionatiMultiselect: [],
        valoriSelezionati: [],
        mostraElementi: false,

        colonneValoriList: [],
        colonneList: [],
        lista: [],
        dataA: "",
        dataDA: "",
        codRange: "",
        strutture: [],
        codStruttura: 0,
        categorieDispositivi: [],
        codCategoriaDisp: 0,
        dispositivi: null,
        dispositivo: "",
        valoriDisponibili: null,
        valore: "",
        isFullScreen: false,

        // Setup
        selectStrutture: null,
        selectCategorieDispositivi: null,
        selectDispositivi: null,

        selectAperto: false,

        aggiornamento: false,
        tipologiaVisualizzazioneGrafico: null,

        dispositivoScelto: null,

        formNascosto: false,
        primaRicerca: false,
        bottoni: [
            {
                'tipo': '!',
                'nome': 'Statistiche',
                'collegamento': this.routeStatistiche,
            },
            {
                'tipo': '!',
                'nome': 'Real time',
                'collegamento': this.routeRealTime,
            },
        ],

    }
},
props: {
    'routeRealTime': {
        required: true,
    },
    'routeStatistiche': {
        required: true,
    },
    'routeDatiStrutture': {
        required: true,
    },
    'routeDatiDispositivi': {
        required: true,
    },
    'routeDatiTipodisp': {
        required: true,
    },
    'routeDatiValoriDisponibili': {
        required: true,
    },
    'routeDatiFiltratiGrafici': {
        required: true,
    },
},
watch: {
    dispositivo() {
        this.valoriSelezionati = null;
        this.valoriSelezionati = [];
        this.mostraElementi = false;
    }
},
mounted() {
    this.setup();
    // this.getDatiPrincipali();

    this.rangeDate = {
        '1 Ora': [1, 'hours'],
        '12 Ore': [12, 'hours'],
        '1 Giorno': [1, 'days'],
        '1 Settimana': [1, 'weeks'],
        '2 Settimane': [2, 'weeks'],
        '1 Mese': [1, 'months'],
        '3 Mesi': [3, 'months'],
        '6 Mesi': [6, 'months'],
        '1 Anno': [1, 'years'],
    };

    // this.isCaricamento = false;
},
methods: {
    toggleMostraElementi(bool) {
        this.mostraElementi = bool;
    },
    async setup() {
        try {
            this.caricamento = true;
    
            // Prendo i dati
            this.selectStrutture = await getRequest(this.routeDatiStrutture, null, null, null);
            this.selectCategorieDispositivi = await getRequest(this.routeDatiTipodisp, null, null, null);
            this.selectDispositivi = await getRequest(this.routeDatiDispositivi, null, null, null);
            this.dispositiviValidiPerSelect();

            this.caricamento = false;
        } catch (e) {
                console.log(e);
        }
    },
    aggiornaSelezioneValori(valori) {
        this.valoriSelezionati = valori;
    },
    dispositiviValidiPerSelect() {
        const struttura = this.codStruttura;
        const tipoDisp = this.codCategoriaDisp;
        const dispositivi = this.selectDispositivi.slice();
        const filtro = dispositivi.filter(dispositivo =>
            (dispositivo.codStruttura == struttura || struttura === 0) &&
            (dispositivo.codTipoDisp === tipoDisp || tipoDisp === 0)
        );
        this.dispositiviFiltrati = filtro;
    },
    updateElementiMultiselect(selectedItems) {
        this.elementiSelezionatiMultiselect = selectedItems;
    },
    //Otteniamo i dati per i dispositivi e per il form
    // async getDatiPrincipali() {  //Dati principali per i filtri dati
    //     try {
    //         let res = await getRequest("/grafici/dati", null, null);

    //         this.strutture = res.strutture;  //importiamo le strutture

    //         this.categorieDispositivi = res.categorieDispositivi;  //importiamo le categorie dispositivi

    //         this.caricamento = false;

    //     }
    //     catch (e) {
    //         console.log(e);
    //     }
    // },
    // toggleCheckbox(item, event) {
    //     try {
    //         // Impedisco la propagazione dell'evento di clic
    //         event.stopPropagation();
    
    //         // Trovo l'indice di item in valoriDisp
    //         const index = this.valoriDisp.indexOf(item);
    
    //         // Se l'elemento è presente in valoriDisp, verifica se è già selezionato
    //         if (index !== -1) {
    //             if (this.lista.includes(item)) {
    //                 // Rimuovi l'elemento dalla lista se è selezionato
    //                 this.lista.splice(this.lista.indexOf(item), 1);
    //             } else {
    //                 // Aggiungi l'elemento alla lista se non è selezionato
    //                 this.lista.push(item);
    //             }
    //         }
    //     } catch (e) {
    //         console.log(e);
    //     }
    // },
    
    // composizione selezione valori
    async getValori() {
        try {
            // const appTag = $("#selectDispositivo").find("option:selected").attr("appTag");
            const select = document.getElementById('selectDispositivo');
            const deveui = select.value;

            this.valoriDisponibili = await getRequest(this.routeDatiValoriDisponibili, {
                'deveui': deveui,
            }, null);


            // this.valoriDisp = res.valoriDisp;

            // this.colonneList = res.colonne;

            // if (!this.lista.includes("Temperatura")) {
            //     this.lista.push("Temperatura");
            // }
        }
        catch (e) {
            console.log(e);
        }
    },
    getRandomColore(index) {
        try {
            var colori = ["#000", "#a6a6a6", "#996633", "#999966", "#996633", "#0000cc", "#7300e6"];
            return colori[index];
        } catch (e) {
                console.log(e);
        }
    },
    // calcola date range
    getDateRange() {
        try {
            if (this.codRange == "") {
                this.dataA = "";
                this.dataDA = "";
            }
            else {
                //data attuale
                this.dataA = moment().format('yyyy-MM-DD HH:mm:ss');
                //data di partenza
                this.dataDA = moment().subtract(this.rangeDate[this.codRange][0], this.rangeDate[this.codRange][1]).format('yyyy-MM-DD HH:mm:ss');
            }
        }
        catch (e) {
            console.log(e);
        }
    },
    // visualizzazione grafico
    async showDati(tipo) {
            const deveui = this.dispositivo;
            const dispositivi = this.selectDispositivi.slice();
            const dispositivo = dispositivi.filter(dispositivo =>
                dispositivo.DevEui == deveui
            )[0];
            const appTag = dispositivo.AppTag;

            this.tipologiaVisualizzazioneGrafico = tipo;
            this.aggiornamento = true;

            let datasets = [];

            // filtri

            let res = await getRequest(this.routeDatiFiltratiGrafici, {
                "tipodisp": dispositivo.tipodispositivo.IdTipo,
                "deveui": deveui,
                "dataDA": this.dataDA,
                "dataA": this.dataA,
            }, null);

            console.log(res);

            let grafico = echarts.init(document.getElementById("chartDispositivo")); // init grafico
            grafico.showLoading(); // caricamento dati

            if (grafico) { grafico.clear(); }

            // if (res.error != undefined) {  //messaggio di errore per l'utente se sbagliasse inserire la data
            //     this.msgErrore = res.error;
            //     $("#modalErrore").modal("show");
            // }
            else {
                // dati
                let dati = [];

                this.lista.forEach(nomeColonna => {
                    res.colonne.forEach((element, key) => {
                        let datiProvvisori = [];
                        if (nomeColonna == this.colonneList[element]) {
                            res.data.Dati.forEach(row => {
                                dati.unshift(row[element]);
                                datiProvvisori.unshift(row[element]);
                            });

                            if (tipo != "polar") {
                                datasets.push({
                                    type: tipo,
                                    name: `Andamento ${res.data.Valori[key]}`,
                                    color: this.getRandomColore(key),
                                    data: datiProvvisori
                                });
                            } else {
                                datasets.push({
                                    coordinateSystem: tipo,
                                    type: "line",
                                    name: `Andamento ${res.data.Valori[key]}`,
                                    color: this.getRandomColore(key),
                                    data: datiProvvisori
                                });
                            }
                        }
                    });
                });

                // dataora
                let dataOra = [];
                res.data.DataOra.forEach(row => {
                    dataOra.push(row);
                });

                // soglie
                let valoreMinimo = [];
                let valoreMassimo = [];

                if (tipo != "polar") {
                    if (res.data.Soglie != undefined) {
                        res.data.Soglie.forEach((row) => {

                            if (row.ValoreMassimo != null) {
                                valoreMassimo.push(row.ValoreMassimo);
                            }
                            if (row.ValoreMinimo != null) {
                                valoreMinimo.push(row.ValoreMinimo);
                            }

                            if (row.AliasMassimo != null && this.lista.includes("Valore")) {
                                datasets.push({
                                    type: 'line',
                                    name: `Soglia ${row.AliasMassimo}`,
                                    color: row.ColoreMassimo,
                                    data: row.ValoreMassimo
                                });
                            }
                            if (this.lista.includes("Valore")) {
                                datasets.push({
                                    type: 'line',
                                    name: `Andamento ${row.AliasMinimo}`,
                                    color: row.ColoreMinimo,
                                    data: row.ValoreMinimo
                                });
                            }
                        });
                    }
                }

                //uniamo gli array in un unico array
                valoreMinimo = valoreMinimo.flat();
                valoreMassimo = valoreMassimo.flat();

                //Riordiniamo gli array per ottenere i valori minori e maggiori
                valoreMassimo.sort((a, b) => b - a);  //da maggiore a minore

                valoreMinimo.sort((a, b) => a - b); //da minore a maggiore

                let sogliaMin = (valoreMinimo.length > 0) ? valoreMinimo[0] : 0;
                let sogliaMax = (valoreMassimo.length > 0) ? valoreMassimo[0] : valoreMinimo[0];

                let nomi = [];

                nomi = datasets.map(element => element.name);

                // gestione visualizzazione/centramento grafico
                let defaultView = this.viewGrafico(dati, sogliaMin, sogliaMax);
                let minVal = defaultView[0]; // minimo
                let maxVal = defaultView[1];  // massimo

                // composizione grafico
                let option = this.optionGrafico(tipo, dataOra.reverse(), datasets, minVal, maxVal, nomi);
                grafico.setOption(option, true); // visualizzazione grafico
                grafico.hideLoading();

                this.aggiornamento = false;
                this.formNascosto = true;
                this.primaRicerca = true;

                this.graficoResponsive(grafico);
            }
        try {
        }
        catch (e) {
            console.log(e);
        }
    },
    // modalità fullscreen del grafico
    graficoFullscreen() {
        try {

            let canvas = document.querySelector("#area-grafico");
            canvas.requestFullscreen();
            this.isFullScreen = true;
        }
        catch (e) {
            console.log(e);
        }
    },
    // chiusura modalità fullscreen del grafico
    graficoChiusuraFullscreen() {
        try {
            document.exitFullscreen();
            this.isFullScreen = false;
        }
        catch (e) {
            console.log(e);
        }
    },
    viewGrafico(valori, sogliaMin, sogliaMax) {
        try {
            const result = [];
            const temp = [];
    
            // inserisco tutti i valori in un array
            valori.forEach(val => {
                if (val !== null) {
                    temp.push(val);
                }
            });
    
            temp.push(sogliaMin, sogliaMax);
    
            const minValore = Math.min.apply(Math, temp);
            const maxValore = Math.max.apply(Math, temp);
    
            // prendo tutti i valori dei dati grafico e soglie
            const arrayBordiGrafico = [minValore, maxValore];
            const k = Math.abs(this.arraySomma(arrayBordiGrafico) / 10); // somma valori 
            result[0] = parseFloat(Math.min.apply(Math, arrayBordiGrafico) - parseFloat(k.toFixed(3))); // minimo
            result[1] = parseFloat(Math.max.apply(Math, arrayBordiGrafico) + parseFloat(k.toFixed(3))); // massimo
    
            return result;
        } catch (e) {
            console.log(e);
        }
    },
    // stampa del grafico
    printGrafico() {
        try {
            const canvas = document.querySelector("#chartDispositivo > div > canvas");
            const canvas_img = canvas.toDataURL("image/png");
            const pdf = new jsPDF("landscape", "px"); // orientation, units
            const width = pdf.internal.pageSize.getWidth();
            pdf.addImage(canvas_img, 'png', 0, 0, width, 400); // image, type, padding left, padding top, width, height
            pdf.autoPrint(); // apertura finestra di stampa
            const blob = pdf.output("bloburl");
            window.open(blob);
        }
        catch (e) {
            console.log(e);
        }
    },
    optionGrafico(tipo, dataOra, datasets, minVal, maxVal, nomi) {
        try {
            let option;
            switch (tipo) {
                case 'polar':
                    option = {
                        animation: false,
                        polar: {
                            center: ['50%', '54%']
                        },
                        legend: {
                            name: nomi
                        },
                        tooltip: {
                            trigger: 'item'
                        },
                        angleAxis: {
                            startAngle: 0
                        },
                        radiusAxis: {
                            min: 0
                        },
                        series: datasets
                    };
                    break;
    
                default:
                    option = {
                        animation: false,
                        tooltip: {
                            trigger: "axis"
                        },
                        legend: {
                            data: nomi
                        },
                        dataZoom: [
                            {
                                type: "slider",
                                xAxisIndex: 0,
                                bottom: 5,
                                fillerColor: "rgba(220, 53, 69, 0.2)",
                                borderColor: "#dc3545",
                                selectedDataBackground: {
                                    lineStyle: {
                                        color: "rgba(0,0,0, 1)",
                                    },
                                    areaStyle: {
                                        color: "rgba(100, 46, 46, 1)"
                                    }
                                },
                                dataBackground: {
                                    areaStyle: {
                                        color: "rgba(211, 28, 28, 1)"
                                    },
                                    lineStyle: {
                                        color: "rgba(0,0,0, 1)",
                                        type: "solid",
                                    }
                                },
                                handleStyle: {
                                    color: "#000000",
                                    shadowBlur: 3,
                                    shadowColor: "rgba(0, 0, 0, 0.6)",
                                    shadowOffsetX: 2,
                                    shadowOffsetY: 2,
    
                                },
                                moveHandleStyle: {
                                    color: "#dc3545",
                                    borderColor: "rgba(0, 0, 0, 1)"
                                },
                                emphasis: {
                                    moveHandleStyle: {
                                        borderColor: "rgba(0, 0, 0, 1)",
                                        color: "rgba(117, 14, 14, 1)"
                                    }
                                }
                            },
                            {
                                type: "inside",
                                yAxisIndex: [0]
                            },
                            {
                                type: "inside",
                                xAxisIndex: [0]
                            }
                        ],
                        xAxis: {
                            data: dataOra,
                        },
                        yAxis: {
                            min: minVal,
                            max: maxVal
                        },
                        series: datasets
                    };
                    break;
            }
    
            return option
        } catch (e) {
                console.log(e);
        }
    },
    graficoResponsive(grafico) {
        try {
            $(window).resize(function () {
                $(grafico).resize();
            });
        } catch (e) {
                console.log(e);
        }
    },
    arraySomma(valori) {
        try {
            let totale = 0;
            for (let index = 0; index < valori.length; index++) {
                totale += Math.abs(valori[index]);
            }
            return totale;
        } catch (e) {
                console.log(e);
        }
    },
},
}
</script>

<style scoped>
.dropdown:focus .btn {
    outline: none;
    box-shadow: none;
    border-color: #ced4da; /* Puoi personalizzare il colore del bordo se desideri */
}
</style>