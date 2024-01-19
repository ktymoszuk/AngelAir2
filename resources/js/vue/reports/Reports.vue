<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo titolo="Elenco" sottotitolo="dellle automazioni dei report"></titolo>
            
            <form :action="routeScaricaReport" method="POST" class="row">
                <input type="hidden" name="_token" :value="csrf">
                <div class="col-12 col-md-6 d-flex align-items-center">
                    <div class="input-group mt-3 m-auto">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">
                            Struttura<asterisco/>
                        </span>
                        <select :disabled="!dispositivi" class="form-select" name="Struttura" v-model="struttura" @change="aggiornaDispositivi()">
                            <option value="">Seleziona</option>
                            <option v-for="struttura in strutture" :value="struttura.id">{{struttura.Nome}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6 d-flex align-items-center">
                    <div class="input-group mt-3 m-auto">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">
                            Dispositivo<asterisco/>
                        </span>
                        <select :disabled="!dispositiviDellaStruttura" class="form-select disabled" name="Dispositivo" v-model="dispositivo">
                            <option v-for="(dispositivo, d) in dispositiviDellaStruttura" :key="d" :value="dispositivo.DevEui">{{dispositivo.Nome}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6 d-flex align-items-center">
                    <div class="input-group mt-3 m-auto">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">
                            Dal<asterisco/>
                        </span>
                        <input class="form-control" type="date" name="dataDA" v-model="dal" @change="controllaDate">
                    </div>
                </div>
                <div class="col-12 col-md-6 d-flex align-items-center">
                    <div class="input-group mt-3 m-auto">
                        <span class="btn border border-end-0 bg-light" style="cursor: auto">
                            Al<asterisco/>
                        </span>
                        <input class="form-control" type="date" name="dataA" v-model="al" @change="controllaDate">
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3 mb-5">
                    <button id="bottoneScarica" name="isJSON" value="0" class="btn btn-primary px-4" :disabled="(!disabledReports && !messaggioDal && !messaggioAl) || !dateValide" @click="getDatiReport">
                        JSON {{ messaggioDateNonValide }}
                    </button>
                    <button id="bottoneScarica" name="isCSV" value="1" class="btn btn-primary px-4 mx-5" :disabled="(!disabledReports && !messaggioDal && !messaggioAl) || !dateValide" @click="getDatiReport">
                        CSV
                    </button>
                    <button id="bottoneScarica" name="isXML" value="2" class="btn btn-primary px-4" :disabled="(!disabledReports && !messaggioDal && !messaggioAl) || !dateValide" @click="getDatiReport">
                        XML
                    </button>
                </div>
                <div id="alertDataReport" class="col-12 center">
                    <div v-if="messaggioDal || messaggioAl || messaggioDateNonValide === false" class="rounded-4 shadow px-5 py-3">
                        <h5 class="text-danger">Attenzione!</h5>
                        <span v-if="!messaggioDateNonValide">
                            La data di inizio non può essere superiore alla data di fine
                        </span>
                        <span v-if="messaggioDal && messaggioAl">
                            Le date non possono essere superiori alla data di oggi
                        </span>
                        <span v-else>
                            <span v-if="messaggioDal">
                                La data di inizio non può essere superiore alla data di oggi
                            </span>
                            <span v-if="messaggioAl">
                                La data di fine non può essere superiore alla data di oggi
                            </span>
                        </span>
                    </div>
                </div>
            </form>

        </div>
    </div>    
</template>
<script>
import { getRequest } from '../../ax-request.js';
import Titolo from '../componenti/Titolo.vue';
import Loader from '../componenti/Loader.vue';
import Nuovo from '../componenti/Nuovo.vue';
import Asterisco from '../componenti/Asterisco.vue';
import moment from 'moment';

export default {
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            idModalScarica: 'scaricaSingoloReportModal',
            nomiReportUnici: [],
            adesso: null,

            strutture: null,
            dispositivi: null,
            struttura: '',
            dispositivo: '',
            dal: '',
            al: '',

            messaggioDal: '',
            messaggioAl: '',
            dateValide: '',
            dispositiviDellaStruttura: null,
            messaggioDateNonValide: null,

            caricamento: null,
        }
    },
    props: {
        'routeScaricaReport': {
            required: false,
        },
        'routeDatiStrutture': {
            required: true,
        },
        'routeDatiDispositivi': {
            required: true,
        },
        'ruolo': {
            required: true,
        },
    },
    components: {
        Titolo,
        Nuovo,
        Asterisco,
    },
    created() {
        this.setup();
    },
    computed: {
        disabledReports() {
            if (this.struttura !== '' && this.dispositivo !== '' && this.dal !== '' && this.al !== '' && this.dal < this.al) {
                return true;
            } else {
                return false;
            }
        },
        controllaDate() {
            this.DataDa = new Date(this.dal);
            this.DataA = new Date(this.al);
            const ora = this.adesso;
            const da = this.DataDa;
            const a = this.DataA;

            if (da < ora && a < ora && da < a) {
                this.dateValide = true;
            } else {
                this.dateValide = false;
            }

            if (da == 'Invalid Date' || a == 'Invalid Date') {
                this.messaggioDateNonValide = true;
            } else {
                if (da < a) {
                    this.messaggioDateNonValide = true;
                } else {
                    this.messaggioDateNonValide = false;
                }
            }

            let messaggioDal;
            let messaggioAl = '';
            
            if ((this.DataDa > this.adesso) && this.dal != '') {
                messaggioDal = 'inizio';
            } else {
                messaggioDal = '';
            }

            if ((this.DataA > this.adesso) && this.al != '') {
                messaggioAl = 'fine';
            } else {
                messaggioAl = '';
            }


            this.messaggioAl = messaggioAl;
            this.messaggioDal = messaggioDal;
        }
    },
    watch: {
    },
    methods: {
        async setup() {
            try {
                this.caricamento = true;
                this.strutture = await getRequest(this.routeDatiStrutture, null, null, null);
                this.dispositivi = await getRequest(this.routeDatiDispositivi, null, null, null);
                this.adesso = new Date(Date.now());
                this.caricamento = false;
            } catch (e) {
                    console.log(e);
            }
        },
        aggiornaDispositivi() {
            try {
                const dispositivi = this.dispositivi.slice();
                const filtro = dispositivi.filter(dispositivo =>
                dispositivo.codStruttura == this.struttura
                );
                this.dispositiviDellaStruttura = filtro;
            } catch (e) {
                    console.log(e);
            }
        }
    },
}
</script>
<style scoped></style>