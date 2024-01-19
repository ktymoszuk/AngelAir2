import './bootstrap';
import 'bootstrap';
import 'leaflet';
// import './ax-mqtt.js';
import 'paho-mqtt';

import {createApp} from 'vue/dist/vue.esm-bundler.js'

// Messaggi alert delle session
import Alert from './vue/componenti/Alert.vue';

// DASHBOARD
import Dashboard from './vue/dashboard/Dashboard.vue';


// MONITORAGGIO //
import Monitoraggio from './vue/monitoraggio/Monitoraggio.vue';
// Statistiche
import Statistiche from './vue/statistiche/Statistiche.vue';
// Grafici
import Grafici from './vue/grafici/Grafici.vue';
// Real-time
import RealTime from './vue/realtime/RealTime.vue';
// Logs
import Logs from './vue/logs/Logs.vue';

// COMANDI
import Comandi from './vue/comandi/Comandi.vue';

// STRUTTURE
import Strutture from './vue/strutture/Strutture.vue';

// DISPOSITIVI
import Dispositivi from './vue/dispositivi/Dispositivi.vue';

// COMANDI DISPOSITIVI
import ComandiDispositivi from './vue/dispositivi/comandi/ComandiDispositivi.vue';

// SOGLIE DISPOSITIVI
import SoglieDispositivi from './vue/dispositivi/soglie/SoglieDispositivi.vue';

// SOGLIE
import Soglie from './vue/soglie/Soglie.vue';

// REPORTS
import Reports from './vue/reports/Reports.vue';

// PROFILO
import Profilo from './vue/profilo/Profilo.vue';

// UTENTI
import Utenti from './vue/utenti/Utenti.vue';


createApp({
    components: {
        // Componenti generale
        Alert,              

        Dashboard,

        // Monitoraggio
        Monitoraggio,
        Grafici,
        Statistiche,
        RealTime,
        Logs,

        Comandi,
        Strutture,
        Dispositivi,
        ComandiDispositivi,
        SoglieDispositivi,
        Soglie,
        Reports,

        Profilo,
        Utenti,
    }
}).mount("#app");