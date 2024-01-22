@extends("index")

@section("content")

<dettaglio
    :soglie-vento="{{ $soglieVento }}"
    :dispositivo="{{ $dispositivo }}"
    :colonne-grezze="{{ $colonneGrezze }}"
    :colonne="{{ $colonne }}"
    route-dati-dispositivo="{{ route('dati_dispositivo') }}"
    route-home="{{ route('dispositivi') }}"
    route-dati-statodisp="{{ route('dati_statodisp') }}"
    route-dati-rawdata="{{ route('dati_rawdata') }}"
></dettaglio>

@endsection
