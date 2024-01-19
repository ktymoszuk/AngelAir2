<template>
    <div>
        <div class="input-group">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">
                Nome<asterisco/>
            </span>
            <input type="text" name="Nome" class="form-control" placeholder="del comando" maxlength="50">
        </div>
        <div class="input-group mt-3">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">
                Codice<asterisco/>
            </span>
            <input type="text" name="Codice" class="form-control" placeholder="del comando (max 10 caratteri)" maxlength="10">
        </div>
        <div class="input-group mt-3">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">
                Categoria dispositivo<asterisco/>
            </span>
            <select name="codTipoDisp" class="form-select">
                <option disabled value="">Seleziona</option>
                <option v-for="option in tipiDispositivi" :value="option.IdTipo" :label="option.Nome"></option>
            </select>
        </div>
        <div class="input-group mt-3">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">
                Automazione<asterisco/>
            </span>
            <select name="codAutomazione" class="form-select">
                <option disabled value="">Seleziona</option>
                <option v-for="option in automazioni" :value="option.id" :label="option.Nome"></option>
            </select>
        </div>
        <div class="btn-group mt-3 w-100" role="group" aria-label="Basic checkbox toggle button group">
            <input type="checkbox" class="btn-check" id="btncheckInvio" autocomplete="off">
            <label class="btn border bg-light" for="btncheckInvio" style="cursor: auto;">Invio</label>
    
            <input type="checkbox" name="isAutomatico" class="btn-check" id="btncheckAutomatico" autocomplete="off">
            <label class="btn btn-outline-primary border-start-0" for="btncheckAutomatico">automatico</label>
    
            <input type="checkbox" name="isManuale" class="btn-check" id="btncheckManuale" autocomplete="off">
            <label class="btn btn-outline-primary" for="btncheckManuale">manuale</label>
        </div>
        <div class="input-group mt-3 mb-5">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">
                Descrizione<asterisco/>
            </span>
            <textarea name="Descrizione" class="form-control" rows="3" placeholder="del comando"></textarea>
        </div>
    </div>
</template>

<script>
import Asterisco from '../../componenti/Asterisco.vue';
export default {
    props: {
        'tipiDispositivi': {
            required: false,
        },
        'automazioni': {
            required: false,
        },
    },
    components: {
        Asterisco,
    }
}
</script>