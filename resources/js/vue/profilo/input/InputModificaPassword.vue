<template>
    <div v-if="profilo">
        <input type="hidden" name="id" :value="profilo.id">
        
        <!-- VECCHIA PASSWORD -->
        <h5 class="fw-light text-uppercase">
            Inserisci la password attuale
        </h5>
        <div class="input-group mb-3">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">Attuale<span class="fw-bold text-danger">
                    &ast;</span></span>
            <input class="form-control" type="password" name="old" placeholder="password" required>
        </div>
        <h5 class="fw-light text-uppercase mt-5">
            Inserisci la nuova password e la conferma
        </h5>
        <div class="input-group mb-3">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">Password<span class="fw-bold text-danger">
                    &ast;</span></span>
            <input class="form-control" type="password" name="new" placeholder="nuova" required>
        </div>
        <div class="input-group mb-4">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">Password<span class="fw-bold text-danger">
                    &ast;</span></span>
            <input class="form-control" type="password" name="confirm" placeholder="conferma" required>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        "profilo": {
            required: false,
        },
    },
}
</script>