<template>
<div class="row shadow px-5 py-3 mt-5 rounded-5" :class="{ 'galleria-card-2': struttura.stato === 0, 'galleria-card-4': struttura.stato === 1 }">
    <div class="col-12 col-md-6 col-lg-8 p-0 d-flex justify-content-center justify-content-md-start mt-3">
        
        <!-- DETTAGLI STRUTTURA -->
        <!-- Bottone -->
        <button class="btn btn-success p-0 me-3 rounded-circle fw-bold fs-4 d-flex justify-content-center align-items-center" style="width:38px; height:38px;" data-bs-toggle="modal" :data-bs-target="'#' + idModalDettagli + chiave">
            <i>i</i>
        </button>
        <!-- Modal -->
        <dettagli-struttura dimensione-modal="md" :modal-id="idModalDettagli" :chiave="chiave" titolo="Dettagli" :sottotitolo="struttura.Nome">
            <template v-slot:contenuto>
                <contenuto-dettagli-struttura :struttura="struttura"></contenuto-dettagli-struttura>
            </template>
        </dettagli-struttura>
        
        <h2 class="text-uppercase fw-light">
            {{ struttura.Nome }}
        </h2>
    </div>
    <div class="col-12 col-md-6 col-lg-4 p-0 d-flex justify-content-center justify-content-md-end align-items-center mt-3">

        <!-- MODIFICA -->
        <!-- Bottone -->
        <button class="btn btn-primary px-5 px-md-3 text-light text-uppercase w-100 me-1 me-lg-1 mt-md-0" data-bs-toggle="modal" :data-bs-target="'#' + idModalModifica + chiave">
            Modifica
        </button>
        <!-- Modal -->
        <modifica v-if="struttura" :modal-id="idModalModifica" :modifica-id="struttura.id" :titolo="'la struttura ' + struttura.Nome" :route="routeModificaStruttura" :chiave="chiave">
            <template v-slot:inputModifica>
                <input-modifica :asset="asset" :struttura="struttura" :strutture="strutture" :cartografie="cartografie"></input-modifica>
            </template>
        </modifica>
        
        <!-- ELIMINA -->
        <!-- Bottone -->
        <button class="btn btn-danger px-5 px-md-3 text-light text-uppercase w-100 ms-1 mt-md-0 mt-lg-0 ms-md-1" data-bs-toggle="modal" :data-bs-target="'#' + idModalElimina + chiave">
            Elimina
        </button>
        <elimina :chiave="chiave" :modal-id="idModalElimina" :elimina-id="struttura.id" cosa-elimino="la struttura" :nome="struttura.Nome" :route="routeEliminaStruttura"></elimina>
        <!-- Modal -->
        
    </div>
    <div class="col-12 col-md-6 col-lg-8 p-0 d-flex flex-column justify-content-center text-center text-md-start mb-3">
        <p v-if="struttura.Indirizzo" class="text-uppercase m-0">
            {{ struttura.Indirizzo }}
        </p>
        <p v-if="struttura.Referente" class="text-uppercase m-0 fw-bold mt-1">
            <span class="text-capitalize">Referente</span>: <span class="fw-normal text-capitalize">{{ struttura.Referente }}</span>
        </p>
    </div>
</div>
</template>

<script>
import Modifica from '../componenti/Modifica.vue';
import Elimina from '../componenti/Elimina.vue';
import InputModifica from './input/InputModifica.vue';
import DettagliStruttura from '../componenti/ModalGenerico.vue';
import ContenutoDettagliStruttura from './ContenutoDettagliStruttura.vue';

export default {
    data() {
        return {
            idModalElimina: 'eliminaStrutturaModal',
            idModalModifica: 'modificaStrutturaModal',
            idModalDettagli: 'dettagliStrutturaModal',
        }
    },
    props: {
        'asset': {
            required: true,
        },
        'chiave': {
            required: true,
        },
        'struttura': {
            required: true,
        },
        'strutture': {
            required: true,
        },
        'cartografie': {
            required: true,
        },
        'routeEliminaStruttura': {
            required: true,
        },
        'routeModificaStruttura': {
            required: true,
        },
    },
    components: {
        Modifica,
        InputModifica,
        Elimina,
        DettagliStruttura,
        ContenutoDettagliStruttura,
    }
}
</script>