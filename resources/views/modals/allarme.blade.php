<!-- MODALE DISPOSITIVI IN ALLARME -->
<div class="modal fade" id="modalAllarme" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dispositivi in Allarme</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-horizontal d-flex justify-content-center" v-for="item in datiList.dispositivi">
                    <li class="list-group-item">@{{ item.Nome }}</li>
                    <li class="list-group-item">@{{ item.struttura.Nome }}</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">CHIUDI</button>
            </div>
        </div>
    </div>
</div>