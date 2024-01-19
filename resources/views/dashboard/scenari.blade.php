<div id="stato-scenari">
    <div class="container mx-auto border border-primary mt-5 mb-5" v-else="!isCaricamento" v-cloak>
        <div class="row">
            <h3 class="card-title text-center m-3"><b>SCENARI</b></h3>
        </div>
        <div class="accordion accordion-flush mb-3 border border-primary" id="accordionFlushExample">
            <div class="accordion-item bg-dark" v-for="(item, index) in datiList">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        :data-bs-target="'#flush-'+item.id" aria-expanded="false" aria-controls="flush-collapseOne"
                        :class="{'allarme text-dark': isAllarmeList[index] == 2, 'warning text-dark': isAllarmeList[index] == 1}">
                        @{{ item.Nome }}
                    </button>
                </h2>
                <div :id="'flush-'+item.id" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <ul class="list-group">
                            <li class="list-group-item text-center m-2" v-for="item2 in item.scenari"
                                :class="{'bg-danger text-white scenarioAllarme': item2.Stato === 2, 'bg-warning text-dark scenarioAllerta': item2.Stato === 1, 'bg-success text-white': item2.Stato === 0}">
                                <div class="row">
                                    <div class="col">
                                        <b> @{{ item2.Nome }} </b>
                                    </div>
                                    <div class="col">
                                        <span v-if="item2.Stato === 0">Stato di Normalità</span>
                                        <span v-else-if="item2.Stato === 1">Stato di Allerta</span>
                                        <span v-else>Stato di Allarme</span>
                                    </div>
                                    @if(Auth::user()->Ruolo == 0 || Auth::user()->Ruolo == 1)
                                    <div class="col" v-if="item2.Stato == 2 || item2.Stato == 1">
                                        <a :href="'/titolo/scenario/?slug='+ item2.ScenarioSlug +'&stato='+ item2.Stato"
                                            style="text-decoration: none">
                                            <button class="btn btn-dark" id="btnDisattiva">DISATTIVA</button>
                                        </a>
                                    </div>
                                    <div class="col" v-else>
                                        <a :href="'/titolo/scenario/?id='+ item2.id+'&stato='+ item2.Stato"
                                            style="text-decoration: none">
                                            <button class="btn btn-light" id="btnAttiva">ATTIVA</button>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/scenari.js') }}"></script>