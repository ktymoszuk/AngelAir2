<template>
    <div v-if="modalId !== null && chiave !== null" class="modal modal-lg fade text-dark" :id="modalId + chiave" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" :aria-labelledby="modalId + chiave + 'Label'" aria-hidden="true">
        <div v-if="dato" class="modal-dialog">
            <div class="modal-content">
                <div class="d-flex justify-content-between align-items-top px-5">
                    <div class="pt-4">
                        <h1 class="fw-light text-uppercase">
                            Ultimo dato
                        </h1>
                        <h3 class="fw-light">del dispositivo {{ dato.DevEui }}</h3>
                        <h4 class="fw-light">{{ dato.DataOra }}</h4>
                    </div>
                    <button type="button" class="btn-close mt-4 pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="row justify-content-center mb-5 pb-5">
                    <div class="col-12 col-lg-8 px-0 px-lg-5 mt-5 fs-5 fw-light">
                        <p>
                            Tipo messaggio: {{ dato.Testo1 }}
                        </p>
                        <p v-if="dato.Numero1">
                            Numero Sat: {{ dato.Numero1 }}
                        </p>
                        <p v-if="dato.Numero2">
                            Fix Age: {{ dato.Numero2 }}
                        </p>
                        <p v-if="dato.Numero3">
                            Batteria: {{ dato.Numero3 }}
                        </p>
                        <p v-if="dato.FloatP1">
                            Latitudine: {{ dato.FloatP1 }}
                        </p>
                        <p v-if="dato.FloatP2">
                            Longitudine: {{ dato.FloatP2 }}
                        </p>
                        <p v-if="dato.FloatP3">
                            Temperatura: {{ dato.FloatP3 }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        'dato': {
            required: false,
        },
        'chiave': {
            required: false,
        },
        'modalId': {
            required: false,
        },
    }
}
</script>

<style scoped>
p {
    margin: 0;
}
</style>