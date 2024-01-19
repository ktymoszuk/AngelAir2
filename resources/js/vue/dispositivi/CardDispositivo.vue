<template>
    <div class="container-fluid">
        <div class="row shadow mt-5 rounded-5"
        :class="{ 'galleria-card-1': dispositivo.isAllarme, 'galleria-card-2': !dispositivo.isAllarme }"
        >
            <div class="col-12 col-sm-4 col-lg-2 col-xxl-2 d-flex align-items-center justify-content-center py-3 position-relative"
                data-bs-toggle="collapse" :data-bs-target="'#d-' + chiave" aria-expanded="true" style="cursor: pointer;">
                <div class="container-allerta">
                    <div class="box-allerta-1 p-1" :class="dispositivo.isAbilitato ? 'color-allerta-1' : 'color-allerta-4'">
                        <div class="w-75 h-75 rounded-circle d-flex justify-content-center align-items-center" style="z-index: 10; overflow: hidden">
                            <img v-if="dispositivo.tipodispositivo.Logo" :src="asset + 'immagini/' + dispositivo.tipodispositivo.Logo" class="w-100" />
                            <img v-else :src="asset + 'immagini/axatel.png'" class="w-100" />
                        </div>
                    </div>
                </div>
                <span class="position-absolute fs-5 fw-light text-uppercase mb-2 mb-md-3 mb-lg-4 circle-span-style" style="top: 50%; left: 50%; transform: translateX(-50%); margin-top: 25px;">
                    <span v-if="dispositivo.isAbilitato">Abilitato</span>
                    <span v-else>Disabilitato</span>
                </span>
            </div>
            <div class="col-12 col-sm-8 col-lg-5 col-xl-6 p-0 d-flex flex-column justify-content-center text-end text-md-center text-lg-start py-3 pe-5 ps-lg-5"
                data-bs-toggle="collapse" :data-bs-target="'#d-' + chiave" aria-expanded="true" style="cursor: pointer;">
                <h2 class="text-uppercase fw-light pt-3">
                    {{ dispositivo.Nome }}
                </h2>
                <h3 v-if="dispositivo.struttura" class="fw-light">
                    {{ dispositivo.struttura.Nome }}
                </h3>
                <p class="text-uppercase m-0 pb-3">
                    DevEui: <span class="fw-bold">{{ dispositivo.DevEui }}</span>
                </p>
            </div>
            <div class="col-12 col-lg-5 col-xl-4 d-flex justify-content-center justify-content-lg-end align-items-center mt-4 mt-lg-0 ps-0 pe-5 pb-4 pb-lg-0">
                <div class="container-fluid">
                    <div class="row ms-4">
    
                        <!-- MODIFICA DISPOSITIVO -->
                        <div class="col-6">
    
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
                        <div class="col-6">
    
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
        </div>
        
        <div v-if="(dispositivo.sogliadispositivo && dispositivo.sogliadispositivo.length > 0) || (dispositivo.comandodispositivo && dispositivo.comandodispositivo.length > 0)" class="container-fluid shadow py-3 px-4 rounded-bottom-5">
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
import InputModifica from "./input/InputModifica.vue";
import TabComandi from "./TabComandi.vue";
import TabSoglie from "./TabSoglie.vue";

export default {
    data() {
        return {
            modalIdModifica: 'modificaDispositivoModal',
            modalIdElimina: 'eliminaDispositivoModal',
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
    },
    components: {
        Modifica,
        Elimina,
        InputModifica,
        TabComandi,
        TabSoglie,
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
</style>