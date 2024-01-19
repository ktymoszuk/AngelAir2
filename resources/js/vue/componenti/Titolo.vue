<template>
    <div class="row mt-5 pt-5 mb-5">
        <div class="col-12 d-flex justify-content-between align-items-start flex-column flex-md-row mt-3 px-0">
            <div>
                <h1 class="fw-light text-uppercase mb-0">{{ titolo }}</h1>
                <h3 class="fw-light">{{ sottotitolo }}</h3>
            </div>
            <div class="d-flex flex-column col-12 col-md-6 col-lg-5 col-xxl-4">
                <a v-if="linkIndietro" class="btn btn-primary px-5 text-uppercase mt-1 px-0" :href="linkIndietro">
                    <span>
                        Indietro
                    </span>
                </a>
                <template v-if="bottoni">
                    <template v-for="(bottone, b) in bottoni">
                        <template v-if="bottone.tipo == '!'">
                            <a class="btn btn-primary px-5 text-uppercase mt-1 mx-0 px-0" :href="bottone.collegamento">
                                <span>
                                    {{ bottone.nome }}
                                </span>
                            </a>
                        </template>
                        <template v-if="bottone.tipo == 'modal'">
                            <button class="btn btn-outline-primary px-5 text-uppercase mt-1 mx-0 px-0" data-bs-toggle="modal" :data-bs-target="'#' + bottone.collegamento">
                                <span>
                                    {{ bottone.nome }}
                                </span>
                            </button>
                        </template>
                        <template v-if="bottone.tipo == 'link'">
                            <a class="btn btn-outline-primary px-5 text-uppercase mt-1 mx-0 px-0" :href="bottone.collegamento">
                                <span>
                                    {{ bottone.nome }}
                                </span>
                            </a>
                        </template>
                    </template>
                </template>
                <slot name="altriBottoni"></slot>
            </div>
        </div>
    </div>
</template>

<script>

// ES BOTTONI:
// this.bottoni = [
//     {
//         'tipo': '!',
//         'nome': 'Statistiche',
//         'collegamento': this.routeDelLink,
//     },
//     {
//         'tipo': 'modal',
//         'nome': 'Nuovo dispositivo',
//         'collegamento': this.modalNuovoDispositivo,
//     },
//     {
//         'tipo': 'link',
//         'nome': 'Grafici',
//         'collegamento': this.routeDelLink,
//     },
// ];


export default {
        props: {
            'titolo': {
                required: true,
            },
            'sottotitolo': {
                required: false,
            },
            'linkIndietro': {
                required: false,
            },
            'bottoni': {
                required: false,
            },
        }
    }
</script>

<style scoped></style>