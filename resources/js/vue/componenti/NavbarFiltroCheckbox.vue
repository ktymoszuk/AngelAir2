<template>
    <div class="container-fluid px-0">
        <div class="row">
            <div class="col-12 col-md-4">
                <button type="button" class="btn btn-outline-success w-100" @click="ckStato(true)">SELEZIONA TUTTI</button>
            </div>
            <div class="col-12 col-md-4 mt-1 mt-md-0">
                <button type="button" class="btn btn-outline-danger w-100" @click="ckStato(false)">DESELEZIONA TUTTI</button>
            </div>
            <div class="col-12 col-md-4 mt-1 mt-md-0">
                <button type="submit" name="id" class="btn btn-primary w-100" :value="id">AGGIUNGI</button>
            </div>
        </div>
    </div>
</template>

<script>
// import { getRequest } from '../ax-request.vue';

export default {
    props: {
        'id': {
            required: true,
        },
        'datiList': {
            required: true,
        },
        'dispositiviCheckList': {
            required: true,
        },
    },
    methods: {
        // filtro checkbox
        ckStato(stato) {
            for (let i = 0; i < this.datiList.length; i++) {
                this.dispositiviCheckList[i] = stato;
            }
        },
    },
}
</script>
