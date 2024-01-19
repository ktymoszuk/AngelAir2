<div
    style="border-bottom:thin solid #dadce0;color:rgba(0,0,0,0.87);line-height:32px;padding-bottom:10px;text-align:center;">
    <div style="font-size:24px">Attenzione nuovo utente registrato </div>
    <h6 style="color:rgba(0,0,0,0.87);font-size:14px;">
        {{ $mailNuovoUtente }}
    </h6>
    <div style="text-align:center; color:black;padding:10px;">
        <p style="font-size: 15px">
            Per favore esegui il login e controlla il nuovo utente.
        </p>
        <a href="{{ route('login') }}"
            style="padding:15px 30px;background-color:#4184f3;text-decoration:none;border-radius:10px;min-width:90px; color:black"
            target="_blank">LOGIN</a>
    </div>
</div>
 