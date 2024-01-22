<template>
    <div v-if="rawData" class="modal modal-xl fade border-0" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="d-flex justify-content-between align-items-center px-5 pt-3">
                    <div class="text-start">
                        <h2 class="modal-title fw-normal" id="exampleModalLabel">Ultimi {{ rawData.length }} dati</h2>
                        <h4 class="fw-light">del dispositivo <span class="fw-normal">{{ nome }}</span> ({{ deveui }})</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-0">
                    <table class="table table-white table-hover table-striped caption-top">
                        <thead class="text-start">
                            <th class="fw-normal ps-2 bg-primary text-white">FRAME</th>
                            <th class="fw-normal ps-2 bg-primary text-white">PAYLOAD</th>
                            <th class="fw-normal ps-2 bg-primary text-white">DATAORA</th>
                        </thead>
                        <tbody class="text-start">
                            <tr v-for="raw in rawData">
                                <td>{{ raw.Frame }}</td>
                                <td>{{ raw.Payload }}</td>
                                <td>{{ raw.DataOra }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        'rawData': {
            required: true,
        },
        'nome': {
            required: true,
        },
        'deveui': {
            required: true,
        },
    }
}
</script>

<style scoped></style>