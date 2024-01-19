<template>
    <div :class="'tab-pane fade nav-comandi-' + dispositivo.id" :id="'nav-comandi-' + dispositivo.id"
        role="tabpanel" aria-labelledby="nav-comandi-tab">
        <div v-if="dispositivo.comandodispositivo.length == 0">
            <h2 class="text-center fw-light text-uppercase text-dark pt-4">Nessun Comando Associato</h2>
        </div>
        <ul class="list-group" v-else>
            <li class="list-group-item w-auto border-0 rounded-4 " v-for="comando in dispositivo.comandodispositivo">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <h5 class="m-0">{{ comando.Nome }}</h5>
                            <div>{{ comando.Descrizione }}</div>
                        </div>
                        <div class="col-8">
                            <span v-if="comando.isAutomatico">
                                Automatico
                            </span>
                            <span v-if="comando.isAutomatico && comando.isManuale"> e </span>
                            <span v-if="comando.isManuale">
                                Manuale
                            </span>
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
                        <elimina :chiave="chiave" :modalId="eliminaModalId" :elimina-id="dispositivo.id + '-' + comando.id" cosa-elimino="l'associazione" :descrizione="dispositivo.Nome + ' - ' + comando.Nome" :route="routeEliminaComandoDispositivo"></elimina>
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
            eliminaModalId: 'eliminaAssociazioneComandoDispositivo',
        }
    },
    props: {
        'chiave': {
            required: true,
        },
        'dispositivo': {
            required: true,
        },
        'routeEliminaComandoDispositivo': {
            required: true,
        },
    },
    components: {
        Elimina,
    }
}
</script>