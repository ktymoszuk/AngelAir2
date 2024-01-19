<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item" :class="{disabled: paginazione.contatore[2] <= 0 }">
            <a class="page-link" tabindex="-1" aria-disabled="true"
                @click="updatePaginazione(paginazione.precedente)">Previous</a>
        </li>
        <li class="page-item" v-for="(item, i) in paginazione.contatore"
            :class="{'visually-hidden-focusable': !paginazione.isVisibile[i]}">
            <a class="page-link" @click="updatePaginazione(item)">@{{ paginazione.pagina[i] }}</a>
        </li>
        <li class="page-item">
            <a class="page-link" @click="updatePaginazione(paginazione.prossimo)">Next</a>
        </li>
    </ul>
</nav>