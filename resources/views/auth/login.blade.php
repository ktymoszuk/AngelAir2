@extends("index")

@section("content")

@include("components.alert")
<div class="register d-flex align-items-start pt-5" style="width: 100vw; height: 100vh; margin: 0;">
    <div class="register-card container">
        <div class="row pt-4">
            <div class="box-container col-12 m-auto">
                <div class="circle"></div>
                <div class="circle"></div>
                <div class="box">
                    <p class="text-end text-light text-uppercase fw-light me-2 mb-4" style="letter-spacing: 0.5rem;">Angel Air</p>
                    <span class="d-none d-md-block title text-center text-uppercase fw-light mb-3">Login</span>
                    <span class="d-block d-md-none title text-center text-uppercase fw-light mb-3 fs-5">Login</span>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="input-container">
                            <input required="" id="email-input" type="email" name="email" placeholder=" " />
                            <label class="label" for="email-input">Email</label>
                            <div class="underline"></div>
                            <div class="sideline"></div>
                            <div class="upperline"></div>
                            <div class="line"></div>
                        </div>
                        <div class="input-container">
                            <input required="" id="password-input" type="password" name="password"/>
                            <label class="label" for="password-input">Password</label>
                            <div class="underline"></div>
                            <div class="sideline"></div>
                            <div class="upperline"></div>
                            <div class="line"></div>
                        </div>
                        <div class="d-flex justify-content-center mt-5 mb-4 pb-2">
                            <button type="submit" class="button2">
                                Accedi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-4 p-0">
            <a class="box-container col-12 m-auto py-0" style="cursor: pointer; text-decoration: none;" href="{{ route('register') }}">
                <div class="box py-0">
                    <p class="text-center text-light text-uppercase fw-light pt-3" style="font-size: 1rem; letter-spacing: 0.5rem;">Registrati</p>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection

