<!-- MODALE INVIO COMANDO -->
<div class="modal fade" id="modalInvio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Invio Comando</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="Codice" :value="codice" class="form-control" placeholder="Codice Comando" hidden>
                <p class="text-center">Selezionato comando <b>@{{ nome }}</b></p>
                <div class="mb-3">
                    @include("navs.nav-invio-comando")
                    <ul class="list-group mx-auto w-50 mb-5">
                        <li class="list-group-item" v-for="(item, i) in dispositiviList">
                            <div class="form-check">
                                <input type="checkbox" name="codDispositivo[]" class="form-check-input"
                                    :id="'ckAddDispositivo' + item.Deveui" :value="item.Deveui"
                                    v-model="dispositiviCheckList[i]" />
                                <label class="form-check-label my-auto"
                                    :for="'ckAddDispositivo' + item.Deveui">@{{ item.Nome }} -
                                    <i>@{{ item.Deveui }}</i></label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer fixed-bottom">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">ANNULLA</button>
                    <button type="button" class="btn btn-warning" @click="invioNS(id)">INVIO</button>
                </div>

                <ul class="list-group">
                    <li :class="'list-group-item d-flex justify-content-between align-items-start list-group-item-'+statoInvioList[i]" v-for="(item, i) in messaggiInvioList">
                        @{{ item }} 
                        <button type="button" class="btn-close shadow-none" @click="messaggiInvioList.pop(i); statoInvioList.pop(i);"></button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>