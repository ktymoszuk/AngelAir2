<div id="notifica" v-cloak>
    <div class="container-fluid" v-show="isAllarme">
        <div class="row">
            <div class="col alert alert-danger my-auto">
                <div class="d-flex justify-content-around">
                    <span class="material-icons icon-centered" style="color: red;">
                        warning
                    </span>
                    <span>Dispositivi in allarme: <b>@{{ countDispositivi }}</b></span>
                    <span class="material-icons icon-centered" data-bs-toggle="modal" data-bs-target="#modalNotifica"
                        style="cursor: pointer;">
                        open_in_new
                    </span>
                </div>
            </div>
            <div class="col alert alert-warning my-auto">
                <div class="d-flex justify-content-around">
                    <span class="material-icons icon-centered" style="color: red;">
                        warning
                    </span>
                    <span>Automazioni in allarme blocco:
                        @{{ countAutomazioni }}</span>
                    <a href="{{ route('automazioni') }}" class="text-decoration-none">
                        <span
                            class="material-icons icon-centered bi bi-exclamation-triangle-fill flex-shrink-0 me-2 d-flex justify-content-end">
                            open_in_new
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-auto my-auto">
                <audio id="allarme-dispositivo">
                    <source src="{{ asset('suoni/allarme-dispositivo.mp3') }}" type="audio/mpeg">
                </audio>
                <audio id="allarme-automazione">
                    <source src="{{ asset('suoni/allarme-automazione.mp3') }}" type="audio/mpeg">
                </audio>
                <button class="btn btn-sm btn-success" @click="volumeSuono(true, false)" v-if="isVolume">
                    <span class="material-icons align-middle">
                        volume_up
                    </span>
                </button>
                <button class="btn btn-sm btn-danger" @click="volumeSuono(false, false)" v-else>
                    <span class="material-icons align-middle">
                        volume_down
                    </span>
                </button>
            </div>
        </div>
    </div>
    @include("notification.allarmi")
    <script src="{{ asset('js/ax-notification/ax-notifica.js') }}"></script>
</div>