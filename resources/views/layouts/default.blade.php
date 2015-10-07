<?php
use Illuminate\Support\Facades\Session;

function displayAlert() {
    if (Session::has('message')) {
        list($type, $message) = explode('|', Session::get('message'));
        return sprintf('<div class="alert alert-%s">%s</div>', $type, $message);
    }

    return '';
}
?>

@if (trim($__env->yieldContent('title')))
    @section('title') | Le Chaudron Baveur @append
@else
    @section('title', 'Le Chaudron Baveur')
@endif
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    @show
</head>
<body>
    @section('header')
        <header id="page-header">
            <section class="container text-center" id="page-logo">
                <h1><a href="{{ url('/') }}">Le Chaudron Baveur</a></h1>
            </section>
            <nav id="page-navigation" role="navigation">
                <section class="container">
                    <ul>
                        @section('nav-left')
                        <li><a href="{{ route('items') }}">Acheter</a></li>
                        <li><a href="{{ route('sell::index') }}">Vendre</a></li>
                        @show
                    </ul>
                    <ul class="align-right">
                        @section('nav-right')
                            @if(Auth::check())
                                @if(Auth::user()->user_type_id == 1)
                                    <li><a href="{{ route('admin::index') }}">Administration</a></li>
                                @endif
                                <li><a href="{{ route('profile') }}">Mon profil</a></li>
                                <li><a href="{{ route('logout') }}">Se déconnecter ({{Auth::user()->email }})</a></li>
                            @else
                                <li><a href="{{ route('register') }}">S'inscrire</a></li>
                                <li><a href="{{ route('login') }}">Se connecter</a></li>
                            @endif
                        @show
                    </ul>
                    <div class="clearfix"></div>
                </section>
            </nav>
        </header>
    @show

    <div id="page-content">
        <section class="container">
            {!! displayAlert() !!}
            @yield('content')</section>
    </div>
    <footer id="page-footer">
        <section class="container text-center">
            <p><span class="copyleft">&copy;</span> {{ date('Y') }} - ALLIAUME Hugo & LAPERRIERE Thibault</p>
        </section>
    </footer>

    @section('js')
    <script src="{{ asset('js/better-html-menu.js') }}"></script>
    <script>BetterHTMLMenu('nav')</script>
    @show
</body>
</html>
