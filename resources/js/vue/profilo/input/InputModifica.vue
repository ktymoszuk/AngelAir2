<template>
    <div v-if="profilo">
        <!-- NOME -->
        <div class="input-group mb-3">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">
                Nome<asterisco/>
            </span>
            <input class="form-control" type="text" name="name" :value="profilo.name" placeholder="inserisci il tuo Nome">
            <input type="hidden" name="id" :value="profilo.id">
        </div>

        <a class="btn btn-outline-primary w-100 text-uppercase" data-bs-toggle="modal" :data-bs-target="'#' + modalPassword + chiave">
            Modifica password
        </a>

    </div>
</template>

<script>
import Asterisco from '../../componenti/Asterisco.vue';

export default {
    props: {
        "profilo": {
            required: false,
        },
        "modalPassword": {
            required: false,
        },
        "chiave": {
            required: false,
        },
    },
    components: {
        Asterisco,
    }
}
</script>