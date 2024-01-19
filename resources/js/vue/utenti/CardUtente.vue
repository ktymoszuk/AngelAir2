<template v-if="utente">
    <div class="row shadow px-5 py-3 mt-4 rounded-5 transition">
        <div class="col-12 col-lg-2 d-flex align-items-center justify-content-center justify-content-xxl-start">
            <div class="container-allerta">
                <div class="box-allerta-1 p-1" :class="{ 'color-allerta-1': utente.isAbilitato, 'color-allerta-4': !utente.isAbilitato }">
                    <div class="w-75 h-75 rounded-circle d-flex justify-content-center align-items-center" style="z-index: 10; overflow: hidden">
                        <img :src="asset + 'immagini/' + utente.FotoProfilo" class="w-100" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 p-0 d-flex flex-column align-items-center justify-content-center align-items-lg-start my-3 m-lg-0">
            <h2 class="text-uppercase fw-light">{{ utente.name }}</h2>
            <h5 v-show="utente.Ruolo === 0" class="fw-light">
                Amministratore
            </h5>
            <h5 v-show="utente.Ruolo == 1" class="fw-light">
                Operatore
            </h5>
            <h5 v-show="utente.Ruolo == 2" class="fw-light">
                Manutentore
            </h5>
            <h5 v-show="utente.Ruolo == 3" class="fw-light">
                Ospite
            </h5>
            <h5 v-show="utente.isAssistenza" class="fw-light">
                Assistenza
            </h5>
        </div>
        <div class="col-12 col-lg-3 p-0 d-flex flex-column align-items-center justify-content-center align-items-lg-start my-3 m-lg-0">
            <h5 v-show="utente.Email" class="fw-light">
                {{ utente.email }}
            </h5>
            <h5 v-show="utente.Telefono" class="fw-light">
                {{ utente.Telefono }}
            </h5>
        </div>
        <div class="col-12 offset-md-2 col-md-8 col-lg-4 offset-lg-0 col-xxl-4 d-flex justify-content-center justify-content-lg-start align-items-center">

            <!-- MODIFICA -->
            <!-- Bottone -->
            <button class="btn btn-primary text-uppercase w-100 mx-1 px-3
            d-flex justify-content-center align-items-center" id="btnModifica" data-bs-toggle="modal" :data-bs-target="'#' + idModalModifica + chiave">
                Modifica
            </button>
            <!-- Modal -->
            <modifica :modal-id="idModalModifica" :modifica-id="utente.id" :titolo="'l\'utente ' + utente.name" :route="routeModificaUtente" :chiave="chiave">
                <template v-slot:inputModifica>
                    <input-modifica :utente="utente"></input-modifica>
                </template>
            </modifica>

            <!-- ELIMINA -->
            <!-- Bottone -->
            <button class="btn btn-danger text-uppercase w-100 ms-1 px-3
                d-flex justify-content-center align-items-center" id="btnCancella" data-bs-toggle="modal" :data-bs-target="'#' + idModalElimina + chiave">
                Elimina
            </button>
            <!-- Modal -->
            <elimina :chiave="chiave" :modal-id="idModalElimina" :elimina-id="utente.id" cosa-elimino="l'utente" :nome="utente.name" :route="routeEliminaUtente"></elimina>

        </div>
    </div>
</template>

<script>
import Modifica from '../componenti/Modifica.vue';
import Elimina from '../componenti/Elimina.vue';
import InputModifica from './input/InputModifica.vue';

export default {
    data(){
        return {
            idModalModifica: 'modificaUtenteModal',
            idModalElimina: 'eliminaUtenteModal',
        }
    },
    props: {
        'utente': {
            required: false,
        },
        'asset': {
            required: false,
        },
        'chiave': {
            required: false,
        },
        'routeModificaUtente': {
            required: false,
        },
        'routeEliminaUtente': {
            required: false,
        },
    },
    components: {
        Modifica,
        Elimina,
        InputModifica,
    }
}
</script>