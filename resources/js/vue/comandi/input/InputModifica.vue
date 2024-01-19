<template>
    <div>
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">Nome</span>
                <input type="text" name="Nome" :value="comando.Nome" class="form-control" placeholder="del comando">
            </div>
        </div>                                
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">Codice</span>
                <input type="text" name="Codice" :value="comando.Codice" class="form-control" placeholder="Codice Comando">
            </div>
        </div>         
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">Tipo dispositivo</span>
                <select name="codTipoDisp" :value="comando.codTipoDisp" class="form-select">
                    <option v-for="option in tipiDispositivi" :value="option.IdTipo" :label="option.Nome"></option>
                </select>
            </div>
        </div>         
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">Automazione</span>
                <select name="codAutomazione" :value="comando.codAutomazione" class="form-select">
                    <option v-for="option in automazioni" :value="option.id" :label="option.Nome"></option>
                </select>
            </div>
        </div>         
        <div class="col-12">
            <div class="input-group mt-3">
                <div class="btn-group w-100" role="group" aria-label="Basic checkbox toggle button group">
                    <span class="btn border border-end-0 bg-light text-start" style="cursor: auto">Invio</span>
    
                    <input type="checkbox" class="btn-check" id="ckAddIsAutomatico" name="isAutomatico" :checked="comando.isAutomatico">
                    <label class="btn btn-outline-primary" for="ckAddIsAutomatico">Automatico</label>
    
                    <input type="checkbox" class="btn-check" id="ckAddIsManuale" name="isManuale" :checked="comando.isManuale">
                    <label class="btn btn-outline-primary" for="ckAddIsManuale">Manuale</label>
                </div>
            </div>
        </div>     
        <div class="col-12">
            <div class="input-group mt-3">
                <span class="btn border border-end-0 bg-light" style="cursor: auto">Descrizione</span>
                <textarea name="Descrizione" class="form-control" rows="3" :value="comando.Descrizione"></textarea>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        'comando': {
            required: false,
        },
        'automazioni': {
            required: false,
        },
        'tipiDispositivi': {
            required: false,
        },
    }
}
</script>