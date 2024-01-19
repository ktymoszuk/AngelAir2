<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo titolo="Gestione" sottotitolo="degli utenti"></titolo>

            <!-- Filtri -->
            <nav class="row mb-5">
                <div class="col-12 col-md-5 col-lg-6 col-xl-4">
                    <div class="input-group">
                        <span class="btn border border-end-0 bg-light d-flex" style="cursor: auto">
                            Cerca
                        </span>
                        <input type="text" class="form-control mx-auto" placeholder="per nome" v-model="testo" @keyup="filtra" />
                    </div>
                </div>
            </nav>

            <!-- Caricamento -->
            <template v-if="caricamento">
                <loader/>
            </template>
            <!-- Visualizzo i dati -->
            <template v-else>
                <!-- Lista degli utenti -->
                <template v-if="utenti != null">
                    <section v-if="utenti.length > 0" class="container-fluid">
                        <template v-for="(utente, u) in utentiFiltrati">
                            <card-utente :asset="asset" :utente="utente" :chiave="u" :route-modifica-utente="routeModificaUtente" :route-elimina-utente="routeEliminaUtente"></card-utente>
                        </template>
                    </section>
                    <section v-else>
                        <no-data-found contenuto="Nessun utente trovato"></no-data-found>
                    </section>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
import { getRequest } from "../../ax-request.js";
import Titolo from "../componenti/Titolo.vue";
import Loader from "../componenti/Loader.vue";
import CardUtente from "./CardUtente.vue";
import NoDataFound from "../componenti/NoDataFound.vue";

export default {
    data(){
        return {
            testo: '',
            bottoni: null,

            // getRequest
            utenti: null,
            utentiFiltrati: null,

            caricamento: null,
        }
    },
    props: {
        'routeDatiUtenti': {
            required: true,
        },
        'routeModificaUtente': {
            required: true,
        },
        'routeEliminaUtente': {
            required: true,
        },
        'asset': {
            required: true,
        },
    },
    components: {
        Titolo,
        Loader,
        CardUtente,
        NoDataFound,
    },
    mounted() {
        this.setup();
    },
    methods: {
        async setup() {
            try {
                this.caricamento = true;
    
                // Setup bottoni
                this.bottoni = [
                    {
                        'tipo': '!',
                        'nome': 'Statistiche',
                        'collegamento': this.routeStatistiche,
                    },
                ];
    
                // Prendo i dati degli utenti
                this.utenti = await getRequest(this.routeDatiUtenti, null, null, null);
                this.filtra();
    
                // Fine caricamento
                this.caricamento = false;
            } catch (e) {
                    console.log(e);
            }
        },
        filtra() {
            try {
                const testo = this.testo.toLowerCase().trim();
    
                const utenti = this.utenti.slice();
    
                this.utentiFiltrati = utenti.filter( utente =>
                    utente.name.toLowerCase().includes(testo) || this.testo == ''
                );
            } catch (e) {
                    console.log(e);
            }
        }
    }
}
</script>