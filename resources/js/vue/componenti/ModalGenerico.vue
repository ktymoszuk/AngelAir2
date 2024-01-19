<template>
    <div v-if="modalId !== null && chiave !== null" class="modal fade text-dark mt-5 pt-4" :class="'modal' + dimensioneModal" :id="modalId + chiave" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" :aria-labelledby="modalId + chiave + 'Label'" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="d-flex justify-content-between align-items-top px-5">
                    <div class="pt-4">
                        <h1 class="fw-light text-uppercase">
                            {{ titolo }}
                        </h1>
                        <h3 class="fw-light">{{ sottotitolo }}</h3>
                    </div>
                    <button type="button" class="btn-close mt-4 pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="row px-5">
                    <div class="col-12 col-lg-8">
                        <slot name="contenuto"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// PROPS NECESSARI
// modalId, chiave, titolo, sottotitolo, dimensioneModal
export default {
    props: {
        'modalId': {
            required: false,
        },
        'chiave': {
            required: false,
        },
        'titolo': {
            required: false,
        },
        'sottotitolo': {
            required: false,
        },
        'dimensioneModal': {
            required: false,
        },
    },
}
</script>

<style scoped></style>