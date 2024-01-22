<?php

use Illuminate\Support\Facades\Route;

// CONTROLLERS
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatisticheController;
use App\Http\Controllers\GraficiController;
use App\Http\Controllers\RealtimeController;
use App\Http\Controllers\DispositiviController;
use App\Http\Controllers\ComandiController;
use App\Http\Controllers\TabelleController;
use App\Http\Controllers\IstruzioniController;
use App\Http\Controllers\ProfiloController;
use App\Http\Controllers\SoglieController;
use App\Http\Controllers\UtentiController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\StruttureController;
use App\Http\Controllers\SoglieDispositiviController;
use App\Http\Controllers\ComandiDispositiviController;
use App\Http\Controllers\MonitoraggioController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ArchivioController;
use App\Http\Controllers\DettagliController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// intro
Route::view('/', 'index')->middleware("utenteAutenticato")->name('index');

// errore
Route::view('/errore', 'errore')->name('errore');

Route::middleware(['utenteAutenticato'])->group(function () {
    
    // AUTH FORTIFY
    Route::view("/register", "auth.register")->name("register"); // pagina registrazione
    Route::view("/login", "auth.login")->name("login"); // pagina registrazione
});

Route::middleware(['utente'])->group(function () {

    // dashboard
    Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard"); // dashboard

    // monitoraggio
    Route::get("/monitoraggio", [MonitoraggioController::class, 'view'])->name('monitoraggio');

    // statistiche
    Route::get("/monitoraggio/statistiche", [StatisticheController::class, 'view'])->name('statistiche');

    // grafici
    Route::get("/monitoraggio/grafici", [GraficiController::class, "index"])->name("grafici"); // grafici
    Route::get("/monitoraggio/grafici/dati", [GraficiController::class, "show"])->name("dati_grafici"); // dati grafici

    // situazione real-time
    Route::get("/monitoraggio/realtime", [RealtimeController::class, 'view'])->name('real_time');
    Route::get("/monitoraggio/realtime/allarmi", [RealtimeController::class, 'allarmi'])->name('allarmi_realtime');

    // logs
    Route::get("/monitoraggio/log/sistema", [LogsController::class, "view"])->name('logs');
    Route::get("/monitoraggio/log/dati", [LogsController::class, "logData"])->name('log_dati');

    // strutture
    Route::get("/strutture", [StruttureController::class, "index"])->name("strutture"); // strutture
    Route::get("/strutture/dati", [StruttureController::class, "show"]); // visualizzazione strutture 
    Route::get("/struttura", [StruttureController::class, "showStruttura"]); // singola strutture 
    Route::get("/struttura/sinottici", [StruttureController::class, "getSinottici"])->name('sinottici_struttura');  //Sinottici disponibili
    Route::get("/struttura/sinottici/{cartografia}", [StruttureController::class, "getSinottico"]);  //Sinottico disponibile
    Route::post("/strutture/update", [StruttureController::class, "update"])->name("update_struttura"); // aggiornamento strutture 
    Route::post("/strutture/delete", [StruttureController::class, "delete"])->name("delete_struttura"); // cancellazione strutture 
    Route::get("/strutture/dowloadcsv", [StruttureController::class, "download"])->name('download_csv_strutture');  //download del file excel per le strutture csv
    Route::post("/strutture/insert", [StruttureController::class, "insertOne"])->name('insert_singola_struttura');  //rotta post per la crezione di una singola struttura
    Route::post("/strutture/create/multiplo", [StruttureController::class, "uploadCsv"])->name('upload_csv_strutture');  // rotta post per l'inserimento del csv con strutture multiple 
    
    Route::get("/strutture/datifiltrati", [StruttureController::class, "show"])->name("dati_filtrati_strutture"); // visualizzazione strutture 

    // dispositivi
    Route::get("/dispositivi", [DispositiviController::class, 'view'])->name('dispositivi');
    Route::post("/dispositivi/nuovo", [DispositiviController::class, 'insertOne'])->name('nuovo_dispositivo');
    Route::post("/dispositivi/nuovi", [DispositiviController::class, 'insertMany'])->name('nuovi_dispositivi');
    Route::post("/dispositivi/modifica", [DispositiviController::class, 'update'])->name('modifica_dispositivo');
    Route::post("/dispositivi/elimina", [DispositiviController::class, 'delete'])->name('elimina_dispositivo');
    Route::get("/dispositivi/download", [DispositiviController::class, "download"])->name('download_excel_dispositivi');  //rotta per il download del csv

    // Dettagli dispositivo
    Route::get("/dispositivo", [DettagliController::class, "dettagli"])->name("dispositivo");
    Route::get("/dispositivo/dati", [DettagliController::class, "dati"])->name("dati_dispositivo");
    Route::get("/dispositivo/statodisp", [DettagliController::class, "statoDisp"])->name("dati_statodisp");
    Route::get("/dispositivo/rawdata", [DettagliController::class, "rawData"])->name("dati_rawdata");
    
    // soglie
    Route::get("/soglie", [SoglieController::class, 'view'])->name('soglie');
    Route::post("/soglie/nuova", [SoglieController::class, 'insert'])->name('nuova_soglia');
    Route::post("/soglie/modifica", [SoglieController::class, 'update'])->name('modifica_soglia');
    Route::post("/soglie/elimina", [SoglieController::class, 'delete'])->name('elimina_soglia');
    Route::get("/soglie/tipodisp", [SoglieController::class, "tipodisp"])->name('dati_colonne_associate'); // select colonne

    // comandi
    Route::get("/comandi", [ComandiController::class, 'view'])->name('comandi');
    Route::post("/comandi/nuovo", [ComandiController::class, 'insertOne'])->name('nuovo_comando');
    Route::post("/comandi/nuovi", [ComandiController::class, 'insertMany'])->name('nuovi_comandi');
    Route::post("/comandi/invia", [ComandiController::class, 'invia'])->name('invia_comando');
    Route::post("/comandi/modifica", [ComandiController::class, 'update'])->name('modifica_comando');
    Route::post("/comandi/elimina", [ComandiController::class, 'delete'])->name('elimina_comando');
    Route::get("/comandi/download", [ComandiController::class, "download"])->name('download_excel_comandi');  //rotta per il download del csv

    // Comandi Dispositivi
    Route::get("/comandodispositivi", [ComandiDispositiviController::class, "view"])->name("comando_dispositivi"); // comando dispositivi
    Route::get("/comandodispositivi/dati", [ComandiDispositiviController::class, "dati"])->name("dati_comando_dispositivi"); // visualizzazione dispositivi
    Route::post("/comandodispositivi/associa", [ComandiDispositiviController::class, "insert"])->name("associa_comando_dispositivo"); // creazione associazione comando-dispositivi
    Route::get("/comandodispositivi/daticomandi", [ComandiDispositiviController::class, "comandi"])->name("dati_comandi_comandi_dispositivi"); // visualizzazione dispositivi
    Route::get("/comandodispositivi/datidispositivi", [ComandiDispositiviController::class, "dispositivi"])->name("dati_dispositivi_comandi_dispositivi"); // visualizzazione dispositivi
    Route::post("/comandodispositivi/elimina", [ComandiDispositiviController::class, 'delete'])->name('elimina_comando_dispositivo');
    
    // Soglie Dispositivi
    Route::get("/sogliadispositivi", [SoglieDispositiviController::class, "view"])->name("soglia_dispositivi"); // comando dispositivi
    Route::get("/sogliadispositivi/dati", [SoglieDispositiviController::class, "show"])->name("dati_soglie_dispositivi"); // visualizzazione dispositivi
    Route::get("/sogliadispositivi/datisoglie", [SoglieDispositiviController::class, "soglie"])->name("dati_soglie_soglie_dispositivi"); // visualizzazione dispositivi
    Route::get("/sogliadispositivi/datidispositivi", [SoglieDispositiviController::class, "dispositivi"])->name("dati_dispositivi_soglie_dispositivi"); // visualizzazione dispositivi
    Route::post("/sogliadispositivi/associa", [SoglieDispositiviController::class, "insert"])->name("associa_soglia_dispositivo"); // creazione associazione soglia-dispositivi
    Route::post("/sogliadispositivi/elimina", [SoglieDispositiviController::class, "delete"])->name("elimina_soglia_dispositivo"); // cancellazione soglia-dispositivo

    // reports
    Route::get("/reports", [ReportsController::class, "view"])->name("reports"); // pagina reports
    Route::get("/reports/dati", [ReportsController::class, "dati"])->name("dati_reports"); // dati reports
    Route::get("/reports/dati?report={report}", [ReportsController::class, "show"]); // dati reports
    Route::get("/reports/dati-download-report", [ReportsController::class, "showDownloadReport"])->name('dati_download_report'); // dati reports
    Route::post("/reports/crea-report", [ReportsController::class, "showDatiJSON"])->name("crea-report"); // registrazione reports nel db
    Route::post("/reports/crea-automazione-reports", [ReportsController::class, "creaAutomazioneReports"])->name("crea-automazione-reports"); // registrazione reports nel db
    Route::post("/reports/scarica-report", [ReportsController::class, "showDatiJSON"])->name("scarica-report"); // download file
    Route::post("/reports/scarica-ultimo-report", [ReportsController::class, "scaricaReportsFormato"])->name("scarica-ultimo-report"); // download file dei report periodici
    Route::post("/reports/elimina-reports", [ReportsController::class, "delete"])->name("elimina-reports"); // download file
    Route::post("/reports/modifica-reports", [ReportsController::class, "update"])->name("modifica-reports"); // modifica dei report periodici
    // archivio reports
    // Route::get("/reports/archivio", [ArchivioController::class, "index"])->name("archivio-reports"); // pagina archivio
    // Route::get("/reports/archivio", [ArchivioController::class, "show"]); // dati archivio
    Route::get("/reports/archivio", [ArchivioController::class, "index"])->name("archivio-reports"); // sinottico strutturale ponte
    Route::get("/reports/archivio/dati", [ArchivioController::class, "show"]); // visualizzazione dati
    // Route::get("/reports/archivio/tutti-dati", [ArchivioController::class, "showAll"])->name('dati_archivio'); // visualizzazione dati
    Route::post("/reports/scarica-report-archivio", [ArchivioController::class, "download"])->name("scarica-report-archivio"); // download file dei report periodici


    // profilo
    Route::get("/profilo", [ProfiloController::class, "view"])->name("profilo"); // modifica profilo utente
    Route::get("/profilo/dati", [ProfiloController::class, "dati"])->name("dati_profilo"); // visualizzazione dati profilo
    Route::post("/profilo/update", [ProfiloController::class, "update"])->name("modifica_profilo"); // aggiornamento dati profilo utente
    Route::post("/profilo/modifica/password", [ProfiloController::class, 'password'])->name('modifica_password');

    // utenti
    Route::get("/utenti", [UtentiController::class, "view"])->name("utenti"); // utenti
    Route::get("/utenti/dati", [UtentiController::class, "show"])->name("dati_utenti"); // visualizzazione utenti
    Route::post("/utenti/update", [UtentiController::class, "update"])->name("modifica_utente"); // aggiornamento utenti 
    Route::post("/utenti/delete", [UtentiController::class, "delete"])->name("elimina_utente"); // cancellazione utenti

    // notifiche
    // Route::get("/notifiche", [NotificheController::class, "index"])->name("notifiche"); // notifiche
    // Route::get("/notifiche/dati", [NotificheController::class, "show"])->name("dati_notifiche"); // visualizzazione notifiche
    // Route::get("/notifiche/update", [NotificheController::class, "update"])->name("modifica_notifica"); // aggiornamento stato ricevimento notifica 

    // mqtt
    // Route::get("/ws", [AllarmiController::class, "index"])->name("mqtt");// allarmi

    // tabelle
    Route::get("/automazioni/dati", [TabelleController::class, 'automazioni'])->name('dati_automazioni');
    Route::get("/strutture/dati", [TabelleController::class, 'strutture'])->name('dati_strutture');
    Route::get("/cartografie/dati", [TabelleController::class, 'cartografie'])->name('dati_cartografie');
    Route::get("/utenti/dati", [TabelleController::class, 'utenti'])->name('dati_utenti');
    Route::get("/dispositivi/dati", [TabelleController::class, 'dispositivi'])->name('dati_dispositivi');
    Route::get("/dispositivi/datipaginati", [TabelleController::class, 'dispositiviPaginati'])->name('dati_paginati_dispositivi');
    Route::get("/tipodisp/dati", [TabelleController::class, 'tipodisp'])->name('dati_tipodisp');
    Route::get("/missioni/dati", [TabelleController::class, 'missioni'])->name('dati_missioni');
    Route::get("/aziende/dati", [TabelleController::class, 'aziende'])->name('dati_aziende');
    Route::get("/comandi/dati", [TabelleController::class, 'comandi'])->name('dati_comandi');
    Route::get("/soglie/dati", [TabelleController::class, 'soglie'])->name('dati_soglie');
    Route::get("/categoriedispositivi/dati", [TabelleController::class, 'categorieDispositivi'])->name('dati_categorie_dispositivi');
    Route::get("/categoriesoglie/dati", [TabelleController::class, 'categorieSoglie'])->name('dati_categorie_soglie');

    // Istruzioni per i fogli excel
    Route::get("/istruzioni", [IstruzioniController::class, "view"])->name('base_istruzioni');  // istruzione per il caricamento dei dispositivi
    Route::get("/istruzioni/{rotta}", [IstruzioniController::class, "getIstruzioni"])->name('istruzioni');  // istruzione per il caricamento dei dispositivi
});
