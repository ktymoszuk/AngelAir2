<template>
    <!-- Modal -->
    <div class="modal modal-xl fade" id="modificaReports" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modificaReportsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div v-if="automazione !== null" class="modal-content">
                <div class="d-flex justify-content-between align-items-top px-5">
                    <div class="pt-4">
                        <h1 class="fw-light text-uppercase">
                            Modifica
                        </h1>
                        <h3 class="fw-light">
                            automazione report
                        </h3>
                    </div>
                    <button type="button" class="btn-close mt-5" data-bs-dismiss="modal" aria-label="Close" @click="modalAperto = false"></button>
                </div>
                <form v-if="datiList != null && automazione" :action="routeModificaReports" method="POST" class="px-5">
                    <input type="hidden" name="_token" :value="csrf">
                    <div class="modal-body container-fluid px-0">
                        <div class="row pb-5">
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <span class="btn border border-end-0 bg-light" style="cursor: auto">Nome</span>
                                    <input class="form-control" type="text" name="Nome" :value="automazione.Nome">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <span class="btn border border-end-0 bg-light" style="cursor: auto">Struttura</span>
                                    <select v-if="datiList != null" class="form-select" name="Struttura" :value="automazione.codStruttura">
                                        <option v-for="struttura in datiList"  :value="struttura.id">{{struttura.Nome}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <span class="btn border border-end-0 bg-light" style="cursor: auto">Frequenza</span>
                                    <input class="form-control" name="Frequenza" type="number" :value="automazione.Frequenza" placeholder="es. 2" min="1">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <span class="btn border border-end-0 bg-light" style="cursor: auto">Periodo</span>
                                    <select class="form-select" name="Periodo" id="periodo" :value="automazione.Periodo">
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

                                    <input type="checkbox" class="btn-check" id="modificaFormatoJSON" :checked="automazione.isJSON" name="isJSON" autocomplete="off" value="json" @click="getFormatiReports">
                                    <label class="btn btn-outline-primary" for="modificaFormatoJSON">JSON</label>
        
                                    <input type="checkbox" class="btn-check" id="modificaFormatoCSV" :checked="automazione.isCSV" name="isCSV" autocomplete="off" value="csv" @click="getFormatiReports">
                                    <label class="btn btn-outline-primary" for="modificaFormatoCSV">CSV</label>
        
                                    <input type="checkbox" class="btn-check" id="modificaFormatoXML" :checked="automazione.isXML" name="isXML" autocomplete="off" value="xml" @click="getFormatiReports">
                                    <label class="btn btn-outline-primary" for="modificaFormatoXML">XML</label>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 d-flex justify-content-center justify-content-lg-end align-items-lg-center mt-5 mt-lg-3">
                                <button type="submit" class="btn btn-primary text-uppercase px-5 px-lg-4 fs-5" name="reportsID" :value="automazione.id">
                                    Aggiorna
                                </button>
                            </div>
                            <!-- :disabled="disabledModificaReports" -->
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
            dispositiviAggiornatiReport: [],
            formatiReports: [],
            filtroStrutturaReports: '',
            quantitaFrequenzaReports: '',
            selezionePeriodoReports: '',
            modalAperto: true,
        }
    },
    components: {
        Asterisco,
    },
    mounted() {
        this.getFormatiReports();
    },
    props: {
        'routeModificaReports': {
            required: false,
        },
        'datiList': {
            required: false,
        },
        'automazione': {
            required: false,
        },
    },
    computed: {
        // Validazione del form con bottone 'disabled'
        disabledModificaReports() {
            if (this.formatiReports.length > 0) {
                return false;
            } else {
                return true;
            }
        },
    },
    methods: {
        // Funzioni report singolo
        aggiornaDispositiviReport(strutturaID) {
            this.dispositiviAggiornatiReport = [];
            this.datiList.forEach(struttura => {
                if (strutturaID === struttura.id) {
                    struttura.dispositivi.forEach(dispositivo => {
                        this.dispositiviAggiornatiReport.push(dispositivo);
                    });
                }
            });
        },
        getFormatiReports(formato) {
            const checkJSON = document.getElementById('modificaFormatoJSON');
            const checkCSV = document.getElementById('modificaFormatoCSV');
            const checkXML = document.getElementById('modificaFormatoXML');
            const checkFormati = [];
            const formatiSelezionati = [];
            this.formatiReports = [];
            checkFormati.push(checkJSON, checkCSV, checkXML);

            for (let f = 0; f < checkFormati.length; f++) {
                const element = checkFormati[f];
                if (element) {
                    if (element.checked) {
                        formatiSelezionati.push(element);
                    }
                }
            }

            for (let check = 0; check < formatiSelezionati.length; check++) {
                const element = formatiSelezionati[check];
                this.formatiReports.push(element.value);
            }
        },
        updateCheck(formato) {
            const checkbox = document.getElementById('modificaFormato' + formato);
            if (checkbox.value == 0) {
                checkbox.value = 1;
            } else if (checkbox.value == 1) {
                checkbox.value = 0;
            }
        },
    },
}
</script>
