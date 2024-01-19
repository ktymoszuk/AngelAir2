<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo titolo="Statistiche" sottotitolo="dei dati" :bottoni="bottoni"></titolo>
            
            <!-- Caricamento -->
            <template v-if="caricamento">
                <loader/>
            </template>
            <!-- Contenuto pagina -->
            <template v-else>
                <div class="row">
                    <div class="col-12 px-0">
                        <h1>
                            Contenuto pagina
                        </h1>
                    </div>
                </div>
            </template>
            
        </div>
    </div>
</template>

<script>
// import { getRequest } from "../../ax-request";
import Titolo from "../componenti/Titolo.vue";
import Loader from '../componenti/Loader.vue';

export default {
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            bottoni: [
                {
                    'tipo': '!',
                    'nome': 'Grafici',
                    'collegamento': this.routeGrafici,
                },
                {
                    'tipo': '!',
                    'nome': 'Real time',
                    'collegamento': this.routeRealTime,
                },
            ],
        }
    },
    props: {
        'routeGrafici': {
            required: true,
        },
        'routeRealTime': {
            required: true,
        },
    },
    components: {
        Titolo,
        Loader,
    },
    watch: {
    },
    mounted() {
        this.setup();
    },
    created() {
    },
    methods: {
        setup() {
            try {
                this.caricamento = true;
    
                // Prendo i dati principali
    
                this.caricamento = false;
            } catch (e) {
                console.log(e);
            }
        }
    }
}
</script>
