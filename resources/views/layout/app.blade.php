<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Angel Air</title>
    @vite('resources/css/app.css')
</head>

<body class="container-intro d-flex flex-column h-100">

@if(Auth::id() != null && Auth::user()->isAbilitato == 1)
    @include("components.navbar")
@endif

<div id="app">
    @yield('content')
</div>

@if(Auth::id() == null)
    @include("components.footer")
@endif

<mqtt/>

@vite('resources/js/app.js')
</body>

</html>