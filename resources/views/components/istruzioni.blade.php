@extends('layout.app')

@section('content')

    <div class="container-fluid mt-5 pt-5 mb-4">
        <div class="container my-4">
            <div class="row text-center d-flex justify-content-center align-items-center align-content-center">
                <div class="col-12">
                    <div class="card shadow-lg">
                        <div class="card-body px-5">
                            <h1 class="text-uppercase text-start fw-light m-0">
                                Istruzioni
                            </h1>
                            <h3 class="text-start fw-light mb-5">
                                per l'inserimento delle colonne nel file Excel
                            </h3>
                            <div class="my-5">
                                @foreach ($istruzioniColonne as $colonna)
                                    <div class="mb-4">
                                        <h5 class="text-center fw-bold mb-0">{{ $colonna['Nome'] }}</h5>
                                        <p class="mb-0">{{ $colonna['Descrizione'] }}</p>
                                        @if ($colonna['Nome'] == 'Categoria')
                                            <ul class="fw-bold"> Ecco la lista delle categorie disponibili:</ul>
                                            @foreach ($categorieDisp as $categoria)
                                                <li style="list-style-type: circle2;">{{ $categoria->Nome }}</li>
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
