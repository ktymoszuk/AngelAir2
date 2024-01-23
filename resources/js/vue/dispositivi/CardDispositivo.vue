<template>
    <div class="container-fluid">
        <form :action="routeDettagliDispositivo" method="GET" class="row shadow mt-5 rounded-5 overflow-hidden">
            <div class="col-12 col-sm-4 col-lg-2 col-xxl-2 d-flex align-items-center justify-content-center" data-bs-toggle="collapse" :data-bs-target="'#d-' + chiave" aria-expanded="true">
                <div class="container-allerta">
                    <div class="box-allerta-1 p-1" :class="{ 'color-allerta-1': dispositivo.StatoComunicazioni === 0, 'color-allerta-2': dispositivo.StatoComunicazioni === 1, 'color-allerta-4': dispositivo.StatoComunicazioni === 2 }">
                        <div class="w-75 h-75 rounded-circle d-flex justify-content-center align-items-center" style="z-index: 10; overflow: hidden">
                            <span class="fs-1 text-uppercase" style="margin-top: -8px;">{{ dispositivo.Differenza }}</span>
                                <span class="fs-5 mt-3">minuti fa</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-8 col-lg-5 col-xl-6 p-0 d-flex flex-column justify-content-center text-end text-md-center text-lg-start"
                data-bs-toggle="collapse" :data-bs-target="'#d-' + chiave" aria-expanded="true">
                <h2 class="text-uppercase text-center text-sm-start fw-light mb-0 pt-3">
                    {{ dispositivo.Nome }}
                </h2>
                <h4 v-if="dispositivo.CodiceStazione" class="text-uppercase text-center text-sm-start fw-light mb-0">
                    <span>
                        {{ dispositivo.CodiceStazione }}
                    </span>
                </h4>
                <p class="text-uppercase text-center text-sm-start fw-light mb-0">
                    DevEui: <span class="fw-bold">{{ dispositivo.DevEui }}</span>
                </p>
                <h5 class="text-uppercase text-center text-sm-start fw-light">
                    <span>
                        Ultimo pacchetto <span class="fw-normal">{{ dispositivo.DataUltimoPacchetto }}</span>
                    </span>
                </h5>
            </div>
            <div class="col-12 col-lg-5 col-xl-4 d-flex justify-content-center justify-content-lg-end align-items-center mt-4 mt-lg-0 ps-0 pe-5 pb-4 pb-lg-0">
                <div class="container-fluid">
                    <div class="row ms-4">

                        <!-- MODIFICA DISPOSITIVO -->
                        <div class="col-4 col-lg-12 col-xxl-4 px-1">
    
                            <!-- Bottone -->
                            <button type="submit" class="btn btn-success m-1 w-100" name="deveui" :value="dispositivo.DevEui">DATI</button>
                        </div>

                        <!-- MODIFICA DISPOSITIVO -->
                        <div class="col-4 col-lg-12 col-xxl-4 px-1">
    
                            <!-- Bottone -->
                            <button type="button" class="btn btn-primary m-1 w-100" id="btnModifica" data-bs-toggle="modal"
                                :data-bs-target="'#' + modalIdModifica + chiave">
                                MODIFICA
                            </button>
                            <!-- Modal -->
                            <modifica :modal-id="modalIdModifica" :titolo="'il dispositivo ' + dispositivo.Nome" :route="routeModificaDispositivo" :chiave="chiave" :modifica-id="dispositivo.id">
                                <template v-slot:inputModifica>
                                    <input-modifica :dispositivo="dispositivo" :strutture="strutture" :categorie-dispositivi="categorieDispositivi" :isAbilitato="dispositivo.isAbilitato"></input-modifica>
                                </template>
                            </modifica>
                        </div>
    
                        <!-- ELIMINA DISPOSITIVO -->
                        <div class="col-4 col-lg-12 col-xxl-4 px-1">
    
                            <!-- Bottone -->
                            <button type="button" class="btn btn-danger m-1 w-100" id="btnCancella" data-bs-toggle="modal" :data-bs-target="'#' + modalIdElimina + chiave">
                                CANCELLA
                            </button>
                            <!-- Modal -->
                            <elimina :chiave="chiave" :modal-id="modalIdElimina" :elimina-id="dispositivo.id" cosa-elimino="il dispositivo" :nome="dispositivo.Nome" :descrizione="'con DevEui: ' + dispositivo.DevEui" :route="routeEliminaDispositivo"></elimina>
                        </div>       
                    </div>
                </div>
            </div>
            <div @click="giraFreccia(chiave)" class="col-12 elemento-card-0 cursor-pointer py-2 text-center" :class="{ 'colore-card-4': dispositivo.StatoComunicazioni == 2, 'colore-card-2': dispositivo.StatoComunicazioni == 1, 'colore-card-1': dispositivo.StatoComunicazioni == 0 }" data-bs-toggle="collapse" :data-bs-target="'#d-' + chiave" aria-expanded="true" >
                <span class="material-icons icon-centered center apri-chiudi" :id="'freccia' + chiave">
                    expand_more
                </span>
            </div>
        </form>
        
        <!-- <div v-if="(dispositivo.sogliadispositivo && dispositivo.sogliadispositivo.length > 0) || (dispositivo.comandodispositivo && dispositivo.comandodispositivo.length > 0)" class="container-fluid shadow py-3 px-4 rounded-bottom-5"> -->
        <div class="container-fluid shadow py-3 px-4 rounded-bottom-5">
            <div :id="'d-' + chiave" class="collapse collapse-pagination mt-3 mb-3 mx-2"
                aria-labelledby="headingOne" data-parent="#accordionDispositivi">

                <!-- Tasti dei tab -->
                <nav>
                    <div class="nav nav-tabs rounded rounded-bottom-0" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link nav-tab active" id="nav-soglie-tab" data-bs-toggle="tab"
                            :href="'#nav-soglie-' + dispositivo.id" role="tab" aria-controls="nav-soglie"
                            aria-selected="true">Soglie</a>
                        <a class="nav-item nav-link nav-tab" id="nav-comandi-tab" data-bs-toggle="tab"
                            :href="'#nav-comandi-' + dispositivo.id" role="tab" aria-controls="nav-comandi"
                            aria-selected="false">Comandi</a>
                    </div>
                </nav>

                <!-- Contenuti dei tab -->
                <div class="tab-content" id="nav-tabContent">

                    <!-- Soglie -->
                    <tab-soglie :chiave="chiave" :dispositivo="dispositivo" :route-elimina-soglia-dispositivo="routeEliminaSogliaDispositivo"></tab-soglie>

                    <!-- Comandi -->
                    <tab-comandi :chiave="chiave" :dispositivo="dispositivo" :route-elimina-comando-dispositivo="routeEliminaComandoDispositivo"></tab-comandi>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Modifica from "../componenti/Modifica.vue";
