@extends("layout.app")

@section("content")
<div class="register d-flex align-items-start pt-3" style="width: 100wv;">
    <div class="register-card container">
        <div class="row pt-5">
            <div class="box-container col-12 m-auto">
                <div class="circle circle-blu"></div>
                <div class="circle circle-blu"></div>
                <div class="box text-center pb-5">
                    <div class="text-center text-light text-uppercase fw-light mt-5 mb-4 me-2" style="letter-spacing: 0.5rem;">
                        <img src="{{ asset('immagini/logo_angeltracking.png') }}" class="img-fluid mx-auto intro ms-3 ms-lg-5" style="width: 20%">
                    </div>
                    <span class="d-sm-none title text-uppercase fw-light mb-5 text-light ms-3" style="font-size: 1rem; letter-spacing: 1.5rem;">Angel Air</span>
                    <span class="d-none d-sm-block d-md-none title text-uppercase fw-light mb-5 text-light ms-3" style="font-size: 2rem; letter-spacing: 2.1rem;">Angel Air</span>
                    <span class="d-none d-md-block d-lg-none title text-uppercase fw-light mb-5 text-light ms-3" style="font-size: 3rem; letter-spacing: 3.2rem;">Angel Air</span>
                    <span class="d-none d-lg-block d-xl-none title text-uppercase fw-light mb-5 text-light ms-5" style="font-size: 4rem; letter-spacing: 4.0rem;">Angel Air</span>
                    <span class="d-none d-xl-block title text-uppercase fw-light mb-5 text-light ms-5" style="font-size: 5rem; letter-spacing: 4.5rem;">Angel Air</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        window.location.href = "{{ route('login') }}";
    }, 3000000000);
</script>
@endsection

<style>
</style>