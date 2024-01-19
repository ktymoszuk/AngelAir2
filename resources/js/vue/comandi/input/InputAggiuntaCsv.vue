<template>
    <div>
        <!-- NOME -->
        <div class="input-group mb-3">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">
                Csv<asterisco/>
            </span>
            <input class="form-control" type="text" name="Nome" placeholder="del comando">
        </div>
    </div>
</template>

<script>
import Asterisco from '../../componenti/Asterisco.vue';
export default {
    components: {
        Asterisco,
    }
}
</script>