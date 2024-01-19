<div class="d-flex justify-content-evenly m-2">
    <input type="radio" class="btn-check" name="options-outlined" id="success-outlined" autocomplete="off" @click="updateChannel(0)" :checked="channel == 0">
    <label class="btn btn-outline-warning text-dark" for="success-outlined">FLUSSO VISIVO</label>

    <input type="radio" class="btn-check" name="options-outlined" id="danger-outlined" autocomplete="off" @click="updateChannel(1)" :checked="channel == 1">
    <label class="btn btn-outline-dark" for="danger-outlined">FLUSSO TERMICO</label>
</div>