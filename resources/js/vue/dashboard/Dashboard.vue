<template>
    <div>
        <div class="container">

            <!-- Titolo -->
            <titolo titolo="Dashboard" sottotitolo="dell'applicativo"></titolo>

            <template v-if="caricamento">
                <loader/>
            </template>
            <template v-else>
                <!-- Contenuto pagina -->
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
import { getRequest } from '../../ax-request.js';
import Titolo from "../componenti/Titolo.vue";
import Loader from "../componenti/Loader.vue";

export default {
    data() {
        return {
            caricamento: null,
        }
    },
    props: {
        // 'route': {
        //     required: true,
        // },
    },
    watch: {
    },
    components: {
        Titolo,
        Loader,
    },
    mounted() {
    },
    created() {
    },
    methods: {
        setup() {
            try {
                this.caricamento = true;
    
                // Operazioni setup
    
                this.caricamento = false;
            } catch (e) {
                console.log(e);
            }
        },
    }
}
</script>
