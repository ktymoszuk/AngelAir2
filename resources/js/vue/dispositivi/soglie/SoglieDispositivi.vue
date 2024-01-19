<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo titolo="Associa" sottotitolo="soglia ai dispositivi" :bottoni="bottoni"></titolo>

            <div class="row">
                <nav v-if="soglia" class="col-12 col-md-4 d-flex justify-content-end align-items-start mt-3 mt-md-0">
                    <div class="input-group mb-5">
                        <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                            Soglia
                        </span>
                        <select id="sceltaSogliaDispositivo" v-model="soglia" class="form-select" @change="showDati()">
                            <option selected disabled value="">Seleziona</option>
                            <option v-for="option in soglie" :value="option.id" :label="option.Nome"></option>
                        </select>
                    </div>
                </nav>
                <div v-else class="col-12 fw-light fs-1 text-uppercase d-flex justify-content-center mt-5 pt-5">
                    <span>Seleziona una soglia</span>
                </div>
                <div v-if="!soglia" class="col-6 offset-3 fw-light fs-1 text-uppercase d-flex justify-content-center mt-3">
                    <select v-model="soglia" class="form-select border-secondary" @change="showDati()">
                            <option selected disabled value="">Seleziona</option>
                            <option v-for="option in soglie" :value="option.id" :label="option.Nome"></option>
                    </select>
                </div>
            </div>

            <div class="row">
                <form v-if="soglia" :action="routeAssociaSogliaDispositivo" method="POST">
                    <input type="hidden" name="_token" :value="csrf">
                    <navbar-filtro-checkbox
                        :id="soglia"
                        :dati-list="datiList"
                        :dispositivi-check-list="dispositiviCheckList"
                        class="pb-4"
                    ></navbar-filtro-checkbox>
                    <div v-if="datiList && datiList.length == 0" class="mt-5">
                        <h3 class="text-center text-uppercase fs-1 fw-light pt-5">Nessun dispositivo da associare</h3>
                    </div>
                    <ul v-else class="list-group m-0 p-0 mt-5 rounded-4 shadow overflow-hidden">
                        <li class="list-group-item m-0 p-0 border-0" v-for="(item, i) in datiList">
                            <div v-if="item.sogliadispositivo.length < 1" class="row m-0 p-0">
                                <div class="form-check m-0 p-0 ">
                                    <input type="checkbox" name="codDispositivo[]" hidden class="form-check-input" v-model="dispositiviCheckList[i]" :id="'ckDispositivo' + item.DevEui"
                                        :value="item.id" />
                                    <label class="text-center form-check-label galleria-card-0 my-auto w-100 h-100 cursor-pointer px-4 py-2" :class="{'colore-card-1': dispositiviCheckList[i]}" :for="'ckDispositivo' + item.DevEui">
                                        <span class="fs-5">{{ item.Nome }} </span>
                                        <span class="fs-5 fw-light"> (DevEui:
                                            <span>{{ item.DevEui }}</span>
                                        )</span>
                                    </label>
                                </div>
                            </div>
                        </li>
                    </ul>
                </form>
            </div>

        </div>
    </div>
</template>

<script>
import { getRequest } from "../../../ax-request.js";
import Titolo from "../../componenti/Titolo.vue";
import NavbarFiltroCheckbox from "../../componenti/NavbarFiltroCheckbox.vue";
export default {
    data(){
        return {
            isCaricamento: true,
            datiList: null,
            optionList: null,
            dispositiviCheckList: [],
            soglie: null,
            listaIdSoglie: [],
            bottoni: null,

            // filtri
            soglia: "",
            
            // form
            id: "",
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    },
    props: {
        'routeAssociaSogliaDispositivo': {
            required: true,
        },
        'routeDatiSoglieDispositivi': {
            required: true,
        },
        'routeDatiSoglie': {
            required: true,
        },
        'routeDatiDispositivi': {
            required: true,
        },
        'routeDispositivi': {
            required: true,
        },
    },
    components: {
        Titolo,
        NavbarFiltroCheckbox,
    },
    mounted() {
        this.setup();
    },
    methods: {
        async setup() {
            try {
                this.bottoni = [
                    {
                        'tipo': '!',
                        'nome': 'Indietro',
                        'collegamento': this.routeDispositivi,
                    },
                ];
    
                this.soglie = await getRequest(this.routeDatiSoglie, null, null, null);
                this.soglie.forEach(soglia => {
                    this.listaIdSoglie.push(soglia.id);
                });
            } catch (e) {
                console.log(e);
            }
        },
        async showDati() {
            try {
                this.datiList = []; // refresh
                
                const res = await getRequest(this.routeDatiDispositivi, {
                    "soglia": this.soglia
                }, null);
    
                res.forEach(row => {
                    this.datiList.push(row);
                    this.dispositiviCheckList.push(false);
                });
            } catch (e) {
                console.log(e);
            }
        }
    }
}
</script>