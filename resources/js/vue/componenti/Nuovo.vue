<template>
    <!-- Modal -->
    <div v-if="modalId" class="modal modal-xl fade" :id="modalId" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" :aria-labelledby="modalId + 'Label'" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0">

                <!-- Titolo -->
                <div class="d-flex justify-content-between align-items-start px-5">
                    <div class="pt-4">
                        <h1 class="fw-light text-uppercase text-start">
                            Aggiungi
                        </h1>
                        <h3 class="fw-light text-start">{{ titolo }}</h3>
                    </div>
                    <button type="button" class="btn-close mt-4 pt-4 ms-5" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-8 px-0 px-lg-5 mt-5">
                            <form class="mx-auto w-100" :action="route" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="_token" :value="csrf">

                                <slot name="inputAggiunta"></slot>

                                <div class="d-flex justify-content-center justify-content-center mt-5">
                                    <button type="submit" class="btn btn-primary fw-light text-uppercase px-5 fs-5 mt-5 mt-lg-3 mb-5">
                                        Aggiungi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// PROPS NECESSARI:
// 'modalId', 'titolo', 'route'

export default {
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    },
    props: {
        'modalId': {
            required: true,
        },
        'titolo': {
            required: true,
        },
        'route': {
            required: true,
        },
    },
}
</script>

<style scoped></style>