import Elimina from "../componenti/Elimina.vue";
import Dettagli from '../componenti/ModalGenerico.vue';
import InputModifica from "./input/InputModifica.vue";
import TabComandi from "./TabComandi.vue";
import TabSoglie from "./TabSoglie.vue";

export default {
    data() {
        return {
            modalIdModifica: 'modificaDispositivoModal',
            modalIdElimina: 'eliminaDispositivoModal',
            modalIdDettagli: 'dettagliDispositivoModal',
            modalIdEliminaComandoDispositivo: 'eliminaComandoDispositivoModal',
            modalIdEliminaSogliaDispositivo: 'eliminaSogliaDispositivoModal',
        }
    },
    props: {
        'asset': {
            required: true,
        },
        'chiave': {
            required: true,
        },
        'dispositivo': {
            required: true,
        },
        'strutture': {
            required: true,
        },
        'categorieDispositivi': {
            required: true,
        },
        'routeModificaDispositivo': {
            required: true,
        },
        'routeEliminaDispositivo': {
            required: true,
        },
        'routeEliminaComandoDispositivo': {
            required: true,
        },
        'routeEliminaSogliaDispositivo': {
            required: true,
        },
        'routeDettagliDispositivo': {
            required: true,
        },
    },
    methods: {
        giraFreccia(chiave) {

            const div = document.getElementById('freccia' + chiave);
            const classi = div.classList;
            if (classi.contains('apri')) {
                div.classList.remove('apri');
            } else {
                div.classList.add('apri');
            }
        }
    },
    components: {
        Modifica,
        Elimina,
        InputModifica,
        TabComandi,
        TabSoglie,
        Dettagli,
    },
}
</script>

<style scoped>
.circle-span-style {
    color: #000000;
    text-shadow: 2px 2px 0 #FFFFFF, 2px -2px 0 #FFFFFF, -2px 2px 0 #FFFFFF, -2px -2px 0 #FFFFFF, 2px 0px 0 #FFFFFF, 0px 2px 0 #FFFFFF, -2px 0px 0 #FFFFFF, 0px -2px 0 #FFFFFF;
    color: #000000;
    z-index: 99;
    text-align: center;
}

.apri {
    transform: rotate(180deg);
}

.apri-chiudi {
    transition: transform .15s linear;
}

</style>