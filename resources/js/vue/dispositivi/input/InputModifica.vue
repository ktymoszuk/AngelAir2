<template>
    <div>
        <!-- Nome -->
        <div class="col-12">
            <div class="input-group">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">
                    Targa<asterisco/>
                </span>
                <input type="text" name="Nome" :value="dispositivo.Nome" class="form-control" placeholder="del dispositivo">
            </div>
        </div>
        <!-- Deveui -->
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">Dispositivo</span>
                <input type="text" name="DevEui" class="form-control" placeholder="codice del tracker" :value="dispositivo.DevEui">
            </div>
        </div>
        
        <!-- Abilitato -->
        <div class="mt-4 m-auto">
            <div class="d-flex justify-content-center flex-column flex-lg-row align-items-center">
                <div>
                    <input type="hidden" id="abilitatoCheck"
                    autocomplete="off" :checked="isAbilitato ? 1 : 0">
                    <input type="hidden" name="isAbilitato" :value="isAbilitato">
                    <div class="container-allerta" style="cursor: pointer;"  @click="isAbilitato = !isAbilitato">
                        <div class="box-allerta-1" :class="{
                            'color-allerta-1': isAbilitato, 'color-allerta-4': !isAbilitato}">
                        </div>
                        <span>
                            <span class="fs-5 fw-light" v-if="isAbilitato">Abilitato</span>
                            <span class="fs-5 fw-light" v-else>Disabilitato</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import Asterisco from '../../componenti/Asterisco.vue';
export default {
    props: {
        'isAbilitato': {
            required: false,
        },
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