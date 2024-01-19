<!-- MODALE RIAVVIO AUTOMAZINOE -->
<div class="modal fade" id="modalRiavvio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Riavvio Automazione</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('riavvio_automazione') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="text" name="Nome" v-model="nome" hidden>
                    Vuoi riavviare l'automazione <b>@{{ nome }}</b>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">CHIUDI</button>
                    <button type="submit" name="id" :value="id" class="btn btn-danger">CONFERMA</button>
                </div>
            </form>
        </div>
    </div>
</div>