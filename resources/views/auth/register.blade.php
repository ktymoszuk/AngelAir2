@extends("index")

@section("content")

@include("components.alert")

<div class="register d-flex align-items-start pt-5" style="width: 100vw; height: 100vh; margin: 0;">
    <div class="register-card container">
        <div class="row pt-4">
            <div class="box-container col-12 m-auto">
                <div class="circle2"></div>
                <div class="circle2"></div>
                <div class="box">
                    <p class="text-end text-light text-uppercase fw-light me-2" style="letter-spacing: 0.5rem;">Angel Air</p>
                    <span class="title text-center text-uppercase fw-light mb-3">
                        <span class="fs-5" style="letter-spacing: 0.8rem;">
                            Registrazione
                        </span>
                    </span>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="input-container">
                            <input required="" id="username-input" type="text" name="name" />
                            <label class="label" for="username-input">Username</label>
                            <div class="underline"></div>
                            <div class="sideline"></div>
                            <div class="upperline"></div>
                            <div class="line"></div>
                        </div>
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
                        <div class="input-container">
                            <input required="" id="confirm-password-input" type="password" name="password_confirmation" />
                            <label class="label" for="confirm-password-input">Conferma password</label>
                            <div class="underline"></div>
                            <div class="sideline"></div>
                            <div class="upperline"></div>
                            <div class="line"></div>
                        </div>
                        <input type="hidden" name="AxAppTag" value="ciao">
                        <input type="hidden" name="Ruolo" value="3">
                        <div class="d-flex justify-content-center mt-5 mb-4 pb-2">
                            <button type="submit" class="button2">
                                Registrati
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-4 p-0">
            <a class="box-container col-12 m-auto py-0" style="cursor: pointer; text-decoration: none;" href="{{ route('login') }}">
                <div class="box py-0">
                    <p class="text-center text-light text-uppercase fw-light pt-3" style="font-size: 1rem; letter-spacing: 0.5rem;">Accedi</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
