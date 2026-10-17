<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    {{-- L'administration ne doit jamais apparaître dans Google. --}}
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Administration') · FABARA</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=barlow:400,500,600,700|barlow-condensed:500,600,700|ibm-plex-mono:400,500&display=swap">
    <link rel="stylesheet" href="{{ asset('css/fabara.css') }}?v=1">
</head>
<body data-whatsapp="{{ $site['whatsapp'] }}">
@include('partials.icons')
@php
    $tabs = [
        ['admin.dashboard', 'Tableau de bord', 'admin.dashboard'],
        ['admin.devis.index', 'Demandes', 'admin.devis.*'],
        ['admin.services.index', 'Services', 'admin.services.*'],
        ['admin.realisations.index', 'Réalisations', 'admin.realisations.*'],
        ['admin.clients.index', 'Clients', 'admin.clients.*'],
        ['admin.deletions.index', 'Suppressions', 'admin.deletions.*'],
        ['admin.settings.edit', 'Paramètres', 'admin.settings.*'],
    ];
@endphp
<header class="adm-top">
    <div class="wrap">
        <a class="brand" href="{{ route('admin.dashboard') }}"><svg><use href="#logo"/></svg><span><b>FABARA</b><small>Administration</small></span></a>
        <nav class="tabs" aria-label="Administration">
            @foreach ($tabs as [$route, $label, $pattern])
                <a href="{{ route($route) }}" @if(request()->routeIs($pattern)) aria-current="page" @endif>
                    {{ $label }}
                    @if ($route === 'admin.devis.index' && $unreadCount > 0)<span class="badge" title="Messages clients non lus">{{ $unreadCount }}</span>@endif
                </a>
            @endforeach
            <a href="{{ route('home') }}" target="_blank" style="color:var(--gold)">Voir le site</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Déconnexion</button></form>
        </nav>
    </div>
</header>

@include('partials.flash')

<main class="wrap app-main">
    @yield('content')
</main>

<script src="{{ asset('js/fabara.js') }}?v=1" defer></script>
</body>
</html>
