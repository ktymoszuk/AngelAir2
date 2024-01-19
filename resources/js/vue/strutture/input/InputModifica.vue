<template>
    <div>

        <!-- Nome -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Nome<asterisco/>
                </span>
                <input type="text" name="Nome" :value="struttura.Nome" class="form-control" placeholder="della struttura">
            </div>
        </div>
        <!-- Referente -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">Referentte</span>
                <input type="text" name="Referente" class="form-control" :value="struttura.Referente">
            </div>
        </div>    
        <!-- Indirizzo -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">Indirizzo</span>
                <input type="text" name="Indirizzo" class="form-control" :value="struttura.Indirizzo" placeholder="Indirizzo (Es. Viale Mercato Nuovo, 75, Vicenza)">
            </div>
        </div>    
        <!-- Latitudine -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Latitudine<asterisco/>
                </span>
                <input type="number" step="any" name="Latitudine" class="form-control" placeholder="della struttura" :value="struttura.Latitudine">
            </div>
        </div>
        <!-- Longitudine -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Longitudine<asterisco/>
                </span>
                <input type="number" step="any" name="Longitudine" class="form-control" placeholder="della struttura" :value="struttura.Longitudine">
            </div>
        </div>

        <div class="col-12 mt-4 mb-1">
            <h2 class="fw-light">
                Modifica cartografie
            </h2>
        </div>

        <!-- Cartografia -->
        <div class="col-12">
            <div class="input-group">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">Cartografia</span>
                <select id="selectCartografiaDaModificare" name="Cartografia" class="form-select"  @change="changeCartografia">
                    <option v-for="item in cartografie" :value="item" :selected="item == 'Cartografia'" :class="{'d-none': item == 'Cartografia3'}" :label="item"></option>
                </select>
            </div>
        </div>
        <div class="col-12 mt-lg-0">
            <div class="input-group mt-3">
                <input type="file" name="Immagine" accept="image/*" class="form-control">
            </div>
        </div>
        <div v-if="valoreCartografiaSelezionata" class="col-12 mt-4">
            <h2 class="fw-light mb-4">
                Mappa Attuale
            </h2>
            <div>
                <img :src="asset + 'immagini/mappa/' + valoreCartografiaSelezionata" class="img-thumbnail shadow-lg img-fluid p-0 rounded-5 w-100" >
                <input type="text" name="CartografiaAttuale" :value="cartografiaSelezionata" hidden>
            </div>
        </div>
        <div v-else class="mx-2 mt-5 py-4 rounded-5 shadow-lg">
            <h1 class="text-center text-uppercase fw-light my-5 py-5">Ancora nessuna mappa caricata</h1>
        </div>

        <input type="hidden" name="codAutomazione" :value="struttura.codAutomazione">
        
    </div>
</template>

<script>
import Asterisco from '../../componenti/Asterisco.vue';

export default {
    data() {
        return {
            valoreCartografiaSelezionata: 'Cartografia',
            cartografiaSelezionata: null,
        }
    },
    props: {
        'struttura': {
            required: false,
        },
        'strutture': {
            required: false,
        },
        'asset': {
            required: false,
        },
        'cartografie': {
            required: false,
        },
    },
    components: {
        Asterisco,
    },
    watch: {
        struttura() {
            setTimeout(() => {
                this.changeCartografia();
            }, 1);
        }
    },
    mounted() {
        this.changeCartografia()
    },
    methods: {
        changeCartografia() {
            // Prelevo l'elemento del select dal DOM
            const cartografia = document.getElementById('selectCartografiaDaModificare');
            // Ricavo il valore
            this.cartografiaSelezionata = cartografia.value;
            // Converto l'oggetto in un arra [[chiave, valore]]
            const res = Object.entries(this.struttura);
            // Per ogni elemento cerco la chiave uguale al valore selezionato
            // e una volta trovato prelevo il valore asociao alla chiave
            res.forEach(r => {
                if (r[0] == this.cartografiaSelezionata) {
                    this.valoreCartografiaSelezionata = r[1];
                }
            });
        }
    },

}
</script>