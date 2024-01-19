<template>
    <!-- Modal -->
    <div v-if="eliminaId !== null && chiave != null" class="modal modal-lg fade mt-5 pt-4" :id="modalId + chiave" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" :aria-labelledby="modalId + chiave + 'Label'" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="d-flex justify-content-between align-items-center px-5 pt-4">
                    <p class="modal-title fs-4 fw-light" id="deleteModalLabel"><span class="text-danger">Attenzione!</span>
                        Questa operazione è irreversibile!</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center mt-5">
                    <div class="fs-3 fw-light text-center">
                        Sei sicuro di voler eliminare <span v-if="cosaElimino">{{ cosaElimino }}</span>
                    </div>
                    <div class="fs-2 text-uppercase text-center">
                        {{ nome }}<span v-if="!descrizione">?</span>
                    </div>
                    <div v-if="descrizione" class="fs-4 fw-light text-uppercase text-center">
                        {{ descrizione }}?
                    </div>

                </div>
                <div class="d-flex px-5 pb-5 mb-3 mt-4 justify-content-center">
                    <form :action="route" method="POST">
                        <input type="hidden" name="_token" :value="csrf">
                        <slot name="infoEliminazione"></slot>
                        <button type="submit" name="id" :value="eliminaId" class="btn text-uppercase btn-danger px-4 fw-light fs-4">Elimina</button>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
// PROPS NECESSARI
// chiave, modalId, eliminaId, cosaElimino, nome, descrizione, route

export default {
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    },
    props: {
        'chiave': {
            required: false,
        },
        'modalId': {
            required: false,
        },
        'eliminaId': {
            required: false,
        },
        'cosaElimino': {
            required: false,
        },
        'nome': {
            required: false,
        },
        'descrizione': {
            required: false,
        },
        'route': {
            required: false,
        },
    },
}
</script>
<style scoped></style>