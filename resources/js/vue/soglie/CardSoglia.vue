<template>
    <div class="row shadow px-0 p-3 mt-4 rounded-5 px-5">
        <div class="col-12 col-sm-4 col-md-3 col-xl-2 d-flex align-items-center justify-content-center justify-content-sm-start justify-content-xxl-start">
            <div class="container-allerta">
                <div class="box-allerta-1 p-1" :style="{ 'backgroundImage': createBackgroundString(soglia.categoriasoglia.Colore) }">
                    <div class="w-75 h-75 rounded-circle d-flex justify-content-center align-items-center" style="z-index: 10; overflow: hidden">
                        <span class="text-uppercase fs-5">{{ soglia.categoriasoglia.Nome }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-8 col-md-5 col-xl-4 col-xxl-3 p-0 text-sm-end d-flex flex-column justify-content-center justify-content-sm-start justify-content-xl-center text-center text-sm-start my-3">
            <h3 class="text-uppercase fw-light text-md-start m-0">
                {{ soglia.Nome }}
            </h3>
            <p v-if="soglia.Descrizione != ''" class=" fw-light text-md-start m-0 p-0">
                {{ soglia.Descrizione }}
            </p>
        </div>
        <div class="col-12 col-md-4 col-xl-3 col-xxl-3 text-sm-center text-md-end text-xl-start p-0 ps-xxl-5 d-flex flex-column justify-content-center justify-content-md-start justify-content-xl-center text-center text-sm-start my-3">
            <p class="fw-light fs-5 m-0">
                <span class="me-3">{{ soglia.AliasMinimo }}</span>
                <span class="fw-normal">{{ soglia.OperatoreMinimo }} {{ soglia.ValoreMinimo }}</span>
            </p>
            <p class="fw-light fs-5 m-0" v-if="soglia.TipoSoglia == 'Range'">
                <span class="me-3">{{ soglia.AliasMassimo }}</span>
                <span class="fw-normal">{{ soglia.OperatoreMassimo }} {{ soglia.ValoreMassimo }}</span>
            </p>
        </div>
        <div class="col-12 offset-md-2 col-md-8 offset-lg-3 col-lg-8 col-xl-3 offset-xl-0 col-xxl-4 p-0 d-flex justify-content-center justify-content-xl-end align-items-center text-center text-sm-start my-3">
            
            <!-- MODIFICA -->
            <!-- Bottone -->
            <button type="button" class="btn btn-primary text-uppercase ms-2 w-50" id="btnModifica" data-bs-toggle="modal"
                :data-bs-target="'#' + idModalModifica + chiave">
                Modifica
            </button>
            <!-- Modal -->
            <!-- 'modalId', 'modificaId', 'titolo', 'route', 'chiave' -->
            <modifica :modal-id="idModalModifica" :modifica-id="soglia.id" :titolo="'la soglia ' + soglia.Nome" :route="routeModificaSoglia" :chiave="chiave">
                <template v-slot:inputModifica>
                    <input-modifica :route-dati-colonne-associate="routeDatiColonneAssociate" :route-modifica-soglia="routeModificaSoglia" :chiave="chiave" :automazioni="automazioni" :categorie-dispositivi="categorieDispositivi" :categorie-soglie="categorieSoglie" :soglia="soglia"></input-modifica>
                </template>
            </modifica>

            <!-- ELIMINA -->
            <!-- Bottone -->
            <button type="button" class="btn btn-danger ms-2 w-50" id="btnCancella" data-bs-toggle="modal"
                :data-bs-target="'#' + idModalElimina + chiave">
                ELIMINA
            </button>
            <!-- Modal -->
            <!-- chiave, modalId, eliminaId, cosaElimino, nome, descrizione, route -->
            <elimina :chiave="chiave" :modal-id="idModalElimina" cosa-elimino="la soglia" :nome="soglia.Nome" :route="routeEliminaSoglia" :elimina-id="soglia.id"></elimina>
        </div>
    </div>
</template>

<script>
import Modifica from "../componenti/Modifica.vue";
import InputModifica from "./input/InputModifica.vue";
import Elimina from "../componenti/Elimina.vue";

export default {
    props: {
        'chiave': {
            required: true,
        },
        'soglia': {
            required: true,
        },
        'automazioni': {
            required: true,
        },
        'categorieSoglie': {
            required: true,
        },
        'categorieDispositivi': {
            required: true,
        },
        'optionsColonnaAssociata': {
            required: false,
        },
        'idModalModifica': {
            required: true,
        },
        'idModalElimina': {
            required: true,
        },
        'routeModificaSoglia': {
            required: true,
        },
        'routeEliminaSoglia': {
            required: true,
        },
        'routeDatiColonneAssociate': {
            required: true,
        },
    },
    components: {
        Modifica,
        InputModifica,
        Elimina,
    },
    methods: {
        createBackgroundString(colore) {
            return `linear-gradient(40deg, #FFFFFF 0%, ${colore} 100%)`;
        },
    }
}
</script>