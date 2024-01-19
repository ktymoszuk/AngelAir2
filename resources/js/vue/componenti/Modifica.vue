<template>
    <div v-if="modalId !== null && chiave !== null" class="modal modal-xl fade text-dark mt-5 pt-4" :id="modalId + chiave" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" :aria-labelledby="modalId + chiave + 'Label'" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="d-flex justify-content-between align-items-top px-5">
                    <div class="pt-4">
                        <h1 class="fw-light text-uppercase">
                            Modifica
                        </h1>
                        <h3 class="fw-light">{{ titolo }}</h3>
                    </div>
                    <button type="button" class="btn-close mt-4 pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-8 px-0 px-lg-5 mt-5">
                        <form :action="route" method="POST" class="px-5 mt-3" enctype="multipart/form-data">
                            <input type="hidden" name="_token" :value="csrf">
                            <div class="row">
                                <slot name="inputModifica"></slot>
                            </div>
                            <div class="modal-body container-fluid px-0">
                                <!-- FINE IMPOSTAZIONI GENERALI -->
                                <div class="col-12 d-flex justify-content-center align-items-center align-items-lg-end my-5">
                                    <button type="submit" class="btn btn-primary fw-light text-uppercase px-5 fs-5" name="id" :value="modificaId">
                                        Aggiorna
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// PROPS CHE MI SERVONO
// 'modalId', 'modificaId', 'titolo', 'route', 'chiave'

export default {
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    },
    props: {
        'modalId': {
            required: false,
        },
        'modificaId': {
            required: false,
        },
        'titolo': {
            required: false,
        },
        'route': {
            required: false,
        },
        'chiave': {
            required: false,
        },
    },
    methods: {
    },
}
</script>
<style scoped></style>