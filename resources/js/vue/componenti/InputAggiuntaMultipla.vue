<template>
    <div>
        <div class="mb-3">
            <a class="btn btn-light text-uppercase w-100 d-flex justify-content-center align-items-center fs-5"
                :href="routeDownloadExcel">
                <span>Scarica il file da compilare</span>
                <!-- <span class="d-lg-none d-block">
                    <font-awesome-icon icon="fa-solid fa-cloud-arrow-up" style="color: #FFFFFF;" />
                </span> -->
            </a>
        </div>
        <div class="mb-3">
            <a class="btn btn-light text-uppercase w-100 d-flex justify-content-center align-items-center fs-5"
                :href="routeBaseIstruzioni + '/' + argomentoIstruzioni">
                <span>Istruzioni per la compilazione</span>
            </a>
        </div>
        <div class="mb-3">
            <div class="input-group mt-4 m-auto">
                <input class="form-control" type="file" id="csvFile" name="csvFile">
            </div>
        </div>
    </div>
</template>

<script>
// Props che mi servono:
// 'argomentoIstruzioni', 'routeBaseIstruzioni', 'routeDownloadExcel'
export default {
    props: {
        'argomentoIstruzioni': {
            required: false,
        },
        'routeBaseIstruzioni': {
            required: false,
        },
        'routeDownloadExcel': {
            required: false,
        },
    }
}
</script>
