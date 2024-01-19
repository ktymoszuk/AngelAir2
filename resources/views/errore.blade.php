@extends("index")

@section("content")
<div class="register d-flex align-items-start pt-3" style="width: 100vw;">
    <div class="register-card container">
        <div class="row mt-5 pt-5">
            <div class="box-container box-hover-red col-12 m-auto">
                <div class="circle"></div>
                <div class="circle"></div>
                    <div class="box text-center py-4">
                        <p class="text-end text-light text-uppercase fw-light me-2" style="letter-spacing: 0.5rem;">Angel Air</p>
                        <span class="title text-uppercase fw-light mb-3 mt-3">Ci dispiace!</span>
                        <p class="title text-uppercase fw-light mb-4 pb-3" style="font-size: 1.2rem; letter-spacing: 0.4rem;">Qualcosa è andato storto con l'autenticazione</p>
                        <div>
                        <p class="title text-uppercase fw-light pb-2" style="font-size: 1rem;letter-spacing: 0.4rem;">
                            Probabilmente non sei ancora stato abilitato
                        </p>
                        <p class="title fw-light mb-4 pb-2" style="font-size: 1rem;letter-spacing: 0.2rem;">
                            per questo non riesci ancora ad accedere alle risorse
                        </p>
                        <span class="title text-uppercase fw-light mb-3 fs-4">Nessun problema!</span>
    
                        <p class="title fw-light mb-4 pb-2" style="font-size: 1rem;letter-spacing: 0.2rem;">
                            Richiedi al tuo amministratore di sistema di abilitare la tua utenza, in questo modo potrai accedere all'applicazione.
                        </p>
                
                        <a class="button2 button2-red mt-4 mb-4 cursor-pointer" style="text-decoration: none;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Accedi
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include("components.footer")
@endsection
