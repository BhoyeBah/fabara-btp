@php
    $onHome = request()->routeIs('home');
    $anchor = fn ($id) => $onHome ? '#'.$id : route('home').'#'.$id;
@endphp
<header class="top">
    <div class="wrap">
        <a class="brand" href="{{ route('home') }}" aria-label="FABARA, accueil">
            <svg><use href="#logo"/></svg>
            <span><b>FABARA</b><small>Bâti – Finition – TP</small></span>
        </a>
        <nav class="nav" aria-label="Navigation principale">
            <a href="{{ $anchor('services') }}">Services</a>
            <a href="{{ route('renovation') }}">Rénovation</a>
            <a href="{{ $anchor('realisations') }}">Réalisations</a>
            <a href="{{ $anchor('calculateur') }}">Calculateur</a>
            <a href="{{ $anchor('contact') }}">Contact</a>
        </nav>
        <div class="nav-account">
            @auth
                <a class="btn btn-gold btn-sm" href="{{ auth()->user()->homeRoute() }}">Mon espace</a>
            @else
                <a class="btn btn-ghost btn-sm" href="{{ route('login') }}">Connexion</a>
                <a class="btn btn-gold btn-sm" href="{{ route('register') }}">Inscription</a>
            @endauth
        </div>
        <button class="menu-btn" id="menuBtn" type="button" aria-expanded="false" aria-controls="mnav">Menu</button>
    </div>
    <div class="wrap mnav-wrap">
        <nav class="mobile-nav" id="mnav" aria-label="Navigation mobile">
            <a href="{{ $anchor('services') }}">Services</a>
            <a href="{{ route('renovation') }}">Moderniser ma vieille maison</a>
            <a href="{{ $anchor('realisations') }}">Réalisations</a>
            <a href="{{ $anchor('calculateur') }}">Calculateur</a>
            <a href="{{ $anchor('devis') }}">Demander un devis</a>
            <a href="{{ $anchor('contact') }}">Contact</a>
            @auth
                <a href="{{ auth()->user()->homeRoute() }}">Mon espace</a>
            @else
                <a href="{{ route('login') }}">Connexion</a>
                <a href="{{ route('register') }}">Inscription</a>
            @endauth
        </nav>
    </div>
</header>
