<template>
    <div v-if="modalId !== null && chiave !== null && comando" class="modal modal-xl fade text-dark" :id="modalId + chiave" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" :aria-labelledby="modalId + chiave + 'Label'" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="d-flex justify-content-between align-items-top px-5">
                    <div class="pt-4">
                        <h1 class="fw-light text-uppercase">
                            Invia
                        </h1>
                        <h3 class="fw-light">il comando {{ comando.Nome }}</h3>
                    </div>
                    <button type="button" class="btn-close mt-4 pt-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12">

                        <input type="text" name="Codice" :value="comando.Codice" class="form-control" hidden>

                        <div class="d-flex justify-content-around mb-5">
                            <button type="button" class="btn btn-outline-success" @click="ckStato(true)">SELEZIONA TUTTI</button>
                            <button type="button" class="btn btn-outline-danger" @click="ckStato(false)">DESELEZIONA TUTTI</button>
                        </div>

                        <ul class="list-group w-100 mx-auto px-5 mb-5">
                            <li class="list-group-item border-0 text-center p-0" v-for="(item, i) in dispositivi">
                                <input type="checkbox" hidden name="codDispositivo[]" class="form-check-input"
                                    :id="'ckAddDispositivo' + item.DevEui"
                                    v-model="dispositiviCheckList[i]" />
                                <label class="form-check-label my-auto w-100 h-100 galleria-card-0 rounded px-3 py-2"
                                    :class="{'colore-card-1': dispositiviCheckList[i]}"
                                    :for="'ckAddDispositivo' + item.DevEui">{{ item.Nome }} -
                                    <i>{{ item.DevEui }}</i></label>

                            </li>
                        </ul>                

                        <div class="d-flex justify-content-center align-items-center">
                            <button class="btn btn-warning px-5 text-white mb-5 text-uppercase fs-5 fw-light text-center" @click="invioNS(comando.id)">Invia</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            dispositiviCheckList: [],
        }
    },
    props: {
        'modalId': {
            required: false,
        },
        'chiave': {
            required: false,
        },
        'comando': {
            required: false,
        },
        'dispositivi': {
            required: false,
        },
    },
    methods: {
        ckStato(stato) {
            for (let i = 0; i < this.dispositivi.length; i++) {
                this.dispositiviCheckList[i] = stato;
            }
        },
        async invioComando(id) {
        },

    }
}
</script>