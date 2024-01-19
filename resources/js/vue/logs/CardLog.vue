<template>
    <div class="row mx-0">
        <div class="col-12 alert mb-1" :class="{
            'alert-danger': log.Codice == 'ERROR', 'alert-success': log.Codice == 'SUCCESS'
        }">
            <div class="row px-3">
                <div class="col-12 col-md-4 col-lg-4 col-xl-2 fw-bold">
                    <span class="fw-normal">{{ log.DataOra }}</span>
                </div>
                <div class="col-12 col-md-7 col-lg-8 col-xl-4 fw-bold">
                    <span class="fw-normal">
                        Utente: <span class="fw-bold"> {{ log.utente.email }}</span>
                    </span>
                </div>
                <div class="col-12 col-lg-12 col-xl-6 fw-bold">
                    <span class="me-2">Log:</span>
                    <span class="fw-normal">{{ log.Messaggio }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        'log': {
            required: true,
        },
    }
}
</script>