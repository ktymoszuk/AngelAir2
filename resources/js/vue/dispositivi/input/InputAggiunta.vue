<template>
    <div>
        <div class="input-group mt-3 m-auto">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">
                Targa<asterisco/>
            </span>
            <input type="text" name="Nome" class="form-control" placeholder="del dispositivo">
        </div>
        
        <div class="input-group mt-3 m-auto">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">
                Dispositivo<asterisco/>
            </span>
            <input type="text" name="DevEui" class="form-control" placeholder="codice del tracker">
        </div>
        
        <!-- Categoria dispositivo -->
        <input type="hidden" name="codTipoDisp" value="1">
        <!-- Struttura -->
        <input type="hidden" name="codStruttura" value="1">

    </div>
</template>

<script>
import Asterisco from '../../componenti/Asterisco.vue';
export default {
    props: {
        'dispositivo': {
            required: false,
        },
        'strutture': {
            required: false,
        },
        'categorieDispositivi': {
            required: false,
        },
    },
    components: {
        Asterisco,
    }
}
</script>