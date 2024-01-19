<template>
    <div v-if="utente">
        <div class="input-group mt-3 m-auto">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">Nome</span>
            <input type="text" class="form-control" name="name" placeholder="Nome"
                :value="utente.name" />
        </div>
        <div class="input-group mt-3 m-auto">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">Telefono</span>
            <input type="text" class="form-control" name="Telefono" placeholder="Telefono"
                :value="utente.Telefono" />
        </div>
        <div class="input-group mt-3 m-auto">
            <span class="btn border border-end-0 bg-light" style="cursor: auto">Email</span>
            <input type="text" class="form-control" name="Email" placeholder="Email"
                :value="utente.email" />
        </div>
        <div  class="input-group mt-3 m-auto">
            <span class="btn border border-end-0 bg-light cursor">Password</span>
            <input type="text" class="form-control" name="Password" placeholder="nuova dell'utente" />
        </div>
        <div  class="input-group mt-3 m-auto">
            <span class="btn border border-end-0 bg-light cursor">Ruolo</span>
            <select name="Ruolo" class="form-select" :value="utente.Ruolo">
                <option value="0">Amministratore</option>
                <option value="1">Operatore</option>
                <option value="2">Manutentore</option>
                <option value="3">Ospite</option>
            </select>
        </div>
        <div class="mt-4 m-auto">
            <div class="d-flex justify-content-center flex-column flex-lg-row align-items-center">
                <div>
                    <input type="hidden" id="abilitatoCheck"
                    autocomplete="off" :checked="utente.isAbilitato ? 1 : 0">
                    <input type="hidden" name="isAbilitato" :value="utente.isAbilitato">
                    <div class="container-allerta" style="cursor: pointer;"  @click="utente.isAbilitato = !utente.isAbilitato">
                        <div class="box-allerta-1" :class="{
                            'color-allerta-1': utente.isAbilitato, 'color-allerta-4': !utente.isAbilitato}">
                        </div>
                        <span>
                            <span class="fs-5 fw-light" v-if="utente.isAbilitato">Abilitato</span>
                            <span class="fs-5 fw-light" v-else>Non abilitato</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default{
    props:{
        "utente":{
            required: false,
        },
    },
}
</script>