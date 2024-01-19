@extends("layout.app")

@section("content")
<div class="container-sm my-auto">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-login border-primary mb-3">
                <div class="card-header border-primary text-center">TITOLO</div>
                <div class="card-body">
                    <h5 class="card-title text-center">LOGIN</h5>
                    <div class="card-body">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="email" id="inpEmail" name="Email"
                                        class="form-control inpEmail" placeholder="Email" autofocus />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" name="Password" class="form-control" id="inputPassword" placeholder="Password">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="d-grid gap-2 col-6">
                                    <button class="btn btn-primary" type="submit">ACCEDI</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer border-dark">
                    <div class="d-flex justify-content-center">
                        <a class="nav-link" href="{{ route('registrazione_utente') }}">Registrati</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <script src="{{ asset('js/index.js') }}"></script> -->
@endsection

<style>
    
</style>