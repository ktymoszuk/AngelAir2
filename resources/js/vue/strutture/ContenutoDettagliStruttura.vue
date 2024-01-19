<template>
    <div class="col-12 mt-4 mb-5 mx-4 px-4">
        <div class="row">
            <div class="col-4 fw-bold">
                Indirizzo
            </div>
            <div class="col-8">
                {{ struttura.Indirizzo }}
            </div>
        </div>
        <div class="row">
            <div class="col-4 fw-bold">
                Nome
            </div>
            <div class="col-8">
                {{ struttura.Nome }}
            </div>
        </div>
        <div class="row">
            <div class="col-4 fw-bold">
                Referente
            </div>
            <div class="col-8">
                {{ struttura.Referente }}
            </div>
        </div>
        <div class="row">
            <div class="col-4 fw-bold">
                Chilometro
            </div>
            <div class="col-8">
                {{ struttura.Km }}
            </div>
        </div>
        <div class="row">
            <div class="col-4 fw-bold">
                Latitudine
            </div>
            <div class="col-8">
                {{ struttura.Latitudine }}
            </div>
        </div>
        <div class="row">
            <div class="col-4 fw-bold">
                Longitudine
            </div>
            <div class="col-8">
                {{ struttura.Longitudine }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        'struttura': {
            required: true,
        }
    },
}
</script>