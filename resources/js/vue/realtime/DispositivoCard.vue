<template>
    <div class="row shadow mt-5 rounded-5">
        <div class="col-12 col-sm-4 col-lg-2 col-xxl-2 d-flex align-items-center justify-content-center py-3 position-relative">
            <div class="container-allerta">
                <div class="box-allerta-1 p-1" :class="{ 'color-allerta-2': dispositivo.isAllarme == 1, 'color-allerta-4': dispositivo.isAllarme == 2}">
                    <div class="w-75 h-75 rounded-circle d-flex justify-content-center align-items-center" style="z-index: 10; overflow: hidden">
                        <img :src="asset + 'immagini/' + dispositivo.tipodispositivo.Logo" class="w-100" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-8 col-lg-5 col-xl-6 p-0 d-flex flex-column justify-content-center text-end text-md-center text-lg-start my-3 py-3 pe-5 ps-lg-5">
            <h2 class="text-uppercase fw-light">
                {{ dispositivo.Nome }}
            </h2>
            <p class="text-uppercase m-0">
                DevEui: <span class="fw-bold">{{ dispositivo.DevEui }}</span>
            </p>
            <p v-if="dispositivo.Comunicazione > 24" class="m-0">
                Il dispositivo non comunica da <span class="text-danger">{{ dispositivo.Comunicazione }}</span> ore
            </p>
            <p v-if="dispositivo.ultimoDato" class="m-0">
                Ultima comunicazione: {{ dispositivo.ultimoDato.DataOra }}
            </p>
            <p v-else class="m-0">
                Ancora nessuna comunicazione avvenuta
            </p>
        </div>
        <div class="col-12 col-lg-5 col-xl-4 d-flex justify-content-center justify-content-lg-end align-items-center mt-4 mt-lg-0 ps-0 pe-5 pb-4 pb-lg-0">
            <div class="container-fluid">
                <div class="row ms-4 justify-content-end">

                    <!-- MOSTRA ULTIMO DATO -->
                    <div class="col-8">

                        <!-- Bottone -->
                        <button v-if="dispositivo.ultimoDato" type="button" class="btn btn-primary m-1 w-100 text-uppercase" data-bs-toggle="modal"
                            :data-bs-target="'#' + modalIdUltimoDato + chiave">
                            Ultimo dato
                        </button>

                        <!-- MODAL -->
                        <ultimo-dato :chiave="chiave" :dato="dispositivo.ultimoDato" :modal-id="modalIdUltimoDato"></ultimo-dato>

                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import UltimoDato from './modal/UltimoDato.vue';

export default {
    data() {
        return {
            modalIdUltimoDato: 'ultimoDatoModal',
        }
    },
    props: {
        'chiave': {
            required: true,
        },
        'asset': {
            required: true,
        },
        'dispositivo': {
            required: true,
        },
    },
    components: {
        UltimoDato,
    }
}
</script>