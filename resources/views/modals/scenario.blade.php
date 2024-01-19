<!-- MODALE ERRORI FORM -->
<div class="modal fade error" id="modalScenario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Esecuzione Scenario</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Sei sicuro di voler eseguire lo scenario <b>@{{ datiList[0].Nome }}</b>?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">CHIUDI</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" @click="invioComandi">CONFERMA</button>
            </div>
        </div>
    </div>
</div>