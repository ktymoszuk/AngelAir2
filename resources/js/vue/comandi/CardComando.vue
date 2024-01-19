<template>
    <div class="row shadow px-5 p-3 mb-4 rounded-5">
        <div class="col-12 col-sm-4 col-lg-3 col-xxl-2 d-flex align-items-center justify-content-center justify-content-sm-start justify-content-xxl-start">
            <div class="container-allerta">
                <div class="box-allerta-1 p-1">
                    <div class="w-75 h-75 rounded-circle d-flex justify-content-center align-items-center" style="z-index: 10; overflow: hidden">
                        <img :src="asset + 'immagini/axatel.png'" class="w-100" />
                    </div>
                </div>
            </div>
        </div>
        <div
            class="col-12 col-sm-8 col-lg-3 col-xxl-4 p-0 d-flex flex-column justify-content-center text-center text-sm-start my-3">
            <h2 class="text-uppercase fw-light">
                {{ comando.Nome }}
            </h2>
            <p class="text-uppercase fw-light m-0">
                Codice comando
                <span class="fw-bold">
                    {{ comando.Codice }}
                </span>
            </p>
        </div>
        <div class="col-12 col-lg-6 col-xxl-6 d-flex justify-content-center justify-content-lg-end align-items-center mt-4 mt-lg-0">

            <!-- MODIFICA -->
            <!-- Bottone -->
            <button type="button" class="btn btn-primary text-uppercase m-1 w-25" id="btnModifica" data-bs-toggle="modal" :data-bs-target="'#' + modalIdModifica + chiave">
                Modifica
            </button>
            <!-- Modal -->
            <modifica :modal-id="modalIdModifica" :modifica-id="comando.id" :titolo="'il comando ' + comando.Nome" :route="routeModificaComando" :chiave="chiave">
                <template v-slot:inputModifica>
                    <input-modifica :comando="comando" :automazioni="automazioni" :tipi-dispositivi="tipiDispositivi"></input-modifica>
                </template>
            </modifica>

            <!-- ELIMINA -->
            <!-- Bottone -->
            <button type="button" class="btn btn-danger text-uppercase m-1 w-25" id="btnCancella" data-bs-toggle="modal" :data-bs-target="'#' + modalIdElimina + chiave">
                Elimina
            </button>
            <!-- Modal -->
            <elimina :chiave="chiave" :modal-id="modalIdElimina" :elimina-id="comando.id" cosa-elimino="il comando" :nome="comando.Nome" :route="routeEliminaComando"></elimina>

            <!-- INVIA -->
            <!-- Bottone -->
            <button type="button" class="btn btn-warning text-uppercase text-white m-1 w-25" data-bs-toggle="modal" :data-bs-target="'#' + modalIdInvio + chiave">
                Invio
            </button>
            <!-- Modal -->
            <invio-comando :chiave="chiave" :comando="comando" :modal-id="modalIdInvio" :dispositivi="dispositivi"></invio-comando>

        </div>
    </div>
</template>

<script>
import Modifica from '../componenti/Modifica.vue';
import Elimina from '../componenti/Elimina.vue';
import InputModifica from './input/InputModifica.vue';
import InvioComando from './modal/InviaComando.vue';

export default {
    data() {
        return {
            modalIdModifica: 'modificaComandoModal',
            modalIdElimina: 'eliminaComandoModal',
            modalIdInvio: 'inviaComandoModal',
        }
    },
    props: {
        'asset': {
            required: true,
        },
        'chiave': {
            required: true,
        },
        'comando': {
            required: true,
        },
        'dispositivi': {
            required: true,
        },
        'tipiDispositivi': {
            required: true,
        },
        'automazioni': {
            required: true,
        },
        'routeModificaComando': {
            required: true,
        },
        'routeEliminaComando': {
            required: true,
        },
    },
    components: {
        Modifica,
        Elimina,
        InputModifica,
        InvioComando,
    },
}
</script>