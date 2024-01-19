<template>
    <div class="container">

        <!-- Titolo -->
        <titolo v-if="profilo" :titolo="profilo.name" :bottoni="bottoni"></titolo>

        <modifica :profilo="profilo" :route-modifica-profilo="routeModificaProfilo"></modifica>
        <!-- Modal modifica profilo -->
        <modifica v-if="profilo" :chiave="chiave" :modal-id="idModalModifica" :titolo="profilo.name" :route="routeModificaProfilo" :modifica-id="profilo.id">
            <template v-slot:inputModifica>
                <!-- Campi del form da modificare -->
                <input-modifica :profilo="profilo" :chiave="chiave" :modal-password="idModalModificaPassword"></input-modifica>
            </template>
        </modifica>
        <!-- Modal modifica password -->
        <modifica v-if="profilo" :chiave="chiave" :modal-id="idModalModificaPassword" titolo="la tua password" :route="routeModificaPassword" :modifica-id="profilo.id">
            <template v-slot:inputModifica>
                <!-- Campi del form da modificare -->
                <input-modifica-password :profilo="profilo"></input-modifica-password>
            </template>
        </modifica>

        <!-- Caricamento -->
        <template v-if="caricamento">
            <loader/>
        </template>
        
        <!-- Visualizzo i dati -->
        <template v-else>
            <!-- DatiProfilo -->
            <section class="row">
                <div class="col-12 col-md-4">
                    <img v-if="profilo && profilo.FotoProfilo" :src="asset + 'immagini/' + profilo.FotoProfilo" alt="">
                </div>
    
    
                <div class="col-12 col-md-8">
                    <h2 v-if="profilo && profilo.email">
                        <i class="fa-regular fa-envelope me-2" style="width: 40px;"></i>
                        <span class="ms-3 fw-light">
                            {{ profilo.email }}
                        </span>
                    </h2>
                    <h2 v-if="profilo && profilo.Telefono" class="mt-3">
                        <i class="fa-solid fa-phone me-2" style="width: 40px;"></i>
                        <span class="ms-3 fw-light">
                            {{ profilo.Telefono }}
                        </span>
                    </h2>
                </div>
            </section>
        </template>

    </div>
</template>


<script>
import { getRequest } from "../../ax-request";

import Titolo from "../componenti/Titolo.vue";
import Loader from '../componenti/Loader.vue';
import Modifica from "../componenti/Modifica.vue";
import InputModifica from "./input/InputModifica.vue";
import InputModificaPassword from "./input/InputModificaPassword.vue";

export default{
    data() {
        return {
            idModalModifica: 'modificaProfiloModal' + this.chiave,
            idModalModificaPassword: 'modificaPasswordModal',
            profilo: null,
            caricamento: true,
            chiave: 'profilo',
            bottoni: null,
        }
    },
    props:{
        "routeDatiProfilo":{
            required: true,
        },
        "routeModificaProfilo" :{
            required: true,
        },
        "routeModificaPassword" :{
            required: true,
        }, 
        "asset" :{
            required: true,
        }, 
    },
    components: {
        Titolo,
        Loader,
        Modifica,
        InputModifica,
        InputModificaPassword,
    },
    created() {
        this.setup();
    },
    methods: {
        async setup() {
            try {
                this.caricamento = true;
                this.profilo = await getRequest(this.routeDatiProfilo, null, null, null);
    
                this.bottoni = [
                    {
                        'tipo': 'modal',
                        'nome': 'Modifica',
                        'collegamento': this.idModalModifica + this.chiave,
                    },
                ];
            
                this.caricamento = false;
            } catch (e) {
                    console.log(e);
            }
        },
    }
}
</script>
<style scoped>

h5, p {
    margin-bottom: 0px;
}

li {
    list-style: none;
}
</style>