<template>
    <!-- Modal -->
    <div v-if="modalId" class="modal modal-xl fade" :id="modalId" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" :aria-labelledby="modalId + 'Label'" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0">

                <!-- Titolo -->
                <div class="d-flex justify-content-between align-items-start px-5">
                    <div class="pt-4">
                        <h1 class="fw-light text-uppercase m-0">
                            Scegli
                        </h1>
                        <h3 class="fw-light text-uppercase m-0">
                            la modalità di inserimento
                        </h3>
                    </div>
                    <button type="button" class="btn-close mt-4 pt-4 ms-5" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-8 px-0 px-lg-5">
                            <div class="d-flex justify-content-center justify-content-center mt-4">
                                <button type="submit" class="btn btn-primary fw-light text-uppercase px-5 fs-5 mt-5 mt-lg-3 mb-5 me-3" data-bs-toggle="modal" :data-bs-target="'#' + modalIdNuovo">
                                    Singolo
                                </button>
                                <button type="submit" class="btn btn-primary fw-light text-uppercase px-5 fs-5 mt-5 mt-lg-3 mb-5 ms-3" data-bs-toggle="modal" :data-bs-target="'#' + modalIdCsv">
                                    Multiplo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        'modalId': {
            required: false,
        },
        'modalIdNuovo': {
            required: false,
        },
        'modalIdCsv': {
            required: false,
        },
    },
}
</script>