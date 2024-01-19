<template>
    <div :class="'tab-pane fade show active nav-soglie-' + dispositivo.id" :id="'nav-soglie-' + dispositivo.id"
        role="tabpanel" aria-labelledby="nav-soglie-tab">
        <div v-if="dispositivo.sogliadispositivo.length == 0">
            <h2 class="text-center fw-light text-uppercase text-dark pt-4">Nessuna Soglia Associata</h2>
        </div>
        <ul class="list-group" v-else>
            <li class="list-group-item w-auto border-0 rounded-4" v-for="soglia in dispositivo.sogliadispositivo">
                <div class="container">
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h5 class="m-0">{{ soglia.Nome }}</h5>
                        </div>
                        <div class="col-4 my-auto">
                            <label class="form-control text-center">{{ soglia.OperatoreMinimo }}
                                {{ soglia.ValoreMinimo }}</label>
                        </div>
                        <div class="col-4 my-auto">
                            <label class="form-control text-center"
                                v-show="soglia.ValoreMassimo != null">{{ soglia.OperatoreMassimo }}
                                {{ soglia.ValoreMassimo }}</label>
                        </div>

                        <!-- ELIMINA -->
                        <!-- Bottone -->
                        <div class="col-1 m-auto">
                            <div class="d-flex justify-content-end">
                                <span
                                    class="p-3 galleria-card-0 rounded-circle"
                                    :class="'material-icons btnCancellaSogliaDisp' +
                                    dispositivo.id"
                                    id="btnEliminaSoglia" data-bs-toggle="modal"
                                    :data-bs-target="'#' + eliminaModalId + chiave"
                                    style="cursor:pointer; color: red;"
                                    identificativo="id">clear</span>
                            </div>
                        </div>
                        <!-- Modal -->
                        <elimina :chiave="chiave" :modalId="eliminaModalId" :elimina-id="dispositivo.id + '-' + soglia.id" cosa-elimino="l'associazione" :descrizione="dispositivo.Nome + ' - ' + soglia.Nome" :route="routeEliminaSogliaDispositivo"></elimina>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
import Elimina from '../componenti/Elimina.vue';
export default {
    data() {
        return {
            eliminaModalId: 'eliminaAssociazioneSogliaDispositivo',
        }
    },
    props: {
        'chiave': {
            required: true,
        },
        'dispositivo': {
            required: true,
        },
        'routeEliminaSogliaDispositivo': {
            required: true,
        },
    },
    components: {
        Elimina,
    }
}
</script>