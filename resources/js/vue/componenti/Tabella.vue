<template>
    <div class="table-responsive mt-3 mb-5">
        <table class="table table-striped table-hover table-white caption-top">
            <thead v-if="colonne" class="bg-primary">
                <th v-for="colonna in colonne" class="ps-2 fw-normal text-white">
                    {{ colonna }}
                </th>
            </thead>
            <tbody v-if="dati">
                <tr v-for="dato in dati">
                    <template v-if="chiavi">
                        <td v-for="chiave in chiavi">{{ (dato[chiave] != undefined) ? dato[chiave] : '' }}</td>
                    </template>
                    <template v-else>
                        <td v-for="colonna in colonne">{{ (dato[colonna] != undefined) ? dato[colonna] : '' }}</td>
                    </template>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
// Props:
// colonne, chiavi*, dati

export default {
    props: {
        'colonne': {
            required: true,
        },
        'chiavi': {
            required: false,
        },
        'dati': {
            required: true,
        },
    },
}
</script>