<template>
    <!-- Modal -->
    <div class="modal modal-xl fade" id="modalCreaAutomazione" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalCreaAutomazioneLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div v-if="datiList !== null" class="modal-content">
                <div class="d-flex justify-content-between align-items-top px-5">
                    <div class="pt-4">
                        <h1 class="fw-light text-uppercase">
                            Crea
                        </h1>
                        <h3 class="fw-light">
                            nuova automazione report
                        </h3>
                    </div>
                    <button type="button" class="btn-close mt-5" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form :action="routeAutomazione" method="POST" class="px-5">
                    <input type="hidden" name="_token" :value="csrf">
                    <div class="modal-body container-fluid">
                        <div class="row pb-5">
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <span class="btn border border-end-0 bg-light" style="cursor: auto">Nome</span>
                                    <input class="form-control" name="Nome" type="text" maxlength="50" placeholder="dell'automazione del report">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <span class="btn border border-end-0 bg-light" style="cursor: auto">
                                        Struttura<asterisco/>
                                    </span>
                                    <select class="form-select" name="Struttura" id="strutture" v-model="filtroStrutturaReports" @change="aggiornaDispositiviReports(filtroStrutturaReports)">
                                        <option v-for="struttura in datiList" :value="struttura.id">{{struttura.Nome}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="input-group mt-3">
                                    <span class="btn border border-end-0 bg-light" style="cursor: auto">Dal</span>
                                    <input type="date" name="inizioAutomazioneReports" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="input-group mt-3">
                                    <span class="btn border border-end-0 bg-light" style="cursor: auto">
                                        Frequenza<asterisco/>
                                    </span>
                                    <input class="form-control" name="Frequenza" type="number" v-model="quantitaFrequenzaReports" placeholder="es. 2" min="1">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="input-group mt-3">
                                    <span class="btn border border-end-0 bg-light" style="cursor: auto">
                                        Periodo<asterisco/>
                                    </span>
                                    <select class="form-select" name="Periodo" id="periodo" v-model="selezionePeriodoReports">
                                        <option value="0">Giorni</option>
                                        <option value="1">Settimane</option>
                                        <option value="2">Mesi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 mt-3">
                                <div class="btn-group w-100" role="group" aria-label="Basic checkbox toggle button group">
                                    <span class="btn border border-end-0 bg-light text-start" style="cursor: auto">
                                        Formato<asterisco/>
                                    </span>

                                    <input id="formatoJSONcrea" type="checkbox" class="btn-check" autocomplete="off" name="isJSON" value="0">
                                    <label class="btn btn-outline-primary" for="formatoJSONcrea" @click="getFormatiReport">JSON</label>

                                    <input id="formatoCSVcrea" type="checkbox" class="btn-check" autocomplete="off" name="isCSV" value="1">
                                    <label class="btn btn-outline-primary border-start-0 border-end-0" for="formatoCSVcrea" @click="getFormatiReport">CSV</label>

                                    <input id="formatoXMLcrea" type="checkbox" class="btn-check" autocomplete="off" name="isXML" value="2">
                                    <label class="btn btn-outline-primary" for="formatoXMLcrea" @click="getFormatiReport">XML</label>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 d-flex justify-content-center justify-content-lg-end mt-5 mt-lg-3">
                                <button :disabled="!disabledReports" type="submit" class="btn btn-primary text-uppercase px-5 px-lg-4 fs-5">
                                    Crea
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Asterisco from '../../componenti/Asterisco.vue';

export default {
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            dispositiviAggiornatiReports: [],
            formatiReports: [],
            filtroStrutturaReports: '',
            quantitaFrequenzaReports: '',
            selezionePeriodoReports: '',
        }
    },
    props: {
        'routeAutomazione': {
            required: false,
        },
        'datiList': {
            required: false,
        },
    },
    components: {
        Asterisco,
    },
    computed: {
        // Validazione del form con bottone 'disabled'
        disabledReports() {
            if (this.filtroStrutturaReports !== '' && this.quantitaFrequenzaReports !== '' && this.selezionePeriodoReports !== '' && this.formatiReports.length !== 0) {
                return true;
            } else {
                return false;
            }
        },
    },
    methods: {
        aggiornaDispositiviReports(strutturaID) {
            this.dispositiviAggiornatiReports = [];
            this.datiList.forEach(struttura => {
                if (strutturaID === struttura.id) {
                    struttura.dispositivi.forEach(dispositivo => {
                        this.dispositiviAggiornatiReports.push(dispositivo);
                    });
                }
            });
        },
        getFormatiReport(formato) {
            // 0 = JSON
            // 1 = CSV
            // 2 = XML
            const checkJSON = document.getElementById('formatoJSONcrea');
            const checkCSV = document.getElementById('formatoCSVcrea');
            const checkXML = document.getElementById('formatoXMLcrea');
            const checkFormati = [];
            const formatiSelezionati = [];
            this.formatiReports = [];
            checkFormati.push(checkJSON, checkCSV, checkXML)

            for (let f = 0; f < checkFormati.length; f++) {
                if (checkFormati[f].checked) {
                    formatiSelezionati.push(checkFormati[f])
                }
            }
            for (let check = 0; check < formatiSelezionati.length; check++) {
                const element = formatiSelezionati[check];
                this.formatiReports.push(element.value);
            }
        },
    },
}
</script>
<style scoped></style>