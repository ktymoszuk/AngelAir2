<template>
    <div class="row shadow px-5 py-3 mt-4 rounded-5 ">
        <div v-if="report.Nome != null" class="col-12 col-lg-6 p-0 d-flex flex-column align-items-center align-items-lg-start  mb-0">
            <h2 class="text-uppercase fw-light">{{ report.Nome }}</h2>
            <h5 class="fw-normal text-center"><span>Ogni </span>
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
                <span> dal {{ report.DataOra }}</span>
            </h5>
        </div>

        <div class="col-12 col-lg-6 d-flex justify-content-center justify-content-lg-end px-0 align-items-center mt-3 mt-lg-0">

            <!-- MODIFICA -->
            <!-- Bottone -->
            <button type="button" class="btn btn-primary text-uppercase w-25 py-2" data-bs-toggle="modal" data-bs-target="#modificaReports">
                <span>Modifica</span>
            </button>
            <!-- Modal -->
            
            <!-- SCARICA -->
            <!-- Bottone -->
            <div class="dropdown mx-4 w-25">
                <button class="btn btn-secondary text-uppercase w-100 py-2 dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Scarica
                    <span>
                        <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    </span>
                </button>
                <ul class="dropdown-menu py-0" aria-labelledby="dropdownMenuButton1">
                    <li>
                        <input type="hidden" name="idReports" id="idReports" :value="report.id">
                        <div type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#scaricaUltimoReport">
                            <span>
                                Scarica l'ultimo
                            </span>
                        </div>
                    </li>
                    <li><a class="dropdown-item" :href="'/reports/archivio?report=' + report.id">Archivio</a></li>
                </ul>
            </div>

            <!-- ELIMINA -->
            <!-- Bottone -->
            <button type="button" class="btn btn-danger text-uppercase w-25 py-2" data-bs-toggle="modal" :data-bs-target="'#' + modalIdElimina + chiave">
                <span>Elimina</span>
            </button>
            <!-- Modal -->
            <!-- // chiave, modalId, eliminaId, cosaElimino, nome, descrizione, route -->
            <elimina :chiave="chiave" :modal-id="modalIdElimina" :elimina-id="report.id" cosa-elimino="il report periodico" :nome="report.Nome" :route="routeEliminaReport"></elimina>
        </div>

    </div>

</template>

<script>
import Modifica from '../componenti/Modifica.vue';
import Elimina from '../componenti/Elimina.vue';

export default {
    data() {
        return {
            modalIdElimina: 'eliminaReportPeriodicoModal',
        }
    },
    props: {
        'chiave': {
            required: true,
        },
        'report': {
            required: true,
        },
        'routeEliminaReport': {
            required: true,
        },
    },
    components: {
        Modifica,
        Elimina,
    }
}
</script>