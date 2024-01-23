<template>
    <div>
        <div class="container-fluid px-5">

            <!-- Titolo -->
            <titolo titolo="Mappa" sottotitolo="dei dispositivi"></titolo>

            <div class="row mb-5 pb-5">

                <!-- Mappa -->
                <div id="mappa" class="mx-auto" style="width: 100%; height: 70vh;"></div>

            </div>
            <template v-if="caricamento">
                <!-- <loader/> -->
            </template>
            <template v-else>
                <!-- Contenuto pagina -->

            </template>
        </div>
    </div>
</template>

<script>
import { getRequest } from '../../ax-request.js';
import Titolo from "../componenti/Titolo.vue";
import Loader from "../componenti/Loader.vue";
import 'leaflet.markercluster';
let xmappa = 0;  //Variabile "globale" mappa per l'eventListener

export default {
    data() {
        return {
            caricamento: null,

            dispositivi: null,
            zoom: 8,

            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            mappa: null,
        }
    },
    props: {
        'routeDatiMappa': {
            required: true,
        },
    },
    watch: {
        dispositivi() {
            this.initMappa();
        },
    },
    components: {
        Titolo,
        Loader,
    },
    mounted() {
        
        // this.zoomMappa = this.mappa.getZoom()
    },
    created() {
        this.setup();
    },
    methods: {
        async setup() {
            try {
                this.caricamento = true;
    
                // Operazioni setup
                this.dispositivi = await getRequest(this.routeDatiMappa, null, null);
    
                this.caricamento = false;
            } catch (e) {
                console.log(e);
            }
        },
        initMappa() {
            this.mappa = L.map('mappa').setView([45.7322829, 10.7409769], this.zoom);

            this.mappa.on('contextmenu', this.handleContextMenu);

            this.mappa.addEventListener('zoomend', ()=> {
                const zoom = this.mappa.getZoom();
                this.zoom = zoom;
            });

            xmappa = this.mappa;

            // CORREZIONE BUG LEAFLET
            L.Marker.prototype._animateZoom = function (opt) {
                if (!this._map) {
                    return
                }
                var pos = this._map._latLngToNewLayerPoint(this._latlng, opt.zoom, opt.center).round();
                this._setPos(pos);
            }

            L.Tooltip.prototype._animateZoom = function (e) {
                if (!this._map) {
                    return
                }
                var pos = this._map._latLngToNewLayerPoint(this._latlng, e.zoom, e.center);
                this._setPosition(pos);
            },

            L.Tooltip.prototype._updatePosition = function () {
                if (!this._map) {
                    return
                }
                var pos = this._map.latLngToLayerPoint(this._latlng);
                this._setPosition(pos);
            },
            // FINE CORREZIONE BUG LEAFLET

            L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                attribution: '&copy; <a href="https://www.axatel.it">Axatel</a>',
                maxZoom: 15,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(this.mappa);

            const clusters = L.markerClusterGroup({
                spiderfyOnMaxZoom: false,
                showCoverageOnHover: true,
                zoomToBoundsOnClick: true,
                iconCreateFunction: (cluster)=>
                {
                    const dispositivi  = cluster.getAllChildMarkers();
                    const allarmi = [];
                    const allerte = [];
                    dispositivi.forEach(dispositivo => {
                        // console.log(dispositivo.dispositivo);
                        if (dispositivo.dispositivo.isAllarme == 1) {
                            allerte.push(dispositivo.dispositivo.Citta);
                        } else if (dispositivo.dispositivo.isAllarme == 2) {
                            allarmi.push(dispositivo.dispositivo.Citta);
                        };
                    });
                    const setAllerte = new Set(allerte);
                    const setAllarmi = new Set(allarmi);

                    let markerColore = dispositivi[0].dispositivo.tipodispositivo.Logo.split('.')[0];
                    if (setAllerte.size > 0) {
                        markerColore += 'Allerta';
                    }
                    if (setAllarmi.size > 0) {
                        markerColore += 'Allarme';
                    }
                    markerColore += '.' + dispositivi[0].dispositivo.tipodispositivo.Logo.split('.')[1];
                    const childCount = cluster.getChildCount();
                    const span = childCount > 2 ? childCount : dispositivi[0].dispositivo.Citta;
                
                    let icona;
                    icona = new L.DivIcon({ html: '<div class="position-relative" style="background-image: url(http://localhost:8000/immagini/marker/' + markerColore + ');"><span class="mt-1 px-1 cluster-tooltip">' + span + '</span></div>', 
                    className: 'marker-cluster-normal', iconSize: new L.Point(40, 40) });
                    
                    return icona;
                }
            });
            clusters.on('clusterclick', function (a) {
                a.layer.spiderfy();
            });
            this.dispositivi.forEach(dispositivo => {
                let logo = dispositivo.tipodispositivo.Logo.split('.')[0];
                if (dispositivo.isAllarme == 1) {
                    logo += 'Allerta';
                }
                if (dispositivo.isAllarme == 2) {
                    logo += 'Allarme';
                }
                logo += '.' + dispositivo.tipodispositivo.Logo.split('.')[1];

                const m = L.marker([dispositivo.Latitudine, dispositivo.Longitudine], {
                    icon: L.icon({
                        iconUrl: "http://localhost:8000/immagini/marker/" + logo,
                        className: 'icona',
                        iconSize: [30, 30],
                        iconAnchor: [20, 40],
                    })
                }).bindTooltip(dispositivo.Nome, { permanent: true, direction: "bottom", offset: [-4, 0] })
                .bindPopup(
                    '<div>' +
                        `<h5 class="mb-0 fw-light pe-5">${dispositivo.Nome}</h5>` +
                    '</div>' 
                );
                clusters.addLayer(m);
                m.dispositivo = dispositivo;
            });

            this.mappa.addLayer(clusters);                
            try {
            } catch (e) {
                console.log(e);
            }
        },
    }
}
</script>

<style>
.leaflet-marker-icon {
    background-color: none;
}

.marker-cluster-alarm div, 
.marker-cluster-normal div {
    color: #000000;
    font-size: large;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}

.cluster-tooltip {
    top: calc( 50% + 15px);
    left: 50%;
    transform: translateX(-50%);
    display: inline-block;
    position: absolute;
    background-color: rgba(255, 255, 255, 0.8);
    margin-top: 20px;
    font-size: 1rem;
    min-width: 30px;
    border-radius: 6px;
    box-shadow: 0px 2px 3px -1px rgba(0,0,0,0.72);
    text-align: center;
}
.outline-tooltip {
    outline: 3px solid rgb(255, 0, 0);
}
.circle {
    width: 32px;
    height: 32px;
    line-height: 32px;
    text-align: center;
    margin-top: -20px;
}
</style>