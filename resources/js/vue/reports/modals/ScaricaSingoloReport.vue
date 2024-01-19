<template>
    <!-- Modal -->
    <div class="modal modal-xl fade" id="modalScaricaSingoloReport" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalScaricaSingoloReportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div v-if="datiList !== null" class="modal-content">
                <div class="d-flex justify-content-between align-items-top px-5">
                    <div class="pt-4">
                        <h1 class="fw-light text-uppercase">
                            Scarica
                        </h1>
                        <h3 class="fw-light">
                            un report
                        </h3>
                    </div>
                    <button type="button" class="btn-close mt-5" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form :action="routeScaricaReport" method="POST" class="px-5">
                    <input type="hidden" name="_token" :value="csrf">
                    <div class="row">
                        <div class="col-12 col-md-6 d-flex align-items-center">
                            <div class="input-group mt-3 m-auto">
                                <span class="btn border border-end-0 bg-light" style="cursor: auto">Struttura</span>
                                <select class="form-select" name="Struttura" v-model="filtroStrutturaReport" @change="aggiornaDispositiviReport(filtroStrutturaReport)">
                                    <option v-for="struttura in datiList" :value="struttura.id">{{struttura.Nome}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 d-flex align-items-center">
                            <div class="input-group mt-3 m-auto">
                                <span class="btn border border-end-0 bg-light" style="cursor: auto">Dispositivo</span>
                                <select class="form-select" name="Dispositivo" v-model="filtroDispositivoReport">
                                    <option v-for="(dispositivo, i) in dispositiviAggiornatiReports" :key="i" :value="dispositivo.id">{{dispositivo.Nome}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 d-flex align-items-center">
                            <div class="input-group mt-3 m-auto">
                                <span class="btn border border-end-0 bg-light" style="cursor: auto">Dal</span>
                                <input class="form-control" type="date" name="dataDA" v-model="reportDal">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 d-flex align-items-center">
                            <div class="input-group mt-3 m-auto">
                                <span class="btn border border-end-0 bg-light" style="cursor: auto">Al</span>
                                <input class="form-control" type="date" name="dataA" v-model="reportAl">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-3 mb-5">
                            <button id="bottoneScarica" name="isJSON" value="0" class="btn btn-primary px-4" :disabled="!disabledReports" @click="getDatiReport">
                                JSON
                            </button>
                            <button id="bottoneScarica" name="isCSV" value="1" class="btn btn-primary px-4 mx-5" :disabled="!disabledReports" @click="getDatiReport">
                                CSV
                            </button>
                            <button id="bottoneScarica" name="isXML" value="2" class="btn btn-primary px-4" :disabled="!disabledReports" @click="getDatiReport">
                                XML
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            dispositiviAggiornatiReports: [],
            formatiReports: [],
            filtroStrutturaReport: '',
            filtroDispositivoReport: '',
            reportDal: '',
            reportAl: '',
        }
    },
    props: {
        'routeScaricaReport': {
            required: false,
        },
        'datiList': {
            required: false,
        },
    },
    computed: {
        // Validazione del form con bottone 'disabled'
        disabledReports() {
            if (this.filtroStrutturaReport !== '' && this.filtroDispositivoReport !== '' && this.reportDal !== '' && this.reportAl !== '') {
                return true;
            } else {
                return false;
            }
        },
    },
    methods: {
        aggiornaDispositiviReport(strutturaID) {
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
            const checkJSON = document.getElementById('formatoJSON');
            const checkCSV = document.getElementById('formatoCSV');
            const checkXML = document.getElementById('formatoXML');
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
        getDatiReport() {
            this.datiReport = [];
            let Dispositivo = [];

            // Dati della struttura
            this.datiReport.push({Struttura: this.filtroStrutturaReport.Nome});
            // Dati del dispositivo
            Dispositivo = [];
            Dispositivo.push({id: this.filtroDispositivoReport.id});
            Dispositivo.push({Nome: this.filtroDispositivoReport.Nome});
            Dispositivo.push({Deveui: this.filtroDispositivoReport.Deveui});
            this.datiReport.push({Dispositivo});
            // Date periodo report 'dal - al'
            this.datiReport.push({DataDal: this.reportDal});
            this.datiReport.push({DataAl: this.reportAl});
        },
    },
}
</script>
<style scoped></style>