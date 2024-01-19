<template>
        <!-- Modal -->
        <div class="modal modal- fade" id="scaricaUltimoReport" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="scaricaUltimoReportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div v-if="archivioDati !== null" class="modal-content">
                <div class="d-flex justify-content-between align-items-top px-5">
                    <div class="pt-4">
                        <h1 class="fw-light text-uppercase">
                            Scarica
                        </h1>
                        <h4 class="fw-light">
                            report {{nome}}
                        </h4>
                    </div>
                    <button type="button" class="btn-close mt-5" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form :action="routeScaricaUltimoReport" method="POST" class="px-5">
                    <input type="hidden" name="_token" :value="csrf">
                    <input type="hidden" name="idModifica" id="bottoneScarica" :value="id">
                    <div class="row">
                        <div class="d-flex justify-content-end align-items-center mt-3 mb-5">
                            <span v-for="files in datiUltimoReport">
                                <button type="submit" v-if="files.Formato == 'json'" id="bottoneScarica" name="json" value="1" class="btn btn-primary px-4 ms-3" @click="$emit('aggiornaDatiReport', true)">
                                    JSON
                                </button>
                                <button type="submit" v-if="files.Formato == 'csv'" id="bottoneScarica" name="csv" value="1" class="btn btn-primary px-4 ms-3" @click="$emit('aggiornaDatiReport', true)">
                                    CSV
                                </button>
                                <button type="submit" v-if="files.Formato == 'xml'" id="bottoneScarica" name="xml" value="1" class="btn btn-primary px-4 ms-3" @click="$emit('aggiornaDatiReport', true)">
                                    XML
                                </button>
                            </span>
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
        }
    },
    props: {
        'id': {
            required: false,
        },
        'nome': {
            required: false,
        },
        'formati': {
            required: false,
        },
        'datiUltimoReport': {
            required: false,
        }, 
        'reportDisponibile': {
            required: false,
        },
        'routeScaricaUltimoReport': {
            required: false,
        },
    },
    computed: {
    },
    methods: {
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
    },
}
</script>
<style scoped></style>