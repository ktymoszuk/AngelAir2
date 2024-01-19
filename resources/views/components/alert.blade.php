@if ($errors->any())
    @foreach ($errors->all() as $error)
        <alert messaggio="{{ $error }}" colore="danger"></alert>
    @endforeach
@endif

@if(Session::has('success'))
    <alert messaggio="{{ session('success') }}" colore="success"></alert>
@endif

@if(Session::has('warning'))
    <alert messaggio="{{ session('warning') }}" colore="warning"></alert>
@endif

@if(Session::has('error'))
    <alert messaggio="{{ session('error') }}" colore="danger"></alert>
@endif
