<template>
    <!-- Modal -->
    <div class="modal modal-lg fade" id="eliminaReports" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="eliminaReportsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div v-if="report !== null" class="modal-content">
                <div class="d-flex justify-content-between align-items-center px-5 pt-4">
                    <p class="modal-title fs-4 fw-light" id="deleteModalLabel"><span class="text-danger">Attenzione!</span>
                        Questa operazione è irreversibile!</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center mt-5">
                    <div class="fs-3 fw-light">
                        Sei sicuro di voler eliminare l'automazione
                    </div>
                    <div class="fs-2 text-uppercase text-center">
                        {{ report.Nome }}
                    </div>
                    <div class="fs-3 fw-light">
                        <span>ogni </span>
                        <span v-if="report.Frequenza > 1">
                            {{report.Frequenza}}
                        </span>
                        <span v-if="report.Frequenza === 1">
                            <span v-if="report.Periodo === 0"> giorno </span>
                            <span v-if="report.Periodo === 1"> settimana </span>
                            <span v-if="report.Periodo === 2"> mese </span>
                        </span>
                        <span v-if="report.Frequenza > 1">
                            <span v-if="report.Periodo === 0"> giorni </span>
                            <span v-if="report.Periodo === 1"> settimane </span>
                            <span v-if="report.Periodo === 2"> mesi </span>
                        </span>
                    </div>
                    <div class="fs-3 fw-light">
                        <span> dal {{ report.DataOra }}</span>                        
                    </div>
                    <div class="fs-2 fw-normal">
                        ?
                    </div>
                </div>
                <div class="d-flex px-5 pb-5 mb-3 mt-4 justify-content-center">
                    <form :action="routeEliminaReports" method="POST">
                        <input type="hidden" name="_token" :value="csrf">
                        <input type="number" hidden name="id" :value="report.id">
                        <button type="submit" class="btn text-uppercase btn-danger px-4 fw-light fs-4">Elimina</button>
                    </form>
                </div>
                
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
        'report': {
            required: false,
        },
        'routeEliminaReports': {
            required: false,
        },
    },
    computed: {
    },
    methods: {
    },
}
</script>
