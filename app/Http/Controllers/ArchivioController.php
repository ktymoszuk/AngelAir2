<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DownloadReport;
use App\Models\Report;

class ArchivioController extends Controller
{
    public function index()
    {
        return view("archivioReports");
    }

    public function show(Request $request) {
        $dati = DownloadReport::where('codReport', $request->report)->get();
        return response()->json($dati);
    }

    public function showAll() {
        $dati = DownloadReport::get();
        return response()->json($dati);
    }

    // Download singolo report dall'archivio
    public function download(Request $request) {
        $nomeReport = $request->nomeFile;
        $formatoReport = $request->formato[0];
        $downloadFile = DownloadReport::where('Nome', $nomeReport)->first();
        $idReport = $downloadFile->codReport;
        $datiReport = Report::where('id', $nomeReport)->first();
        $nomeFile = $nomeReport . '.' . $formatoReport;
        $filePath = (public_path('download/reports/' . $formatoReport . '/' . $nomeFile));
        $headers = ['Content-Type: application/' . $formatoReport];
        // Cambio nel DB isDownload dei file scaricati con almeno un formato
        $changeIsDownload = DownloadReport::where('Nome', $nomeReport)->get();
        foreach ($changeIsDownload as $key => $file) {
            $file->isDownload = 1;
            
            $file->save();
        }
        return response()->download($filePath, $nomeFile, $headers);
    }
}
